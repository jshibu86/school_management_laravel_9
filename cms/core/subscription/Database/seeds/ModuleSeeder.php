<?php
namespace cms\core\subscription\Database\seeds;

use cms\core\subscription\Models\ModuleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Cms;
use cms\core\configurations\helpers\Configurations;
use cms\core\subscription\Models\PlanPriceModel;
use cms\core\schoolmanagement\Models\SchoolProfile;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $allmodules = Cms::allModules();
        $exclude = Configurations::EXCLUDEMODULES;

        foreach ($allmodules as $key => $value) {
            if (in_array($value["name"], $exclude)) {
                unset($allmodules[$key]);
            }
        }

        // Reindex the array
        $allmodules = array_values($allmodules);
        foreach ($allmodules as $module) {
            $type = $module["type"] == "core" ? 1 : 2;
            $old = ModuleModel::where("module_name", "=", $module["name"])
                ->where("type", "=", $type)
                ->first();
            //already available
            if (count((array) $old) == 0) {
                $obj = new ModuleModel();
                $obj->module_name = $module["name"];
                $obj->module_description = $module["name"];
                $obj->module_slug = Str::slug($module["name"]);
                $obj->type = $type;

                $obj->status = 1;
                $obj->save();
            }
        }
        return 0;
    }
}
