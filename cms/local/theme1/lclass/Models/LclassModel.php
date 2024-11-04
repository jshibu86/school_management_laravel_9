<?php

namespace cms\lclass\Models;

use cms\fees\Models\SchoolTypeModel;
use cms\section\Models\SectionModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class LclassModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "lclass";
    protected $guarded = [];

    public function section()
    {
        return $this->hasMany(SectionModel::class, "class_id", "id");
    }

    public function schooltype()
    {
        return $this->belongsTo(SchoolTypeModel::class, "school_type_id", "id");
    }

    public static function classname($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
