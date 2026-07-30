<?php

namespace App\Http\Controllers\Api\V2; // V1 yerine V2

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;

/**
 * @group Products
 *
 * Managing Products
 */
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(9); // get() yerine paginate()
        return ProductResource::collection($products);
    }
}
