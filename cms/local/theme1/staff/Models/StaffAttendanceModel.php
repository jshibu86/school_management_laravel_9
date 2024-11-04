<?php

namespace cms\staff\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class StaffAttendanceModel extends Model
{
    protected $table = "staff_attendance";
    protected $guarded = [];
}
