<?php

namespace Core\Providers;

use App\Http\Middleware\EncryptCookies;
use Core\Helpers\Helper;
use Core\Http\Middleware\VerifyCsrfToken;
use Illuminate\Http\Resources\Json\JsonResource;
use ReflectionClass;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->pushMiddlewareToGroup(VerifyCsrfToken::class, 'web');
        $this->pushMiddlewareToGroup(EncryptCookies::class, 'api');

        JsonResource::withoutWrapping();
        $this->registerCommands();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AuthServiceProvider::class);
    }

    /**
     * Get the namespace based on directory.
     *
     * @return string
     */
    protected function getNamespace(): string
    {
        return Helper::coreNamespace();
    }

    /**
     * Get the commands' module path.
     *
     * @return string
     */
    public function getModulePath(): string
    {
        $reflection = new ReflectionClass(get_called_class());
        return dirname($reflection->getFileName(), 2);
    }
}
