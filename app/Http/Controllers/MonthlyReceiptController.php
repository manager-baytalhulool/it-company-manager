<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MonthlyReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthlyReceipts = Receipt::select(
            DB::raw("DATE_FORMAT(date, '%Y-%m') as month_year"),
            DB::raw("DATE_FORMAT(date, '%Y') as year"),
            DB::raw("DATE_FORMAT(date, '%b') as month"),
            DB::raw("SUM(amount) as value")
        )
            ->where(DB::raw("YEAR (date)"), DB::raw("Year(CURDATE())"))
            ->orWhere(DB::raw("YEAR (date)"), DB::raw("Year(CURDATE()) - 1"))
            ->orderBy('month_year')
            ->groupBy('month_year', 'year', 'month')
            ->get();

        $months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        $currentYearMonthlyReceipts = $monthlyReceipts->where('year', now()->year)->pluck('value', 'month');
        $previousYearMonthlyReceipts = $monthlyReceipts->where('year', now()->year - 1)->pluck('value', 'month');
        return response()->json([
            'success' => true,
            'currentYearMonthlyReceipts' => $currentYearMonthlyReceipts, 'months' => $months,
            'previousYearMonthlyReceipts' => $previousYearMonthlyReceipts,

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
