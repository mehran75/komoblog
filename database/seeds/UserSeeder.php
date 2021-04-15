<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Mehran Rafiee',
                'email' => 'mehranrafiee5@gmail.com',
                'password' => '@Mehran1234',
                'role' => 'admin'
            ],
            [
                'name' => 'Akbar salemi',
                'email' => 'akbar123@gmail.com',
                'password' => 'P@ssw0rd',
                'role' => 'user'
            ],
            [
                'name' => 'Asghar kharabi',
                'email' => 'asghar321@gmail.com',
                'password' => 'P@ssw0rd',
                'role' => 'user'
            ],

        ];

        DB::table('users')->insert($data);
    }
}
