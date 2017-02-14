<?php

namespace Baytek\Laravel\Menu;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{

    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/Routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../resources/Migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/Views', 'Content');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Menu::class, function ($app) {
            return new Menu(config('cms.menu'));
        });

        $this->app->bind(MenuItem::class, function ($app) {
            return new MenuItem(config('cms.menu'));
        });
    }

    public function provides()
    {
        return [
            Menu::class,
            MenuItem::class,
        ];
    }
}