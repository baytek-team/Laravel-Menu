<?php

namespace Baytek\Laravel\Menu;

use Baytek\Laravel\Content\Installer;
use Baytek\Laravel\Menu\Seeders\MenuSeeder;
use Baytek\Laravel\Menu\Models\Menu;
use Baytek\Laravel\Menu\MenuServiceProvider;
use Spatie\Permission\Models\Permission;

use Artisan;
use DB;

class MenuInstaller extends Installer
{
    public $name = 'Menu';
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
        foreach(['view', 'create', 'update', 'delete'] as $permission) {

            // If the permission exists in any form do not reseed.
            if(Permission::where('name', title_case($permission.' '.$this->name))->exists()) {
                return false;
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

        return (new $this->model)->whereIn('key', $relevantRecords)->count() === 0;
    }
}
