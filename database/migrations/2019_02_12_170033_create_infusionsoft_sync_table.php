<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfusionsoftSyncTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infusionsoft_sync', function (Blueprint $table) {
            $table->increments('id');
            $table->string('field');
            $table->string('old_value');
            $table->string('new_value');
            $table->unsignedTinyInteger('status');
            $table->string('contacts_updated');
            $table->datetime('started_at')->nullable();
            $table->datetime('finished_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infusionsoft_sync');
    }
}
