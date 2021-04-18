<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Model\Comment;
use App\Model\Post;
use App\Repositories\CommentRepository;
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

    protected $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $post_id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index($post_id)
    {
        return CommentResource::collection($this->commentRepository->indexComments($post_id));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\CommentRequest $request
     * @return CommentResource
     */
    public function store(CommentRequest $request, $post_id)
    {

        $data = $request->validated();
        $data['author_id'] = auth('api')->id();

        return new CommentResource($this->commentRepository->storeComment($data, $post_id));

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return CommentResource
     */
    public function show($id)
    {
        return new CommentResource($this->commentRepository->showComment($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return CommentResource
     */
    public function update(CommentRequest $request, $id)
    {
        $data = $request->validated();
        $data['author_id'] = auth('api')->id();

        return new CommentResource($this->commentRepository->updateComment($data, $id));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(['success'=> $this->commentRepository->deleteComment(auth('api')->user(), $id)]);

    }

}
