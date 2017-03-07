<?php
namespace Baytek\Laravel\Menu\Seeders;

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
                'content' => 'Menu Content Type',
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
                'key' => 'primary-navigation-menu',
                'title' => 'Primary Navigation Menu',
                'content' => 'Main site navigation.',
                'relations' => [
                    ['content-type', 'menu']
                ]
            ]
        ]);
    }
}
