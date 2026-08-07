<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Services\JournalEntryService;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public JournalEntryService $journalEntryService;

    public function __construct(JournalEntryService $journalEntryService)
    {
        $this->journalEntryService = $journalEntryService;
    }


    public function index(Request $request)
    {
        $expenses = Expense::search($request->search)->orderBy('created_at', 'desc')->paginate();
        return response()->json([
            'success' => true,
            'message' => 'Expenses fetched successfully',
            'data' => [
                'expenses' => $expenses,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
        ]);

        $expense = Expense::create($data);

        $this->journalEntryService->createJournalEntryOnExpense($expense);

        return response()->json([
            'success' => true,
            "message" => "Expense created successfully.",
            'expense' => $expense,
        ]);
    }

    public function show(Expense $expense)
    {
        return response()->json([
            'success' => true,
            'message' => 'Expense loaded successfully',
            'data' => [
                'expense' => $expense,
            ]
        ]);
    }

    public function update(Request $request, Expense $expense)
    {
        $data = $request->validate([
            'description' => 'required',
            'amount' => 'required|numeric',
        ]);

        // $expense->update($data);

        return response()->json([
            'success' => true,
            "message" => "Expense updated successfully.",
            'expense' => $expense,
        ]);
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();
        return response()->json([
            'success' => true,
            "message" => "Expense deleted successfully.",
            'data' => [
                'expense' => $expense,
            ]
        ]);
    }
}
