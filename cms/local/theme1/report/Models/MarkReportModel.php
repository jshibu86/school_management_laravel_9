<?php

namespace cms\report\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class MarkReportModel extends Model
{
    protected $table = "mark_report";
    protected $guarded = [];

    protected $casts = [
        "afdomain" => "json",
        "pfdomain" => "json",
    ];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
