<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCyclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycles', function (Blueprint $table) {
            $table->text('default_selections');
            $table->text('default_selections_veg');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cycles', function (Blueprint $table) {
            $table->dropColumn('default_selections');
            $table->dropColumn('default_selections_veg');
        });
    }
}
