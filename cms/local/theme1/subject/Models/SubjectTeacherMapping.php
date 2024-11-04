<?php

namespace cms\subject\Models;

use cms\teacher\Models\TeacherModel;
use Illuminate\Database\Eloquent\Model;
use cms\subject\Models\SubjectModel;
use cms\section\Models\SectionModel;
use cms\lclass\Models\LclassModel;
use User;
class SubjectTeacherMapping extends Model
{
    protected $table = "subject_teachermapping";
    protected $guarded = [];

    public function Teacher()
    {
        return $this->belongsTo(TeacherModel::class, "teacher_id");
    }

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, "subject_id")->select(
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

    public function class()
    {
        return $this->belongsTo(LclassModel::class, "class_id")->select(
            "id",
            "name"
        );
    }
}
