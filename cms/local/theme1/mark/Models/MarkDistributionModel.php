<?php

namespace cms\mark\Models;

use Illuminate\Database\Eloquent\Model;
use cms\mark\Models\SchoolTypeModel;
use User;

class MarkDistributionModel extends Model
{
    protected $table = "mark_distribution";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
    public function school()
    {
        return $this->belongsTo(SchoolTypeModel::class, "school_type_id");
    }
}
