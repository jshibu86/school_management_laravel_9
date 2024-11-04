<?php

namespace cms\subject\Models;

use cms\chapter\Models\ChapterModel;
use cms\lclass\Models\LclassModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class SubjectModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "subject";
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

    public function subjectmapping()
    {
        return $this->hasMany(SubjectTeacherMapping::class, "subject_id", "id");
    }

    public function class()
    {
        return $this->belongsTo(LclassModel::class, "class_id");
    }

    public function chapter()
    {
        return $this->hasMany(ChapterModel::class, "subject_id");
    }

    public static function subjectname($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
