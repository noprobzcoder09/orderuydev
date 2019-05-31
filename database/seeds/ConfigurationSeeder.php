<?php

use Illuminate\Database\Seeder;
use App\Models\Configurations;

class ConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $config1 = [
        	'slug' => 'report-error-api',
        	'name' => 'Api Error Reporting',
        	'value' => 'noel@fusedsoftware.com,victor@fusedsoftware.com,jerson@fusedsoftware.com',
        ];


        $config2 = [
            'slug' => 'manage-subscription-text',
            'name' => 'Manage Subscription Text',
            'value' => '',
        ];

        Configurations::firstOrCreate($config1);
        Configurations::firstOrCreate($config2);

    }
}
