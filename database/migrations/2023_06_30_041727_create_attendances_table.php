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
        Schema::create('attendances', function (Blueprint $table) {
            //プロパティ
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->string('year_and_month', 7);
            $table->date('date');
            $table->time('arrival_time');
            $table->time('leave_time');
            $table->time('rest_time');
            $table->time('work_time');
            $table->time('over_work_time');
            $table->datetime('inserted');
            $table->integer('insert_staff_id')->unsigned();
            $table->datetime('updated');
            $table->integer('update_staff_id')->unsigned();

            // 外部キー制約
            $table->foreign('staff_id')->references('id')->on('staffs');
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
        Schema::dropIfExists('attendances');
    }
};
