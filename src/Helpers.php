<?php

namespace OpenDeveloper\Developer\Helpers;

use OpenDeveloper\Developer\Developer;
use OpenDeveloper\Developer\Auth\Database\Menu;
use OpenDeveloper\Developer\Extension;

class Helpers extends Extension
{
    /**
     * Bootstrap this package.
     *
     * @return void
     */
    public static function boot()
    {
        static::registerRoutes();

        Developer::extend('helpers', __CLASS__);
    }

    /**
     * Register routes for open-developer.
     *
     * @return void
     */
    public static function registerRoutes()
    {
        parent::routes(function ($router) {
            /* @var \Illuminate\Routing\Router $router */
            $router->get('helpers/terminal/database', 'OpenDeveloper\Developer\Helpers\Controllers\TerminalController@database');
            $router->post('helpers/terminal/database', 'OpenDeveloper\Developer\Helpers\Controllers\TerminalController@runDatabase');
            $router->get('helpers/terminal/artisan', 'OpenDeveloper\Developer\Helpers\Controllers\TerminalController@artisan');
            $router->post('helpers/terminal/artisan', 'OpenDeveloper\Developer\Helpers\Controllers\TerminalController@runArtisan');
            $router->get('helpers/scaffold', 'OpenDeveloper\Developer\Helpers\Controllers\ScaffoldController@index');
            $router->post('helpers/scaffold', 'OpenDeveloper\Developer\Helpers\Controllers\ScaffoldController@store');
            $router->get('helpers/routes', 'OpenDeveloper\Developer\Helpers\Controllers\RouteController@index');
        });
    }

    public static function import()
    {
        $lastOrder = Menu::max('order');

        $root = [
            'parent_id' => 0,
            'order'     => $lastOrder++,
            'title'     => 'Helpers',
            'icon'      => 'icon-cogs',
            'uri'       => '',
        ];

        $root = Menu::create($root);

        $menus = [
            [
                'title'     => 'Scaffold',
                'icon'      => 'icon-keyboard',
                'uri'       => 'helpers/scaffold',
            ],
            [
                'title'     => 'Database terminal',
                'icon'      => 'icon-database',
                'uri'       => 'helpers/terminal/database',
            ],
            [
                'title'     => 'Laravel artisan',
                'icon'      => 'icon-terminal',
                'uri'       => 'helpers/terminal/artisan',
            ],
            [
                'title'     => 'Routes',
                'icon'      => 'icon-list-alt',
                'uri'       => 'helpers/routes',
            ],
        ];

        foreach ($menus as $menu) {
            $menu['parent_id'] = $root->id;
            $menu['order'] = $lastOrder++;

            Menu::create($menu);
        }

        parent::createPermission('Developer helpers', 'ext.helpers', 'helpers/*');
    }
}
