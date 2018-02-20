<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'amir',
            'email' => 'amir@gmail.com',
            'password' => bcrypt(12345),
        ]);

        DB::table('users')->insert([
            'name' => 'iman',
            'email' => 'iman@gmail.com',
            'password' => bcrypt(12345),
        ]);
    }
}
