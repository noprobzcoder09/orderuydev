<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableUserAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            //
            $table->text('address1')->nullable()->change();
            $table->text('address2')->nullable()->change();
            $table->text('suburb')->nullable()->change();
            $table->string('state',250)->default("")->change();
            $table->string('country',250)->default("")->change();
            $table->string('postcode',15)->default("")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_addresses', function (Blueprint $table) {
            //
        });
    }
}
