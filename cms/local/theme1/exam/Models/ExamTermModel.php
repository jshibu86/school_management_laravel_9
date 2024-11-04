<?php

namespace cms\exam\Models;

use Illuminate\Database\Eloquent\Model;
use User;

class ExamTermModel extends Model  
{
    protected $table = "exam_term";
    protected $guarded = [];

    public function marks()
    {
        return $this->hasMany(MarkModel::class, 'term_id');
    }
}
