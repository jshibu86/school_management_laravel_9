<?php
namespace cms\core\module\helpers;

//helpers
use Configurations;
use App\Models\MultiTenant;
use Cms;

//models
use cms\core\module\Models\ModuleModel;
use Session;
class Module
{
    public static function registerModule($moduletype = "all")
    {
        $modules = Cms::allModules();

        if ($moduletype == "tenant") {
            $exclude = Configurations::EXCLUDEMODULES;

            foreach ($modules as $key => $value) {
                if (in_array($value["name"], $exclude)) {
                    unset($modules[$key]);
                }
            }
            foreach ($modules as $key => $value) {
                if (!in_array($value["name"], Session("module_list"))) {
                    unset($modules[$key]);
                }
            }

            // Reindex the array
            $modules = array_values($modules);
        }

        if ($moduletype == "all" || $moduletype == "tenant") {
            foreach ($modules as $module) {
                $type = $module["type"] == "core" ? 1 : 2;
                $old = ModuleModel::select("version", "id")
                    ->where("name", "=", $module["name"])
                    ->where("type", "=", $type)
                    ->first();
                //already available
                if (count((array) $old) > 0) {
                    //check version is same
                    if ($old->version != $module["version"]) {
                        $obj = ModuleModel::find($old->id);
                        $obj->version = $module["version"];
                        if (isset($module["configuration"])) {
                            $obj->configuration_view = $module["configuration"];
                        }
                        if (isset($module["configuration_data"])) {
                            $obj->configuration_data =
                                $module["configuration_data"];
                        }
                        $obj->save();
                    }
                } else {
                    $obj = new ModuleModel();
                    $obj->name = $module["name"];
                    $obj->type = $type;
                    $obj->version = $module["version"];
                    if (isset($module["configuration"])) {
                        $obj->configuration_view = $module["configuration"];
                    }
                    if (isset($module["configuration_data"])) {
                        $obj->configuration_data =
                            $module["configuration_data"];
                    }
                    $obj->status = 1;
                    $obj->save();
                }
            }
        } else {
            foreach ($modules as $module) {
                if ($module["type"] == $moduletype) {
                    $type = $module["type"] == "core" ? 1 : 2;
                    $old = ModuleModel::select("version", "id")
                        ->where("name", "=", $module["name"])
                        ->where("type", "=", $type)
                        ->first();
                    //already available
                    if (count((array) $old) > 0) {
                        //check version is same
                        if ($old->version != $module["version"]) {
                            $obj = ModuleModel::find($old->id);
                            $obj->version = $module["version"];
                            if (isset($module["configuration"])) {
                                $obj->configuration_view =
                                    $module["configuration"];
                            }
                            if (isset($module["configuration_data"])) {
                                $obj->configuration_data =
                                    $module["configuration_data"];
                            }
                            $obj->save();
                        }
                    } else {
                        $obj = new ModuleModel();
                        $obj->name = $module["name"];
                        $obj->type = $type;
                        $obj->version = $module["version"];
                        if (isset($module["configuration"])) {
                            $obj->configuration_view = $module["configuration"];
                        }
                        if (isset($module["configuration_data"])) {
                            $obj->configuration_data =
                                $module["configuration_data"];
                        }
                        $obj->status = 1;
                        $obj->save();
                    }
                }
            }
        }
    }

    public static function getId($module_name, $type = 2)
    {
        $data = ModuleModel::where("name", "=", $module_name)
            ->where("type", $type)
            ->select("id")
            ->first();
        if (count((array) $data)) {
            return $data->id;
        } else {
            return 0;
        }
    }

    public static function registerModuleTenant($moduletype = "all")
    {
        $modules = Cms::allModules();

        if ($moduletype == "tenant") {
            $exclude = Configurations::EXCLUDEMODULES;

            foreach ($modules as $key => $value) {
                if (in_array($value["name"], $exclude)) {
                    unset($modules[$key]);
                }
            }
            foreach ($modules as $key => $value) {
                if (!in_array($value["name"], Session("module_list"))) {
                    unset($modules[$key]);
                }
            }

            // Reindex the array
            $modules = array_values($modules);
        }

        // get All tenants

        # code...
        foreach ($modules as $module) {
            $type = $module["type"] == "core" ? 1 : 2;
            $old = ModuleModel::select("version", "id")
                ->where("name", "=", $module["name"])
                ->where("type", "=", $type)
                ->first();
            //already available
            if (count((array) $old) > 0) {
                //check version is same
                if ($old->version != $module["version"]) {
                    $obj = ModuleModel::find($old->id);
                    $obj->version = $module["version"];
                    if (isset($module["configuration"])) {
                        $obj->configuration_view = $module["configuration"];
                    }
                    if (isset($module["configuration_data"])) {
                        $obj->configuration_data =
                            $module["configuration_data"];
                    }
                    $obj->save();
                }
            } else {
                $obj = new ModuleModel();
                $obj->name = $module["name"];
                $obj->type = $type;
                $obj->version = $module["version"];
                if (isset($module["configuration"])) {
                    $obj->configuration_view = $module["configuration"];
                }
                if (isset($module["configuration_data"])) {
                    $obj->configuration_data = $module["configuration_data"];
                }
                $obj->status = 1;
                $obj->save();
            }
        }
    }
}
