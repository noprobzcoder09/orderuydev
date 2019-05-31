<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('notif_name', 200);
            $table->text('notif_description');
            $table->string('notif_value', 250);
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
        Schema::dropIfExists('settings_notifications');
    }
}
