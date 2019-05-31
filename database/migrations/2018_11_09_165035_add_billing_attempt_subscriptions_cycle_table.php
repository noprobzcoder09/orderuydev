<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingAttemptSubscriptionsCycleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions_cycles', function (Blueprint $table) {
            $table->unsignedInteger('billing_attempt')->nullable();
            $table->text('billing_attempt_desc')->nullable();
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
            $table->dropColumn('billing_attempt_desc');
            $table->dropColumn('billing_attempt');
        });
    }
}
