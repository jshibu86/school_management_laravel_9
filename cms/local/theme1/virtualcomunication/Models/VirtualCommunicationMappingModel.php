<?php

namespace cms\virtualcomunication\Models;

use Illuminate\Database\Eloquent\Model;
use cms\core\user\Models\UserModel;

class VirtualCommunicationMappingModel extends Model
{
    protected $table = "virtual_comunication_list_mapping";

    public function user(){
        return $this->belongsTo(UserModel::class, "participants");
    }
}

