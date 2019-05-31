<?php

use Illuminate\Database\Seeder;
use App\Models\ActivityLogCredentials;

class ActivityLogCredentialSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActivityLogCredentials::create(['deletion_password' => bcrypt('fusedsoftware')]);
    }
}
