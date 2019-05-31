<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesBulk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('stripe_subscription_id');
        });

        Schema::table('subscriptions_selections', function (Blueprint $table) {
            $table->unsignedTinyInteger('cycle_subscription_status')->default(0);
        });

        Schema::table('cycles_meal_plans', function (Blueprint $table) {
            $table->dropForeign('cycles_meal_plans_meal_plans_id_foreign');
            $table->dropColumn('meal_plans_id');
            $table->string('default_selections_veg');
        });

        Schema::table('meals_status_changes', function (Blueprint $table) {
            $table->dropForeign('meals_status_changes_meal_id_foreign');
            $table->dropColumn('meal_id');
            $table->dropForeign('meals_status_changes_replace_meal_id_foreign');
            $table->dropColumn('replace_meal_id');
            $table->dropColumn('new_status');
            $table->dropColumn('change_date')    ;
            $table->unsignedInteger('cycle_id')->default(0);
            $table->string('meal_ids_remove')->nullable();
            $table->string('meal_ids_add')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedInteger('stripe_subscription_id');
        });

        Schema::table('subscriptions_selections', function (Blueprint $table) {
            $table->dropColumn('cycle_subscription_status')->default(0);
        });

        Schema::table('cycles_meal_plans', function (Blueprint $table) {
            $table->unsignedInteger('meal_plans_id')->default(0);
            $table->dropColumn('default_selections_veg');
        });

        Schema::table('meals_status_changes', function (Blueprint $table) {
            $table->unsignedInteger('meal_id')->default(0);
            $table->unsignedInteger('replace_meal_id')->default(0);
            $table->unsignedInteger('new_status')->default(0);
            $table->datetime('change_date');

            $table->dropColumn('cycle_id')->default(0);
            $table->dropColumn('meal_ids_remove')->nullable();
            $table->dropColumn('meal_ids_add')->nullable();
        });
    }
}
