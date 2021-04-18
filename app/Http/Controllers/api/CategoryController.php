<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepository;

class CategoryController extends Controller
{

    protected CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CategoryResource::collection($this->categoryRepository->indexCategories());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @return CategoryResource
     */
    public function store(CategoryRequest $request)
    {
        return new CategoryResource($this->categoryRepository->storeCategory($request->validated()));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return CategoryResource
     */
    public function show($id)
    {
        return new CategoryResource($this->categoryRepository->showCategory($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @param int $id
     * @return CategoryResource
     */
    public function update(CategoryRequest $request, $id)
    {
        return new CategoryResource($this->categoryRepository->updateCategory($request->validated(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CategoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryRequest $request, $id)
    {
        return response([
            'success' => $this->categoryRepository->deleteCategory($id)
        ]);

    }
}
