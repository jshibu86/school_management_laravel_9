<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Cms;
use cms\core\configurations\helpers\Configurations;

class TenantSchoolMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "tenants:school-migrate {--tenants=}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Command for run Once onboard New School run their Migration also bulk run Migration";

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
                        "tmp_tenant" .
                        DIRECTORY_SEPARATOR .
                        "migration"
                )
            ) {
                \File::makeDirectory(
                    base_path() .
                        DIRECTORY_SEPARATOR .
                        "cms" .
                        DIRECTORY_SEPARATOR .
                        "tmp_tenant" .
                        DIRECTORY_SEPARATOR .
                        "migration",
                    0777,
                    true
                );
            }
            $cms = Cms::allModulesPath(false);
            foreach ($cms as $module) {
                if (
                    !in_array($module, Configurations::EXCLUDEMODULESBASEBATH)
                ) {
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
                            $destination =
                                base_path() .
                                DIRECTORY_SEPARATOR .
                                "cms" .
                                DIRECTORY_SEPARATOR .
                                "tmp_tenant" .
                                DIRECTORY_SEPARATOR .
                                "migration" .
                                DIRECTORY_SEPARATOR .
                                substr(
                                    $file,
                                    strrpos($file, DIRECTORY_SEPARATOR) + 1
                                );

                            // Copy the file to the destination
                            \File::copy($file, $destination);

                            // Set permissions to 777
                            chmod($destination, 0777);
                        }
                    }
                }
            }
        }
        tenancy()->runForMultiple($this->option("tenants"), function ($tenant) {
            $this->call("migrate", [
                "--path" =>
                    DIRECTORY_SEPARATOR .
                    "cms" .
                    DIRECTORY_SEPARATOR .
                    "tmp_tenant" .
                    DIRECTORY_SEPARATOR .
                    "migration",
            ]);
            $this->line("Tenant Migration Done: {$tenant->getTenantKey()}");
        });
    }
}
