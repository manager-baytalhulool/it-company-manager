<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::all();
        return response()->json([
            'success' => true,
            'message' => 'Currencies fetched successfully',
            'data' => $currencies,
        ]);
    }
}
