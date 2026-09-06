<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $businessId = $request->user()?->business_id ?? 1;

        $products = Product::where('business_id', $businessId)
            ->active()
            ->with('category')
            ->latest()
            ->cursorPaginate(15);

        return ProductResource::collection($products);
    }

    public function show(Request $request, Product $product): ProductResource
    {
        $product->load('category');
        return new ProductResource($product);
    }

    public function lowStock(Request $request): AnonymousResourceCollection
    {
        $businessId = $request->user()?->business_id ?? 1;

        $lowStockProducts = Product::where('business_id', $businessId)
            ->lowStock()
            ->with('category')
            ->get();

        return ProductResource::collection($lowStockProducts);
    }
}
