<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('coupon_code', 35);
            $table->string('discount_type', 100);
            $table->double('discount_value', 15, 8);

            $table->integer('used');
            $table->integer('user');
            $table->date('expiry_date');
            $table->text('products');
            $table->integer('min_order');
            $table->integer('max_uses');
            $table->integer('number_used');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
