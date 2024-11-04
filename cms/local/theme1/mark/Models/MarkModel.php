<?php

namespace cms\mark\Models;

use cms\students\Models\StudentsModel;
use cms\subject\Models\SubjectModel;
use cms\lclass\Models\LclassModel;
use cms\section\Models\SectionModel;
use cms\exam\Models\ExamTermModel;
use cms\academicyear\Models\AcademicyearModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class MarkModel extends Model
{
    use HasUserStamps;

    protected $table = "mark_entry";
    protected $guarded = [];

    protected $casts = [
        "distribution" => "json",
    ];

    public static function markname($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function students()
    {
        return $this->belongsTo(StudentsModel::class, "student_id")->select(
            "id",
            "reg_no",
            "first_name",
            "last_name",
            "image"
        );
    }

    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, "subject_id");
    }
    public function class(){
        return $this->belongsTo(LclassModel::class, "class_id");
    }
    public function section(){
        return $this->belongsTo(SectionModel::class, "section_id");
    }
    public function term(){
        return $this->belongsTo(ExamTermModel::class, "term_id");
    }
    public function academicyear(){
        return $this->belongsTo(AcademicyearModel::class, "academic_year");
    }
}
