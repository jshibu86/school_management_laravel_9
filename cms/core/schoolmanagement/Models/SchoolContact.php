<?php

namespace cms\core\schoolmanagement\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolContact extends Model
{
    protected $table = "school_contact";

    public function school()
    {
        return $this->belongsTo(SchoolProfile::class, "school_id");
    }
}
