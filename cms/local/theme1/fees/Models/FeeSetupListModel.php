<?php

namespace cms\fees\Models;

use Illuminate\Database\Eloquent\Model;
use User;

class FeeSetupListModel extends Model
{
    protected $table = "fee_setup_lists";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
