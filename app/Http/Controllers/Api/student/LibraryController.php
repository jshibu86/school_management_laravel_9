<?php

namespace App\Http\Controllers\Api\student;

use App\Http\Controllers\Api\StudentBaseController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use cms\core\usergroup\Models\UserGroupModel;
use cms\fees\Models\AcademicFeeModel;
use cms\library\Models\LibraryMemberModel;
use Illuminate\Http\Request;
use Configurations;
use Carbon\Carbon;
use cms\library\Models\BookCategoryModel;
use cms\library\Models\EbookModel;
use cms\library\Models\LibraryModel;
use DB;

class LibraryController extends StudentBaseController
{
    use ApiResponse;
    public function GetSubscription()
    {
        $fees = Configurations::getConfig("site")->library_subscription;
        return $fees;
    }
    public function LibrarySubscription(Request $request)
    {
        DB::beginTransaction();
        try {
            $student = $this->GetStudent($request->user()->id);
            //
            $is_exists = LibraryMemberModel::where(
                "student_id",
                $student->id
            )->first();

            if ($is_exists) {
                return $this->error(
                    "This Student Already Registered || Or Inactive State",
                    200
                );
            } else {
                $bmember_info = LibraryMemberModel::withTrashed()
                    ->latest("id")
                    ->first();
                $member_username = Configurations::GenerateUsername(
                    $bmember_info != null
                        ? $bmember_info->member_username
                        : null,
                    "LM"
                );

                $member_type = UserGroupModel::where(
                    "id",
                    Configurations::STUDENT
                )->first();
                $date = Carbon::now("Asia/Kolkata")->toDateString();
                $obj = new LibraryMemberModel();

                $obj->member_username = $member_username;
                $obj->date_ofjoin = $date;
                $obj->academic_year = Configurations::getCurrentAcademicyear();
                $obj->student_id = $student->id;
                $obj->group_id = $member_type->id;
                $obj->member_type = strtolower($member_type->group);

                if ($obj->save()) {
                    $months_library = Configurations::GetMonthsOfAcademicYear(
                        Configurations::getCurrentAcademicyear(),
                        date("Y-m-d")
                    );

                    // save acdemic fees
                    $fee = new AcademicFeeModel();
                    $fee->academic_year = Configurations::getCurrentAcademicyear();
                    $fee->student_id = $student->id;
                    $fee->model_id = $obj->id;
                    $fee->model_name = LibraryMemberModel::class;
                    $fee->added_date = date("Y-m-d");
                    $fee->type = "library";
                    $fee->fee_name = "Library Fees";
                    $fee->due_amount =
                        sizeof($months_library) * $this->GetSubscription();
                    $fee->month_info = json_encode($months_library);
                    $fee->save();
                }
            }
            DB::commit();
            return $this->success([], "Successfully Subscrice Library", 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
    }

    public function LibraryCategory($type)
    {
        return BookCategoryModel::where("category_type", $type)->get();
    }

    public function LibraryBooks(Request $request, $type)
    {
        $search_query = $request->query->get("search", 0);
        $category_id = $request->query->get("category_id", 0);
        // get category ids for particulat type online or offline

        $category_ids = BookCategoryModel::where("status", 1)
            ->where("category_type", $type)
            ->pluck("id");

        if ($type == 1) {
            $books = EbookModel::query();
        } else {
            $books = LibraryModel::query();
        }

        $books = $books
            ->whereIn("category_id", $category_ids)
            ->when($category_id, function ($q) use ($category_id) {
                $q->where("category_id", $category_id);
            })
            ->when($search_query, function ($q) use ($search_query) {
                $q->where("title", "like", "%" . $search_query . "%");
            })
            ->get();

        return $this->success($books, "Successfully Fetched Books", 200);
    }
}
