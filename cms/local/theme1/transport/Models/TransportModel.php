<?php

namespace cms\transport\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class TransportModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "transport_vehicle";
    protected $guarded = [];

    public static function transportname($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->vehicle_name . "-" . $data->bus_no;
    }
}
