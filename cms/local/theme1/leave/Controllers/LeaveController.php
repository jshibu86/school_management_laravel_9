<?php

namespace cms\leave\Controllers;

use DB;
use CGate;
use Session;
use User;

use Carbon\Carbon;

use Configurations;
use Illuminate\Http\Request;
use cms\leave\Models\LeaveModel;
use App\Http\Controllers\Controller;
use cms\leave\Models\LeaveTypeModel;
use cms\teacher\Models\TeacherModel;
use cms\students\Models\StudentsModel;
use Yajra\DataTables\Facades\DataTables;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupMapModel;

class LeaveController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $error = CGate::module();
            if ($error == 1) {
                return redirect()
                    ->route("errorPage")
                    ->with(
                        "error",
                        "You do not have access to this module. Please contact the administrator for further assistance."
                    );
            } else {
                return $next($request);
            }
        });
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $leave_id = $request->query->get("leave");

            $leave_data = LeaveModel::where("id", $leave_id)->first();

            // getting user

            $user = UserModel::where("id", $leave_data->user_id)->first();

            $view = view("leave::admin.showleave", [
                "user" => $user,

                "leave_data" => $leave_data,
            ])->render();
            return response()->json(["viewfile" => $view]);
        }
        $fillter = $request->query->get("fillter");
        $role = $request->query->get("role");
        $status_ = $request->query->get("leavestatus");

        //dd($fillter);
        // dd($status_);
        $groups = UserGroupModel::where("status", 1)
            ->pluck("group", "id")
            ->toArray();
        if ($fillter == "fillter") {
            // dd($status_);
            return view("leave::admin.index", [
                "roles" => $groups,
                "status" => Configurations::STATUS,
                "role" => $role,
                "status_" => $status_,
            ]);
        }

        return view("leave::admin.index", [
            "roles" => $groups,
            "status" => Configurations::STATUS,
        ]);
    }

    public function leaveprint(Request $request, $id)
    {
        $leave_data = LeaveModel::where("id", $id)->first();

        // getting student or teacher
        $user = UserModel::where("id", $leave_data->user_id)->first();
        // dd($user);
        return view("leave::admin.print", [
            "user" => $user,

            "leave_data" => $leave_data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = LeaveTypeModel::pluck("leave_type", "id")->toArray();

        $groups = UserGroupModel::where("status", 1)
            ->pluck("group", "id")
            ->toArray();
        return view("leave::admin.edit", [
            "layout" => "create",
            "type" => $type,
            "groups" => $groups,
            "users" => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        //dd(Configurations::Activestudent()->id);

        // if (Session::get("ACTIVE_GROUP") == "Super Admin") {
        //     return redirect()
        //         ->back()

        //         ->with("exception_error", "Access Denied");
        // }
        $this->validate($request, [
            "reason" => "max:190",
            "leave_type_id" => "required",
            "from_date" => "required",
            "to_date" => "required",
        ]);
        DB::beginTransaction();
        try {
            $obj = new LeaveModel();
            $obj->leave_type_id = $request->leave_type_id;
            $obj->from_date = $request->from_date;
            $obj->to_date = $request->to_date;
            $obj->no_days = $request->no_days;
            $obj->academic_year = Configurations::getCurrentAcademicyear();
            $obj->reason = $request->reason;
            $obj->application_date = $date = Carbon::now(
                "Asia/Kolkata"
            )->toDateString();
            if ($request->attachment) {
                $obj->attachment = $this->uploadAttachment(
                    $request->attachment,
                    null,
                    "school/leave/"
                );
            }
            if (Session::get("ACTIVE_GROUP") != "Super Admin") {
                $active_user = User::getUser()->id;

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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("leave.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $leave_id = $id;

        $leave_data = LeaveModel::where("id", $leave_id)->first();

        // getting user

        $user = UserModel::where("id", $leave_data->user_id)->first();

        return view("leave::admin.viewleavewithhistory", [
            "user" => $user,

            "leave_data" => $leave_data,
        ]);

        // dd("show");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = LeaveModel::find($id);
        $type = LeaveTypeModel::pluck("leave_type", "id")->toArray();
        $groups = UserGroupModel::where("status", 1)
            ->pluck("group", "id")
            ->toArray();
        $group = UserGroupModel::where("id", $data->group_id)->first();

        // getting user_ids

        $user_ids = UserGroupMapModel::where(
            "group_id",
            $data->group_id
        )->pluck("user_id");

        $users = UserModel::whereIn("id", $user_ids)
            ->whereNull("deleted_at")
            ->select([
                "users.id as id",
                DB::raw("CONCAT(users.username, ' - ', users.email) as text"),
            ])
            ->pluck("text", "id")
            ->toArray();

        return view("leave::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "type" => $type,
            "groups" => $groups,
            "group" => $group->group,
            "users" => $users,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            "reason" => "max:190",
            "leave_type_id" => "required",
            "from_date" => "required",
            "to_date" => "required",
        ]);

        try {
            $obj = LeaveModel::find($id);
            $obj->leave_type_id = $request->leave_type_id;
            $obj->from_date = $request->from_date;
            $obj->to_date = $request->to_date;
            $obj->no_days = $request->no_days;
            $obj->reason = $request->reason;

            if ($request->attachment) {
                $obj->attachment = $this->uploadAttachment(
                    $request->attachment,
                    null,
                    "school/leave/"
                );
            }
            if (Session::get("ACTIVE_GROUP") != "Super Admin") {
                $active_user = User::getUser()->id;

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

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("leave.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_leave)) {
            $delObj = new LeaveModel();
            foreach ($request->selected_leave as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new LeaveModel();
            $delItem = $delObj->find($id);
            if ($delItem->attachment) {
                $this->deleteImage(
                    null,
                    $delItem->attachment ? $delItem->attachment : null
                );
            }

            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("leave.index");
    }
    public function leavetypeDestroy($id, Request $request)
    {
        if (!empty($request->selected_leave)) {
            $delObj = new LeaveModel();
            foreach ($request->selected_leave as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new LeaveTypeModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("leave.leavetypes");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-leave");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        $role = $request->get("role");
        $leavestatus = $request->get("leavestatus");

        // return $role;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = LeaveModel::query();

        $data = $data
            ->select(
                DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                "leave.id as id",
                "leave_types.leave_type as type",
                "users.name as name",
                "user_groups.group as group",
                "leave.from_date as from",
                "leave.to_date as to",
                "leave.application_status as leavestatus",
                "leave.group_id as group_id",

                DB::raw(
                    "(CASE WHEN " .
                        DB::getTablePrefix() .
                        (new LeaveModel())->getTable() .
                        '.status = "0" THEN "Disabled"
            WHEN ' .
                        DB::getTablePrefix() .
                        (new LeaveModel())->getTable() .
                        '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
                )
            )
            ->join("leave_types", "leave.leave_type_id", "=", "leave_types.id")
            ->leftjoin("users", "users.id", "=", "leave.user_id")
            ->join("user_groups", "user_groups.id", "=", "leave.group_id")

            ->whereIn("leave.application_status", [1, 2, -1]);

        if ($role && $role != "null" && $role != null) {
            //return $role;

            $data = $data->where("leave.group_id", $role);
        }

        if (Session::get("ACTIVE_GROUP") != "Super Admin") {
            $active_user = User::getUser()->id;

            $group_id = UserGroupMapModel::where(
                "user_id",
                $active_user
            )->first();

            $data = $data->where("user_id", $active_user);
        }

        if ($leavestatus && $leavestatus != "null" && $leavestatus != null) {
            $data = $data->where("leave.application_status", $leavestatus);
        } else {
            $data = $data;
        }

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("applicantname", function ($data) {
                return "<div class='d-flex appname'><span>" .
                    $data->name .
                    "</span><span class='badge bg-primary'>" .
                    $data->group .
                    "</span></div>";
            })
            ->addColumn("fromto", function ($data) {
                return $data->from . "-" . $data->to;
            })
            ->addColumn("status", function ($data) {
                if ($data->leavestatus == -1) {
                    return "<span class='badge bg-danger'>Rejected</span>";
                } elseif ($data->leavestatus == 1) {
                    return "<span class='badge bg-success'>Approved</span>";
                } else {
                    return "<span class='badge bg-warning'>Pending</span>";
                }
            })
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("actdeact", function ($data) {
                if ($data->id != "1") {
                    $statusbtnvalue =
                        $data->status == "Enabled"
                            ? "<i class='glyphicon glyphicon-remove'></i>&nbsp;&nbsp;Disable"
                            : "<i class='glyphicon glyphicon-ok'></i>&nbsp;&nbsp;Enable";
                    return '<a class="statusbutton btn btn-default" data-toggle="modal" data="' .
                        $data->id .
                        '" href="">' .
                        $statusbtnvalue .
                        "</a>";
                } else {
                    return "";
                }
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.dropdownaction", [
                    "data" => $data,
                    "route" => "leave",
                ])->render();
            });

        //return $status_;
        // if ($status_) {
        //     return $status_;
        // }
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["action", "applicantname", "status"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request, $id, $action)
    {
        // dd($request->all());
        CGate::authorize("edit-leave");
        if ($request->ajax()) {
            LeaveModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }
        if ($id) {
            //dd($action);

            LeaveModel::find($id)->update([
                "application_status" => $action,
            ]);
            if ($action == 1) {
                LeaveModel::find($id)->update([
                    "approved_by" => User::getUser()->id,
                ]);
            }
            if ($action == -1) {
                LeaveModel::find($id)->update([
                    "rejected_by" => User::getUser()->id,
                ]);
            }
        }

        if ($request->leave_id) {
            LeaveModel::find($request->leave_id)->update([
                "application_status" => $request->feedback,
            ]);
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function leavetypes(Request $request)
    {
        if ($request->isMethod("post")) {
            dd($request->all());
        }

        return view("leave::admin.leavetypes", ["layout" => "create"]);
    }

    public function leavetypeStore(Request $request)
    {
        $data = $request->all();

        unset($data["_token"]);
        unset($data["submit_cat_continue"]);
        unset($data["submit_cat"]);

        LeaveTypeModel::create($data);

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("leavetype.create")
                ->with("success", "Saved Successfully");
        }

        return redirect()
            ->route("leave.leavetypes")
            ->with("success", "Leave Type Added Successfully");
    }

    public function leavetypecreate()
    {
        return view("leave::admin.levetypeedit", ["layout" => "create"]);
    }

    public function leavetypeEdit(Request $request, $id)
    {
        if ($request->isMethod("put")) {
            $data = $request->all();

            unset($data["_token"]);
            unset($data["submit_cat_continue"]);
            unset($data["submit_cat"]);

            LeaveTypeModel::find($id)->update($data);
            return redirect()
                ->route("leave.leavetypes")
                ->with("success", "Leave Type Updated Successfullly");
        } else {
            if ($id) {
                $data = LeaveTypeModel::find($id);

                return view("leave::admin.levetypeedit", [
                    "data" => $data,
                    "layout" => "edit",
                ]);
            }
        }
    }

    public function getDataLeavetype(Request $request)
    {
        CGate::authorize("view-leave");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = LeaveTypeModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "leave_type",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new LeaveTypeModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new LeaveTypeModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )->where("status", "!=", -1);

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("actdeact", function ($data) {
                if ($data->id != "1") {
                    $statusbtnvalue =
                        $data->status == "Enabled"
                            ? "<i class='glyphicon glyphicon-remove'></i>&nbsp;&nbsp;Disable"
                            : "<i class='glyphicon glyphicon-ok'></i>&nbsp;&nbsp;Enable";
                    return '<a class="statusbutton btn btn-default" data-toggle="modal" data="' .
                        $data->id .
                        '" href="">' .
                        $statusbtnvalue .
                        "</a>";
                } else {
                    return "";
                }
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "leavetype",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->make(true);
    }

    public function gethistoryLeave(Request $request, $id)
    {
        CGate::authorize("view-leave");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        // return $role;

        $find_member = LeaveModel::where("id", $id)->first();

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = LeaveModel::query();

        $data = $data
            ->select(
                DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                "leave.id as id",
                "leave_types.leave_type as type",
                "users.name as name",
                "user_groups.group as group",
                "leave.from_date as from",
                "leave.to_date as to",
                "leave.application_status as leavestatus",
                "leave.group_id as group_id",

                DB::raw(
                    "(CASE WHEN " .
                        DB::getTablePrefix() .
                        (new LeaveModel())->getTable() .
                        '.status = "0" THEN "Disabled"
            WHEN ' .
                        DB::getTablePrefix() .
                        (new LeaveModel())->getTable() .
                        '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
                )
            )
            ->join("leave_types", "leave.leave_type_id", "=", "leave_types.id")
            ->leftjoin("users", "users.id", "=", "leave.user_id")
            ->join("user_groups", "user_groups.id", "=", "leave.group_id")
            ->where("leave.id", "!=", $find_member->id)
            ->where("leave.user_id", $find_member->user_id);

        if (Session::get("ACTIVE_GROUP") != "Super Admin") {
            $active_user = User::getUser()->id;

            $group_id = UserGroupMapModel::where(
                "user_id",
                $active_user
            )->first();

            $data = $data->where("user_id", $active_user);
        }

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("applicantname", function ($data) {
                return "<div class='d-flex appname'><span>" .
                    $data->name .
                    "</span><span class='badge bg-primary'>" .
                    $data->group .
                    "</span></div>";
            })
            ->addColumn("fromto", function ($data) {
                return $data->from . "-" . $data->to;
            })
            ->addColumn("status", function ($data) {
                if ($data->leavestatus == -1) {
                    return "<span class='badge bg-danger'>Rejected</span>";
                } elseif ($data->leavestatus == 1) {
                    return "<span class='badge bg-success'>Approved</span>";
                } else {
                    return "<span class='badge bg-warning'>Pending</span>";
                }
            })
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("actdeact", function ($data) {
                if ($data->id != "1") {
                    $statusbtnvalue =
                        $data->status == "Enabled"
                            ? "<i class='glyphicon glyphicon-remove'></i>&nbsp;&nbsp;Disable"
                            : "<i class='glyphicon glyphicon-ok'></i>&nbsp;&nbsp;Enable";
                    return '<a class="statusbutton btn btn-default" data-toggle="modal" data="' .
                        $data->id .
                        '" href="">' .
                        $statusbtnvalue .
                        "</a>";
                } else {
                    return "";
                }
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.dropdownaction", [
                    "data" => $data,
                    "route" => "leave",
                ])->render();
            });

        //return $status_;
        // if ($status_) {
        //     return $status_;
        // }
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["action", "applicantname", "status"])
            ->make(true);
    }
}
