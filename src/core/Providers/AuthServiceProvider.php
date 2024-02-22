<?php

namespace Core\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Role\Services\RoleUserProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Passport::hashClientSecrets();
        $this->registerProviders();
    }

    /**
     * Register custom authentication providers.
     *
     * @return void
     */
    protected function registerProviders()
    {
        Auth::provider('role_user', function ($app, array $config) {
            return new RoleUserProvider(
                $app['hash'],
                $config['model'],
                $config
            );
        });
    }
}
