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
        Schema::create('option_items', function (Blueprint $table) {
            $table->id(); // プライマリキー
            $table->foreignId('people_id')->constrained('people')->onDelete('cascade'); // peopleテーブルの外部キー
            $table->foreignId('option_id')->constrained('options')->onDelete('cascade'); // optionsテーブルの外部キー
            $table->json('item1')->nullable();
            $table->json('item2')->nullable();
            $table->json('item3')->nullable();
            $table->json('item4')->nullable();
            $table->json('item5')->nullable();
            $table->text('bikou')->nullable(); // 備考カラム
            $table->timestamps(); // created_at, updated_at カラム
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('option_items');
    }
};
