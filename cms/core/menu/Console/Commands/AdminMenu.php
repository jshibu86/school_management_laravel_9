<?php

namespace cms\core\menu\Console\Commands;

use Illuminate\Console\Command;

//helpers
use Menu;
class AdminMenu extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "update:cms-menu-core";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update School Management Central Db Admin menus";

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
        Menu::registerMenu("core");
    }
}
