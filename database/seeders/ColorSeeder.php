<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('colors')->insert([
            ['name'=>'White'],
            ['name'=>'Black'],
            ['name'=>'Red'],
            ['name'=>'Green'],
            ['name'=>'Pink'],
            ['name'=>'Blue'],
        ]);
    }
}
