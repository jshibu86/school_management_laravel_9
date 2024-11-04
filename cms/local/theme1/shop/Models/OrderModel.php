<?php

namespace cms\shop\Models;

use cms\core\user\Models\UserModel;
use User;
use cms\shop\Models\OrderItemsModel;
use cms\students\Models\StudentsModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use cms\core\configurations\helpers\Configurations;

class OrderModel extends Model 
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "orders";
    protected $guarded = [];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function orderitems()
    {
        return $this->hasMany(OrderItemsModel::class, "order_id", "id");
    }
    public function student()
    {
        return $this->belongsTo(StudentsModel::class, "student_id");
    }
    public function user()
    {
        return $this->belongsTo(UserModel::class, "user_id");
    }
    public function orderstatus(){
        return $this->belongsTo(Configurations::ORDERSTATUS, "order_status");
    }
}
