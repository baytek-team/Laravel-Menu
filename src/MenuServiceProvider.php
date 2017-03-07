<?php

namespace Baytek\Laravel\Menu;

use Illuminate\Support\ServiceProvider;

use Blade;
use Route;

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
        $this->loadMigrationsFrom(__DIR__.'/../resources/Migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/Views', 'Menu');

        // $this->publishes([
        //     __DIR__.'/../resources/Views' => resource_path('views/vendor/Menu'),
        // ], 'views');

        // $this->publishes([
        //     __DIR__.'/../resources/migrations/' => database_path('migrations')
        // ], 'migrations');

        $this->publishes([
            __DIR__.'/../resources/Config/menus.php' => config_path('menus.php'),
        ], 'config');

        $this->bootBladeDirectives();

        $this->bootRoutes();

        (new MenuInstaller)->installCommand();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Menu::class, function ($app) {

            return new Menu();
        });

        $this->app->bind(Anchor::class, function ($app) {

            return new Anchor();
        });

        $this->app->bind(Button::class, function ($app) {

            return new Button();
        });

        $this->app->bind(Link::class, function ($app) {

            return new Link();
        });
    }

    public function provides()
    {
        return [
            Menu::class,
            Anchor::class,
            Button::class,
            Link::class,
        ];
    }


    public function bootRoutes()
    {
        Route::group([
                'namespace' => \Baytek\Laravel\Menu\Controllers::class,
                'middleware' => ['web'],
            ], function ($router) {

                // Add the default route to the routes list for this provider
                $router->resource('admin/menu', 'MenuController');

                $router->bind('menu', function ($slug) {

                    // Try to find the page with the slug, this should also check its parents and should also split on /
                    $menu = Webpage::where('contents.key', $slug)->ofContentType('webpage')->first();

                    // Show the 404 page if not found
                    if (is_null($menu)) {
                        abort(404);
                    }
                    return $menu;
                });
            });
    }

    public function bootBladeDirectives()
    {
        Blade::directive('anchor', function ($expression) {

            $anchor = "new \Baytek\Laravel\Menu\Anchor($expression)";
            return "<?php if (isset(\$__menu)): \$__menu[] = $anchor; else: echo $anchor; endif;?>";
        });

        Blade::directive('button', function ($expression) {

            $button = "new \Baytek\Laravel\Menu\Button($expression)";
            return "<?php if (isset(\$__menu)): \$__menu[] = $button; else: echo $button; endif;?>";
        });

        Blade::directive('link', function ($expression) {

            $link = "new \Baytek\Laravel\Menu\Link($expression)";
            return "<?php if (isset(\$__menu)): \$__menu[] = $link; else: echo $link; endif;?>";
        });

        Blade::extend(function ($value) {

            return preg_replace(
                // Expression
                '/(.*?)@menu(\((.*?)\))?(.*?)@endmenu?(.*?)/s',
                // Replacement
                '$1 <?php $__menu = [];$__menuParams = [$3];?> $4<?php echo new \Baytek\Laravel\Menu\Menu($__menu, $__menuParams); unset($__menu);?>$5',
                // Value
                $value
            );
        });
    }
}
