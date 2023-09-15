<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shift_submission_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shift_submission_id')->unsigned();
            $table->date('date');
            $table->time('work_time_from')->nullable();
            $table->time('work_time_to')->nullable();
            $table->integer('shift_type_id')->nullable();
            $table->tinyInteger('is_work_off')->default(0);
            $table->datetime('inserted');
            $table->integer('insert_staff_id')->unsigned();
            $table->datetime('updated');
            $table->integer('update_staff_id')->unsigned();

            // 外部キー制約
            $table->foreign('shift_submission_id')->references('id')->on('shift_submissions');
            $table->foreign('insert_staff_id')->references('id')->on('staffs');
            $table->foreign('update_staff_id')->references('id')->on('staffs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shift_submission_details');
    }
};
