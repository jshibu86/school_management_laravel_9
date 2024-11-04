<?php

namespace cms\transport\Models;

use Illuminate\Database\Eloquent\Model;

use User;

class TransportRoute extends Model
{
    protected $table = "transport_route";
    protected $guarded = [];

    public static function routename($id)
    {
        $data = self::where("id", $id)->first();
        return $data->from . "-" . $data->to;
    }
}
