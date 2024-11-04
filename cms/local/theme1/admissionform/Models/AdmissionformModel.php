<?php

namespace cms\admissionform\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionformModel extends Model
{
    //use HasFactory;
    protected $table = "admissionform";    

    protected $fillable = ["menu_name", "is_active"];
    protected $casts = ['checked' => 'boolean',
        
    ];
}
