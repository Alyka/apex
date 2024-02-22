<?php

namespace Core\Providers;

use BadMethodCallException;
use Core\Helpers\Helper;
use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as SupportServiceProvider;
use Illuminate\Contracts\Foundation\CachesConfiguration;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

/**
 * @method void prependMiddlewareToGroup(string $middleware, string $group)
 * @method void pushMiddlewareToGroup(string $middleware, string $group)
 * @method void aliasMiddleware(string $name, string $middleware)
 */
abstract class ServiceProvider extends SupportServiceProvider
{
    /**
     * Service provider classes to register.
     *
     * @var string[]
     */
    protected $providers = [];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        $this->registerConsoleKernel();
    }

    /**
     * Recursively and distinctively merge into the existing auth configuration
     *
     * The merged config will take precedence over existing config.
     *
     * @param string $path
     * @param string $key
     * @return void
     */
    protected function mergeConfigFromReverse($path, $key)
    {
        if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $config = $this->app->make('config');

            $defaultConfig = $config->get($key, []);
            $customConfig = require $path;

            $config->set($key, array_merge_recursive_distinct(
                $defaultConfig,
                $customConfig
            ));
        }
    }

    /**
     * Register all of the commands in the given directory.
     *
     * @return void
     */
    protected function registerCommands()
    {
        $paths = $this->getCommandPath();
        $paths = array_unique(Arr::wrap($paths));

        $paths = array_filter($paths, function ($path) {
            return is_dir($path);
        });

        if (empty($paths)) {
            return;
        }

        foreach ((new Finder)->in($paths)->files() as $command) {
            $command = $this->getNamespace().str_replace(
                ['/', '.php'],
                ['\\', ''],
                Str::after(
                    $command->getRealPath(),
                    $this->getModulePath().DIRECTORY_SEPARATOR
                )
            );

            if (is_subclass_of($command, Command::class) && ! (new ReflectionClass($command))->isAbstract()) {
                Artisan::starting(function ($artisan) use ($command) {
                    $artisan->resolve($command);
                });
            }
        }
    }

    /**
     * Get the namespace based on directory.
     *
     * @return string
     */
    protected function getNamespace(): string
    {
        return Helper::moduleNamespace();
    }

    /**
     * Get the commands' path.
     *
     * @return string
     */
    public function getCommandPath(): string
    {
        $reflection = new ReflectionClass(get_called_class());
        return dirname($reflection->getFileName(), 2) . DIRECTORY_SEPARATOR . 'Commands';
    }

    /**
     * Get the commands' module path.
     *
     * @return string
     */
    public function getModulePath(): string
    {
        $reflection = new ReflectionClass(get_called_class());
        return dirname($reflection->getFileName(), 3);
    }

    /**
     * Hanlde dynamic method calls.
     *
     * @param string $method
     * @param array $args
     * @return void
     */
    public function __call($method, $args)
    {
        $kernelCallables = [
            'pushMiddleware',
            'prependMiddleware',
            'prependToMiddlewarePriority',
            'appendToMiddlewarePriority',
        ];

        $routerCallables = [
            'prependMiddlewareToGroup',
            'pushMiddlewareToGroup',
            'aliasMiddleware',
        ];

        if (in_array($method, $kernelCallables))
            return $this->app[Kernel::class]->{$method}(...$args);

        elseif (in_array($method, $routerCallables))
            return $this->app[Router::class]->{$method}(...$args);
        else
            throw new BadMethodCallException(sprintf(
                'Method %s::%s does not exist.', static::class, $method
            ));
    }
}
