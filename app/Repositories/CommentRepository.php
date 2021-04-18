<?php


namespace App\Repositories;


use App\Interfaces\CommentInterface;
use App\Model\Comment;
use App\Model\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class CommentRepository implements CommentInterface
{

    /**
     * @inheritDoc
     */
    public function storeComment($values, $postId)
    {

        if (!Post::where('is_published', true)->findOrFail($postId)->exists()) {
            throw new ModelNotFoundException("Post doesn't exist or isn't reachable");
        }

        $comment = new Comment;
        $comment->body = $values['body'];
        $comment->author_id = $values['author_id'];
        $comment->post_id = $postId;

        $comment->saveOrFail();


        return $comment;

    }

    /**
     * @inheritDoc
     */
    public function indexComments($postId)
    {
        return Post::where('is_published', true)->findOrFail($postId)->comments()->paginate();
    }

    /**
     * @inheritDoc
     */
    public function showComment($id)
    {
        $comment = Comment::with('post')->findOrFail($id);

        if (!$comment->post->is_published) {
            throw new ModelNotFoundException("Post doesn't exist or isn't reachable");
        }

        return $comment;
    }

    /**
     * @inheritDoc
     */
    public function updateComment(array $values, $id)
    {


        $comment = Comment::with('post')->findOrFail($id);

        if (!$comment->post->is_published) {
            throw new ModelNotFoundException("Post doesn't exist or isn't reachable");
        }

        $comment->body = $values['body'];

        $comment->update();


        return $comment;
    }

    /**
     * @inheritDoc
     */
    public function deleteComment($user, $id): bool
    {
        $comment = Comment::with('post')->findOrFail($id);

        if (!$comment->post->is_published) {
            throw new ModelNotFoundException("Post doesn't exist or isn't reachable");
        }

        return $comment->delete();
    }
}
