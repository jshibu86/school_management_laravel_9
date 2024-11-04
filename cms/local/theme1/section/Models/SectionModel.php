<?php

namespace cms\section\Models;

use cms\lclass\Models\LclassModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;
use Illuminate\Support\Arr;
use Configurations;
class SectionModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "section";
    protected $guarded = [];

    public function class()
    {
        return $this->belongsTo(LclassModel::class, "class_id", "id");
    }

    // public function getSchoolTypeAttribute($value)
    // {
    //     return Arr::get(Configurations::SCHOOLTYPES, $value);
    // }

    public static function sectionname($id)
    {
        $section = self::withTrashed()
            ->where("id", $id)
            ->first();

        if ($section) {
            return $section->name;
        } else {
            return "no name";
        }
    }
}
