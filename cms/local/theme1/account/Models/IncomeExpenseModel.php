<?php

namespace cms\account\Models;

use cms\academicyear\Models\AcademicyearModel;
use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class IncomeExpenseModel extends Model
{
    protected $table = "income_expense";
    protected $guarded = [];

    protected $with = ["category"];

    /**
     * Get the user that owns the IncomeExpenseModel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(
            IncomeExpenseCategoryModel::class,
            "category_id"
        );
    }

    public function academicyear()
    {
        return $this->belongsTo(AcademicyearModel::class, "academic_year");
    }
}
