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
        Schema::create('costs', function (Blueprint $table) {
            //プロパティ
            $table->increments('id');
            $table->string('year_and_month', 7);
            $table->decimal('personal_expenses', 9, 2);
            $table->decimal('product_expenses', 9, 2);
            $table->decimal('rent_expenses', 9, 2);
            $table->decimal('utility_expenses', 9, 2);
            $table->decimal('advertising_expenses', 9, 2);
            $table->decimal('insurance_expenses', 9, 2);
            $table->decimal('total_expenses', 9, 2);
            $table->datetime('inserted');
            $table->integer('insert_staff_id')->unsigned();
            $table->datetime('updated');
            $table->integer('update_staff_id')->unsigned();

            // 外部キー制約
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
        Schema::dropIfExists('costs');
    }
};
