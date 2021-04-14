<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Model\Category;
use App\Model\Label;
use App\Model\Post;
use App\Model\PostCategory;
use App\Model\PostLabel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class posts extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $author = auth('api')->user();

        try {
            $posts = Post::with('categories')
                ->with('comments')
                ->with('labels')
                ->where('is_published', true);
            if ($author != null) {
                $posts = $posts->orWhere('author_id', $author->id);
            }

            return Response([
                'status' => 'Success',
                'data' => $posts->paginate(10)
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => 'Failed',
                'message' => 'Failed to retrieve the requested information',
                'debug' => $e
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\PostRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request): Response
    {
        $request = $request->validated();
        try {

            DB::beginTransaction();

            $post = new Post();
            $post->title = $request['title'];
            $post->body = $request['body'];
            $post->excerpt = $request['excerpt'];
            $post->author_id = Auth::id();
            $post->is_published = $request['is_published'];
            $post->photo = $request['image_id'];

            if ($post->save()) {

//                assigning categories
                $cats = Category::findOrFail($request['category_ids']);
                $data = array();

                foreach ($cats as $cat) {
                    array_push($data, array(
                        'post_id' => $post->id,
                        'category_id' => $cat->id
                    ));
                }

                PostCategory::insert($data);

//                assigning labels
                if ($request['label_ids'] != null) {
                    $labels = Label::findOrFail($request['label_ids']);
                    $data = array();

                    foreach ($labels as $label) {
                        array_push($data, array(
                            'post_id' => $post->id,
                            'label_id' => $label->id
                        ));
                    }

                    PostLabel::insert($data);
                }

                DB::commit();

                $post->categories;
                $post->labels;
                $post->comments;

                return Response(['status' => 'Success',
                    'message' => $request['title'] . " is now a new post!",
                    'data' => $post
                ]);
            } else {
                DB::rollBack();
                return Response(['status' => 'Failed',
                    'message' => "Something went wrong here!"], 500);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return Response(['status' => 'Failed',
                'message' => 'failed to draft your requested post',
                'debug' => $e]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return Response(['status' => 'Failed',
                'message' => 'Model not found',
                'debug' => $e]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {

            $author = auth('api')->user();

            $post = Post::findOrFail($id);

            if (!$post->is_published && ($author == null || $author->id != $post->author_id)) {
                return Response([
                    'status' => 'Failed',
                    'message' => "You can't reach this post!"
                ], 403);
            }

            $post->categories;
            $post->comments;
            $post->labels;
            return Response([
                'status' => 'Success',
                'data' => $post
            ]);
        } catch (ModelNotFoundException $e) {
            return Response(['status' => 'Failed',
                'message' => 'Model not found',
                'debug' => $e]);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        $request = $request->validated();

        try {

            DB::beginTransaction();

            $post = Post::findORFail($id);
            $post->title = $request['title'];
            $post->body = $request['body'];
            $post->excerpt = $request['excerpt'];
            $post->author_id = Auth::id();
            $post->is_published = $request['is_published'];
            $post->photo = $request['image_id'];

            if ($post->save()) {

//                deleting previous assignments
                DB::table('post_categories')
                    ->where('post_id', $post->id)->delete();

                DB::table('post_labels')
                    ->where('post_id', $post->id)->delete();

//                assigning categories
                $cats = Category::findOrFail($request['category_ids']);
                $data = array();

                foreach ($cats as $cat) {
                    array_push($data, array(
                        'post_id' => $post->id,
                        'category_id' => $cat->id
                    ));
                }

                PostCategory::insert($data);

//                assigning labels
                if ($request['label_ids'] != null) {
                    $labels = Label::findOrFail($request['label_ids']);
                    $data = array();

                    foreach ($labels as $label) {
                        array_push($data, array(
                            'post_id' => $post->id,
                            'label_id' => $label->id
                        ));
                    }

                    PostLabel::insert($data);
                }

                DB::commit();

                $post->categories;
                $post->commnets;
                $post->labels;

                return Response(['status' => 'Success',
                    'message' => $request['title'] . " is now a new post!",
                    'data' => $post
                ]);
            } else {
                DB::rollBack();
                return Response([
                    'status' => 'Failed',
                    'message' => "Something went wrong here!"
                ], 500);
            }
        } catch (QueryException $e) {
            DB::rollBack();
            return Response(['status' => 'Failed',
                'message' => 'failed to draft your requested post',
                'debug' => $e]);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
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
    public
    function destroy($id)
    {
        // there should be a soft delete option though!
        try {
            DB::beginTransaction();

            Post::findOrFail($id)->delete();
            DB::table('post_categories')->where('post_id', $id)->delete();
            DB::table('post_labels')->where('post_id', $id)->delete();

            DB::commit();
            return Response([
                'status' => 'Success'
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return Response([
                'status' => 'Failed',
                'message' => 'Failed to remove the post',
                'debug' => $e
            ]);
        }
    }
}
