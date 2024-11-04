<?php

namespace cms\exam\Models;

use cms\academicyear\Models\AcademicyearModel;
use cms\lclass\Models\LclassModel;
use cms\section\Models\SectionModel;
use cms\subject\Models\SubjectModel;
use Illuminate\Database\Eloquent\Model;
use User;

class ExamModel extends Model
{
    protected $table = "exam";
    protected $guarded = [];

    public function academyyear()
    {
        return $this->hasOne(AcademicyearModel::class, "id", "academic_year");
    }
    public function class()
    {
        return $this->hasOne(LclassModel::class, "id", "class_id");
    }
    public function section()
    {
        return $this->hasOne(SectionModel::class, "id", "section_id");
    }
    public function subject()
    {
        return $this->hasOne(SubjectModel::class, "id", "subject_id");
    }
    public function questions()
    {
        return $this->hasMany(ExamQuestionModel::class, "exam_id")->orderBy(
            "order",
            "asc"
        );
    }

    public function sections()
    {
        return $this->hasMany(ExamSectionModel::class, "exam_id")->orderBy(
            "section_order",
            "asc"
        );
    }
    public function notification()
    {
        return $this->hasOne(ExamNotificationModel::class, "exam_id");
    }
}
