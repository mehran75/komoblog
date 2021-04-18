<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Model\Category;
use App\Model\PostCategory;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CategoryResource::collection(Category::paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $request = $request->validated();

        $cat = new Category;
        $cat->name = $request['name'];
        $cat->save();

        return response(new CategoryResource($cat), 201);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response(new CategoryResource(Category::findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function update(CategoryRequest $request, $id)
    {
        $request = $request->validated();

        $cat = Category::findOrFail($id);
        $cat->name = $request['name'];

        $cat->update();

        return response(new CategoryResource($cat), 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {

        if (PostCategory::where('category_id', $id)->exists()) {
            return response(
                ['message' => "Category can not be removed! there are postController attached to it"]);
        }

        $cat = Category::findOrFail($id);
        $cat->delete();

        return response([
            'message' => 'category destroyed!'
        ]);

    }
}
