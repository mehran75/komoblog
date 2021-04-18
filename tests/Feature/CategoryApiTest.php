<?php

namespace Tests\Feature;

use App\Model\Category;
use App\Model\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::findOrFail(1);
        $user->role = 'admin';
        $user->saveOrFail();

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
        $this->post('api/categoryController', [
            'name' => Str::random(7)
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
        $this->post('api/categoryController', [])
            ->assertStatus(422)
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
        $response = $this->post('api/categoryController', [
            'name' => $name,
        ]);

        if ($response->status() != 200) {
            self::assertTrue(false);
        }
        $this->post('api/categoryController', [
            'name' => $name,
        ])->assertStatus(200)
            ->assertJson(['status' => 'Failed']);

    }

    /**
     * Testing store function
     * @group store_category
     * @return void
     * unauthorized user error
     */
    public function testCreateCategory4()
    {
        $this->actingAs(User::findOrFail(2));

        $name = strtr(Str::random(7), '0123456789', str_repeat('C', 10));
        $this->post('api/categoryController', [
            'name' => $name,
        ])->assertStatus(401)
            ->assertJson(['status' => 'Failed']);

        $this->actingAs(User::findOrFail(1));

    }

    /**
     * testing destroy
     * @group destroy_category
     * @returns void
     * successful
     * */
    public function testDestroyCategory1()
    {
        $cat_id = Category::all()->last()->id;
        $this->delete('api/categoryController/'. $cat_id)
            ->assertStatus(200)
            ->assertJson([
                    'status' => 'Success'
                ]
            );
    }

    /**
     * testing destroy
     * @group destroy_category
     * @returns void
     * unauthorized user error
     * */
    public function testDestroyCategory2()
    {
        $this->actingAs(User::findOrFail(2));

        $cat_id = Category::all()->last()->id;

        $this->delete('api/categoryController/' . $cat_id)
            ->assertStatus(401)
            ->assertJson([
                    'status' => 'Failed'
                ]
            );

        $this->actingAs(User::findOrFail(1));

    }

}
