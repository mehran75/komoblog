<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
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
        $hash = Hash::make('P@ssw0rd');
        $data = [
            [
                'name' => 'Mehran Rafiee',
                'email' => 'mehranrafiee5@gmail.com',
                'password' => $hash,
                'role' => 'admin'
            ],
            [
                'name' => 'Akbar salemi',
                'email' => 'akbar123@gmail.com',
                'password' => $hash,
                'role' => 'user'
            ],
            [
                'name' => 'Asghar kharabi',
                'email' => 'asghar321@gmail.com',
                'password' => $hash,
                'role' => 'user'
            ],

        ];

        DB::table('users')->insert($data);
    }
}
