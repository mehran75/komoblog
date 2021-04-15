<?php

namespace Tests\Feature;

use App\Model\Post;
use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostApiTest extends TestCase
{

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();

//        $user = factory(User::class)->create();
        $this->actingAs(User::find(3), 'api');

    }

    protected function getUser($id)
    {
        return User::findOrFail($id);
    }

    /**
     * A basic feature test example.
     * @group store_post
     * @return void
     * successful
     */
    public function testCreatePost1()
    {
        $data = [
            'title' => 'test1',
            'body' => Str::random(random_int(250, 1000)),
            'excerpt' => 'test test test ...',
            'photo' => 'test.png',
            'is_published' => true,
            'category_ids' => [1, 5],
            'label_ids' => [5, 3]
        ];

        $this->post('api/posts', $data)
            ->assertStatus(200)
            ->assertJson([
                'status' => 'Success']);
    }

    /**
     * @group store_post
     * validation error
     */
    public function testCreatePost2()
    {
        $response = $this->post('api/posts', [
            'title' => 'test1',
            'body' => 'lourom ipsum glergnoergerg',
            'categoriy_ids' => [1, 2],
        ]);


        $response->assertStatus(422);
    }

    /**
     * @group store_post
     * validation error
     */
    public function testCreatePost3()
    {
        $response = $this->post('api/posts', [
            'title' => 'test1',
            'body' => Str::random(random_int(250, 1000)),
            'excerpt' => 'test test test ...',
            'photo' => 'test.png',
            'is_published' => true,
            'category_ids' => [1, 5],
            'label_ids' => [5, 3, 2, 4, 1, 6]
        ]);


        $response->assertStatus(422);
    }


    /**
     * A basic feature test example.
     * @group show_post
     * @return void
     * successful
     */
    public function testShowPost1()
    {
        $this->get('api/posts/1')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'title',
                    'body',
                    'excerpt',
                    'photo',
                    'is_published',
                    'categories',
                    'labels',
                    'comments'
                ]
            ]);
    }


    /**
     * A basic feature test example.
     * @group show_post
     * @return void
     * unauthorized user
     */
    public function testShowPost2()
    {

        $post_id = DB::table('posts')
            ->where('is_published', false)
            ->whereNotIn('author_id', [3])->first();

        $this->get('api/posts/' . $post_id->id)
            ->assertStatus(401);


    }


    /**
     * A basic feature test example.
     * @group update_post
     * @return void
     * successful
     */
    public function testUpdatePost1()
    {
        $data = [
            'title' => 'test1',
            'body' => Str::random(random_int(250, 500)),
            'excerpt' => 'test test test ...',
            'photo' => 'test.png',
            'is_published' => 0,
            'category_ids' => [1, 5],
            'label_ids' => [5, 3]
        ];

        $post_id = DB::table('posts')
            ->where('author_id', 3)->first();


        $this->patch('api/posts/' . $post_id->id, $data)
            ->assertStatus(200)
            ->assertJson([
                'status' => 'Success']);
    }

    /**
     * A basic feature test example.
     * @group update_post
     * @return void
     * unauthorized user
     */
    public function testUpdatePost2()
    {

        $data = [
            'title' => 'test1',
            'body' => Str::random(random_int(250, 500)),
            'excerpt' => 'test test test ...',
            'photo' => 'test.png',
            'is_published' => false,
            'category_ids' => [1, 5],
            'label_ids' => [5, 3]
        ];


        $post_id = DB::table('posts')
            ->where('author_id', [1, 2])->first();


        $this->patch('api/posts/' . $post_id->id, $data)
            ->assertStatus(401);
    }


    /**
     * A basic feature test example.
     * @group destroy_post
     * @return void
     * successful
     */
    public function testDestroyPost1()
    {

        $post = Post::all()->last();

//        make sure the post author is the current user
        $post->author_id = 3;
        $post->save();

        $this->delete('api/posts/' . $post->id)
            ->assertStatus(200)
            ->assertJson([
                'status' => 'Success'
            ]);

    }


    /**
     * A basic feature test example.
     * @group destroy_post
     * @return void
     * unauthorized user
     */
    public function testDestroyPost2()
    {

        $post = Post::all()->last();

//        make sure the post author is not the current user
        $post->author_id = 2;
        $post->save();

        $this->delete('api/posts/' . $post->id)
            ->assertStatus(401)
            ->assertJson([
                'status' => 'Failed'
            ]);

    }
}
