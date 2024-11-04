<?php

namespace cms\leave\Models;

use cms\academicyear\Models\AcademicyearModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;
use cms\leave\Models\LeaveTypeModel;
use cms\core\user\Models\UserModel;

class LeaveModel extends Model
{
    protected $table = "leave";
    protected $guarded = [];

    public static function leavename($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function academicyear()
    {
        return $this->belongsTo(AcademicyearModel::class, "academic_year");
    }

    public function leave_type()
    {
        return $this->belongsTo(LeaveTypeModel::class, "leave_type_id");
    }
    public function user()
    {
        return $this->belongsTo(UserModel::class, "user_id");
    }
}
