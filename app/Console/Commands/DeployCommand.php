<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
class DeployCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "run:deploy";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Run All the Commands to Deploy migration module Menu";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call("cms:migrate-core");
        Artisan::call("update:cms-menu-core");
        Artisan::call("update:cms-module-core");
        Artisan::call("tenants:migrate");
        Artisan::call("tenants:update-module");
        Artisan::call("tenants:update-menu");

        $this->info("Run All Commands Successfully");
    }
}
