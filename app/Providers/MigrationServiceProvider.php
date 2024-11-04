<?php

namespace App\Providers;
use cms\core\configurations\helpers\Configurations;
use Illuminate\Support\ServiceProvider;

class MigrationServiceProvider extends ServiceProvider
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
        $coreModuleMigrationPath = Configurations::getCoreModuleMigrationPath();

        config([
            "tenancy.migration_parameters.--path" => $coreModuleMigrationPath,
        ]);
    }
}
