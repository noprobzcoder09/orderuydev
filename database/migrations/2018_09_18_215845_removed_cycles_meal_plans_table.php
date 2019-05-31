<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovedCyclesMealPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cycles_meal_plans');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('cycles_meal_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cycle_id');
            $table->text('default_selections');
            $table->text('default_selections_veg');
            $table->timestamps();
        });
    }
}
