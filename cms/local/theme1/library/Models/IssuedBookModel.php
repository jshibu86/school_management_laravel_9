<?php

namespace cms\library\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class IssuedBookModel extends Model
{
    protected $table = "issued_books";
    protected $guarded = [];

    protected $dates = ["return_date", "issued_date"];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function getReturnDateAttribute($value)
    {
        $date = $value != null ? date("d-M-Y", strtotime($value)) : $value;
        return $date;
    }

    public function getReturnedDateAttribute($value)
    {
        $date = $value != null ? date("d-M-Y", strtotime($value)) : $value;
        return $date;
    }

    public function getIssuedDateAttribute($value)
    {
        return date("d-M-Y", strtotime($value));
    }
}
