<?php

namespace cms\shop\Models;

use Illuminate\Database\Eloquent\Model;
use User;

class SupplierModel extends Model
{
    protected $table = "supplier";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
