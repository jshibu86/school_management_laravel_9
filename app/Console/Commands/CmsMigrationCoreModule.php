<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Ramesh\Cms\Generators\FileGenerator;
use Cms;

class CmsMigrationCoreModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "make:cms-migration-core {name} {module-name} {--create=} {--table=} ";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Make Core Module Migration";

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
        $module_name = $this->argument("module-name");
        $name = $this->argument("name");

        $FileGenerator = new FileGenerator();
        $FileGenerator
            ->setPath(
                base_path() .
                    DIRECTORY_SEPARATOR .
                    "cms" .
                    DIRECTORY_SEPARATOR .
                    "core"
            )
            ->setClass($name)
            ->setModule($module_name)
            ->MakeMigration($this->option("create"), $this->option("table"))
            ->create();

        $this->info(
            "Created Migration to core module:" . $FileGenerator->filename
        );
    }
}
