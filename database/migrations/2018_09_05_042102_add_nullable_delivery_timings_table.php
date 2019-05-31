<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableDeliveryTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_timings', function (Blueprint $table) {
            //
            $table->string('delivery_day', 12)->default()->change();
            $table->string('cutoff_day', 12)->default()->change();
            $table->time('cutoff_time')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_timings', function (Blueprint $table) {
            //
        });
    }
}
