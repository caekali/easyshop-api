<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
          $categories = Category::all();

        return $this->successResponse(CategoryResource::collection($categories));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:categories,name',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $data['image_url'] = $path;
        }

        Category::create($data);

        return $this->successResponse(message: 'Category added');
    }
 /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $category = Category::findOrfail($id);
        return $this->successResponse($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|unique:categories,name,'.$id,
            'image' => 'sometimes|image|mimes:jpeg,png,jpg',
        ]);

        if ($request->hasFile('image')) {

            if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
                Storage::disk('public')->delete($category->image_url);
            }

            $path = $request->file('image')->store('categories', 'public');

            $data['image_url'] = $path;
        }

        $category->update($data);

        return $this->successResponse(message: 'Catergory updated');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category) {
            $category->delete();

            if ($category->image_url && Storage::disk('public')->exists($category->image_url)) {
                Storage::disk('public')->delete($category->image_url);
            }

            return $this->successResponse(message: 'Category Deleted');

        }

        return $this->successResponse(message: 'Category not found with id '.$id);

    }
}
