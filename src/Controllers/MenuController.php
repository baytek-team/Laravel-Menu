<?php

namespace Baytek\Laravel\Menu\Controllers;

use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Controllers\Controller;
use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Menu\Models\Menu;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use View;

class MenuController extends ContentController
{
    /**
     * The model the Content Controller super class will use to access the resource
     *
     * @var Baytek\Laravel\Content\Types\Webpage\Webpage
     */
    protected $model = Menu::class;

    /**
     * List of views this content type uses
     * @var [type]
     */
    protected $views = [
        'index' => 'menu.index',
        'create' => 'menu.create',
        'edit' => 'menu.edit',
        'show' => 'menu.index',
    ];

    /**
     * Show the index of all content with content type 'menu'
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Menu $parent = null)
    {
        $this->viewData['index'] = [
            'parent' => $parent,
            'menus' => Menu::childrenOf('menu')->get(),
        ];

        return parent::contentIndex();
    }

    /**
     * Show the form for creating a new menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Menu $parent = null)
    {
        $this->viewData['create'] = [
            'parent' => $parent,
            'parents' => Menu::childrenOf('menu')->get(),
        ];

        return parent::contentCreate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->redirects = false;

        $request->merge(['key' => str_slug($request->title)]);

        $menu = parent::contentStore($request);
        $menu->saveRelation('parent-id', $request->parent_id);

        return redirect(route($this->names['singular'].'.show', $menu));
    }

    /**
     * Show the form for creating a new menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $parent)
    {
        $this->viewData['edit'] = [
            'parent' => $parent,
            'parents' => Menu::childrenOf($parent->parent())->get(),
        ];

        return parent::contentEdit($parent);
    }

    /**
     * Show the form for creating a new menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        $this->viewData['show'] = [
            'menu' => $menu,
            'menus' => Menu::childrenOf($menu)->get(),
        ];

        return parent::contentShow($menu);
    }

}