<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::orderBy('created_at', 'desc')->with(['project:id,name,account_id', 'project.account:id,name'])->paginate();
        return response()->json([
            'success' => true,
            'invoices' => $invoices
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required',
            'date' => 'required',
            'due_date' => 'required',
            'description' => 'required',
            'amount' => 'required',
            // 'currency_id' => 'required',
        ]);

        $project = Project::find($request->project_id);
        $account = Account::find($project->account_id);
        $data['currency_id'] = $account->currency_id;

        $invoice = Invoice::create($data);

        return $invoice;
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
{
    $invoice->load('project.account');
    return response()->json([
        'success' => true,
        'invoice' => $invoice
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'project_id' => 'required',
            'date' => 'required',
            'due_date' => 'required',
            'description' => 'required',
            'amount' => 'required'
        ]);

        $invoice->update($data);

        return response()->json([
            'success' => true,
            'invoice' => $invoice
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json([
            'success' => true,
            'invoice' => $invoice,

        ]);
    }
}
