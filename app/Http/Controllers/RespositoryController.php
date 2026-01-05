<?php

namespace App\Http\Controllers;

use App\Models\Repository;
use Illuminate\Http\Request;

class RepositoryController extends Controller
{
    public function index()
    {
        $repositories = Repository::all();
        return response()->json(['data' => $repositories]);
    }

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

    public function show(Repository $repository)
    {
        return response()->json(['data' => $repository]);
    }

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

    public function destroy(Repository $repository)
    {
        $repository->delete();
        return response()->json([
            'success' => true,
            'message' => 'Repository deleted successfully'
        ]);
    }
}
