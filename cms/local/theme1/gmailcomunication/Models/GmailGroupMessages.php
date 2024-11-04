<?php

namespace cms\gmailcomunication\Models;

use Illuminate\Database\Eloquent\Model;
use  cms\core\user\Models\UserModel;
class GmailGroupMessages extends Model  
{
    protected $table = "gmail_group_messages";

    public function username(){
        return $this->belongsTo(UserModel::class, "userid");
    }
}
