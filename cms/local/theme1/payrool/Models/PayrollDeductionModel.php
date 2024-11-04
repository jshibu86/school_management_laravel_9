<?php

namespace cms\payrool\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class PayrollDeductionModel extends Model
{
    protected $table = "payroll_deduction";
    protected $guarded = [];
}
