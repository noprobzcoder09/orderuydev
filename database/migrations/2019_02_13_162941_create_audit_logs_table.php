<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->longText('description');
            $table->integer('action_by')->unsigned();
            $table->string('ip_address', 100);
            $table->string('country', 255);
            $table->string('device_name', 255);
            $table->string('platform_name', 255);
            $table->string('browser_name', 255);
            $table->string('browser_version', 255);
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
        Schema::dropIfExists('audit_logs');
    }
}
