<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coupons', function (Blueprint $table) {
            // /             
            $table->integer('used')->default(0)->change();
            $table->text('user')->nullable()->change();
            $table->date('expiry_date')->change();
            $table->text('products')->nullable()->change();
            $table->integer('min_order')->default(0)->change();
            $table->integer('max_uses')->change();
            $table->integer('number_used')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coupons', function (Blueprint $table) {
            //
            //$table->removeColumn('delete_at');
        });
    }
}
