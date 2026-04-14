<?php

namespace App\Http\Controllers;

use App\Exports\ProjectsExport;
use App\Models\Account;
use App\Models\Currency;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;

class ProjectController extends Controller
{
    public SyncService $syncService;

    public function __construct(SyncService $syncService)
    {
        $this->syncService = $syncService;
    }

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
        $projects = Project::select('account_id', 'projects.id', 'projects.name', 'projects.amount', 'projects.original_amount', 'paid', 'live_url', 'demo_url', 'started_at', 'is_live', 'projects.created_at', 'projects.currency_id', 'projects.updated_at')
            ->search($request->search)
            ->orderBy('projects.created_at', 'desc')
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

    public function sync(): JsonResponse
    {
        $this->syncService->projects();

        return response()->json([
            'success' => true,
            'message' => 'Project synced successfully'
        ]);
    }

    public function export()
    {
        return FacadesExcel::download(new ProjectsExport, 'projects.csv', Excel::CSV);
    }
}
