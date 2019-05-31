<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDisabledFieldZonesTimingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_zones', function (Blueprint $table) {
            $table->unsignedTinyInteger('disabled')->default(0);
        });

        Schema::table('delivery_timings', function (Blueprint $table) {
            $table->unsignedTinyInteger('disabled')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_zone', function (Blueprint $table) {
            $table->dropColumn('disabled');
        });

        Schema::table('delivery_timings', function (Blueprint $table) {
            $table->dropColumn('disabled');
        });
    }
}
