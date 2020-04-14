<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router)
    {
        // 分组路由
        $this->mapRoutes($router);

        // 全局路由, 优先级高会覆盖分组路由中相同路径路由
        $router->group(['namespace' => $this->namespace], function ($router) {
            require app_path('Http/routes.php');
        });
    }


    /**
     * 分组路由自动加载方法
     *     加载路径：Http/Routes
     *     注意此方法基于Laravel 5.1 编写，其他版本需要适时修改可实现同样功能
     *
     * @param Router $router
     * @return void
     */
    protected function mapRoutes(Router $router)
    {
        foreach($this->subDirList(app_path('Http/Routes')) as $version){
            foreach($this->subDirList(app_path('Http/Routes/' . $version)) as $middleware){
                foreach (glob(app_path('Http/Routes/' . $version . '/' . $middleware .'/*.php')) as $file) {
                    $router->group([
                        'namespace' => $this->namespace,
                        'prefix'     => 'xxx/' . $version,
                        'middleware' => strtolower($middleware) == 'public' ? [] : explode('_', $middleware)
                    ], function($router) use($file) {
                        require $file;
                    });
                }
            }
        }
    }

    protected function subDirList($path)
    {
        $list = scandir($path);
        if($list == false){
            return [];
        }
        return array_slice($list, 2);
    }
}
