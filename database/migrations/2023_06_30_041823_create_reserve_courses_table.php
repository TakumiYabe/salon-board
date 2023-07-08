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
        Schema::create('reserve_courses', function (Blueprint $table) {
            //プロパティ
            $table->increments('id');
            $table->string('name', 50);
            $table->time('required_time');
            $table->integer('customer_category_id')->unsigned();
            $table->decimal('price', 9, 2);
            $table->decimal('hourly_price', 9, 2);
            $table->string('context', 200);
            $table->datetime('inserted');
            $table->integer('insert_staff_id')->unsigned();
            $table->datetime('updated');
            $table->integer('update_staff_id')->unsigned();

            // 外部キー制約
            $table->foreign('customer_category_id')->references('id')->on('customer_categories');
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
        Schema::dropIfExists('reserve_courses');
    }
};
