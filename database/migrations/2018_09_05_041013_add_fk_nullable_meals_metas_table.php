<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkNullableMealsMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meals_metas', function (Blueprint $table) {
            //
            $table->foreign('meal_id')->references('id')->on('meals');
            $table->text('meta_key')->nullable()->change();
            $table->text('meta_value')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meals_metas', function (Blueprint $table) {
            //
            $table->dropForeign('meals_metas_meal_id_foreign');
        });
    }
}
