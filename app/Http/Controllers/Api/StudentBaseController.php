<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use cms\students\Models\StudentsModel;
use Illuminate\Http\Request;

class StudentBaseController extends Controller
{
    public function GetStudent($id)
    {
        return StudentsModel::where("user_id", $id)->first();
    }
}
