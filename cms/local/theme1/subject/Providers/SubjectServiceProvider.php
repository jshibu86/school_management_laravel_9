<?php

namespace cms\subject\Providers;

use Illuminate\Support\ServiceProvider;
use Route;
use Cms;
class SubjectServiceProvider extends ServiceProvider
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
            ->namespace("cms\subject\Controllers")
            ->group(__DIR__ . "/../routes.php");
    }
    public function registerAdminRoot()
    {
        Route::prefix("administrator")
            ->middleware(["web", "Admin"])
            ->namespace("cms\subject\Controllers")
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

        $viewPath = resource_path("views/modules/subject");

        //$sourcePath = __DIR__.'/../resources/views';
        $Path = __DIR__ . "/../resources/views";
        $sourcePath =
            base_path() . "/cms/local/" . $theme . "/subject/resources/views";

        $this->publishes([
            $sourcePath => $viewPath,
        ]);
        $this->loadViewsFrom(
            array_merge(
                array_map(
                    function ($path) {
                        return $path . "/modules/subject";
                    },
                    [$Path]
                ),
                [$sourcePath, $Path]
            ),
            "subject"
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
