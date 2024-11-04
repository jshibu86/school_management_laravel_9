<?php

namespace cms\core\schoolmanagement\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolApproval extends Model
{
    protected $table = "school_approvals";
    protected $fillable = ["school_id", "user_id"];

    public function school()
    {
        return $this->belongsTo(SchoolProfile::class, "school_id");
    }
}
