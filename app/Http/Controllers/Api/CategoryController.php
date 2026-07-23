<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        // Tüm kategoriler, description kısaltılmış (limit(20))
        return CategoryResource::collection(Category::all());
    }

    public function show(Category $category)
    {
        // Route model binding — {category} otomatik olarak ilgili kayda çevrilir
        return new CategoryResource($category);
    }

    public function list()
    {
        // /lists/categories için — description hiç gösterilmez (dropdown senaryosu)
        return CategoryResource::collection(Category::all());
    }
}
