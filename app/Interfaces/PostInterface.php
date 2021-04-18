<?php

namespace App\Interfaces;

use App\Http\Requests\PostRequest;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

interface PostInterface
{


    /**
     * Create new post
     * @param array $values
     * @return Post
     */
    public function storePost(array $values);


    /**
     * get all of the published posts paginated.
     * @param $user : if null => only published posts will return
     *                if user.role = 'admin' => all the posts
     *                else => all the published posts along with the user's unpublished ones
     *
     * @return PaginatedResourceResponse : returns posts
     */
    public function indexPosts($user);

    /**
     * get all of the published posts paginated.
     * @param $id : post id
     * @param $user : if null => only if the post is published
     *                if user.role = 'admin' or user is the author => the requested post will return
     *
     * @return Post
     */
    public function showPost($id, $user);


    /**
     *  update a post
     * @param $id: post id
     * @param array $values
     * @return Post
     */
    public function updatePost($id, array $values);

    /**
     *  delete a post
     * @param $user
     * @param $id
     * @return bool
     */
    public function deletePost($user, $id): bool;



}
