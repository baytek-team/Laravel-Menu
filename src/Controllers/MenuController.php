<?php

namespace Baytek\Laravel\Menu\Controllers;

use Baytek\Laravel\Content\Controllers\ContentController;
use Baytek\Laravel\Content\Controllers\Controller;
// use Baytek\Laravel\Content\Models\Content;
// use Baytek\Laravel\Content\Models\ContentMeta;
// use Baytek\Laravel\Content\Models\ContentRelation;
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
        'index' => 'index',
        'create' => 'create',
        'edit' => 'edit',
        'show' => 'show',
    ];

    /**
     * Show the index of all content with content type 'menu'
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->viewData['index'] = [
            'menus' => Menu::childrenOf('menu')->get(),
        ];

        return parent::index();
    }

    /**
     * Show the form for creating a new menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->viewData['create'] = [
            'parents' => Menu::childrenOf('menu')->get(),
        ];

        return parent::create();
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

        $menu = parent::store($request);

        $menu->saveRelation('parent-id', $request->parent_id);

        return redirect(route($this->names['singular'].'.show', $menu));
    }

    /**
     * Show the form for creating a new menu.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->viewData['edit'] = [
            'parents' => Menu::childrenOf('menu')->get(),
        ];

        return parent::edit($id);
    }

}