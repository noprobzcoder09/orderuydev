<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftdeleteUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_addresses', function ($table) {
            $table->softDeletes();
        });

        Schema::table('users', function ($table) {
            $table->softDeletes();
        });

        Schema::table('user_notes', function ($table) {
            $table->softDeletes();
        });

        Schema::table('user_details', function ($table) {
            $table->softDeletes();
        });

        Schema::table('user_meta', function ($table) {
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
        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn('deleted_at');
        });

        Schema::table('user_meta', function (Blueprint $table) {
            $table->removeColumn('deleted_at');
        });

        Schema::table('user_details', function (Blueprint $table) {
            $table->removeColumn('deleted_at');
        });

        Schema::table('user_notes', function (Blueprint $table) {
            $table->removeColumn('deleted_at');
        });

        Schema::table('user_addresses', function (Blueprint $table) {
            $table->removeColumn('deleted_at');
        });
    }
}
