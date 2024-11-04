<?php

namespace cms\library\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class LibraryModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "books";
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

    public static function libraryname($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
