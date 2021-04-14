<?php

namespace Tests\Feature;

use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class PostApiTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

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
            'body' => Str::random(random_int(100, 1000)),
            'excerpt' => 'test test test ...',
            'image_id' => 'test.png',
            'is_published' => true,
            'category_ids' => [1 , 5]
        ];

        $this->post('api/posts', $data)
            ->assertStatus(200)
            ->assertJson([
                'status' => 'Success',
                'data' => $data
            ]);
    }

    /**
     * A basic feature test example.
     * @group store_post
     * @return void
     */
    public function testCreatePost2()
    {
        $response = $this->post('api/posts', [
            'title' => 'test1',
            'body' => 'lourom ipsum glergnoergerg',
            'categoriy_ids' => [1, 2],
        ]);


        $response->assertStatus(500);
    }
}
