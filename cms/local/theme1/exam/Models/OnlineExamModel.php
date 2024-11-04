<?php

namespace cms\exam\Models;

use cms\students\Models\StudentsModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class OnlineExamModel extends Model
{
    protected $table = "online_exam";
    protected $guarded = [];
    const DISABLEEXAMTYPE = ["Home Work", "Admission"];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function student()
    {
        return $this->belongsTo(StudentsModel::class, "student_id");
    }

    public function examsubmision()
    {
        return $this->hasMany(
            OnlineExamSubmissionModel::class,
            "online_exam_id",
            "id"
        );
    }
}
