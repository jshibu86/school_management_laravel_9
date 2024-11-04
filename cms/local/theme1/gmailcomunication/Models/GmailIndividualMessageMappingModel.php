<?php

namespace cms\gmailcomunication\Models;

use Illuminate\Database\Eloquent\Model;
use cms\core\user\Models\UserModel;

class GmailIndividualMessageMappingModel extends Model 
{
    protected $table = "gmail_individual_messages_mapping";

    public function senter()
    {
        return $this->belongsTo(UserModel::class, "senter");
    }
    public function reciver()
    {
        return $this->belongsTo(UserModel::class, "reciever");
    }
    public function senter_info()
    {
        return $this->belongsTo(UserModel::class, "senter");
    }
}
