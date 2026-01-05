<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use App\Exports\RepositoriesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RepositoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $repositories = Repository::all();
        return response()->json([
            'data' => $repositories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'name' => 'required|string',
            'url' => 'required|url',
            'provider' => 'nullable|string'
        ]);

        $repository = Repository::create($data);

        return response()->json([
            'success' => true,
            'data' => $repository
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Repository $repository)
    {
        return response()->json(['data' => $repository]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Repository $repository)
    {
         $data = $request->validate([
            'name' => 'required|string',
            'url' => 'required|url',
            'provider' => 'nullable|string'
        ]);

        $repository->update($data);

        return response()->json([
            'success' => true,
            'data' => $repository
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Repository $repository)
    {
        $repository->delete();
        return response()->json([
            'success' => true,
            'message' => 'Repository deleted successfully'
        ]);
    }

    public function export()
    {
        return Excel::download(new RepositoriesExport, 'repositories.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
