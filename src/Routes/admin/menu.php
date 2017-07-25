<?php

use Baytek\Laravel\Content\Models\Content;

// Add the default route to the routes list for this provider
$router->resource('menu', 'MenuController');

$router->bind('menu', function ($slug) {

    // Try to find the page with the slug, this should also check its parents and should also split on /
    $menu = Content::ofContentType('menu')->where('contents.key', $slug)->first();

    // Show the 404 page if not found
    if(is_null($menu)) {
        abort(404);
    }

    return $menu;
});