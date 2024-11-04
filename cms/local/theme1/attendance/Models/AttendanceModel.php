<?php

namespace cms\attendance\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class AttendanceModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "attendance";
    protected $guarded = [];

    public static function attendancename($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    /**
     * Get all of the attendancestudents for the AttendanceModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendancestudents()
    {
        return $this->hasMany(StudentAttendanceModel::class, "attendance_id");
    }
}
