<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Model\Comment;
use App\Model\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Mockery\Exception;

class CommentController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $post_id
     * @param $is_published
     * @return \Illuminate\Http\Response
     */
    public function index($post_id)
    {
        $author = auth('api')->user();

        try {
            if ($author == null) {
                $post = Post::findOrFail($post_id);

                if (!$post->is_published) {
                    return response([
                        'message' => "You can't reach this post!"
                    ], 403);

                }
            }
            $comments = DB::table('commentController')
                ->select('commentController.*')
                ->join('postController', 'postController.id', '=', 'commentController.post_id')
                ->where('post_id', $post_id)
                ->where('postController.is_published', true);

            if ($author != null) {
                $comments = $comments->orWhere('postController.author_id', $author);
            }

            return Response([
                'status' => 'Success',
                'data' => $comments->paginate(10)
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
    public function store(CommentRequest $request)
    {

        $request = $request->validated();
        try {

            $post = Post::findOrFail($request['post_id']);

            if (!$post->is_published) {
                return Response([
                    'status' => 'Failed',
                    'message' => "You can't reach this post!"
                ], 401);
            }

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
                'message' => 'failed to draft your requested comment',
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

            if (!$post->is_published && ($author == null || $author->id != $post->author_id || $author->role != 'admin')) {
                return Response([
                    'status' => 'Failed',
                    'message' => "You can't reach this post!"
                ], 401);
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
    public function update(Request $request, $id)
    {

        try {
            $request = $request->validate([
                'body' => 'required|max:1000'
            ]);

            $comment = Comment::with('postController')
                ->where('postController.is_published', true)
                ->where('commentController.author_id', Auth::id());

            if ($comment->count() == 0) {
                return Response([
                    'status' => 'Failed',
                    'message' => "This comment or post doesn't exist"
                ], 404);
            }

            $comment->body = $request['body'];

            $comment->saveOrFail();
            return Response(['status' => 'Success',
                'message' => "your comment just updated!",
                'data' => $comment
            ]);

        } catch (QueryException $e) {
            return Response(['status' => 'Failed',
                'message' => 'failed to draft your requested comment',
                'debug' => $e], 500);
        } catch (ModelNotFoundException $e) {
            return Response(['status' => 'Failed',
                'message' => 'Model not found',
                'debug' => $e], 404);
        } catch (ValidationException $e) {
            return Response([
                'status' => 'Failed',
                'data' => ['body' => 'A body is required']
            ], 400);
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
        // there should be a soft delete option though!
        try {
            $comment = Comment::findOrFail($id);

            if ($comment->author_id != Auth::id() || Auth::user()->role != 'admin') {
                return Response([
                    'status', 'Failed',
                    'message' => 'Unauthorized user'
                ], 401);
            }


            $comment->post();

            if (!$comment->post->is_published) {
                return Response([
                    'status' => 'Failed',
                    'message' => "This comment or post doesn't exist"
                ], 404);
            }

            $comment->delete();
            return Response([
                'status' => 'Success'
            ]);
        } catch (\Throwable $e) {
            return Response([
                'status' => 'Failed',
                'message' => 'Failed to remove the comment',
                'debug' => $e
            ]);
        }
    }

}
