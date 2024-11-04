<?php

namespace cms\classtimetable\Models;

use cms\attendance\Models\AttendanceModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use cms\subject\Models\SubjectModel;
use cms\teacher\Models\TeacherModel;
use cms\exam\Models\ExamTermModel;
use cms\classtimetable\Models\PeriodClassMappingModel;
use cms\section\Models\SectionModel;
use cms\lclass\Models\LclassModel;
use User;

class ClasstimetableModel extends Model
{
    protected $table = "classtimetable";
    protected $guarded = [];

    public static function classtimetablename($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, "subject_id")->select(
            "id",
            "name"
        );
    }
    public function class()
    {
        return $this->belongsTo(LclassModel::class, "class_id")->select(
            "id",
            "name"
        );
    }
    public function section()
    {
        return $this->belongsTo(SectionModel::class, "section_id")->select(
            "id",
            "name"
        );
    }

    public function staff()
    {
        return $this->belongsTo(TeacherModel::class, "teacher_id")->select(
            "id",
            "teacher_name"
        );
    }

    public function attendance()
    {
        return $this->hasOne(AttendanceModel::class, "period_id");
    }
    public function periods()
    {
        return $this->belongsTo(
            PeriodClassMappingModel::class,
            "period_id"
        )->select("id", "period_class_id", "from", "to", "type", "break_min");
    }
    public function terms()
    {
        return $this->belongsTo(ExamTermModel::class, "term_id")->select(
            "id",
            "exam_term_name"
        );
    }
}
