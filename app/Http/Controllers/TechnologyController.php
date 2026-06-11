<?php

namespace App\Http\Controllers;

use App\Models\Technology;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class TechnologyController extends Controller
{
    public function index()
    {
        $technologies = Technology::select('id', 'name', 'order_no', 'created_at', 'updated_at', DB::raw("CONCAT('" . config('app.url') . "/storage/technologies/', image) AS image"))
            ->orderBy('order_no')
            ->get();
        return response()->json([
            'success' => true,
            'data' => ['technologies' => $technologies]
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'order_no' => 'nullable|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug($request->name) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('technologies', $filename, 'public');
            $validatedData['image'] = $filename;
        }

        $technology = Technology::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Technology created successfully',
            'data' => ['technology' => $technology]
        ], 201);
    }

    public function show(string $id)
    {
        $technology = Technology::select('id', 'name', 'order_no', 'created_at', 'updated_at', DB::raw("CONCAT('" . config('app.url') . "/storage/technologies/', image) AS image"))
            ->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => ['technology' => $technology]
        ]);
    }

    public function update(Request $request, string $id)
    {
        $technology = Technology::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'order_no' => 'nullable|integer',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug($request->name ?? $technology->name) . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('technologies', $filename, 'public');
            $validatedData['image'] = $filename;
        }

        $technology->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Technology updated successfully',
            'data' => ['technology' => $technology]
        ]);
    }

    public function destroy(string $id)
    {
        $technology = Technology::findOrFail($id);
        $technology->delete();
        return response()->json([
            'success' => true,
            'message' => 'Technology deleted successfully',
        ]);
    }
}
