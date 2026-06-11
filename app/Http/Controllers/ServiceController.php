<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::select('id', 'name', 'description', 'slug', 'order_no', 'icon', 'color', 'page_component', 'video_thumbnail', 'video_path', DB::raw("CONCAT('" . config("app.url") . "/storage/services/', image_path) AS image_path"),)->with([
            'projects' => function ($query) {
                $query->select('projects.id', 'projects.name', 'projects.slug', 'service_id');
            }
        ])->orderBy("order_no")
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Services retrieved successfully',
            'data' => [
                'services' => $services
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order_no' => 'nullable|integer',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filename = Str::slug($request->name) . '-' . now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('services', $filename, 'public');
            $validatedData['image_path'] = $filename;
        }

        $service = Service::create($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Service created successfully',
            'data' => [
                'service' => $service
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $service = Service::with(['projects' => function ($query) {
            $query->with(['featuredImage' => function ($q) {
                $q->select(
                    'id',
                    'imageable_id',
                    'imageable_type',
                    DB::raw("CONCAT('" . config('app.url') . "/storage/projects/', path) AS path"),
                    'is_featured'
                );
            }, 'services']);
        }])->where('slug', $slug)->firstOrFail();


        $service->video_thumbnail = $service->video_thumbnail ? config("app.url") . "/storage/services/" . $service->video_thumbnail : null;
        $service->video_path = $service->video_path ? config("app.url") . "/storage/services/" . $service->video_path : null;
        $service->image_path = $service->image_path ? config("app.url") . "/storage/services/" . $service->image_path : null;

        return response()->json([
            'success' => true,
            'message' => 'Service retrieved successfully',
            'data' => [
                'service' => $service
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);


        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'order_no' => 'nullable|integer',
            'icon' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['name']);

        if ($request->hasFile('image_path')) {
            $file = $request->file('image_path');
            $filename = Str::slug($request->name ?? $service->name) . '-' . now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('services', $filename, 'public');
            $validatedData['image_path'] = $filename;
        } else {
            unset($validatedData['image_path']);
        }

        $service->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully',
            'data' => [
                'service' => $service
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();

        // if ($service->has('quoteRequests')) {
        //     foreach ($service->quoteRequests as $quoteRequest) {
        //         Mail::raw("The service '{$service->name}' has been deleted. Now, we no longer provide this service.", function ($message) use ($quoteRequest) {
        //             $message->to($quoteRequest->email)
        //                 ->subject('Service Deleted Notification');
        //         });
        //     }
        // }

        return response()->json([
            'success' => true,
            'message' => 'Service deleted successfully',
        ]);
    }
}
