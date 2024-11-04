<?php

namespace cms\ExamTimetable\Models;
use cms\ExamTimetable\Models\ExamTimetableModel;
use Illuminate\Database\Eloquent\Model;

class ExamPeriodMappingModel extends Model
{
    protected $table = "examperiod_mapping";

    public function examtimetable()
    {
        return $this->hasMany(ExamTimetableModel::class, "period_id");
    }
}
