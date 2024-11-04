<?php

namespace cms\teacher\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class DesignationModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "designation";
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

    public static function designationtype($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();

        return $data ? $data->type : "Not Assign";
    }
}
