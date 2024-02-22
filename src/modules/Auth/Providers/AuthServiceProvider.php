<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Role\Services\RoleUserProvider;
use Laravel\Passport\Passport;
use Modules\Auth\Services\AuthService;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePassport();
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

    /**
     * Register all passport configurations
     *
     * @return void
     */
    protected function configurePassport(): void
    {
        $hours = AuthService::config('token_expiry_time');
        Passport::tokensExpireIn(now()->addHours($hours));

        Passport::hashClientSecrets();
    }
}
