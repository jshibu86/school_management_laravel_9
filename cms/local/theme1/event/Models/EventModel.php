<?php

namespace cms\event\Models;

use Illuminate\Database\Eloquent\Model;
use User;

class EventModel extends Model
{
    protected $table = "event";
    protected $guarded = [];

    protected $casts = [
        "event_date" => "date:Y-m-d",
    ];
}
