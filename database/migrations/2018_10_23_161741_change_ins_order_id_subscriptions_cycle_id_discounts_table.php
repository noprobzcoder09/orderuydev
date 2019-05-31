<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeInsOrderIdSubscriptionsCycleIdDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions_discounts', function (Blueprint $table) {
            $table->renameColumn('ins_order_id','subscriptions_cycle_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions_discounts', function (Blueprint $table) {
            $table->renameColumn('subscriptions_cycle_id','ins_order_id');
        });
    }
}
