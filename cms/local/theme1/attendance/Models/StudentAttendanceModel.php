<?php

namespace cms\attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class StudentAttendanceModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "attendance_students";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function attendance()
    {
        return $this->belongsTo(AttendanceModel::class, "attendance_id");
    }
}
