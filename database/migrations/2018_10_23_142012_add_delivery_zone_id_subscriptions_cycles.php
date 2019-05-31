<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeliveryZoneIdSubscriptionsCycles extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions_cycles', function (Blueprint $table) {
            $table->unsignedInteger('delivery_zone_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions_cycles', function (Blueprint $table) {
            $table->dropColumn('delivery_zone_id');
        });
    }
}
