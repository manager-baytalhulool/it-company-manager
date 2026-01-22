<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

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
        $currencies = Currency::select(['id', 'name', 'code', 'symbol', 'exchange_rate'])->paginate();
        return response()->json([
            'success' => true,
            'message' => 'Currencies fetched successfully',
            'data' => [
                'currencies' => $currencies,
            ]
        ]);
    }
}
