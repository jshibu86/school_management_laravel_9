<?php

namespace cms\homework\Models;

use User;
use cms\lclass\Models\LclassModel;
use cms\section\Models\SectionModel;
use cms\subject\Models\SubjectModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeworkModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "homework";
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = User::getUser()->id;
            $query->updated_by = User::getUser()->id;
        });
        static::updating(function ($query) {
            $query->updated_by = User::getUser()->id;
        });
    }

    public static function homeworkname($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function subjectname($id)
    {
        $id = $this->subject_id;

        $subject = SubjectModel::where("id", $id)->first();

        return $subject->name;
    }
    public function section()
    {
        return $this->belongsTo(SectionModel::class, "section_id");
    }
    public function submissions()
    {
        return $this->hasOne(
            HomeworkSubmissionModel::class,
            "homework_id",
            "id"
        );
    }
    public function class()
    {
        return $this->belongsTo(LclassModel::class, "class_id");
    }
}
