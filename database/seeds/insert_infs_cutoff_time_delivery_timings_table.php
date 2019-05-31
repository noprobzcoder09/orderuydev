<?php

use Illuminate\Database\Seeder;

class insert_infs_cutoff_time_delivery_timings_table extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('delivery_timings')
            ->whereIn('id',[1,2])
            ->update(['infs_cutoff_time' => '23:45']);
    }
}
