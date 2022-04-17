<?php

namespace Shahnewaz\Permissible\Providers;

use Illuminate\Support\ServiceProvider;
use Shahnewaz\Permissible\Console\Commands\RolePermissionSeed;
use Shahnewaz\Permissible\Console\Commands\Setup;
use Shahnewaz\Permissible\Http\Middleware\PermissionAccessGuard;
use Shahnewaz\Permissible\Http\Middleware\RoleAccessGuard;
use Shahnewaz\Permissible\Services\PermissibleService;


class PermissibleServiceProvider extends ServiceProvider
{
    /**
     * This provider cannot be deferred since it loads routes.
     * If deferred, run `php artisan route:cache`
     **/
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        if ($this->app->runningInConsole()) {
            $this->commands([
                RolePermissionSeed::class
            ]);
        }
        if ($this->app->runningInConsole()) {
            $this->commands([
                Setup::class
            ]);
        }

        $this->load();
        $this->publish();
    }

    public function load()
    {
        // Routes
        $this->loadRoutesFrom($this->packagePath('src/routes/web.php'));
        // Migrations
        $this->loadMigrationsFrom($this->packagePath('database/migrations'));
        // Translations
        $this->loadTranslationsFrom($this->packagePath('resources/lang'), 'permissible');
        // Views
        $this->loadViewsFrom($this->packagePath('resources/views'), 'permissible');
    }

    // Root path for package files

    private function packagePath($path)
    {
        return __DIR__ . "/../../$path";
    }

    // Facade provider

    private function publish()
    {
        // Publish Translations
        $this->publishes([
            $this->packagePath('resources/lang') => resource_path('lang/vendor/permissible'),
        ], 'permissible-translations');

        // Publish Permissible Config
        $this->publishes([
            $this->packagePath('config/permissible.php') => config_path('permissible.php'),
        ], 'permissible-config');

        $this->publishes([
            $this->packagePath('config/jwt.php') => config_path('jwt.php'),
        ], 'permissible-jwt-config');

        $this->publishes([
            $this->packagePath('config/auth.php') => config_path('auth.php'),
        ], 'permissible-auth-config');

        $this->publishes([
            $this->packagePath('config/jwt.php') => config_path('jwt.php'),
        ], 'config');

        $this->publishes([
            $this->packagePath('config/auth.php') => config_path('auth.php'),
        ], 'config');

        // Publish views
        $this->publishes([
            $this->packagePath('resources/views') => resource_path('views/vendor'),
        ], 'permissible-views');
    }

    // Class loaders for package

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->packagePath('config/permissible.php'), 'permissible');
        // Add route middlewares
        $this->app['router']->aliasMiddleware(
            'role', RoleAccessGuard::class
        );
        $this->app['router']->aliasMiddleware(
            'permission', PermissionAccessGuard::class
        );

        // Register Permissible Service
        $this->app->singleton('permissible', function ($app) {
            return new PermissibleService;
        });
    }

    // Publish required resouces from package

    public function provides()
    {
        return ['permissible'];
    }
}
