<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BimonthlyReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::select('currency_id', 'id', 'date', 'amount', 'status', DB::raw('DATE_FORMAT(`date`, "%b") as month'))->with('currency', 'receipts')->orderBy('date')->get();
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

        $bimonthlySales = [];
        $i = 0;
        foreach ($monthlyInvoices as $m => $monthlyInvoice) {
            $j = $i + 1;
            if($j % 2) {
                $bimonthlySales[$i]['label'] = $m. ' - ';
                $bimonthlySales[$i]['amount'] = $monthlyInvoice->sum('amount');
            } else {
                $bimonthlySales[$i-1]['label'] = $bimonthlySales[$i-1]['label'].$m;
                $bimonthlySales[$i-1]['amount'] += $monthlyInvoice->sum('amount');
            }
            $i++;
        }

        return response()->json([
            'success' => true,
            'bimonthlySales' => $bimonthlySales,
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
