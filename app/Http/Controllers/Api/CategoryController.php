<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest; // yeni import

/**
 * @group Categories
 *
 * Managing Categories
 */
class CategoryController extends Controller
{
    /**
     * Get Categories
     *
     * Getting the list of the categories
     *
     * @queryParam page Which page to show. Example: 2
     */
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

    /**
     * POST categories
     *
     * @bodyParam name string required Name of the category. Example: Clothing
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('categories', 'public');
        }

        $category = Category::create($data);

        return new CategoryResource($category);
    }

    public function update(Category $category, StoreCategoryRequest $request)
    {
        $category->update($request->validated());

        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'Bu kategoriye bağlı ürünler var, önce onları silmeniz veya başka kategoriye taşımanız gerekiyor.'
            ], 409); // Conflict
        }

        $category->delete();
        return response()->noContent();
    }
}
