<?php

namespace cms\classtimetable\Models;

use Illuminate\Database\Eloquent\Model;
use cms\classtimetable\Models\PeriodModel;
use User;

class PeriodClassMappingModel extends Model
{
    protected $table = "period_class_mapping";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
    public function Timetableperiod()
    {
        return $this->hasMany(ClasstimetableModel::class, "period_id");
    }
    public function period()
    {
        return $this->belongsTo(PeriodModel::class, "period_class_id");
    }
}
