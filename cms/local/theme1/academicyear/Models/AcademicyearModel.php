<?php

namespace cms\academicyear\Models;
use cms\mark\Models\MarkModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class AcademicyearModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "academicyear";
    protected $fillable = ["title", "year", "start_date", "end_date", "status"];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($query) {
            $query->created_by = User::getUser()->id;
            $query->updated_by = User::getUser()->id;
        });
        static::updating(function ($query) {
            $query->updated_by = User::getUser()->id;
        });
    }

    public static function academicyear($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->year;
    }
    public function marks()
    {
        return $this->hasMany(MarkModel::class, "academic_year")->where(
            "academic_year",
            $this->id
        );
    }
}
