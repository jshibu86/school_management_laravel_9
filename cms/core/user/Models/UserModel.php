<?php

namespace cms\core\user\Models;

use cms\payrool\Models\PayrollModel;
use cms\payrool\Models\SaleryPayrollPayment;
use cms\staff\Models\StaffAttendanceModel;
use cms\students\Models\StudentsModel;
use cms\teacher\Models\TeacherModel;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;

use Spatie\MediaLibrary\InteractsWithMedia;
use User;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    use SoftDeletes;
    // use InteractsWithMedia;

    const COLLECTION_PROFILE_PICTURES = "profile_photo";
    protected $table = "users";

    protected $hidden = ["password", "online", "ip", "device_token"];

    protected $fillable = ["status"];

    const IMG_COLUMN = "image_url";

    public function getImageUrlAttribute()
    {
        /** @var Media $media */
        $media = $this->getMedia(self::COLLECTION_PROFILE_PICTURES)->first();
        if (!empty($media)) {
            return $media->getFullUrl();
        }

        return null;
    }

    public function group()
    {
        return $this->belongsToMany(
            "cms\core\usergroup\Models\UserGroupModel",
            "user_group_map",
            "user_id",
            "group_id"
        );

        //->withPivot([ ARRAY OF FIELDS YOU NEED FROM meta TABLE ]);
        //return $this->hasManyThrough('cms\core\usergroup\Models\UserGroupModel','cms\core\usergroup\Models\UserGroupMapModel',
        // 'user_id','id','id');
    }
    public function groupMap()
    {
        return $this->hasMany(
            "cms\core\usergroup\Models\UserGroupMapModel",
            "user_id",
            "id"
        );
    }

    public function salerypayroll()
    {
        return $this->hasOne(PayrollModel::class, "user_id")->latest();
    }

    public function salerypayrollpayment()
    {
        return $this->hasOne(SaleryPayrollPayment::class, "user_id")->latest();
    }

    public static function getUserName($id)
    {
        $user = self::where("id", $id)->first();

        return $user->username;
    }

    public function attendance()
    {
        return $this->hasMany(StaffAttendanceModel::class, "user_id")->where(
            "attendance_date",
            date("Y-m-d")
        );
    }

    public function student()
    {
        return $this->hasOne(StudentsModel::class, "user_id");
    }
    public function teacher()
    {
        return $this->hasOne(TeacherModel::class, "user_id");
    }

    public function routeNotificationForFcm()
    {
        return $this->device_token;
    }
}
