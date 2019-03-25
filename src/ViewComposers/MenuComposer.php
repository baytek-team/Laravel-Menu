<?php

namespace Baytek\Laravel\Menu\ViewComposers;

use Illuminate\View\View;
use Baytek\Laravel\Menu\Models\Menu;
use Baytek\Laravel\Menu\Models\MenuItem;
use Baytek\Laravel\Content\Models\Content;

class MenuComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    // protected $users;

    // /**
    //  * Create a new profile composer.
    //  *
    //  * @param  UserRepository  $users
    //  * @return void
    //  */
    // public function __construct(UserRepository $users)
    // {
    //     // Dependencies automatically resolved by service container...
    //     $this->users = $users;
    // }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $menus = Menu::childrenOfType(content_id('admin-menu'), 'menu')->get();

        foreach($menus as &$menu) {
            $menu->items = MenuItem::childrenOf($menu->id)->withMeta()->withContents()->get();
        }
        // Menu::childrenOfType(content_id('admin-menu'), 'menu')->get()->pluck('id');
        $view->with('menu', $menus);
    }
}