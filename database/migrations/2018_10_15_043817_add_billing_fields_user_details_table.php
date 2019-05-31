<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBillingFieldsUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_details', function (Blueprint $table) {
            // /             
            $table->string('billing_first_name', 45)->default('')->nullable();
            $table->string('billing_last_name', 45)->default('')->nullable();
            $table->string('billing_mobile_phone',25)->default('')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_details', function (Blueprint $table) {
            //
            $table->removeColumn('billing_first_name');
            $table->removeColumn('billing_last_name');
            $table->removeColumn('billing_mobile_phone');
        });
    }
}
