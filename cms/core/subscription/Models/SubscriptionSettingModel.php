<?php

namespace cms\core\subscription\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionSettingModel extends Model
{
    protected $table = "subscription_setting";
    protected $fillable = ["notify_days", "payment_info", "reminder_info"];
}
