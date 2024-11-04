<?php

namespace cms\transport\Models;

use Illuminate\Database\Eloquent\Model;

use User;

class TransportStop extends Model
{
    protected $table = "transport_stop";
    protected $guarded = [];

    public static function stopname($id)
    {
        $data = self::where("id", $id)->first();
        return $data->stop_name;
    }
}
