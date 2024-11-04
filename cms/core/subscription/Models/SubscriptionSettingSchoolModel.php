<?php

namespace cms\core\subscription\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionSettingSchoolModel extends Model
{
    protected $table = "subscription_setting_school";
    protected $fillable = ["school_info", "privilege_days"];
}
