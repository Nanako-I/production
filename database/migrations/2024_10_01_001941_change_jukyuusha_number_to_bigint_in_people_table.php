<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeJukyuushaNumberToBigintInPeopleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('people', function (Blueprint $table) {
            // jukyuusha_numberカラムをbigInteger型に変更
            $table->bigInteger('jukyuusha_number')->unsigned()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('people', function (Blueprint $table) {
            // jukyuusha_numberカラムをinteger型に戻す
            $table->integer('jukyuusha_number')->unsigned()->change();
        });
    }
}
