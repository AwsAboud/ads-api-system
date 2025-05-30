<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService){}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $res = $this->categoryService->getAll();

        return $this->successResponse(CategoryResource::collection($res));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());
        
        return $this->successResponse(new CategoryResource($category), code:201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $category = $this->categoryService->getOne();

        return $this->successResponse(CategoryResource::collection( $category));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest Request  $request, Category $category)
    {
        $category = $this->categoryService->update($request->validated());
        
        return $this->successResponse(new CategoryResource($category));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successResponse(null, code:204);
    }
}
