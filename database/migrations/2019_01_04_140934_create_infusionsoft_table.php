<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfusionsoftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infusionsoft_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('app_name')->nullable();
            $table->string('version')->nullable();
            $table->string('legacy_key')->nullable();
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('access_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('token_type')->nullable();
            $table->string('expires_in')->nullable();
            $table->string('scope')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('environment')->nullable();
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
        Schema::dropIfExists('infusionsoft_accounts');
    }
}
