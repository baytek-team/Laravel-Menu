<?php
namespace Baytek\Laravel\Menu\Seeders;

use Baytek\Laravel\Menu\Models\Menu;
use Baytek\Laravel\Menu\Models\MenuItem;
use Baytek\Laravel\Content\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedStructure([
            [
                'key' => 'menu',
                'title' => 'Menu',
                'content' => Menu::class,
                'relations' => [
                    ['parent-id', 'content-type']
                ],
                'meta' => [
                    'class' => 'ui menu',
                    'prepend' => '<div class="ui container inverted">',
                    'append' => '</div>',
                ]
            ],
            [
                'key' => 'menu-item',
                'title' => 'Menu Item',
                'content' => MenuItem::class,
                'relations' => [
                    ['parent-id', 'content-type']
                ],
                'meta' => [
                    'class' => 'ui menu',
                    'prepend' => '<div class="ui container inverted">',
                    'append' => '</div>',
                ]
            ],
            [
                'key' => 'admin-menu',
                'title' => 'Administration Navigation Menu',
                'content' => '',
                'relations' => [
                    ['content-type', 'menu'],
                    ['parent-id', 'menu'],
                ]
            ],
            [
                'key' => 'webpage-menu',
                'title' => 'Webpage Navigation Menu',
                'content' => '',
                'relations' => [
                    ['content-type', 'menu'],
                    ['parent-id', 'admin-menu'],
                ]
            ],
            [
                'key' => 'webpage-menu',
                'title' => 'Webpage Navigation Menu',
                'content' => '',
                'meta' => [

                ],
                'relations' => [
                    ['content-type', 'menu'],
                    ['parent-id', 'admin-menu'],
                ]
            ]
        ]);
    }
}
