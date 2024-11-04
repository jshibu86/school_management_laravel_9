<?php

namespace cms\chapter\Models;

use User;
use Illuminate\Database\Eloquent\Model;
use cms\chapter\Models\ChapterTopicContentModel;

class ChapterTopicModel extends Model
{
    protected $table = "chapter_topics";
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
    public function contents()
    {
        return $this->hasMany(
            ChapterTopicContentModel::class,

            "topic_id"
        )->where("deleted_by", null);
    }
}
