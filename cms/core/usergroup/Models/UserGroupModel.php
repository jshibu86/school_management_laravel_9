<?php

namespace cms\core\usergroup\Models;

use Illuminate\Database\Eloquent\Model;
// use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class UserGroupModel extends Model
{
    // use HasUserStamps;
    use SoftDeletes;
    protected $table = "user_groups";
    protected $fillable = ["status"];

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
}
