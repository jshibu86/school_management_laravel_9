<?php

namespace cms\shop\Models;

use cms\productcategory\Models\ProductcategoryModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use cms\shop\Models\SupplierModel;
use User;

class ProductModel extends Model
{
    use HasUserStamps;
    use SoftDeletes;
    protected $table = "products";
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where("status", 1);
    }

    public static function name($id)
    {
        $data = self::withTrashed()
            ->where("id", $id)
            ->first();
        return $data->name;
    }

    public function category()
    {
        return $this->belongsTo(ProductcategoryModel::class, "category_id");
    }

    public function supplier()
    {
        return $this->belongsTo(SupplierModel::class, "supplier_id");
    }
}
