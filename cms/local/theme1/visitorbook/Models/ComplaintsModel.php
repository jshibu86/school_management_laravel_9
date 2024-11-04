<?php

namespace cms\visitorbook\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class ComplaintsModel extends Model
{
    protected $table = "complaints";
    protected $guarded = [];
}
