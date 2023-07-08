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
        Schema::create('deductions', function (Blueprint $table) {
            //プロパティ
            $table->increments('id');
            $table->integer('staff_id')->unsigned();
            $table->string('year_and_month', 7);
            $table->decimal('health_insurance_fee', 9, 2);
            $table->decimal('employee_person_insurance_fee', 9, 2);
            $table->decimal('employee_insurance_fee', 9, 2);
            $table->decimal('income_tax', 9, 2);
            $table->decimal('resident_tax', 9, 2);
            $table->decimal('social_security_amount', 9, 2);
            $table->decimal('tax_amount', 9, 2);
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
        Schema::dropIfExists('deductions');
    }
};
