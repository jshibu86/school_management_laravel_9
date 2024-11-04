<?php

namespace cms\library\Models;

use cms\core\user\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class LibraryMemberModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "library_member";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function user()
    {
        return $this->hasOne(UserModel::class, "id", "member_id");
    }
}
