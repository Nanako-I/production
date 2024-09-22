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
            $table->string('person_name');//追記
            $table->date('date_of_birth');
            // $table->integer('age');
            // integerからstringに手打ちで修正↓
            $table->string('gender')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('disability_name')->nullable();//追記
		  $table->bigInteger('jukyuusha_number');
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
    Schema::dropIfExists('people');//←該当するテーブル名を入れる
    Schema::enableForeignKeyConstraints();
    }
    
};
