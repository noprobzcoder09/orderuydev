<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCycleDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cycle_dates', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('delivery_timings_id');
            $table->date('delivery_date');
            $table->date('cutover_date');
            $table->integer('status');
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
        Schema::dropIfExists('cycle_dates');
    }
}
