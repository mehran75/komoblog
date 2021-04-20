<?php


namespace App\Repositories;


use App\Http\Requests\PostRequest;
use App\Interfaces\PostInterface;
use App\Model\Category;
use App\Model\Label;
use App\Model\Post;
use App\Model\PostCategory;
use App\Model\PostLabel;
use App\Model\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Resources\Json\PaginatedResourceResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class PostRepository implements PostInterface
{

    /**
     * @inheritDoc
     */
    public function indexPosts($user)
    {

        $postQuery = Post::query();

        if ($user == null) {
            $postQuery = $postQuery
                ->where('is_published', true);

        } elseif ($user->role != 'admin') {
            $postQuery = $postQuery->where('author_id', $user->id)
                ->orWhere('is_published', true);
        }

        return $postQuery->paginate();
    }

    /**
     * @inheritDoc
     */
    public function storePost(array $values, User $user)
    {
        try {
            DB::beginTransaction();

            $post = new Post();
            $post->title = $values['title'];
            $post->body = $values['body'];
            $post->excerpt = $values['excerpt'];
            $post->author_id = $user;
            $post->is_published = $values['is_published'];
            $post->photo = $values['photo'];

            $post->saveOrFail();

//                assigning categoryController
            $post->categories()->sync($values['category_ids']);

//                assigning labelController
            if (array_key_exists('label_ids', $values)) {
                $post->labels()->sync($values['label_ids']);
            }

            DB::commit();

            return $post;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('failed to store the new post!', $e->getCode(), $e);
        }

    }


    /**
     * @inheritDoc
     */
    public function showPost($id, $user)
    {

        $post = Post::findOrFail($id);

        if ($post->is_published) {
            return $post;
        } elseif ($user != null && ($post->author_id == $user->id || $user->role == 'admin')) {
            return $post;
        }

        throw new ModelNotFoundException("post doesn't exists or is unreachable");

    }

    /**
     * @inheritDoc
     */
    public function updatePost($id, array $values, User $user)
    {
        try {
            DB::beginTransaction();

            $post = Post::findOrFail($id);

            if ($user->role != 'admin' && $post->author_id != $user->id) {
                throw new ModelNotFoundException("post doesn't exists or is unreachable");
            }

            $post->title = $values['title'];
            $post->body = $values['body'];
            $post->excerpt = $values['excerpt'];
            $post->is_published = $values['is_published'];

            if (array_key_exists('photo', $values)) {
                $post->photo = $values['photo'];
            }

            $post->save();

//                assigning categoryController
            $post->categories()->sync($values['category_ids']);

//                assigning labelController
            if (array_key_exists('label_ids', $values)) {
                $post->labels()->sync($values['label_ids']);
            }

            DB::commit();

            return $post;

        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('failed to update the requested post!', $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function deletePost($user, $id): bool
    {
        if ($user->role == 'admin') {
            return Post::findOrFail($id)->delete();
        } else {
            return Post::findOrFail($id)->where('author_id', $user->id)->delete();
        }
    }


    /**
     * @inheritDoc
     */
    public function saveImage()
    {
        // TODO: Implement saveImage() method.
    }
}
