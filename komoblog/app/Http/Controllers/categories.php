<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Model\Category;
use App\Model\Post;
use App\Model\PostCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class categories extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        return Response(DB::table('categories')->paginate(10));
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
        try {
            if ($cat->save()) {
                return Response(['status' => 'Success',
                    'message' => $request['name'] . " is now a new category!",
                ]);
            } else {
                return Response(['status' => 'Failed',
                    'message' => "There were some issues with creating this category,
               and reloading the page won't help you!"]);
            }
        } catch (QueryException $e) {
            return Response(['status' => 'Failed',
                'message' => 'failed to create your requested category',
                'debug' => $e]);
        }

//        catch (Exception $e) {
//            return Response(['status' => 'Failed',
//                'message' => 'Oh Boy! You made such a mistake that even I couldn\'t figure it out!!
//                 go to your room and think about it',
//                'debug' => $e]);
//        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        not the best approach though
        try {
            return Response(Category::findOrFail($id));
        } catch (ModelNotFoundException $e) {
            return Response(['status' => 'Failed',
                'message' => 'Model not found',
                'debug' => $e]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\CategoryRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $request = $request->validated();

        try {

            $cat = Category::findOrFail($id);
            $cat->name = $request['name'];

            if ($cat->update()) {
                return Response([
                    'status' => 'Success',
                    'result' => $cat,
                ]);
            } else {
                return Response([
                    'status' => 'Failed',
                    'message' => "couldn't update the message"
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return Response(['status' => 'Failed',
                'message' => 'Model not found',
                'debug' => $e]);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            $count = PostCategory::where('category_id', $id)->count();;

            if ($count != 0) {
                return Response(['status' => 'Failed',
                    'message' => "Category can not be removed! there are posts attached to it"]);
            }

            $cat = Category::findOrFail($id);
            if ($cat->delete()) {
                return Response([
                    'status' => 'Success'
                ]);
            } else {
                return Response([
                    'status' => 'Failed',
                    'message' => "couldn't delete the category"
                ]);
            }
        } catch (ModelNotFoundException $e) {
            return Response(['status' => 'Failed',
                'message' => 'Model not found',
                'debug' => $e]);
        }
    }
}
