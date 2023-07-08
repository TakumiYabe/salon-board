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
        Schema::create('reserves', function (Blueprint $table) {
            //プロパティ
            $table->increments('id');
            $table->string('code', 10);
            $table->integer('reserve_course_id')->unsigned();
            $table->date('sales_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('required_time');
            $table->integer('change_staff_id')->unsigned();
            $table->tinyInteger('is_fix')->default(0);
            $table->tinyInteger('is_void')->default(0);
            $table->integer('customer_id');
            $table->string('memo', 500);
            $table->string('counseling_content', 500);
            $table->string('treatment_content', 500);
            $table->datetime('inserted');
            $table->integer('insert_staff_id')->unsigned();
            $table->datetime('updated');
            $table->integer('update_staff_id')->unsigned();

            // 外部キー制約
            $table->foreign('reserve_course_id')->references('id')->on('reserve_courses');
            $table->foreign('change_staff_id')->references('id')->on('staffs');
            $table->foreign('insert_staff_id')->references('id')->on('staffs');
            $table->foreign('update_staff_id')->references('id')->on('staffs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reserves');
    }
};
