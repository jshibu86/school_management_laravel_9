<?php

namespace cms\transport\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\transport\Models\TransportStaff;
use Illuminate\Validation\Rule;
use cms\core\user\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\transport\Models\TransportModel;
use Session;
use DB;
use CGate;
use Configurations;
use Hash;
class TransportStaffController extends Controller
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
        return view("transport::admin.staff.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("transport::admin.staff.edit", ["layout" => "create"]);
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
                "employee_name" =>
                    "required|min:3|max:50|unique:" .
                    (new TransportStaff())->getTable() .
                    ",employee_name",
                "email" =>
                    "required|email|unique:" .
                    (new UserModel())->getTable() .
                    ",email",
                "mobile" =>
                    "required|unique:" .
                    (new UserModel())->getTable() .
                    ",mobile",
                "license_no" =>
                    "required|unique:" .
                    (new TransportStaff())->getTable() .
                    ",license_no",
                "national_id_number" => "required",
            ],
            [
                "employee_name" => "Please Fill out Driver Name",
            ]
        );
        DB::beginTransaction();
        try {
            $role = UserGroupModel::where("group", "Driver")->first();
            if (empty($role)) {
                $message = "Create a Driver role and Assigen Permissions";
                $route = route("usergroup.create");
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error_link", $message)
                    ->with("link", $route);
                //throw new Exception("Create a Customer Role First");
            } else {
                $employee_info = TransportStaff::withTrashed()
                    ->latest("id")
                    ->first();
                // dd($employee_info);
                $password = Configurations::Generatepassword(4);
                $employee_code = Configurations::GenerateUsername(
                    $employee_info != null
                        ? $employee_info->employee_code
                        : null,
                    "D"
                );

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

                    $obj = new TransportStaff();
                    $obj->employee_name = $request->employee_name;
                    $obj->user_id = $user->id;
                    $obj->employee_code = $employee_code;
                    $obj->email = $request->email;
                    $obj->mobile = $request->mobile;
                    $obj->gender = $request->gender;
                    $obj->dob = $request->dob;
                    $obj->national_id_number = $request->national_id_number;
                    $obj->address_communication =
                        $request->address_communication;
                    $obj->date_ofjoin = date("Y-m-d");
                    $obj->blood_group = $request->blood_group;
                    $obj->maritial_status = $request->maritial_status;
                    $obj->license_no = $request->license_no;
                    if ($request->imagec) {
                        $user->images = $this->uploadImage(
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

            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("transportstaff.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("transportstaff.index");
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
        $data = TransportStaff::find($id);

        //dd($data);
        return view("transport::admin.staff.edit", [
            "layout" => "edit",
            "data" => $data,
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
        //dd($request->all());
        $user_id = TransportStaff::find($id);
        $this->validate(
            $request,
            [
                "employee_name" => "required",
                "email" => [
                    "required",
                    Rule::unique("users")
                        ->whereNull("deleted_at")
                        ->ignore($user_id->user_id),
                ],
                "mobile" => [
                    "required",
                    Rule::unique("users")
                        ->whereNull("deleted_at")
                        ->ignore($user_id->user_id),
                ],
                "license_no" => [
                    "required",
                    Rule::unique("transport_staff")
                        ->whereNull("deleted_at")
                        ->ignore($user_id->id),
                ],
                "national_id_number" => "required",
            ],
            [
                "employee_name" => "Please Fill out Driver Name",
            ]
        );

        //dd($request->all());

        try {
            $user = UserModel::find($user_id->user_id);

            $user->email = $request->email;
            $user->mobile = $request->mobile;

            if ($user->save()) {
                $obj = TransportStaff::find($id);

                $obj->employee_name = $request->employee_name;

                $obj->email = $request->email;
                $obj->mobile = $request->mobile;
                $obj->gender = $request->gender;
                $obj->dob = $request->dob;
                $obj->national_id_number = $request->national_id_number;
                $obj->address_communication = $request->address_communication;

                $obj->blood_group = $request->blood_group;
                $obj->maritial_status = $request->maritial_status;
                $obj->license_no = $request->license_no;
                if ($request->imagec) {
                    $obj->image = $this->uploadImage($request->imagec, "image");
                }
                $obj->save();
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
        return redirect()->route("transportstaff.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_1)) {
            $delObj = new TransportStaff();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            // dd($id);
            // get this staff already assigned bus
            DB::statement("SET FOREIGN_KEY_CHECKS=0;");
            $is_exists = TransportModel::where("status", 1)
                ->where("staff_id", $id)
                ->first();

            if ($is_exists) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "Whoops !! This Staff Assigned Vehicle Driver"
                    );
            }
            $delObj = new TransportStaff();
            $delItem = $delObj->find($id);

            $user = UserModel::where("id", $delItem->user_id)->forceDelete();
            $delItem->forceDelete();
            DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("transportstaff.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-1");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = TransportStaff::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "employee_name",
            "email",
            "mobile",
            "employee_code",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new TransportStaff())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new TransportStaff())->getTable() .
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
                    "route" => "transportstaff",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-1");
        if ($request->ajax()) {
            TransportStaff::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new TransportStaff();
            foreach ($request->selected_1 as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }
}
