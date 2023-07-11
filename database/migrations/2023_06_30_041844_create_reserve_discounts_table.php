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
        Schema::create('reserve_discounts', function (Blueprint $table) {
            //プロパティ
            $table->increments('id');
            $table->integer('reserve_id')->unsigned();
            $table->date('sales_date');
            $table->integer('discount_type_id')->unsigned();
            $table->decimal('discount_price', 9, 2);
            $table->string('memo', 200);
            $table->datetime('inserted');
            $table->integer('insert_staff_id')->unsigned();
            $table->datetime('updated');
            $table->integer('update_staff_id')->unsigned();

            // 外部キー制約
            $table->foreign('reserve_id')->references('id')->on('reserves');
            $table->foreign('discount_type_id')->references('id')->on('discount_types');
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
        Schema::dropIfExists('reserve_discounts');
    }
};
