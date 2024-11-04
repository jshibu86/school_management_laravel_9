<?php

namespace cms\dormitory\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class DormitoryModel extends Model
{
    protected $table = "dormitory";
    protected $guarded = [];

    public static function dormitoryname($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
