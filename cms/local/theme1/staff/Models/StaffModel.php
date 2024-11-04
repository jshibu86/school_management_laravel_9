<?php

namespace cms\staff\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class StaffModel extends Model
{
    protected $table = "staff";
    protected $guarded = [];
}
