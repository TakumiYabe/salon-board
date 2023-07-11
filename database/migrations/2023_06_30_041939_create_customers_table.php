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
        Schema::create('customers', function (Blueprint $table) {
            //プロパティ
            $table->increments('id');
            $table->string('code', 4);
            $table->string('name', 20);
            $table->string('name_kana', 20);
            $table->date('birthday');
            $table->integer('age');
            $table->char('sex_code', 1);
            $table->string('address', 100);
            $table->string('mail_address', 100);
            $table->string('tel', 20);
            $table->integer('visit_count');
            $table->datetime('inserted');
            $table->integer('insert_staff_id')->unsigned();
            $table->datetime('updated');
            $table->integer('update_staff_id')->unsigned();

            // 外部キー制約
            $table->foreign('sex_code')->references('code')->on('sexes');
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
        Schema::dropIfExists('customers');
    }
};
