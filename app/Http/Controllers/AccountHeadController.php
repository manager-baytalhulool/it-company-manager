<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use Illuminate\Http\Request;

class AccountHeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountHeads = AccountHead::orderBy('created_at', 'desc')->get();
        $table = 'categories';
        $model = 'category';
        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => [
                'accountHeads' => $accountHeads,
                'table' => $table,
                'model' => $model,
            ]

        ]);
    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(AccountHead $accountHead)
    {
        $table = 'categories';
        $model = 'category';
        $accountHead->load('forGeneralEntries');
        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => [
                'accountHead' => $accountHead,
                'table' => $table,
                'model' => $model,
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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

    public function trialBalance()
    {
        $accountHeads = AccountHead::with('generalEntries')->orderBy('created_at', 'desc')->get();
        $model = 'trial balance';
        return response()->json([
            'success' => true,
            'message' => 'Data fetched successfully',
            'data' => [
                'accountHeads' => $accountHeads,
                'model' => $model,
            ]
        ]);
    }
}
