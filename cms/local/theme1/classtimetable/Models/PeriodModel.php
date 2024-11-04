<?php

namespace cms\classtimetable\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class PeriodModel extends Model
{
    protected $table = "period_class";
    protected $guarded = [];

    public function periods()
    {
        return $this->hasMany(
            PeriodClassMappingModel::class,
            "period_class_id"
        );
    }

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
