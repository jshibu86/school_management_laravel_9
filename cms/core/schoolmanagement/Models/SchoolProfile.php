<?php

namespace cms\core\schoolmanagement\Models;

use Illuminate\Database\Eloquent\Model;
use cms\core\schoolmanagement\Models\SchoolPlanPayment;

class SchoolProfile extends Model
{
    protected $table = "school_profile";
    protected $connection = "mysql";

    protected $fillable = ["status"];

    public function contacts()
    {
        return $this->hasMany(SchoolContact::class, "school_id");
    }
    public function approvels()
    {
        return $this->hasMany(SchoolApproval::class, "school_id");
    }
    public function plan_payment()
    {
        return $this->hasOne(SchoolPlanPayment::class, "school_id");
    }
}
