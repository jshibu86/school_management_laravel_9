<?php

namespace cms\wallet\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class WalletModel extends Model  
{
    protected $table = "wallet";
    protected $guarded = [];

    public static function walletname($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function attachments()
    {
        return $this->hasMany(WalletAttachmentsModel::class, "wallet_id");
    }
}
