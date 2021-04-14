<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostApiTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStorePost()
    {
        $response = $this->post('api/posts/', [
            'title' => 'test1',
            'body' => 'lourom ipsum glergnoergerg',
            'categoriy_ids' => [1, 2],
        ]);


        $response->assertStatus(500);
    }
}
