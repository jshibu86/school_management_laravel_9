<?php

namespace cms\chapter\Models;

use Illuminate\Database\Eloquent\Model;
use User;

class ChapterTopicContentModel extends Model
{
    protected $table = "chapter_content";
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
}
