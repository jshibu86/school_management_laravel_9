<?php

namespace cms\core\menu\Console\Commands;

use App\Models\MultiTenant;
use Illuminate\Console\Command;
use cms\core\subscription\Models\PlanPriceModel;
use cms\core\schoolmanagement\Models\SchoolProfile;
use cms\core\subscription\Models\ModuleModel;
//helpers
use Menu;
use Session;
class MenuUpdateTenantDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "tenants:update-menu";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Update Newly created or Edited Menus to tenants Databases";

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
        $tenants = MultiTenant::get();

        if (sizeof($tenants)) {
            foreach ($tenants as $multitenant) {
                $schoolProfileDB = SchoolProfile::where(
                    "tenant_id",
                    $multitenant->id
                )->first();
                $filterList = PlanPriceModel::where(
                    "plan_id",
                    $schoolProfileDB->plan_id
                )
                    ->get("modules")
                    ->first();
                $modulesArray = json_decode($filterList->modules, true);
                $moduleList = ModuleModel::where("status", 1)
                    ->whereIn("id", $modulesArray)
                    ->pluck("module_name")
                    ->toArray();
                Session::put(["module_list" => $moduleList]);
                $multitenant->run(function () {
                    Menu::registerMenuTenants("tenant");
                });

                $this->info("Menu Updated:" . $multitenant->id);
            }
        }
    }
}
