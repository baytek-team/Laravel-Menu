<?php

use Baytek\Laravel\Menu\Models\Menu;

// Add the default route to the routes list for this provider
Route::resource('menu', 'MenuController');
Route::get('menu/{menu}/create', 'MenuController@create');

Route::group(['as' => 'menu.'], function () {
	Route::resource('menu/{menu}/item', 'MenuItemController');
});

Route::bind('menu', function ($slug) {

    // Try to find the page with the slug, this should also check its parents and should also split on /
    $menu = Menu::where('contents.key', $slug)->first();

    // Show the 404 page if not found
    if(is_null($menu)) {
        abort(404);
    }

    return $menu;
});