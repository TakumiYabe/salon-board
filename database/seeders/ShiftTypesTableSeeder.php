<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShiftTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shift_types')->insert([
            'id' => 1,
            'name' => '全日',
            'work_time_from' => '9:00',
            'work_time_to' => '20:00',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);

        DB::table('shift_types')->insert([
            'id' => 2,
            'name' => '早番',
            'work_time_from' => '9:00',
            'work_time_to' => '16:00',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);

        DB::table('shift_types')->insert([
            'id' => 3,
            'name' => '遅番',
            'work_time_from' => '13:00',
            'work_time_to' => '20:00',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);
    }
}
