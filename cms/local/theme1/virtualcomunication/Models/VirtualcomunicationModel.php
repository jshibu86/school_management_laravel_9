<?php

namespace cms\virtualcomunication\Models;

use Illuminate\Database\Eloquent\Model;
use cms\core\user\Models\UserModel;

class VirtualcomunicationModel extends Model
{
    protected $table = "virtual_comunication_list";

    public function user()
    {
        return $this->belongsTo(UserModel::class, "moderator");
    }
    public function creater()
    {
        return $this->belongsTo(UserModel::class, "moderator");
    }
}
