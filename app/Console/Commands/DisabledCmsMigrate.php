<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DisabledCmsMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "cms-migrate {--module=} {--path=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "This command has been disabled";

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
        $this->info("This command is disabled and will not execute.");
        return 0;
    }
}
