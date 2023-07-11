<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReserveCoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('reserve_courses')->insert([
            'id' => 1,
            'name' => 'コースA',
            'required_time' => '1:00:00',
            'customer_category_id' => 2,
            'price' => 6000,
            'hourly_price' => 6000,
            'context' => 'コースAの説明です。',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);

        DB::table('reserve_courses')->insert([
            'id' => 2,
            'name' => 'コースB',
            'required_time' => '1:30:00',
            'customer_category_id' => 2,
            'price' => 8500,
            'hourly_price' => 5600,
            'context' => 'コースBの説明です。',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);

        DB::table('reserve_courses')->insert([
            'id' => 3,
            'name' => 'コースC',
            'required_time' => '2:00:00',
            'customer_category_id' => 2,
            'price' => 11000,
            'hourly_price' => 5500,
            'context' => 'コースCの説明です。',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);
    }
}
