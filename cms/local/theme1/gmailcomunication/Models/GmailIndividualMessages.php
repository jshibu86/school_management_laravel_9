<?php

namespace cms\gmailcomunication\Models;

use Illuminate\Database\Eloquent\Model;
use cms\core\user\Models\UserModel;

class GmailIndividualMessages extends Model  
{
    protected $table = "gmail_individual_messages";

    public function senter()
    {
        return $this->belongsTo(UserModel::class, "from_id");
    }
    public function reciver()
    {
        return $this->belongsTo(UserModel::class, "to_id");
    }
}



