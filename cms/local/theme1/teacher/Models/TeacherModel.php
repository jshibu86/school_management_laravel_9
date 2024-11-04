<?php

namespace cms\teacher\Models;

use User;
use cms\core\user\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use cms\students\Models\AttachementModel;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use cms\classteacher\Models\ClassteacherModel;

class TeacherModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "teacher";

    protected $guarded = [];

    protected $dates = ["date_ofjoin", "start_date", "end_date"];

    protected $casts = [
        "dob" => "datetime:Y-m-d",
    ];

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

    public static function teachername($id)
    {
        $teacher = self::where("id", $id)->first();

        if ($teacher) {
            return $teacher->teacher_name;
        } else {
            return "no name";
        }
    }
    public function attachment()
    {
        return $this->hasMany(AttachementModel::class, "teacher_id", "id");
    }

    public function designation()
    {
        return $this->hasOne(DesignationModel::class, "id", "designation_id");
    }
    public function user()
    {
        return $this->belongsTo(UserModel::class, "user_id");
    }
    public function classteacher()
    {
        return $this->belongsTo(ClassteacherModel::class, "id", "teacher_id");
    }
}
