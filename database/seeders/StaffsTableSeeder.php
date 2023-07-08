<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('staffs')->insert([
            'id' => 1,
            'code' => '0001',
            'staff_type_id' => 1,
            'password' => 'password',
            'name' => 'マネージャー テスト',
            'name_kana' => 'マネージャー テスト',
            'birthday' => '1996/07/30',
            'sex_code' => 'F',
            'address' => '北海道中央区大通東2-4-5',
            'tel' => '000-1111-2222',
            'mail_address' => 'test@mail.com',
            'hourly_wage' => 1050,
            'haire_date' => '2020/04/12',
            'is_void' => 0,
            'memo' => 'お子様が小学生です。',
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);
    }
}
