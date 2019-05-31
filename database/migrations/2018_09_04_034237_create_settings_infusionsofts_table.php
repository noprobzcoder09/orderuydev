<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsInfusionsoftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_infusionsofts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('infs_set_name', 200);
            $table->text('infs_set_description');
            $table->string('infs_set_value', 250);
            $table->boolean('show_in_admin');
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
        Schema::dropIfExists('settings_infusionsofts');
    }
}
