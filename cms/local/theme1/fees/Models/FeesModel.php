<?php

namespace cms\fees\Models;

use cms\academicyear\Models\AcademicyearModel;
use cms\lclass\Models\LclassModel;
use cms\section\Models\SectionModel;
use cms\students\Models\StudentsModel;
use Illuminate\Database\Eloquent\Model;
use User;
use Illuminate\Database\Eloquent\SoftDeletes;  
class FeesModel extends Model   
{
    use SoftDeletes;
    protected $table = "fee_collection";
    protected $guarded = [];

    public function classinfo()
    {
        return $this->belongsTo(LclassModel::class, "class_id");
    }
    public function section()
    {
        return $this->belongsTo(SectionModel::class, "section_id");
    }
    public function academicyear()
    {
        return $this->belongsTo(AcademicyearModel::class, "academic_year");
    }

    public function student()
    {
        return $this->belongsTo(StudentsModel::class, "student_id");
    }
}
