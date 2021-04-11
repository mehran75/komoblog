<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Model\Category;
use App\Model\Comment;
use App\Model\Post;
use App\Model\PostCategory;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class comments extends Controller
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
    public function index($post_id)
    {
//        $author = auth('api')->user();

        try {
            $comments = Comment::where('post_id', $post_id)->paginate(10);

            return Response([
                'status' => 'Success',
                'data' => $comments
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
     * @param \App\Http\Requests\CommentRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request): Response
    {

        $request = $request->validated();
        try {

            $comment = new Comment;
            $comment->body = $request['body'];
            $comment->author_id = Auth::id();
            $comment->post_id = $request['post_id'];

            $comment->saveOrFail();
            return Response(['status' => 'Success',
                'message' => "your comment just posted!",
                'data' => $comment
            ]);

        } catch (QueryException $e) {
            return Response(['status' => 'Failed',
                'message' => 'failed to draft your requested post',
                'debug' => $e]);
        } catch (ModelNotFoundException $e) {
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
    public function show($psot_id, $id)
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


}
