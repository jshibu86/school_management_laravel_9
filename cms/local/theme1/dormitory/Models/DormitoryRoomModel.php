<?php

namespace cms\dormitory\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class DormitoryRoomModel extends Model
{
    protected $table = "dormitory_rooms";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function dormitory()
    {
        return $this->belongsTo(DormitoryModel::class, "dormitory_id");
    }
}
