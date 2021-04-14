<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        for ($i = 0; $i < 10; $i++) {
            array_push($data, [
                'title' => Str::random(6),
                'body' => Str::random(1000),
                'author_id' => random_int(1, 10),
                'excerpt' => Str::random(100),
                'is_published' => (bool)rand(0, 1),
                'photo' => Str::random(20),
            ]);
        }

        DB::table('posts')->insert($data);


        $data = [];
        for ($i = 0; $i < 10; $i++) {
            array_push($data, [
                'post_id' => $i+1,
                'category_id' => random_int(1, 10),
            ]);
        }

        DB::table('post_categories')->insert($data);

        $data = [];
        for ($i = 0; $i < 15; $i++) {
            array_push($data, [
                'post_id' => random_int(1, 10),
                'label_id' => random_int(1, 10),
            ]);
        }

        DB::table('post_labels')->insert($data);

    }
}
