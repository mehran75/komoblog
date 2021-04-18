<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Interfaces\PostInterface;
use App\Model\Category;
use App\Model\Label;
use App\Model\Post;
use App\Model\PostCategory;
use App\Model\PostLabel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use Throwable;

class postController extends Controller
{


    protected $postInterface;

    public function __construct(PostInterface $postInterface)
    {
        $this->postInterface = $postInterface;

    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user = auth('api')->user();

        return PostResource::collection($this->postInterface->indexPosts($user));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
     * @return Response
     */
    public function store(PostRequest $request): Response
    {

        try {
//            not the best place for storing image
            $imageName = time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('images'), $imageName);

            $data = $request->validated();
            $data['photo'] = $imageName;
//            not the best approach
            $data['author_id'] = auth('api')->user()->id;

            $post = $this->postInterface->storePost($data);

            return response(new PostResource($post));
        } catch (Exception $e) {
            return Response([
                'message' => 'Model not found',
                'debug' => $e
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $user = auth('api')->user();

        return response(new PostResource($this->postInterface->showPost($id, $user)));

    }


    /**
     * Update the specified resource in storage.
     *
     * @param PostRequest $request
     * @param int $id
     * @return Response
     */
    public function update(PostRequest $request, $id)
    {
        try {
//            not the best place for storing image [duplicate]
            $data = $request->validated();

            if ($request->photo) {
                $imageName = time() . '.' . $request->photo->extension();
                $request->photo->move(public_path('images'), $imageName);

                $data['photo'] = $imageName;
            }

//            not the best approach
            $data['author_id'] = auth('api')->user()->id;

            $post = $this->postInterface->updatePost($id, $data);

            return response(new PostResource($post));
        } catch (Exception $e) {
            return Response([
                'message' => 'Model not found',
                'debug' => $e
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param PostRequest $request
     * @param int $id
     * @return Response
     */
    public function destroy(PostRequest $request, $id)
    {
        return response(['success'=> $this->postInterface->deletePost(auth('api')->user(), $id)]);
    }
}
