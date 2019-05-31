<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfsCuttoffTimeDeliveryTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_timings', function (Blueprint $table) {
            $table->time('infs_cutoff_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_timings', function (Blueprint $table) {
            $table->dropColumn('infs_cutoff_time');
        });
    }
}
