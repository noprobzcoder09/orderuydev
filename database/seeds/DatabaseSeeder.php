<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(UsersTableSeeder::class);
        // $this->call(InsertCountry::class);
        // $this->call(InsertState::class);
        // $this->call(InsertMeals::class);
        // $this->call(InsertZones::class);
        // $this->call(InsertDefaultSelections::class);
        // $this->call(InsertDefaultUser::class);
        // $this->call(InsertDefaultTimings::class);
        // $this->call(InsertDefaultDeliveryZoneTimings::class);
        // $this->call(InsertConfigurations::class);
        // $this->call(InfussionAccounts::class);
        // $this->call(InsertAdminEmails::class);
        // $this->call(insert_infs_cutoff_time_delivery_timings_table::class);
        // $this->call(ActivityLogCredentialSeed::class);
        $this->call(ConfigurationSeeder::class);
    }
}
