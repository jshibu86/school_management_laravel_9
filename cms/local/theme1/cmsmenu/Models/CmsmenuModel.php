<?php

namespace cms\cmsmenu\Models;

use Illuminate\Database\Eloquent\Model;

class CmsmenuModel extends Model
{
    protected $table = "home_page_menu";
    protected $fillable = [  'key', 'value', 'type' ];
}
