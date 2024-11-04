<?php

namespace cms\gallery\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryModel extends Model
{
    protected $table = "gallery";

    protected $guarded = [];

    protected $casts = [
        "created_date" => "date:Y-m-d",
    ];
}
