<?php

namespace Baytek\Laravel\Menu;

use Illuminate\Support\ServiceProvider;
use Blade;

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
        // $this->loadRoutesFrom(__DIR__.'/Routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../resources/Migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/Views', 'Content');

        Blade::directive('anchor', function ($expression)
        {
            $anchor = "new \Baytek\Laravel\Menu\Anchor($expression)";
            return "<?php if (isset(\$__menu)): \$__menu[] = $anchor; else: echo $anchor; endif;?>";
        });

        Blade::directive('button', function ($expression)
        {
            $button = "new \Baytek\Laravel\Menu\Button($expression)";
            return "<?php if (isset(\$__menu)): \$__menu[] = $button; else: echo $button; endif;?>";
        });

        Blade::directive('link', function ($expression)
        {
            $link = "new \Baytek\Laravel\Menu\Link($expression)";
            return "<?php if (isset(\$__menu)): \$__menu[] = $link; else: echo $link; endif;?>";
        });

        Blade::extend(function($value)
        {
            return preg_replace(
                // Expression
                '/(.*?)@menu(\((.*?)\))?(.*?)@endmenu?(.*?)/s',
                // Replacement
                '$1 <?php $__menu = [];$__menuParams = array_collapse([$3]);?> $4<?php echo new \Baytek\Laravel\Menu\Menu($__menu, $__menuParams); unset($__menu);?>$5',
                // Value
                $value
            );
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Menu::class, function ($app)
        {
            return new Menu();
        });

        $this->app->bind(Anchor::class, function ($app)
        {
            return new Anchor();
        });

        $this->app->bind(Button::class, function ($app)
        {
            return new Button();
        });

        $this->app->bind(Link::class, function ($app)
        {
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
}