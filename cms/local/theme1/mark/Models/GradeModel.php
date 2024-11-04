<?php

namespace cms\mark\Models;

use Illuminate\Database\Eloquent\Model;

use User;

class GradeModel extends Model
{
    protected $table = "grade_system_grades";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
