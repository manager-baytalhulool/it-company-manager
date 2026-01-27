<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BimonthlyReceiptController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthlyReceipts = Receipt::select(DB::raw("DATE_FORMAT(date, '%b') as month"), DB::raw("SUM(amount) as amount"))
            ->where(DB::raw("YEAR (date)"), DB::raw("Year(CURDATE())"))
            ->orderBy(DB::raw("month"))
            ->groupBy(DB::raw("month"))
            ->get();
        $bimonthlyReceipts = [];
        foreach ($monthlyReceipts as $i => $monthlyReceipt) {
            $j = $i + 1;
            if ($j % 2) {

                $bimonthlyReceipts[$i]['label'] = $monthlyReceipt->month . ' - ';
                $bimonthlyReceipts[$i]['amount'] = $monthlyReceipt->amount;
            } else {
                $bimonthlyReceipts[$i - 1]['label'] = $bimonthlyReceipts[$i - 1]['label'] . $monthlyReceipt->month;
                $bimonthlyReceipts[$i - 1]['amount'] += $monthlyReceipt->amount;
            }
        }

        return response()->json([
            'success' => true,
            'bimonthlyReceipts' => $bimonthlyReceipts,
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
