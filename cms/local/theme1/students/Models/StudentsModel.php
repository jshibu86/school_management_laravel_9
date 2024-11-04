<?php

namespace cms\students\Models;

use cms\attendance\Models\StudentAttendanceModel;
use User;
use cms\core\user\Models\UserModel;
use cms\lclass\Models\LclassModel;
use cms\section\Models\SectionModel;
use cms\department\Models\DepartmentModel;
use cms\fees\Models\AcademicFeeModel;
use cms\students\Models\ParentModel;
use Illuminate\Database\Eloquent\Model;
use cms\students\Models\AttachementModel;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Configurations;
class StudentsModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "students";
    protected $guarded = [];

    //protected $dates = ["dob"];

    public function parent()
    {
        return $this->hasOne(ParentModel::class, "id", "parent_id");
    }
    public function attachment()
    {
        return $this->hasMany(AttachementModel::class, "student_id", "id");
    }
    public function user()
    {
        return $this->belongsTo(UserModel::class, "user_id");
    }

    public function class()
    {
        return $this->belongsTo(LclassModel::class, "class_id");
    }
    public function section()
    {
        return $this->belongsTo(SectionModel::class, "section_id");
    }
    public function department()
    {
        return $this->belongsTo(DepartmentModel::class, "stu_department");
    }
    public function librarysubscribed()
    {
        return $this->hasOne(AcademicFeeModel::class, "student_id")
            ->where("academic_year", Configurations::getCurrentAcademicyear())
            ->where("type", "library");
    }

    public function hostelsubscribed()
    {
        return $this->hasOne(AcademicFeeModel::class, "student_id")
            ->where("academic_year", Configurations::getCurrentAcademicyear())
            ->where("type", "hostel");
    }

    public static function studentname($id)
    {
        $student = self::where("id", $id)->first();

        return $student->first_name . " " . $student->last_name;
    }

    public function photo()
    {
        if (file_exists(public_path() . $this->image)) {
            return $this->image;
        } else {
            return "/assets/images/default.jpg";
        }
    }

    public function attendance()
    {
        return $this->hasMany(StudentAttendanceModel::class, "student_id");
    }
}
