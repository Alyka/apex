<?php

namespace Modules\Auth\Providers;

use Core\Providers\ServiceProvider;
use Modules\Auth\Contracts\AuthRepository;
use Modules\Auth\Contracts\AuthService as AuthServiceContract;
use Modules\Auth\Repositories\AuthRepositoryEloquent;
use Modules\Auth\Services\AuthService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerMigrations();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->app->register(AuthServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(AuthServiceContract::class, AuthService::class);

        parent::register();
    }

    /**
     * Register migrations.
     *
     * @return void
     */
    protected function registerMigrations()
    {
        $serviceMigrationPath = __DIR__ . '/../Database/Migrations';

        $this->loadMigrationsFrom($serviceMigrationPath);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFromReverse(__DIR__ . '/../configs/auth.php', AuthService::name());
    }
}
