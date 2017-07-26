<?php

namespace Baytek\Laravel\Menu;

use Baytek\Laravel\Content\Models\Content;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;

use Blade;
use Route;

class MenuServiceProvider extends AuthServiceProvider
{

    protected $defer = true;

    /**
     * List of permission policies used by this package
     * @var [type]
     */
    protected $policies = [
        Models\Menu::class => Policies\MenuPolicy::class,
    ];

    /**
     * List of artisan commands provided by this package
     * @var Array
     */
    protected $commands = [
        Commands\MenuInstaller::class,
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'menu');

        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/menu'),
        ], 'views');

        // Publish routes to the App
        $this->publishes([
            __DIR__.'/../src/Routes' => base_path('routes'),
        ], 'routes');

        $this->publishes([
            __DIR__.'/../config/menu.php' => config_path('menu.php'),
        ], 'config');

        $this->bootBladeDirectives();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);

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

        $this->app->register(RouteServiceProvider::class);
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
                '/(.*?)@clink(\((.*?)\))?(.*?)@endclink?(.*?)/s',
                // Replacement
                '$1 <?php ob_start(); ?>$4<?php $__menu_content = ob_get_clean(); echo new \Baytek\Laravel\Menu\Link($__menu_content, $3); unset($__menu_content)?> $5',
                // Value
                $value
            );
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

        Blade::extend(function ($value) {

            return preg_replace(
                // Expression
                '/(.*?)@ifBreadcrumbs?(.*?)/s',
                // Replacement
                '$1 <?php if(is_null(Route::getCurrentRoute()) && count(explode(\'/\', Route::getCurrentRoute()->uri())) == 1): ?> $2',
                // Value
                $value
            );
        });

        Blade::extend(function ($value) {

            return preg_replace(
                // Expression
                '/(.*?)@endBreadcrumbs?(.*?)/s',
                // Replacement
                '$1 <?php endif; ?> $2',
                // Value
                $value
            );
        });

        Blade::directive('breadcrumbs', function ($expression) {
            return "<?php echo new \Baytek\Laravel\Menu\Breadcrumbs?>";
        });

    }
}
