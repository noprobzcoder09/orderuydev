<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTotalRecurrDiscountSubscriptionsDiscounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscriptions_discounts', function (Blueprint $table) {
            $table->decimal('total_recur_discount',15,3)->default(0)->nullable();
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
            $table->dropColumn('total_recur_discount');
        });
    }
}
