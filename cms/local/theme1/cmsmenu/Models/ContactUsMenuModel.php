<?php

namespace cms\cmsmenu\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUsMenuModel extends Model
{
    protected $table = "contactus_menu";
    protected $fillable = [  'key', 'value' ];
}
