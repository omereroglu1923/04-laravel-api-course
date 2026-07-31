<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Requests\Brand\CreateBrandRequest;
use App\Http\Resources\Brand\BrandResource;
use App\Models\Brand;
use Essa\APIToolKit\Api\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BrandController extends Controller
{
    use ApiResponse;

    public function __construct()
    {

    }

    public function index(): AnonymousResourceCollection
    {
        $brands = Brand::useFilters()->dynamicPaginate();

        return BrandResource::collection($brands);
    }

    public function store(CreateBrandRequest $request): JsonResponse
    {
        $brand = Brand::create($request->validated());

        return $this->responseCreated('Brand created successfully', new BrandResource($brand));
    }

    public function show(Brand $brand): JsonResponse
    {
        return $this->responseSuccess(null, new BrandResource($brand));
    }

    public function update(UpdateBrandRequest $request, Brand $brand): JsonResponse
    {
        $brand->update($request->validated());

        return $this->responseSuccess('Brand updated Successfully', new BrandResource($brand));
    }

    public function destroy(Brand $brand): JsonResponse
    {
        $brand->delete();

        return $this->responseDeleted();
    }

   
}
