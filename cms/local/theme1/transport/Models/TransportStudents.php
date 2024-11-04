<?php

namespace cms\transport\Models;

use cms\academicyear\Models\AcademicyearModel;
use cms\students\Models\StudentsModel;
use Illuminate\Database\Eloquent\Model;

class TransportStudents extends Model
{
    protected $table = "transport_students";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function student()
    {
        return $this->belongsTo(StudentsModel::class, "student_id");
    }

    public function stop()
    {
        return $this->belongsTo(TransportStop::class, "transport_stop_id");
    }

    public function route()
    {
        return $this->belongsTo(TransportRoute::class, "transport_route_id");
    }

    public function bus()
    {
        return $this->belongsTo(TransportModel::class, "transport_vehicle_id");
    }

    public function academicyear()
    {
        return $this->belongsTo(AcademicyearModel::class, "academic_year");
    }
}
