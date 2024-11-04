<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use CGate;
use Session;
use User;

use Carbon\Carbon;

use Configurations;
use App\Traits\ApiResponse;
use cms\leave\Models\LeaveModel;
use cms\leave\Models\LeaveTypeModel;
use cms\teacher\Models\TeacherModel;
use cms\students\Models\StudentsModel;
use Yajra\DataTables\Facades\DataTables;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\classteacher\Models\ClassteacherModel;
class LeaveApiController extends Controller
{
    use ApiResponse, FileUploadTrait;
    //

    public function create($layout = null, $id = null)
    {
        $types = LeaveTypeModel::select("id", "leave_type")->get();
        if ($layout == "create") {
            $data = [
                "leave_types" => $types,
            ];
        } else {
            $leave_data = LeaveModel::find($id);
            if ($leave_data->application_status == 1) {
                return $this->success(
                    "Leave Was Approved,So You Cannot Edit Now.",
                    200
                );
            }
            $data = [
                "leave_types" => $types,
                "leave_data" => $leave_data,
            ];
        }
        return $this->success($data, "Data Fetched Successfully", 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "leave_type_id" => "required",
            "from_date" => "required",
            "to_date" => "required",
        ]);
        DB::beginTransaction();
        try {
            $from_date = Carbon::parse($request->from_date)->format("m/d/Y");
            $to_date = Carbon::parse($request->to_date)->format("m/d/Y");
            $no_days = Carbon::parse($request->to_date)->diffInDays(
                Carbon::parse($request->from_date)
            );
            // dd($no_days);
            $timezone = Configurations::getConfig("site")->time_zone;
            $obj = new LeaveModel();
            $obj->leave_type_id = $request->leave_type_id;
            $obj->from_date = $request->from_date;
            $obj->to_date = $request->to_date;
            $obj->no_days = $no_days;
            $obj->academic_year = Configurations::getCurrentAcademicyear();
            $obj->reason = $request->reason;
            $obj->application_date = $date = Carbon::now(
                $timezone
            )->toDateString();
            if ($request->attachment) {
                $obj->attachment = $this->uploadAttachment(
                    $request->attachment,
                    null,
                    "school/leave/"
                );
            }
            if (Session::get("ACTIVE_GROUP") != "Super Admin") {
                $active_user = $request->user()->id;

                $group_id = UserGroupMapModel::where(
                    "user_id",
                    $active_user
                )->first();

                $obj->group_id = $group_id->group_id;
                $obj->user_id = $active_user;
            } else {
                $obj->group_id = $request->member_type;
                $obj->user_id = $request->member_id;
            }

            $obj->save();
            $obj->from_date = Carbon::parse($obj->from_date)->format("d/m/Y");
            $obj->to_date = Carbon::parse($obj->to_date)->format("d/m/Y");
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            return $this->error($message, 500);
        }

        return $this->success($obj, "Leave Applied Successfully", 200);
    }

    public function show($id)
    {
        $leave_id = $id;

        $leave_data = LeaveModel::with("leave_type")
            ->where("id", $leave_id)
            ->first();

        // getting user

        $user = UserModel::where("id", $leave_data->user_id)->first();
        $group_map = UserGroupMapModel::where("user_id", $leave_data->user_id)
            ->pluck("group_id")
            ->first();
        $user->group_name = UserGroupModel::where("id", $leave_data->group_id)
            ->pluck("group")
            ->first();

        return $this->success(
            [
                "user" => $user,
                "leave_data" => $leave_data,
            ],
            "Data Fetched Successfully",
            200
        );
    }

    public function LeaveList(Request $request, $type = null)
    {
        $user_id = $request->user()->id;
        // dd($request->user());
        if ($type != null) {
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $current_academic_term = Configurations::getCurrentAcademicterm();

            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $classteacher = ClassteacherModel::with("class", "section")
                ->where([
                    "academic_year" => $current_academic_year,
                    "teacher_id" => $teacher_id,
                ])
                ->first();
            $user_ids = StudentsModel::where([
                "academic_year" => $classteacher->academic_year,
                "class_id" => $classteacher->class_id,
                "section_id" => $classteacher->section_id,
                "status" => 1,
            ])
                ->whereNull("deleted_by")
                ->whereNull("deleted_at")
                ->pluck("user_id");
            $data = LeaveModel::with("leave_type", "user")
                ->whereIn("user_id", $user_ids)
                ->orderBy("application_date", "desc")
                ->get();
        } else {
            $data = LeaveModel::with("leave_type", "user")
                ->where("user_id", $user_id)
                ->orderBy("application_date", "desc")
                ->get();
        }

        $data = $data->transform(function ($list) {
            $list->from_date = Carbon::parse($list->from_date)->format("d/m/Y");
            $list->to_date = Carbon::parse($list->to_date)->format("d/m/Y");
            $list->application_date = Carbon::parse(
                $list->application_date
            )->format("d/m/Y");
            $list->application_status =
                Configurations::STATUS[$list->application_status];
            return $list;
        });
        $leave_action = Configurations::STATUS;
        $action = [];
        foreach ($leave_action as $key => $value) {
            $action[] = ["id" => $key, "text" => $value];
        }
        $leave_data = [
            "leave_info" => $data,
            "leave_action" => $action,
        ];
        return $this->success($leave_data, "Data Fetched Successfully", 200);
    }

    public function Update(Request $request, $id)
    {
        $this->validate($request, [
            "leave_type_id" => "required",
            "from_date" => "required",
            "to_date" => "required",
        ]);

        try {
            $from_date = Carbon::parse($request->from_date)->format("m/d/Y");
            $to_date = Carbon::parse($request->to_date)->format("m/d/Y");
            $no_days = Carbon::parse($request->to_date)->diffInDays(
                Carbon::parse($request->from_date)
            );
            // dd($no_days);
            $timezone = Configurations::getConfig("site")->time_zone;
            $obj = LeaveModel::find($id);
            $obj->leave_type_id = $request->leave_type_id;
            $obj->from_date = $from_date;
            $obj->to_date = $to_date;
            $obj->no_days = $no_days;
            $obj->reason = $request->reason;

            if ($request->attachment) {
                $obj->attachment = $this->uploadAttachment(
                    $request->attachment,
                    null,
                    "school/leave/"
                );
            }
            if (Session::get("ACTIVE_GROUP") != "Super Admin") {
                $active_user = $request->user()->id;

                $group_id = UserGroupMapModel::where(
                    "user_id",
                    $active_user
                )->first();

                $obj->group_id = $group_id->group_id;
                $obj->user_id = $active_user;
            } else {
                $obj->group_id = $request->member_type;
                $obj->user_id = $request->member_id;
            }
            $obj->save();

            $obj->from_date = Carbon::parse($obj->from_date)->format("d/m/Y");
            $obj->to_date = Carbon::parse($obj->to_date)->format("d/m/Y");
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            return $this->error($message, 500);
        }

        return $this->success(
            $obj,
            "Leave Appliciation Updated Successfully",
            200
        );
    }

    public function destroy($id, Request $request)
    {
        if ($id) {
            $delObj = new LeaveModel();
            $delItem = $delObj->find($id);
            if ($delItem->application_status == 1) {
                return $this->error(
                    "Already Leave was Approved.So You can't allowed to delete it.",
                    500
                );
            }
            if ($delItem->attachment) {
                $this->deleteImage(
                    null,
                    $delItem->attachment ? $delItem->attachment : null
                );
            }

            $delItem->delete();
        }
        return $this->success("Deleted Successfully", 200);
    }

    public function LeaveAction(
        Request $request,
        $id = null,
        $application_status = null
    ) {
        LeaveModel::find($id)->update([
            "application_status" => $application_status,
        ]);
        if ($application_status == 1) {
            LeaveModel::find($id)->update([
                "approved_by" => $request->user()->id,
            ]);
        }
        if ($application_status == -1) {
            LeaveModel::find($id)->update([
                "rejected_by" => $request->user()->id,
            ]);
        }

        return $this->success("Application Status Changed successfully", 200);
    }
}
