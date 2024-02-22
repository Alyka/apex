<?php

namespace Modules\Role\Providers;

use Core\Providers\ServiceProvider;
use Modules\Role\Contracts\RoleRepository;
use Modules\Role\Contracts\RoleService as RoleServiceContract;
use Modules\Role\Repositories\RoleRepositoryEloquent;
use Modules\Role\Services\RoleService;

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

        $this->app->bind(RoleRepository::class, RoleRepositoryEloquent::class);
        $this->app->bind(RoleServiceContract::class, RoleService::class);
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
        $source = __DIR__ . '/../configs/config.php';

        $this->mergeConfigFrom($source, RoleService::name());
    }
}
