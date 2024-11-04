<?php

namespace cms\core\subscription\Models;
use cms\core\subscription\Models\PlanPriceModel;

use Illuminate\Database\Eloquent\Model;

class SubscriptionModel extends Model
{
    protected $table = "subscription_plan";

    public function plan_price_info()
    {
        return $this->hasOne(PlanPriceModel::class, "plan_id");
    }
}
