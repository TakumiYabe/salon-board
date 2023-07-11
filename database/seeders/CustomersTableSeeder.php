<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customers')->insert([
            'id' => 1,
            'code' => '0001',
            'name' => '新規 テスト',
            'name_kana' => '新規 テスト',
            'birthday' => '1995/07/30',
            'age' => 27,
            'sex_code' => 'M',
            'address' => '北海道中央区大通東2-4-5',
            'tel' => '000-1111-2222',
            'mail_address' => 'test@mail.com',
            'visit_count' => 0,
            'inserted' => Carbon::now(),
            'insert_staff_id' => 1,
            'updated' => Carbon::now(),
            'update_staff_id' => 1,
        ]);
    }
}
