<?php

namespace cms\students\Models;

use cms\wallet\Models\WalletModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class ParentModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "parent";
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

    public function wallet()
    {
        return $this->hasOne(WalletModel::class, "parent_id");
    }
}
