<?php

namespace cms\inventory\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class InventoryDistributionUserModel extends Model
{
    protected $table = "inventory_distribution_users";
    protected $guarded = [];
}
