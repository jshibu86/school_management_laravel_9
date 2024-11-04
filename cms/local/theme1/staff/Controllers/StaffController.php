<?php

namespace cms\staff\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\staff\Models\StaffModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\academicyear\Models\AcademicyearModel;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\usergroup\Models\UserGroupModel;
use Configurations;
use cms\department\Models\DepartmentModel;
use cms\staff\Models\StaffAttendanceModel;
use cms\teacher\Models\DesignationModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
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
    public function index()
    {
        return view("staff::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designation_list = [];
        $gender = Configurations::GENDER;
        $religion = Configurations::RELIGION;
        $bloodgroup = Configurations::BLOODGROUPS;
        $maritialstatus = Configurations::MARITIALSTATUS;
        $data = DesignationModel::whereNull("deleted_at")
            ->where("status", 1)
            ->get();
        if (!empty($data)) {
            foreach ($data as $designation) {
                $designation_list[$designation->id] =
                    $designation->designation_name . "-" . $designation->type;
            }
        }

        $departments = DepartmentModel::where("status", 1)

            ->pluck("dept_name", "id")
            ->toArray();

        $usergroup = UserGroupModel::whereNotIn("id", [1, 3, 4])
            ->pluck("group", "id")
            ->toArray();
        return view("staff::admin.edit", [
            "layout" => "create",
            "designation_list" => $designation_list,
            "gender" => $gender,
            "maritialstatus" => $maritialstatus,
            "religion" => $religion,
            "bloodgroup" => $bloodgroup,
            "departments" => $departments,
            "usergroup" => $usergroup,
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
        //dd($request->all());
        $this->validate(
            $request,
            [
                "employee_name" => "required|regex:/^[a-zA-Z\s]*$/",
                "email" =>
                    "required|email|unique:" .
                    (new UserModel())->getTable() .
                    ",email",
                "mobile" =>
                    "required|numeric|unique:" .
                    (new UserModel())->getTable() .
                    ",mobile",
                "license_no" =>
                    "unique:" . (new StaffModel())->getTable() . ",license_no",
                "national_id_number" => "required",
            ],
            [
                "employee_name" => "Please Fill out Staff Name",
            ]
        );
        DB::beginTransaction();
        try {
            $role = UserGroupModel::where("id", $request->group_id)->first();
            $firstLetter = strtoupper(substr($role->group, 0, 1));

            //dd($firstLetter);
            if (empty($role)) {
                $message = "There is No Group";
                $route = route("usergroup.create");
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error_link", $message)
                    ->with("link", $route);
                //throw new Exception("Create a Customer Role First");
            } else {
                $employee_info = StaffModel::where(
                    "group_id",
                    $request->group_id
                )
                    ->latest("id")
                    ->first();
                // dd($employee_info);
                $password = Configurations::Generatepassword(4);
                $employee_code = Configurations::GenerateUsername(
                    $employee_info != null
                        ? $employee_info->employee_code
                        : null,
                    $firstLetter
                );
                // dd($employee_info->employee_code, $firstLetter, $employee_code);
                $user = new UserModel();
                $user->name = $request->employee_name;
                $user->username = $employee_code;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $Hash = Hash::make($password);
                $user->password = $Hash;
                if ($request->imagec) {
                    $user->images = $this->uploadImage(
                        $request->imagec,
                        "image"
                    );
                }
                if ($user->save()) {
                    //user role set
                    $usertypemap = new UserGroupMapModel();
                    $usertypemap->user_id = $user->id;
                    $usertypemap->group_id = $role->id;
                    $usertypemap->save();

                    $obj = new StaffModel();
                    $obj->employee_name = $request->employee_name;
                    $obj->user_id = $user->id;
                    $obj->group_id = $role->id;
                    $obj->employee_code = $employee_code;
                    $obj->designation_id = $request->designation_id;
                    $obj->email = $request->email;
                    $obj->mobile = $request->mobile;
                    $obj->qualification = $request->qualification;
                    $obj->gender = $request->gender;
                    $obj->religion = $request->religion;
                    $obj->dob = $request->dob;
                    $obj->national_id_number = $request->national_id_number;

                    $address_communication = [
                        "house_no" => $request->house_no,
                        "street_name" => $request->street_name,
                        "postal_code" => $request->postal_code,
                        "province" => $request->province,
                        "country" => $request->country,
                    ];

                    $obj->address_communication = json_encode(
                        $address_communication
                    );
                    $obj->date_ofjoin = $request->date_ofjoin;
                    $obj->blood_group = $request->blood_group;
                    $obj->maritial_status = $request->maritial_status;
                    $obj->license_no = $request->license_no;
                    if ($request->imagec) {
                        $obj->image = $this->uploadImage(
                            $request->imagec,
                            "image"
                        );
                    }
                    $obj->save();
                }

                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            dd($e);

            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("staff.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("staff.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = StaffModel::find($id);

        $designation_list = [];
        $gender = Configurations::GENDER;
        $maritialstatus = Configurations::MARITIALSTATUS;
        $religion = Configurations::RELIGION;
        $bloodgroup = Configurations::BLOODGROUPS;
        $data_list = DesignationModel::whereNull("deleted_at")
            ->where("status", 1)
            ->get();
        if (!empty($data_list)) {
            foreach ($data_list as $designation) {
                $designation_list[$designation->id] =
                    $designation->designation_name . "-" . $designation->type;
            }
        }
        $address_communication = json_decode($data->address_communication);

        $usergroup = UserGroupModel::whereNotIn("id", [1, 3, 4])
            ->pluck("group", "id")
            ->toArray();
        // dd($data);
        return view("staff::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "designation_list" => $designation_list,
            "gender" => $gender,
            "maritialstatus" => $maritialstatus,
            "address_communication" => $address_communication,
            "religion" => $religion,
            "bloodgroup" => $bloodgroup,
            "usergroup" => $usergroup,
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
        // dd($request->all());
        $user_id = StaffModel::find($id);
        $this->validate(
            $request,
            [
                "employee_name" => "required|regex:/^[a-zA-Z\s]*$/",
                "email" => [
                    "required",
                    Rule::unique("users")
                        ->whereNull("deleted_at")
                        ->ignore($user_id->user_id),
                ],
                "mobile" => [
                    "required",
                    "numeric",
                    Rule::unique("users")
                        ->whereNull("deleted_at")
                        ->ignore($user_id->user_id),
                ],

                "national_id_number" => "required",
                "qualification" => "required",
            ],
            [
                "employee_name" => "Please Fill out Driver Name",
            ]
        );

        try {
            DB::beginTransaction();

            $obj = StaffModel::find($id);

            $obj->employee_name = $request->employee_name;

            $obj->email = $request->email;
            $obj->mobile = $request->mobile;
            $obj->gender = $request->gender;
            $obj->religion = $request->religion;
            $obj->dob = $request->dob;
            $obj->national_id_number = $request->national_id_number;
            $address_communication = [
                "house_no" => $request->house_no,
                "street_name" => $request->street_name,
                "postal_code" => $request->postal_code,
                "province" => $request->province,
                "country" => $request->country,
            ];

            $obj->address_communication = json_encode($address_communication);

            $obj->blood_group = $request->blood_group;
            $obj->maritial_status = $request->maritial_status;
            $obj->designation_id = $request->designation_id;
            $obj->license_no = $request->license_no;
            if ($request->imagec) {
                $obj->image = $this->uploadImage($request->imagec, "image");
            }

            if ($obj->save()) {
                $user = UserModel::find($obj->user_id);
                $user->name = $request->employee_name;
                // $user->username = $employee_code;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                // $Hash = Hash::make($password);
                // $user->password = $Hash;
                if ($request->imagec) {
                    $user->images = $obj->image;
                }
            }

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
        return redirect()->route("staff.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_staff)) {
            $delObj = new StaffModel();
            foreach ($request->selected_staff as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new StaffModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("staff.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-staff");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = StaffModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "staff.id as id",
            "employee_name as teacher_name",
            "employee_code",
            "email",
            "mobile",
            "designation.designation_name as designation",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new StaffModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new StaffModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->leftjoin(
                "designation",
                "staff.designation_id",
                "=",
                "designation.id"
            )
            ->where("staff.status", "!=", -1)
            ->orderBy("staff.id", "asc");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("designation_name", function ($data) {
                if ($data->designation != null) {
                    return $data->designation;
                } else {
                    return "<span class='text-danger'>Not Assign</span>";
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
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "staff",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["action", "designation_name"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-staff");
        if ($request->ajax()) {
            StaffModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_staff)) {
            $obj = new StaffModel();
            foreach ($request->selected_staff as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function StaffAttendance(Request $request)
    {
        [$date, $time, $month, $year] = Configurations::getcurrentDateTime();
        $acyear = AcademicyearModel::find(
            Configurations::getCurrentAcademicyear()
        )->year;
        if ($request->ajax()) {
            $user_ids = UserGroupMapModel::where(
                "group_id",
                $request->query->get("group_id")
            )->pluck("user_id");

            $users = [];

            $users_data = UserModel::with("attendance")
                ->where("status", 1)
                ->whereNull("deleted_at")
                ->whereIn("id", $user_ids)

                ->get();
            //return $users_data;
            $view = view("staff::admin.staffattendancetable", [
                "users_data" => $users_data,
                "acyear" => $acyear,
                "date" => $date,
                "group_id" => $request->query->get("group_id"),
            ])->render();

            return response()->json(["view" => $view]);
            return "ok";
        }
        if ($request->isMethod("post")) {
            try {
                if ($request->attendences) {
                    foreach ($request->attendences as $key => $value) {
                        # code...

                        $exists = StaffAttendanceModel::where([
                            "group_id" => $request->group_id,
                            "user_id" => $key,
                            "attendance_date" => $date,
                        ])->first();

                        if ($exists) {
                            $exists->update(["attendance" => $value]);
                        } else {
                            $attendance = new StaffAttendanceModel();
                            $attendance->user_id = $key;
                            $attendance->group_id = $request->group_id;
                            $attendance->attendance_date = $date;
                            $attendance->attendance_month = $month;
                            $attendance->attendance_year = $year;
                            $attendance->attendance = $value;
                            $attendance->academic_year = Configurations::getCurrentAcademicyear();
                            $attendance->save();
                        }
                    }
                }

                return redirect()
                    ->back()
                    ->with("success", "Successfully Added Attendance");
            } catch (\Exception $e) {
                // dd($e);
                return redirect()
                    ->back()
                    ->with("error", "Something Went Wrong");
            }
        }

        $user_group = UserGroupModel::where("status", 1)
            ->whereNotIn("id", [1, 4, 5])
            ->pluck("group", "id")
            ->toArray();

        $academic_data = Configurations::getAcademicandTermsInfo();

        return view("staff::admin.staffattendance", [
            "layout" => "create",
            "user_group" => $user_group,
            "date" => $date,
            "academic_data" => $academic_data,
        ]);

        dd("here");
    }
}
