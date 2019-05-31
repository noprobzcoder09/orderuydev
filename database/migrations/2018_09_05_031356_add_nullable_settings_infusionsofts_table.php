<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNullableSettingsInfusionsoftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings_infusionsofts', function (Blueprint $table) {
            //
            $table->text('infs_set_description')->nullable()->change();
            $table->string('infs_set_value',250)->default("")->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings_infusionsofts', function (Blueprint $table) {
            //
        });
    }
}
