<?php

namespace cms\classtimetable\Providers;

use Illuminate\Support\ServiceProvider;
use Route;
use Cms;
class ClasstimetableServiceProvider extends ServiceProvider
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
            ->namespace('cms\classtimetable\Controllers')
            ->group(__DIR__ . '/../routes.php');
    }

    /**
     * register admin route
     */
    public function registerAdminRoute() {

        Route::prefix('administrator')
            ->middleware(['web','Admin'])
            ->namespace('cms\classtimetable\Controllers')
            ->group(__DIR__ . '/../adminroutes.php');

    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews() {
        $theme = Cms::getCurrentTheme();

        $viewPath = resource_path('views/modules/classtimetable');

        //$sourcePath = __DIR__.'/../resources/views';
        $Path = __DIR__.'/../resources/views';
        $sourcePath = base_path().'/cms/local/'.$theme.'/classtimetable/resources/views';

        $this->publishes([
            $sourcePath => $viewPath
        ]);
        $this->loadViewsFrom(array_merge(array_map(function ($path) {
            return $path . '/modules/classtimetable';
        }, [$Path]), [$sourcePath,$Path]), 'classtimetable');
    }

    /*
     * register middleware
     */
    public function registerMiddleware() {
        app('router')->aliasMiddleware('MiddleWareName', middlewarepath::class);
    }

}
