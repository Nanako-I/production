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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            // 名前を「姓」「名」「フリガナの姓」「フリガナの名」に分割
            $table->string('last_name'); // 姓
            $table->string('first_name'); // 名
            $table->string('last_name_kana'); // フリガナの姓
            $table->string('first_name_kana'); // フリガナの名
            
            $table->date('date_of_birth');
            $table->string('gender')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('disability_name')->nullable();
            $table->bigInteger('jukyuusha_number')->unsigned();
            $table->boolean('medical_care')->default(false)->nullable();
            $table->integer('kubun_number');
            $table->string('filename')->nullable();
            $table->string('path')->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('people');
        Schema::enableForeignKeyConstraints();
    }
};

