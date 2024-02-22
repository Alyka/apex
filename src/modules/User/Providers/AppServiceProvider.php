<?php

namespace Modules\User\Providers;

use Modules\User\Contracts\UserService as UserServiceContract;
use Modules\User\Services\UserService;
use Modules\User\Contracts\UserRepository;
use Modules\User\Repositories\UserRepositoryEloquent;
use Core\Providers\ServiceProvider;

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

        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(UserServiceContract::class, UserService::class);
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
        $source = __DIR__ . '/../config/config.php';

        $this->mergeConfigFrom($source, UserService::name());
    }
}
