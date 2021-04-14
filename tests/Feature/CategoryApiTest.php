<?php

namespace Tests\Feature;

use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();

        $user = factory(User::class)->create();
        $this->actingAs($user, 'api');

        $this->withoutExceptionHandling();
    }

    /**
     * Testing store function
     * @group store_category
     * @return void
     * successful
     */

    public function testCreateCategory1()
    {
        $this->post('api/categories', [
            'name' => strtr(Str::random(7), '0123456789', str_repeat('A', 7)),
        ])->assertStatus(200)
            ->assertJson(['status' => 'Success']);
    }

    /**
     * Testing store function
     * @group store_category
     * @return void
     * validation error
     */

    public function testCreateCategory2()
    {
        $this->post('api/categories', [
            'name' => 'Housing01',
        ])->assertStatus(422)
            ->assertJson(['status' => 'Failed']);

    }

    /**
     * Testing store function
     * @group store_category
     * @return void
     * duplicate error
     */
    public function testCreateCategory3()
    {
        $name = strtr(Str::random(7), '0123456789', str_repeat('C', 10));
        $response = $this->post('api/categories', [
            'name' => $name,
        ]);

        if ($response->status() != 200) {
            self::assertTrue(false);
        }
        $this->post('api/categories', [
            'name' => $name,
        ])->assertStatus(200)
            ->assertJson(['status' => 'Failed']);

    }

    /**
     * testing destroy
     * @group destroy_category
     * @returns void
     * */
    public function testDestroyCategory1()
    {

    }

}
