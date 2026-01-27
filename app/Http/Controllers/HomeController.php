<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientsCount = Account::count();

        $pendingInvoices = Invoice::where('status', 'pending')->count();
        // $pendingIncome = Invoice::where('status', 'pending')->sum('amount');

        $pendingIncome = 0;
        $pendingInvoices = Invoice::where('status', 'pending')->get();
        foreach ($pendingInvoices as $i => $pendingInvoice) {
            if ($pendingInvoice->currency_id == 1) {
                $pendingIncome += $pendingInvoice->amount;
            } else {
                $pendingIncome += $pendingInvoice->currency->value * $pendingInvoice->amount;
            }
        }
        $pendingInvoicesCount = $pendingInvoices->count();

        $projectsCount = Project::count();

        $monthlyReceipts = Receipt::select(DB::raw("DATE_FORMAT(date, '%b') as month"), DB::raw("SUM(amount) as value"))
            ->where(DB::raw("YEAR (date)"), DB::raw("Year(CURDATE())"))
            ->groupBy(DB::raw("month"))
            ->get();
        $monthlyReceipts = $monthlyReceipts->pluck('value', 'month');

        $monthlyInvoices = Invoice::select('currency_id', 'id', 'date', 'amount', 'status', DB::raw('DATE_FORMAT(`date`, "%b") as month'))->with('currency', 'receipts')->orderBy('date')->get();
        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
        foreach ($monthlyInvoices as $i => $invoice) {
            if ($invoice->currency_id == 1) {
                continue;
            }
            if ($invoice->status == 'paid') {
                $monthlyInvoices[$i]->amount = $invoice->receipts->sum('amount');
            } else {
                $monthlyInvoices[$i]->amount = $invoice->currency->value * $invoice->amount;
            }
        }

        $monthlySales = $monthlyInvoices->groupBy('month')->map(function ($group) {
            return [
                'month' => $group->first()['month'], // opposition_id is constant inside the same group, so just take the first or whatever.
                'amount' => $group->sum('amount'),
            ];
        });

        $accounts = Account::select('name', 'amount', 'address', 'latitude', 'longitude')->where('amount', '>', 0)
            ->orderBy('amount', 'desc')
            ->get();
        $markers = $accounts;
        $accounts = $accounts->pluck('amount', 'name');

        $invoices = Invoice::orderBy('date', 'desc')->with('project.account')->limit(10)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'clientsCount' => $clientsCount,
                'pendingIncome' => $pendingIncome,
                'pendingInvoicesCount' => $pendingInvoicesCount,
                'projectsCount' => $projectsCount,
                'monthlyReceipts' => $monthlyReceipts,
                'monthlySales' => $monthlySales,
                'accounts' => $accounts,
                'markers' => $markers,
                'invoices' => $invoices
            ]
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
