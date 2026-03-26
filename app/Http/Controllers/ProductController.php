<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->for == 'select') {
            $products = Product::select(['id', 'name'])->get();
            return response()->json([
                'success' => true,
                'data' => ['products' => $products]
            ]);
        }

        $products = Product::search($request->search)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return response()->json([
            'success' => true,
            'data' => ['products' => $products]
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'demo_url' => 'nullable|url',
            'download_url' => 'nullable|url',
        ]);

        $product = Product::create($data);
        return response()->json(['success' => true, 'product' => $product]);
    }

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data' => ['product' => $product]
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'demo_url' => 'nullable|url',
            'download_url' => 'nullable|url',
        ]);

        $product->update($data);
        return response()->json(['success' => true, 'data' => ['product' => $product]]);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['success' => true]);
    }
}
