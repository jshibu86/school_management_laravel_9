<?php

namespace cms\payrool\Models;

use App\Models\User as ModelsUser;
use Illuminate\Database\Eloquent\Model;
use User;

class PayrollModel extends Model
{
    protected $table = "salery_payroll";
    protected $guarded = [];

    public function userinfo()
    {
        return $this->belongsTo(ModelsUser::class, "user_id");
    }

    public function grade()
    {
        return $this->belongsTo(SaleryTemplateModel::class, "grade_id");
    }
}
