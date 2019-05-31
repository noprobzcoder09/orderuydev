<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLengthSku extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meal_plans', function (Blueprint $table) {
            $table->string('sku',45)->change();
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->string('meal_sku',45)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meal_plans', function (Blueprint $table) {
            $table->string('sku',15)->change();
        });

        Schema::table('meals', function (Blueprint $table) {
            $table->string('meal_sku',15)->change();
        });
    }
}
