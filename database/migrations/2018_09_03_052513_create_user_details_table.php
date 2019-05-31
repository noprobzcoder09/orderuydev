<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('first_name', 45);
            $table->string('last_name', 45);
            $table->text('mobile_phone');
            $table->mediumText('delivery_notes');
            $table->mediumText('dietary_notes');
            $table->unsignedInteger('delivery_zone_timings_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('delivery_zone_timings_id')->references('id')->on('delivery_zone_timings');
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
        Schema::dropIfExists('user_details');
    }
}
