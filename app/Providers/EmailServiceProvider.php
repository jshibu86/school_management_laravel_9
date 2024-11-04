<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (env("IS_ENABLE_PROVIDER")) {
            \CmsMail::setMailTrapConfig();
            \CmsMail::setMailConfig();
        }
    }
}
