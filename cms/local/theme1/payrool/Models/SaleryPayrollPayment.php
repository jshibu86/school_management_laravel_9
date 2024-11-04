<?php

namespace cms\payrool\Models;

use cms\academicyear\Models\AcademicyearModel;
use cms\core\user\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class SaleryPayrollPayment extends Model
{
    protected $table = "salery_payroll_payment";
    protected $guarded = [];

    protected $casts = [
        "particulars" => "json",
        "deduction" => "json",
    ];
    public function grade()
    {
        return $this->belongsTo(SaleryTemplateModel::class, "grade_id");
    }
    public function user()
    {
        return $this->belongsTo(UserModel::class, "user_id");
    }
    public function academicyear()
    {
        return $this->belongsTo(AcademicyearModel::class, "academic_year");
    }
}
