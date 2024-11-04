<?php

namespace cms\account\Models;

use Illuminate\Database\Eloquent\Model;
use Sqits\UserStamps\Concerns\HasUserStamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use User;

class IncomeExpenseCategoryModel extends Model
{
    protected $table = "income_expense_category";
    protected $guarded = [];
}
