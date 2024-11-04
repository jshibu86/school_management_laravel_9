<?php

namespace cms\ExamTimetable\Models;
use cms\subject\Models\SubjectModel;
use Illuminate\Database\Eloquent\Model;

class ExamTimetableModel extends Model
{
    protected $table = "examtimetable";

    public function subject_names()
    {
        return $this->belongsTo(SubjectModel::class,"subject");
    }
}
