<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\MultiTenant;
use cms\core\user\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use cms\core\configurations\Models\ConfigurationModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use Illuminate\Support\Facades\Artisan;

class SchoolOnboardJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $multiTenant;
    protected $schoolContactDB;
    protected $schoolProfileDB;
    public function __construct(
        MultiTenant $multiTenant,
        $schoolContactDB,
        $schoolProfileDB
    ) {
        $this->multiTenant = $multiTenant;
        $this->schoolContactDB = $schoolContactDB;
        $this->schoolProfileDB = $schoolProfileDB;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->multiTenant->run(function () {
            $password = "admin123";
            $hashedPassword = Hash::make($password);
            $user = new UserModel();
            $user->name = "Admin";
            $user->username = "admin";
            $user->email = "admin@gmail.com";
            $user->password = $hashedPassword;
            $user->save();

            $user_grp = new UserGroupModel();
            $user_grp->group = "Super Admin";
            $user_grp->save();

            $user_grp_map = new UserGroupMapModel();
            $user_grp_map->user_id = $user->id;
            $user_grp_map->group_id = $user_grp->id;
            $user_grp_map->save();

            $form_data["school_name"] = $this->schoolProfileDB->school_name;
            $form_data["school_email"] = $this->schoolProfileDB->email;
            $form_data["school_phone"] = $this->schoolProfileDB->phoneno;
            $form_data["city"] = $this->schoolProfileDB->city;
            $form_data["pin_code"] = $this->schoolProfileDB->pincode;
            $form_data["country"] = $this->schoolProfileDB->country;
            $form_data["imagec"] = $this->schoolProfileDB->image;
            $form_data["schoolicon"] = $this->schoolProfileDB->image;

            $obj = ConfigurationModel::where("name", "=", "site")->first();
            if (count((array) $obj) == 0) {
                $obj = new ConfigurationModel();
            }
            $obj->name = "site";
            $obj->parm = json_encode($form_data);
            $obj->save();

            Artisan::call("update:cms-module-core-tenant");
            Artisan::call("update:cms-menu-core-tenant");
        });
    }
}
