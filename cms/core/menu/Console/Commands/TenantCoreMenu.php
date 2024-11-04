<?php

namespace cms\core\menu\Console\Commands;

use Illuminate\Console\Command;

//helpers
use Menu;
class TenantCoreMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "update:cms-menu-core-tenant";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update Core Menus in Tenant Db with out some Menus and Module whil onboard time";

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
     * @return mixed
     */
    public function handle()
    {
        Menu::registerMenu("tenant");
    }
}
