<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkNullableCyclesMealPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cycles_meal_plans', function (Blueprint $table) {
            //
            $table->foreign('cycle_id')->references('id')->on('cycle_dates');
            $table->foreign('meal_plans_id')->references('id')->on('meal_plans');
            $table->text('default_selections')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cycles_meal_plans', function (Blueprint $table) {
            //
            $table->dropForeign('cycles_meal_plans_cycle_id_foreign');
            $table->dropForeign('cycles_meal_plans_meal_plans_id_foreign');
        });
    }
}
