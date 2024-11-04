<?php

namespace cms\mark\Models;

use Illuminate\Database\Eloquent\Model;

use User;

class GradeSystemModel extends Model
{
    protected $table = "grade_system";
    protected $guarded = [];

    /**
     * Get all of the comments for the GradeSystemModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function grades()
    {
        return $this->hasMany(GradeModel::class, "grade_sys_name_id", "id");
    }

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
