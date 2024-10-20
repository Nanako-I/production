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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('people_id');
            $table->datetime('pickup_time')->nullable(); // 迎えの予定時間
            $table->boolean('pickup_completed')->default(false); // 迎え完了のチェック
            $table->datetime('dropoff_time')->nullable(); // 送りの予定時間
            $table->boolean('dropoff_completed')->default(false); // 送り完了のチェック
            $table->timestamps();

            // 外部キー制約
            $table->foreign('people_id')->references('id')->on('people')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transports');
    }
};
