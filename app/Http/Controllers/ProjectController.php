<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->for == 'select') {
            $projects = Project::select(['id', 'name'])->get();
            return response()->json([
                'success' => true,
                'message' => 'Projects fetched successfully',
                'data' => [
                    'projects' => $projects,
                ]
            ]);
        }
        $projects = Project::orderBy('created_at', 'desc')
            ->with([
                'account' => function ($q) {
                    $q->select('id', 'currency_id', 'name');
                },
                "currency" => function ($q) {
                    $q->select("id", "code");
                }
            ])
            ->paginate();
        return response()->json([
            'success' => true,
            'message' => 'Projects fetched successfully',
            'data' => [
                'projects' => $projects,

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
            'account_id' => 'required',
            'currency_id' => 'required',
            'original_amount' => 'nullable|numeric',
            'amount' => 'nullable|numeric',
            "paid" => "nullable",
            "started_at" => "required",
            "is_available" => "nullable|boolean",
            "is_duplicable" => "nullable|boolean",
            "is_sellable" => "nullable|boolean",
            "is_live" => "nullable|boolean",
            "live_url" => "nullable|url",
            "demo_url" => "nullable|url",
        ]);


        DB::beginTransaction();
        $project = Project::create($data);

        $account = Account::find($request->account_id);
        // increment project count in accounts
        $account->update([
            'project_count' => $account->project_count + 1,
        ]);
        DB::commit();

        return response()->json([
            'success' => true,
            "message" => "Project created successfully.",
            'project' => $project,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {

        $project->load([
            'account:id,name,person',
            'currency:id,code'
        ]);

        // $project->load([
        //     'account:id,name,person',
        //     'currency:id,name'
        // ])->only(['id', 'name', 'account', 'currency']);
        // $project = Project::select('id', 'name')
        // ->with([
        //     'account:id,name,person',
        //     'currency:id,name'
        // ])
        // ->findOrFail($id);
        // $project->load(['account', 'currency'])->select('id', 'name');
        return response()->json([
            'success' => true,
            "data" => [
                'project' => $project
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'name' => 'required',
            'account_id' => 'required|exists:accounts,id',
            'currency_id' => 'required|exists:currencies,id',
            'amount' => 'nullable|numeric',
            'started_at' => 'required|date',
        ]);

        $project->update($data);
        return response()->json(['success' => true, 'message' => 'Project updated.', 'project' => $project]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        $project->delete();
        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully',
            'project' => $project,
        ]);
    }
}
