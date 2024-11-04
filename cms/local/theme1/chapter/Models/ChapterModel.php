<?php

namespace cms\chapter\Models;

use cms\lclass\Models\LclassModel;
use cms\section\Models\SectionModel;
use cms\subject\Models\SubjectModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;

use User;

class ChapterModel extends Model
{
    use HasUserStamps;

    protected $table = "chapter";
    protected $guarded = [];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($query) {
    //         $query->created_by = User::getUser()->id;
    //         $query->updated_by = User::getUser()->id;
    //     });
    //     static::updating(function ($query) {
    //         $query->updated_by = User::getUser()->id;
    //     });
    // }
    public static function chaptername($id)
    {
        $data = self::where("id", $id)->first();
        return $data->chapter_name;
    }

    public function class()
    {
        return $this->belongsTo(LclassModel::class, "class_id");
    }
    public function section()
    {
        return $this->belongsTo(SectionModel::class, "section_id");
    }
    public function subject()
    {
        return $this->belongsTo(SubjectModel::class, "subject_id");
    }
    public function topics()
    {
        return $this->hasMany(ChapterTopicModel::class, "chapter_id")->where(
            "deleted_by",
            null
        );
    }
}
