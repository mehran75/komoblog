<?php

namespace App\Interfaces;

use App\Http\Requests\PostRequest;
use App\Model\Comment;
use App\Model\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;

interface CommentInterface
{


    /**
     * Create new post
     * @param $postId
     * @param array $values
     * @return Comment
     */
    public function storeComment(array $values, $postId);


    /**
     * get all of the published posts paginated.
     * @param $postId
     * @return PaginatedResourceResponse : returns comments
     */
    public function indexComments($postId);

    /**
     * get all of the published posts paginated.
     * @param $id : comment id
     * @return Comment
     */
    public function showComment($id);


    /**
     *  update a post
     * @param $id: comment id
     * @param array $values
     * @return Comment
     */
    public function updateComment(array $values, $id);

    /**
     *  delete a post
     * @param $user
     * @param $id
     * @return bool
     */
    public function deleteComment($user, $id): bool;



}
