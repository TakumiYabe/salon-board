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
        Schema::create('customer_discounts', function (Blueprint $table) {
            //プロパティ
            $table->increments('id');
            $table->integer('customer_id')->unsigned();
            $table->integer('discount_id')->unsigned();
            $table->tinyInteger('is_fix')->default(0);
            $table->tinyInteger('is_void')->default(0);
            $table->datetime('inserted');
            $table->integer('insert_staff_id')->unsigned();
            $table->datetime('updated');
            $table->integer('update_staff_id')->unsigned();

            // 外部キー制約
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('discount_id')->references('id')->on('discounts');
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
        Schema::dropIfExists('customer_discounts');
    }
};
