<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
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
                'name' => strtr(Str::random(7), '0123456789', str_repeat('B', 10)),
            ]);
        }

        DB::table('categories')->insert($data);
    }
}
