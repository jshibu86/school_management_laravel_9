<?php

namespace cms\dormitory\Models;

use cms\academicyear\Models\AcademicyearModel;
use cms\students\Models\StudentsModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class DormitoryStudentModel extends Model
{
    protected $table = "dormitory_students";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function room()
    {
        return $this->belongsTo(DormitoryRoomModel::class, "room_id");
    }
    public function dormitory()
    {
        return $this->belongsTo(DormitoryModel::class, "dormitory_id");
    }

    public function student()
    {
        return $this->belongsTo(StudentsModel::class, "student_id");
    }

    public function academicyear()
    {
        return $this->belongsTo(AcademicyearModel::class, "academic_year");
    }
}
