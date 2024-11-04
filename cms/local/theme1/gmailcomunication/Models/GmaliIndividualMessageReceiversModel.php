<?php

namespace cms\gmailcomunication\Models;

use Illuminate\Database\Eloquent\Model;
use cms\core\user\Models\UserModel;

class GmaliIndividualMessageReceiversModel extends Model  
{
    protected $table = "gmail_individual_messages_receivers";

 
    public function reciver_info()
    {
        return $this->belongsTo(UserModel::class, "user_id");
    }
}
