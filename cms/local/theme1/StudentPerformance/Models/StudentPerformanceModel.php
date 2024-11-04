<?php

namespace cms\StudentPerformance\Models;

use Illuminate\Database\Eloquent\Model;

class StudentPerformanceModel extends Model
{
    protected $table = "studentperformance";

    protected $fillable = [
        'school_type', // Add school_type to the fillable array
        'academic_year',
        'term_id',
        'class_id',
        'section_id',
        'month_year',
        'period'      
        // Add other fillable attributes here as needed
    ];
}
