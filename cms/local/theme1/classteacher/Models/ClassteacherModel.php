<?php

namespace cms\classteacher\Models;

use cms\teacher\Models\TeacherModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use cms\section\Models\SectionModel;
use cms\lclass\Models\LclassModel;
use User;

class ClassteacherModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "classteacher";
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

    public function Teacher()
    {
        return $this->belongsTo(TeacherModel::class, "teacher_id");
    }
    public function section()
    {
        return $this->belongsTo(SectionModel::class, "section_id")->select(
            "id",
            "name"
        );
    }

    public function class()
    {
        return $this->belongsTo(LclassModel::class, "class_id")->select(
            "id",
            "name"
        );
    }
}
