<?php

namespace cms\payrool\Models;

use Illuminate\Database\Eloquent\Model;
use User;

class SaleryTemplateModel extends Model
{
    protected $table = "salery_template";
    protected $guarded = [];

    protected $casts = [
        "particulars" => "json",
    ];
}
