<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('staff_types')->insert([
            'id' => 1,
            'name' => 'マネージャー',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);

        DB::table('staff_types')->insert([
            'id' => 2,
            'name' => '社員',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);

        DB::table('staff_types')->insert([
            'id' => 3,
            'name' => 'アルバイト',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);

    }
}
