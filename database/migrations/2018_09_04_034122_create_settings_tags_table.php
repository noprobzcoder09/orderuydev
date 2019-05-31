<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tag_name', 100);
            $table->string('tag_description', 200);
            $table->integer('tag_id');
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
        Schema::dropIfExists('settings_tags');
    }
}
