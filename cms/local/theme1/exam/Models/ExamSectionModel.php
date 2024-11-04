<?php

namespace cms\exam\Models;

use Illuminate\Database\Eloquent\Model;
use User;

class ExamSectionModel extends Model
{
    protected $table = "exam_section";
    protected $guarded = [];

    public function questions()
    {
        return $this->hasMany(ExamQuestionModel::class, "section_id")->orderBy(
            "order",
            "asc"
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
