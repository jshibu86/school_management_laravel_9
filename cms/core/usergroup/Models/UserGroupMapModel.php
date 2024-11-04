<?php

namespace cms\core\usergroup\Models;

use Illuminate\Database\Eloquent\Model;
use cms\core\usergroup\Models\UserGroupModel;

class UserGroupMapModel extends Model  
{
    public $timestamps = false;
    protected $table='user_group_map';

    public  function usergroup(){
       
        return $this->belongsTo(UserGroupModel::class, "group_id");
     
    }
}
