<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            //プロパティ
            $table->increments('id');
            $table->string('code', 4);
            $table->integer('staff_type_id')->unsigned();
            $table->string('password', 60);
            $table->string('name', 20);
            $table->string('name_kana', 20);
            $table->date('birthday');
            $table->char('sex_code', 1);
            $table->string('address', 100);
            $table->string('tel', 20);
            $table->string('mail_address', 100);
            $table->decimal('hourly_wage', 6, 2);
            $table->date('haire_date');
            $table->tinyInteger('is_void')->default(0);
            $table->string('memo', 400)->default('');
            $table->datetime('inserted');
            $table->integer('insert_staff_id')->unsigned();
            $table->datetime('updated');
            $table->integer('update_staff_id')->unsigned();

            // 外部キー制約
            $table->foreign('sex_code')->references('code')->on('sexes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shift_submissions');
    }
};
