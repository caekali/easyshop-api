<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;

class HomeController extends BaseController
{
    public function index()
    {
        $categories = Category::limit(8)->withCount('products')->get();
        
        $topSelling = Product::withSum('orderItems as total_sold', 'quntity')
            ->orderByDesc('total_sold')
            ->limit(8)
            ->get();

        // Filter new products by date
        $newIn = Product::where('created_at', '>=', now()->subWeek())
            ->limit(8)
            ->get();

        return $this->successResponse([
            'categories' => CategoryResource::collection($categories),
            'top_selling' => ProductResource::collection($topSelling),
            'new_in' => ProductResource::collection($newIn),
        ]);
    }
}
