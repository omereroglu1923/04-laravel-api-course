<?php

namespace App\Http\Controllers\Api\V2; // V1 yerine V2

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use OpenApi\Attributes as OA;

/**
 * @group Categories
 *
 * Managing Categories
 */
class CategoryController extends Controller
{
    #[OA\Get(
        path: '/categories',
        tags: ['Categories'],
        summary: 'Get list of categories',
        responses: [
            new OA\Response(response: 200, description: 'Successful operation'),
            new OA\Response(response: 401, description: 'Unauthenticated'),
            new OA\Response(response: 403, description: 'Forbidden'),
        ]
    )]
    public function index()
    {
        // v1'den farklı: sadece id'si 3'ten küçük kategorileri döndür (test amaçlı)
        return CategoryResource::collection(Category::where('id', '<', 3)->get());
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
