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
        Schema::create('scheduled_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('people_id');
            $table->foreign('people_id')->references('id')->on('people')->onDelete('cascade');
            $table->dateTime('arrival_datetime');
            $table->dateTime('exit_datetime');
            $table->unsignedBigInteger('visit_type_id');
            $table->foreign('visit_type_id')->references('id')->on('visit_types')->onDelete('cascade');
            $table->text('notes')->nullable();
            $table->string('pick_up')->nullable(); // 迎えの要否のカラムを追加
            $table->string('drop_off')->nullable(); // 送りの要否のカラムを追加
            $table->dateTime('pick_up_time')->nullable(); // 迎え予定時間
            $table->dateTime('drop_off_time')->nullable(); // 送り予定時間
            $table->string('pick_up_staff')->nullable(); // 迎え担当者名
            $table->string('drop_off_staff')->nullable(); // 送り担当者名
            $table->string('pick_up_bus')->nullable(); // 迎えバス名
            $table->string('drop_off_bus')->nullable(); // 送りバス名
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduled_visits');
    }
};
