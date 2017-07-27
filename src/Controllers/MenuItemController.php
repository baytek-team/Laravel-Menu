<?php

namespace Baytek\Laravel\Menu\Controllers;

use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Controllers\Controller;
use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Menu\Models\Menu;
use Baytek\Laravel\Menu\Models\MenuItem;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use View;

class MenuItemController extends ContentController
{
    /**
     * The model the Content Controller super class will use to access the resource
     *
     * @var Baytek\Laravel\Content\Types\Webpage\Webpage
     */
    protected $model = MenuItem::class;

    /**
     * View namespace used to load models
     * @var String
     */
    protected $viewNamespace = 'menus';

    /**
     * List of views this content type uses
     * @var Array
     */
    protected $views = [
        'index' => 'item.index',
        'create' => 'item.create',
        'edit' => 'item.edit',
        'show' => 'item.index',
    ];

    /**
     * Show the index of all content with content type 'menu'
     *
     * @return \Illuminate\Http\Response
     */
    // public function index(Menu $menu)
    // {
    //     $this->viewData['index'] = [
    //         'menu' => $menu,
    //         'menus' => Content::childrenOf('menu')->get(),
    //     ];
    //     return parent::contentIndex();
    // }

    /**
     * Show the form for creating a new menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Menu $menu)
    {
        $this->viewData['create'] = [
            'menu' => $menu,
            'item' => new Menu,
            'parents' => Content::childrenOf('menu')->get(),
        ];

        return parent::contentCreate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Menu $menu)
    {
        $this->redirects = false;

        $request->merge([
            'key' => str_slug($request->title)
        ]);

        $item = parent::contentStore($request);
        $item->saveRelations([
            'content-type' => content_id('menu-item'),
            'parent-id' => $menu->id,
        ]);

        $item->saveMetadatas(array_filter($request->except(['_token', 'content', 'title'])));

        return redirect(route('menu.item.show', [$menu, $item]));
    }

    /**
     * Show the form for creating a new menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        $this->viewData['edit'] = [
            'menu' => $menu,
            'item' => new Menu,
            'parents' => Menu::childrenOf('menu')->get(),
        ];

        return parent::contentEdit($menu);
    }

    public function show(Menu $menu)
    {
        $this->viewData['show'] = [
            'menu' => $menu,
            'menus' => Menu::childrenOf($menu)->get(),
        ];

        return parent::contentShow($menu);
    }

}