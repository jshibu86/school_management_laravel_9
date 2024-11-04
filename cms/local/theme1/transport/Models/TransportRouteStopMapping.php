<?php

namespace cms\transport\Models;

use Illuminate\Database\Eloquent\Model;

use User;

class TransportRouteStopMapping extends Model
{
    protected $table = "transport_route_stop_mapping";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }
}
