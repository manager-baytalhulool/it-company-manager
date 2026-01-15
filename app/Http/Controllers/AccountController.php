<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Project;
use App\Models\Receipt;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
if($request->for == 'select') {
        $accounts = Account::select(['id', 'name'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Accounts fetched successfully',
            'data' => [
                'accounts' => $accounts,
            ]
        ]);
        }
        $accounts = Account::select(['id', 'name', 'person', 'amount', 'original_amount'])->orderBy('amount', 'desc')->paginate();
        return response()->json([
            'success' => true,
            'message' => 'Accounts retrieved successfully',
            'data' => [
                'accounts' => $accounts,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'person' => 'nullable',
            'phone' => 'nullable',
            'currency_id' => 'required',
            'parent_id' => 'nullable',
            'address' => 'nullable'
        ]);

        $account = Account::create($data);
        return response()->json([
            'success' => true,
            "message" => "Account created successfully.",
            'account' => $account,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Account $account)
    {
        $account->load('projects');
        $projectIds = $account->projects->pluck('id');
        $receipts = Receipt::whereIn('project_id', $projectIds)->get();
        return response()->json([
            'success' => true,
            'message' => 'Account loaded successfully',
            'data' => [
                'account' => $account,
                'receipts' => $receipts,

            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Account $account)
    {
        $data = $request->validate([
            'name' => 'required',
            'person' => 'nullable',
            'phone' => 'nullable',
            'currency_id' => 'required',
            'parent_id' => 'nullable',
            'address' => 'nullable'
        ]);

        $account->update($data);

        return response()->json([
            'success' => true,
            "message" => "Account updated successfully.",
            'account' => $account,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();
        return response()->json([
            'success' => true,
            "message" => "Account deleted successfully.",
            'data' => [
                'account' => $account,
            ]
        ]);
    }

    public function recalculate()
    {
        $accounts = Account::get();
        foreach ($accounts as $i => $account) {
            $totalOriginalAmount = 0;
            $totalAmount = 0;
            $projectCount = 0;

            $projects = Project::where('account_id', $account->id)->get();
            foreach ($projects as $j => $project) {
                $projectCount += 1;

                $receipts = Receipt::from('receipts as r')
                    ->select('r.id', 'r.project_id', 'r.original_amount', 'r.amount')
                    // ->leftJoin('projects as p', 'p.id', '=', 'r.project_id')
                    ->where('project_id', $project->id)
                    ->get();

                foreach ($receipts as $k => $receipt) {
                    $totalAmount += $receipt->amount;
                    $totalOriginalAmount += $receipt->original_amount;
                }
            }



            $account->project_count = $projectCount;
            $account->amount = $totalAmount;
            $account->original_amount = $totalOriginalAmount;
            $account->save();
        }
    }

    public function setCurrencies()
    {
        $accounts = Account::get();
        foreach ($accounts as $i => $account) {
            $currency = Currency::where('currency', $account->currency)->first();
            $account->currency_id = $currency->id;
            $account->save();
        }
    }
}
