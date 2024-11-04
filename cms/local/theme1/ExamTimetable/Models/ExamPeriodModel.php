<?php

namespace cms\ExamTimetable\Models;

use Illuminate\Database\Eloquent\Model;
use cms\ExamTimetable\Models\ExamPeriodMappingModel;
class ExamPeriodModel extends Model
{
    protected $table = "examperiod";

    protected $fillable = [
        // Add other attributes here
        'status',
    ];

    public function examperiodmapping()
    {
        return $this->hasMany(ExamPeriodMappingModel::class,"exam_period_id");
    }
}
