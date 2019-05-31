<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkSubscriptionsSelectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions_selections', function (Blueprint $table) {
            //
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('cycle_id')->references('id')->on('cycle_dates');
            $table->foreign('subscription_id')->references('id')->on('subscriptions');
            $table->text('menu_selections')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscriptions_selections', function (Blueprint $table) {
            //
            $table->dropForeign('subscriptions_selections_user_id_foreign');
            $table->dropForeign('subscriptions_selections_cycle_id_foreign');
            $table->dropForeign('subscriptions_selections_subscription_id_foreign');
        });
    }
}
