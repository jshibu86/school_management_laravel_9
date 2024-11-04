<?php

namespace cms\exam\Models;

use Illuminate\Database\Eloquent\Model;
use User;

class ExamQuestionModel extends Model
{
    protected $table = "exam_questions";
    protected $guarded = [];

    public function subquestion()
    {
        return $this->hasMany(
            SubQuestionMapping::class,

            "exam_question_id"
        );
    }
}
