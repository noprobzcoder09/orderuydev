<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCyclesMealPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cycles_meal_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cycle_id');
            $table->unsignedInteger('meal_plans_id');
            $table->text('default_selections');
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
        Schema::dropIfExists('cycles_meal_plans');
    }
}
