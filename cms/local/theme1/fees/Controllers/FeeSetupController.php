<?php

namespace cms\fees\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\fees\Models\FeeSetupModel;
use cms\fees\Models\SchoolTypeModel;
use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\fees\Models\FeeTypeModel;
use Configurations;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;
use cms\section\Models\SectionModel;
use cms\department\Models\DepartmentModel;
use cms\fees\Models\FeeSetupListModel;
use cms\academicyear\Models\AcademicyearModel;

class FeeSetupController extends Controller
{
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
        return view("fees::feesetup.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $info = Configurations::getAcademicandTermsInfo();

        $academicyears = Configurations::getAcademicyears();

        // already addded class
        $added_class = FeeSetupModel::where("status", 1)->pluck("class_id");
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->whereNotIn("id", $added_class)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $feetypes = FeeTypeModel::where("status", 1)
            ->pluck("type_name", "id")
            ->toArray();
        // dd($info);
        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();
        $school_type_info = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();
        return view("fees::feesetup.edit", [
            "layout" => "create",
            "class_lists" => $class_lists,
            "academicyears" => $academicyears,
            "info" => $info,
            "feetypes" => $feetypes,
            "departments" => $departments,
            "school_type_info" => $school_type_info,
            "selectedfeetype" => [],
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
        $this->validate($request, [
            "academic_year" => "required",
            "class_id" => "required",
        ]);
        DB::beginTransaction();
        try {
            $obj = new FeeSetupModel();
            $obj->academic_year = $request->academic_year;
            $obj->class_id = $request->class_id;
            $obj->school_type = $request->school_type;
            $obj->department_id = $request->department_id;
            $obj->total_amount = $request->total_amount;
            if ($obj->save()) {
                // save fee lists
                for ($i = 0; $i < sizeof($request->fee_id); $i++) {
                    # code...
                    $lists = new FeeSetupListModel();
                    $lists->fee_setup_id = $obj->id;
                    $lists->fee_id = $request->fee_id[$i];
                    $lists->fee_name = $request->fee_name[$i];
                    $lists->fee_amount = $request->fee_amount[$i];
                    $lists->is_compulsory = $request->is_compulsory[$i];
                    $lists->save();
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

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("feesetup.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("feesetup.index");
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
        $data = FeeSetupModel::with("feelists")->find($id);
        $info = Configurations::getAcademicandTermsInfo();

        $academicyears = Configurations::getAcademicyears();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        // dd($data);
        $feetypes = FeeTypeModel::where("status", 1)
            ->pluck("type_name", "id")
            ->toArray();
        // dd($info);
        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();

        $selectedid = $data->feelists->pluck("fee_id");

        $selectedfeetype = $data->feelists->pluck("fee_id");

        $school_type_info = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();
        // dd(eetypes,)
        //dd($selectedid);
        return view("fees::feesetup.edit", [
            "layout" => "edit",
            "data" => $data,
            "class_lists" => $class_lists,
            "academicyears" => $academicyears,
            "info" => $info,
            "feetypes" => $feetypes,
            "departments" => $departments,
            "selectedid" => $selectedid,
            "school_type_info" => $school_type_info,
            "selectedfeetype" => $selectedfeetype,
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
        // abort(500);
        // $this->validate($request, [
        //     "academic_year" => "required",
        //     "class_id" => "required",
        // ]);
        // dd($request->all());
        try {
            DB::beginTransaction();
            $obj = FeeSetupModel::find($id);
            $obj->total_amount = $request->total_amount;
            if ($obj->save()) {
                // save fee lists
                for ($i = 0; $i < sizeof($request->fee_id); $i++) {
                    # code...
                    $lists = FeeSetupListModel::where([
                        "fee_setup_id" => $id,
                        "fee_id" => $request->fee_id[$i],
                    ])->first();
                    if ($lists) {
                        $lists->fee_name = $request->fee_name[$i];
                        $lists->fee_amount = $request->fee_amount[$i];
                        $lists->is_compulsory = $request->is_compulsory[$i];
                        $lists->save();
                    } else {
                        $list = new FeeSetupListModel();
                        $list->fee_setup_id = $id;
                        $list->fee_id = $request->fee_id[$i];
                        $list->fee_name = $request->fee_name[$i];
                        $list->fee_amount = $request->fee_amount[$i];
                        $list->is_compulsory = $request->is_compulsory[$i];
                        $list->save();
                    }
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
        return redirect()->route("feesetup.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // dd($id,$request->all());
        if (!empty($request->selected_1)) {
            $delObj = new FeeSetupModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $del_list = FeeSetupListModel::where("fee_setup_id", $id)->delete();

            if ($del_list) {
                $delObj = FeeSetupModel::find($id);
                $delObj->delete();
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("feesetup.index");
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

        $data = FeeSetupModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "fee_setup.id as id",
            "fee_setup.academic_year",
            "fee_setup.class_id",
            "fee_setup.school_type",
            "fee_setup.department_id",
            "fee_setup.total_amount",
            "lclass.name as classname",
            "department.dept_name as deptname",
            "school_type.school_type as schooltype",
            "academicyear.year as acyear",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new FeeSetupModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new FeeSetupModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("fee_setup.status", "!=", -1)
            ->leftjoin("lclass", "lclass.id", "=", "fee_setup.class_id")
            ->leftjoin(
                "academicyear",
                "academicyear.id",
                "=",
                "fee_setup.academic_year"
            )
            ->leftjoin(
                "school_type",
                "school_type.id",
                "=",
                "fee_setup.school_type"
            )
            ->leftjoin(
                "department",
                "department.id",
                "=",
                "fee_setup.department_id"
            );

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
                    "route" => "feesetup",
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
            FeeSetupModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new FeeSetupModel();
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

    public function CheckDepartmentApplies(Request $request)
    {
        $school_type = $request->query("school", 0);
        $is_applies = SchoolTypeModel::where("id", $school_type)
            ->pluck("is_department")
            ->first();
        return response()->json(["is_applies" => $is_applies]);
    }
}
