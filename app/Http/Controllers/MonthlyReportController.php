<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::select('currency_id', 'id', 'date', 'amount', 'status', DB::raw('DATE_FORMAT(`date`, "%b") as month'))->with('currency', 'receipts')
        ->whereRaw("YEAR(`date`) = YEAR(CURDATE())")
        ->orderBy('date')
        ->get();
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        foreach ($invoices as $i => $invoice) {
            if($invoice->currency_id == 1) {
                continue;
            }
            if($invoice->status == 'paid') {
                $invoices[$i]->amount = $invoice->receipts->sum('amount');
            } else {
                $invoices[$i]->amount = $invoice->currency->value * $invoice->amount;
            }
        }

        $monthlyInvoices = $invoices->groupBy('month');

        $monthlyInvoices = $monthlyInvoices->map(function ($group) {
            return [
                'month' => $group->first()['month'], // opposition_id is constant inside the same group, so just take the first or whatever.
                'amount' => $group->sum('amount'),
            ];
        });
        // dd($monthlyInvoices);
        return response()->json([
            'success' => true,
            'monthlyInvoices' => $monthlyInvoices,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
