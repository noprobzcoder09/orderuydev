<?php

use Illuminate\Database\Seeder;

class InfussionAccounts extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'app_name' => 'ru582',
                'version' => null,
                'legacy_key' => "",
                'client_id' => 'mru3wzzjh4k46w8atkhpje6g',
                'client_secret' => 'vHKbKSkGJq',
                'access_token' => null,
                'refresh_token' => null,
                'expires_in' => null,
                'scope' => null,
                'environment' => "local",
                'redirect_url' => 'http://localhost/youfuel/ultimate_you_fuel/public/infusionsoft/oauth/callbacktoken'
            ],
            [
                'app_name' => 'ru582',
                'version' => null,
                'legacy_key' => "",
                'client_id' => 'mru3wzzjh4k46w8atkhpje6g',
                'client_secret' => 'vHKbKSkGJq',
                'access_token' => null,
                'refresh_token' => null,
                'expires_in' => null,
                'scope' => null,
                'environment' => "development",
                'redirect_url' => 'http://orders.ultimateyoufuel.com/infusionsoft/oauth/callbacktoken'
            ],
            [
                'app_name' => 'ru582',
                'version' => null,
                'legacy_key' => "",
                'client_id' => 'mru3wzzjh4k46w8atkhpje6g',
                'client_secret' => 'vHKbKSkGJq',
                'access_token' => null,
                'refresh_token' => null,
                'expires_in' => null,
                'scope' => null,
                'environment' => "live",
                'redirect_url' => 'http://orders.ultimateyoufuel.com/infusionsoft/oauth/callbacktoken'
            ]
        ];
        
        foreach($data as $row) {
            if (DB::table('infusionsoft_accounts')->where($row)->count() <= 0) {
                DB::table('infusionsoft_accounts')
                ->insert($row);
            }
        }
    }
}
