<?php

namespace cms\department\Providers;

use Illuminate\Support\ServiceProvider;
use Route;
use Cms;
class DepartmentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register() {
        $this->registerViews();
        //$this->registerRoute();
        $this->registerAdminRoute();
        //$this->registerMiddleware();
    }

    /**
     * register route
     */
    public function registerRoute()
    {
        Route::prefix('')
            ->middleware(['web'])
            ->namespace('cms\department\Controllers')
            ->group(__DIR__ . '/../routes.php');
    }

    /**
     * register admin route
     */
    public function registerAdminRoute() {

        Route::prefix('administrator')
            ->middleware(['web','Admin'])
            ->namespace('cms\department\Controllers')
            ->group(__DIR__ . '/../adminroutes.php');

    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews() {
        $theme = Cms::getCurrentTheme();

        $viewPath = resource_path('views/modules/department');

        //$sourcePath = __DIR__.'/../resources/views';
        $Path = __DIR__.'/../resources/views';
        $sourcePath = base_path().'/cms/local/'.$theme.'/department/resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);
        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/department';
        }, [$Path]), [$sourcePath,$Path]), 'department');
    }

    /*
     * register middleware
     */
    public function registerMiddleware() {
        app('router')->aliasMiddleware('MiddleWareName', middlewarepath::class);
    }

}
