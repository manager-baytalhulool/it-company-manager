<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Receipt;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $receipts = Receipt::orderBy('date', 'desc')->with('invoice.project.account')->get();
        return response()->json([
            'success' => true,
            'receipts' => $receipts
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
        $receipt = Receipt::create($data);

        $invoice = Invoice::find($request->invoice_id);
        $invoice->status = 'paid';
        $invoice->save();

        $project = Project::find($invoice->project_id);
        // new column for this
        // $project->original_amount += $receipt->original_amount; // original paid amount
        $project->paid += $receipt->amount;
        $project->save();

        $account = Account::find($project->account_id);
        $account->amount += $receipt->amount;
        $account->original_amount += $receipt->original_amount;
        $account->save();

        return response()->json([
            'success' => true,
            'receipt' => $receipt,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Receipt $receipt)
    {
        return response()->json([
            'success' => true,
            'receipt' => $receipt,
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
}
