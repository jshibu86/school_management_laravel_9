<?php

namespace cms\shop\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class PurchaseOrderModel extends Model
{
    protected $table = "purchase_order";
    protected $guarded = [];

    protected $appends = ["status_text", "pur_price"];

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function getPurchaseDateAttribute($value)
    {
        return date("d-M-Y", strtotime($value));
    }

    public function getPurPriceAttribute()
    {
        return $this->purchase_price;
    }
    public function getStatusTextAttribute()
    {
        return $this->status == 0 ? "In Active" : "Active";
    }

    public function product()
    {
        return $this->hasOne(ProductModel::class, "id", "product_id");
    }
    public function vendor()
    {
        return $this->belongsTo(SupplierModel::class, "vendor_id");
    }
}
