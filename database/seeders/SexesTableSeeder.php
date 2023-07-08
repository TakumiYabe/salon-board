<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SexesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sexes')->insert([
            'code' => 'M',
            'name' => '男性',
        ]);
        DB::table('sexes')->insert([
            'code' => 'F',
            'name' => '女性',
        ]);
    }
}
