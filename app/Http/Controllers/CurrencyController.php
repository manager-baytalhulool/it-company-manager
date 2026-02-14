<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->for == 'select') {
            $currencies = Currency::select(['id', 'name'])->get();
            return response()->json([
                'success' => true,
                'message' => 'Currencies fetched successfully',
                'data' => [
                    'currencies' => $currencies,
                ]
            ]);
        }
        $currencies = Currency::select(['currencies.id', 'currencies.name', 'code', 'symbol', 'exchange_rate', DB::raw('SUM(amount) as amount'), DB::raw('SUM(original_amount) as original_amount')])->leftJoin('accounts as a', 'currencies.id', '=', 'a.currency_id')->groupBy('currencies.id')->paginate();

        return response()->json([
            'success' => true,
            'message' => 'Currencies fetched successfully',
            'data' => [
                'currencies' => $currencies,
            ]
        ]);
    }
}
