<?php

namespace cms\core\module\Console\Commands;

use Illuminate\Console\Command;
use Module;

class ModuleTenantUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "update:cms-module-core-tenant";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update module both core and local to on boared school with some restrice modules";

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
        Module::registerModule("tenant");
    }
}
