<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();

        return $this->successResponse($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:products,name',
            'slug' => 'required|string|unique:products,slug',
            'description' => 'required|string',
            'price' => 'required|decimal:2',
            'stock' => 'required|integer',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg',
        ]);

        $path = $request->file('image')->store();
        $data['image_url'] = $path;
        Product::create($data);

        return response()->json('Product added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();

            return response()->json([
                'message' => 'Product deleted successfully',
            ]);
        }

        return response()->json([
            'message' => 'Product not found with id '.$id,
        ]);

    }
}
