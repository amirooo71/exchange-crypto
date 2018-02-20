<?php

use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currencies')->insert([
            'symbol' => 'usd'
        ]);

        DB::table('currencies')->insert([
            'symbol' => 'btc'
        ]);
    }


}
