<?php

namespace cms\fees\Models;

use Illuminate\Database\Eloquent\Model;
use User;

class FeeSetupModel extends Model
{
    protected $table = "fee_setup";
    protected $guarded = [];

    public function feelists()
    {
        return $this->hasMany(FeeSetupListModel::class, "fee_setup_id");
    }
}
