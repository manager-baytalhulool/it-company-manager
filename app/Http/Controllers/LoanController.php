<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller
{

    public function index(Request $request)
    {
        $loans = Loan::search($request->search)->orderBy('created_at', 'desc')->with('account:id,name')->paginate();
        return response()->json([
            'success' => true,
            'message' => 'Loans fetched successfully',
            'data' => [
                'loans' => $loans,
            ]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'account_id' => 'nullable|exists:accounts,id',
            'person' => 'nullable',
            'description' => 'required',
            'amount' => 'required|numeric',
            'paid_amount' => 'nullable|numeric',
            'status' => 'required|in:active,paid',
        ]);

        $loan = Loan::create($data);
        return response()->json([
            'success' => true,
            "message" => "Loan created successfully.",
            'loan' => $loan,
        ]);
    }

    public function show(Loan $loan)
    {
        $loan->load('account');
        return response()->json([
            'success' => true,
            'message' => 'Loan loaded successfully',
            'data' => [
                'loan' => $loan,
            ]
        ]);
    }

    public function update(Request $request, Loan $loan)
    {
        $data = $request->validate([
            'account_id' => 'nullable|exists:accounts,id',
            'person' => 'nullable',
            'description' => 'required',
            'amount' => 'required|numeric',
            'paid_amount' => 'nullable|numeric',
            'status' => 'required|in:active,paid',
        ]);

        if ($data['status'] === 'paid') {
            $data['paid_amount'] = $data['amount'];
        }

        $loan->update($data);

        return response()->json([
            'success' => true,
            "message" => "Loan updated successfully.",
            'loan' => $loan,
        ]);
    }

    public function destroy(Loan $loan)
    {
        $loan->delete();
        return response()->json([
            'success' => true,
            "message" => "Loan deleted successfully.",
            'data' => [
                'loan' => $loan,
            ]
        ]);
    }
}
