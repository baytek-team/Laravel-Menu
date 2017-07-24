<?php

namespace Baytek\Laravel\Menu\Commands;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Commands\Installer;
use Baytek\Laravel\Menu\Seeders\MenuSeeder;
use Baytek\Laravel\Menu\Models\Menu;
use Baytek\Laravel\Menu\MenuServiceProvider;
use Spatie\Permission\Models\Permission;

use Artisan;
use DB;

class MenuInstaller extends Installer
{
    public $name = 'Menu';
    protected $protected = ['Menu'];
    protected $provider = MenuServiceProvider::class;
    protected $model = Menu::class;
    protected $seeder = MenuSeeder::class;

    public function shouldPublish()
    {
        return true;
    }

    public function shouldMigrate()
    {
        $pluginTables = [
            env('DB_PREFIX', '').'contents',
            env('DB_PREFIX', '').'content_meta',
            env('DB_PREFIX', '').'content_histories',
            env('DB_PREFIX', '').'content_relations',
        ];

        return collect(array_map('reset', DB::select('SHOW TABLES')))
            ->intersect($pluginTables)
            ->isEmpty();
    }

    public function shouldProtect()
    {
        foreach ($protected as $model) {
            foreach(['view', 'create', 'update', 'delete'] as $permission) {

                // If the permission exists in any form do not reseed.
                if(Permission::where('name', title_case($permission.' '.$model)->exists()) {
                    return false;
                }
            }
        }

        return true;
    }

    public function shouldSeed()
    {
        $relevantRecords = [
            'menu',
            'primary-navigation-menu',
        ];

        return Content::whereIn('key', $relevantRecords)->count() === 0;
    }
}
