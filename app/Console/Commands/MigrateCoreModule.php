<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cms;
class MigrateCoreModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "cms:migrate-core";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Migration For Only Core Module";

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
        if (true) {
            if (
                !\File::exists(
                    base_path() .
                        DIRECTORY_SEPARATOR .
                        "cms" .
                        DIRECTORY_SEPARATOR .
                        "tmp" .
                        DIRECTORY_SEPARATOR .
                        "migration"
                )
            ) {
                \File::makeDirectory(
                    base_path() .
                        DIRECTORY_SEPARATOR .
                        "cms" .
                        DIRECTORY_SEPARATOR .
                        "tmp" .
                        DIRECTORY_SEPARATOR .
                        "migration",
                    0777,
                    true
                );
            }
            $cms = Cms::getCoreModulePath(false);
            foreach ($cms as $module) {
                if (
                    \File::exists(
                        base_path() .
                            $module .
                            DIRECTORY_SEPARATOR .
                            "Database" .
                            DIRECTORY_SEPARATOR .
                            "Migration" .
                            DIRECTORY_SEPARATOR
                    )
                ) {
                    $files = \File::files(
                        base_path() .
                            $module .
                            DIRECTORY_SEPARATOR .
                            "Database" .
                            DIRECTORY_SEPARATOR .
                            "Migration" .
                            DIRECTORY_SEPARATOR
                    );

                    foreach ($files as $file) {
                        \File::copy(
                            $file,
                            base_path() .
                                DIRECTORY_SEPARATOR .
                                "cms" .
                                DIRECTORY_SEPARATOR .
                                "tmp_central" .
                                DIRECTORY_SEPARATOR .
                                "migration" .
                                DIRECTORY_SEPARATOR .
                                substr(
                                    $file,
                                    strrpos($file, DIRECTORY_SEPARATOR) + 1
                                )
                        );
                    }
                }
            }

            $this->call("migrate", [
                "--path" =>
                    DIRECTORY_SEPARATOR .
                    "cms" .
                    DIRECTORY_SEPARATOR .
                    "tmp_central" .
                    DIRECTORY_SEPARATOR .
                    "migration",
            ]);
        }
        $this->info("Core Modules Migrated Successfully");
    }
}
