<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use F9Web\ApiResponseHelpers;

class ProductController extends Controller
{
    use ApiResponseHelpers;

    public function index()
    {
        $products = Product::with('category')->paginate(9);

        return ProductResource::collection($products); // respondWithSuccess kaldırıldı
    }
}
