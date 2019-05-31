<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkNullableMealsStatusChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meals_status_changes', function (Blueprint $table) {
            //
            $table->foreign('meal_id')->references('id')->on('meals');
            $table->foreign('replace_meal_id')->references('id')->on('meals');
            $table->date('change_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meals_status_changes', function (Blueprint $table) {
            //
            $table->dropForeign('meals_status_changes_meal_id_foreign');
            $table->dropForeign('meals_status_changes_replace_meal_id_foreign');
        });
    }
}
