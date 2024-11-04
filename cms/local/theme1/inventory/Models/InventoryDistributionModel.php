<?php

namespace cms\inventory\Models;

use cms\core\usergroup\Models\UserGroupModel;
use cms\shop\Models\ProductModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class InventoryDistributionModel extends Model
{
    protected $table = "inventory_distribution";
    protected $guarded = [];

    public function product()
    {
        return $this->hasMany(ProductModel::class, "product_id");
    }

    public function group()
    {
        return $this->hasMany(UserGroupModel::class, "user_group_id");
    }
}
