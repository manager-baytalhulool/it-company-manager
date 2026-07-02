<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\JournalEntry;
use App\Models\Project;
use App\Models\Receipt;
use App\Services\SyncService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public SyncService $syncService;

    public function __construct(SyncService $syncService)
    {
        $this->syncService = $syncService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $receipts = Receipt::search($request->search)
            ->orderBy('date', 'desc')
            ->with([
                'project:id,name,account_id,currency_id',
                'project.currency:id,code',
                'project.account:id,name',
                'invoice:id,amount,project_id',
                'invoice.project:id,name,account_id',
                'invoice.project.account:id,name'
            ])->paginate();


        return response()->json([

            'success' => true,
            'message' => 'Receipts fetched successfully',
            'data' => [
                'receipts' => $receipts
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required',
            'project_id' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'original_amount' => 'required',
            'invoice_id' => 'required',
        ]);
        DB::beginTransaction();
        $receipt = Receipt::create($data);

        $invoice = Invoice::find($request->invoice_id);
        $invoice->status = InvoiceStatus::PAID->value;
        $invoice->save();

        $project = Project::withTrashed()->find($invoice->project_id);
        // new column for this
        // $project->original_amount += $receipt->original_amount; // original paid amount
        $project->paid += $receipt->amount;
        $project->save();

        $account = Account::find($project->account_id);
        $account->amount += $receipt->amount;
        $account->original_amount += $receipt->original_amount;
        $account->save();

        $journalEntryService = new \App\Services\JournalEntryService();
        $journalEntryService->createJournalEntryOnReceipt($receipt);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Receipt created successfully',
            'data' => [
                'receipt' => $receipt,
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Receipt $receipt)
    {
        $receipt->load([
            'project:id,name,account_id',
            'invoice:id,amount',
            'project.account:id,name'
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Receipt fetched successfully',
            'data' => [
                'receipt' => $receipt,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receipt $receipt)
    {
        $oldAmount = $request->amount;
        $oldOriginalAmount = $request->original_amount;
        $data = $request->validate([
            'date' => 'required',
            'project_id' => 'required',
            'description' => 'required',
            'amount' => 'required',
            'original_amount' => 'required',
        ]);
        $receipt->update($data);

        $project = Project::find($receipt->project_id);
        $project->original_amount = ($project->original_amount - $oldOriginalAmount) + $receipt->original_amount; // original paid amount
        $project->paid = ($project->paid - $oldAmount) + $receipt->amount;
        $project->save();

        $account = Account::find($project->account_id);
        $account->amount = ($account->amount - $oldAmount) + $receipt->amount;
        $account->original_amount = ($account->original_amount - $oldOriginalAmount) + $receipt->original_amount;
        $account->save();

        return response()->json([
            'success' => true,
            'receipt' => $receipt,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return response()->json([
            'message' => 'Receipt has been deleted successfully',
            'receipt' => $receipt
        ]);
    }

    public function sync(): JsonResponse
    {
        $this->syncService->receipts();
        return response()->json([
            'success' => true,
            'message' => 'Receipts synced successfully'
        ]);
    }
}
