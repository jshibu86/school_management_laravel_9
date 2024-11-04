<?php

namespace cms\gmailcomunication\Models;

use Illuminate\Database\Eloquent\Model;
use  cms\core\user\Models\UserModel;

class GmailGroupReceptiantsModel extends Model  
{
    protected $table = "gmail_group_receptiants";

    public function username(){
        return $this->belongsTo(UserModel::class, "user_id");
    }
}
