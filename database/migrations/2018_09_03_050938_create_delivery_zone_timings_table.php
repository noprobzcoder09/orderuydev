<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeliveryZoneTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_zone_timings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('delivery_zone_id');
            $table->unsignedInteger('delivery_timings_id');
            $table->foreign('delivery_zone_id')->references('id')->on('delivery_zones');
            $table->foreign('delivery_timings_id')->references('id')->on('delivery_timings');
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
        Schema::dropIfExists('delivery_zone_timings');
    }
}
