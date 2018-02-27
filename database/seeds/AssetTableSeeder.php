<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class AssetTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('assets')->insert([
            'symbol' => 'btc'
        ]);

        DB::table('assets')->insert([
            'symbol' => 'etc'
        ]);

        DB::table('assets')->insert([
            'symbol' => 'xrp'
        ]);
    }
}
