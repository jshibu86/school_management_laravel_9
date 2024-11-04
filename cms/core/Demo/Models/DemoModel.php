<?php

namespace cms\core\Demo\Models;

use Illuminate\Database\Eloquent\Model;

class DemoModel extends Model
{
    protected $table = "demos";
    protected $fillable = ["demo_date", "demo_time"];
}
