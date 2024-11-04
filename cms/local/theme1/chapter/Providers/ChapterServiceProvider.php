<?php

namespace cms\chapter\Providers;

use Illuminate\Support\ServiceProvider;
use Route;
use Cms;
class ChapterServiceProvider extends ServiceProvider
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
    public function register()
    {
        $this->registerViews();
        //$this->registerRoot();
        $this->registerAdminRoot();
        $this->registerMiddleware();
    }

    public function registerRoot()
    {
        Route::prefix("")
            ->middleware(["web"])
            ->namespace("cms\chapter\Controllers")
            ->group(__DIR__ . "/../routes.php");
    }
    public function registerAdminRoot()
    {
        Route::prefix("administrator")
            ->middleware(["web", "Admin"])
            ->namespace("cms\chapter\Controllers")
            ->group(__DIR__ . "/../adminroutes.php");
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $theme = Cms::getCurrentTheme();

        $viewPath = resource_path("views/modules/chapter");

        //$sourcePath = __DIR__.'/../resources/views';
        $Path = __DIR__ . "/../resources/views";
        $sourcePath =
            base_path() . "/cms/local/" . $theme . "/chapter/resources/views";

        $this->publishes([
            $sourcePath => $viewPath,
        ]);
        $this->loadViewsFrom(
            array_merge(
                array_map(
                    function ($path) {
                        return $path . "/modules/chapter";
                    },
                    [$Path]
                ),
                [$sourcePath, $Path]
            ),
            "chapter"
        );
    }
    /*
     * register middleware
     */
    public function registerMiddleware()
    {
        app("router")->aliasMiddleware("MiddleWareName", middlewarepath::class);
    }
}
