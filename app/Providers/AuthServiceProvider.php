<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

use App\Models\Users;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('create', 'App\Policies\AdministratorPolicy@create');
        Gate::define('update', 'App\Policies\AdministratorPolicy@update');
        Gate::define('delete', 'App\Policies\AdministratorPolicy@delete');
        Gate::define('edit', 'App\Policies\AdministratorPolicy@edit');
        Gate::define('list', 'App\Policies\AdministratorPolicy@list');
        Gate::define('manage', 'App\Policies\AdministratorPolicy@manage');
        Gate::define('manage-users', 'App\Policies\AdministratorPolicy@manageUsers');
        Gate::define('manage-setup', 'App\Policies\AdministratorPolicy@manageSetup');
        Gate::define('manage-api', 'App\Policies\AdministratorPolicy@manageApi');
    }
}
