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
            Schema::create('options', function (Blueprint $table) {
                $table->id(); // 主キー
                $table->string('title'); // 記録項目の題名
                $table->foreignId('people_id')->constrained('people'); // peopleテーブルの外部キー
                $table->foreignId('facility_id')->nullable()->constrained('facilities');
                $table->string('item1')->nullable(); // 記録項目のアイテム
                $table->string('item2')->nullable();
                $table->string('item3')->nullable();
                $table->string('item4')->nullable();
                $table->string('item5')->nullable();
                $table->boolean('flag')->default(false); // チェックを入れたら表示されるフラグ
                $table->timestamps(); // created_at と updated_at カラム
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('options');
    }
};
