<?php

namespace cms\section\Controllers;

use DB;
use CGate;
use Session;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use cms\classteacher\Models\ClassteacherModel;
use cms\classtimetable\Models\ClasstimetableModel;
use cms\section\Models\SectionModel;
use Yajra\DataTables\Facades\DataTables;
use cms\core\configurations\helpers\Configurations;
use cms\department\Models\DepartmentModel;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;

class SectionController extends Controller
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
    public function index(Request $request)
    {
        // check for ajax request here
        if ($request->ajax()) {
            $class_id = $request->query->get("class", 0);
            $type = $request->query->get("type", 0);

            if ($type === "timetable") {
                $sec = ClasstimetableModel::where("class_id", $class_id)
                    ->pluck("section_id")
                    ->toArray();
                $sections = SectionModel::select("id", "name as text")
                    ->where("class_id", $class_id)
                    ->whereNotIn("id", $sec)
                    ->where("status", 1)
                    ->orderBy("name", "asc")
                    ->get();
                return $sections;
            }
            //return $type;

            $sections = SectionModel::select([
                "section.id as id",
                DB::raw(
                    "CONCAT(section.name, IFNULL(CONCAT('-', department.dept_name), CONCAT('-','NA'))) as text"
                ),
            ])
                ->where("class_id", $class_id)
                ->leftjoin(
                    "department",
                    "department.id",
                    "=",
                    "section.department_id"
                )
                ->where("section.status", 1)
                ->orderBy("name", "asc")
                ->get();

            // $sections = SectionModel::select("id", "name as text")
            //     ->where("class_id", $class_id)
            //     ->where("status", 1)
            //     ->orderBy("name", "asc")
            //     ->leftjoin("section", "department_id", "=", "section.class_id")
            //     ->join("lcalss", "lcalss.id", "=", "section.class_id")
            //     ->get();

            return $sections;
        }
        return view("section::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class_list = LclassModel::where("status", "!=", -1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();

        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();

        return view("section::admin.edit", [
            "layout" => "create",
            "class_list" => $class_list,
            "departments" => $departments,
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
        $this->validate($request, [
            "name" => ["required"],

            "capacity" => "required",
        ]);
        //checking already section there
        $s_name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        if ($request->department_id) {
            $s_found = SectionModel::where("status", 1)
                ->where("class_id", $request->class_id)
                ->where("name", $s_name)
                ->where("department_id", $request->department_id)
                ->first();
        } else {
            $s_found = SectionModel::where("status", 1)
                ->where("class_id", $request->class_id)
                ->where("name", $s_name)
                ->first();
        }

        if ($s_found) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "exception_error",
                    "This Section has already in this Class"
                );
        }

        $school_type = LclassModel::find($request->class_id)->school_type_id;
        $obj = new SectionModel();
        $obj->name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");

        $obj->class_id = $request->class_id;
        $obj->capacity = $request->capacity;
        $obj->department_id = $request->department_id;

        $obj->school_type = $school_type;
        $obj->save();
        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("section.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("section.index");
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
        $data = SectionModel::find($id);

        $class_list = LclassModel::where("status", "!=", -1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();
        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();
        return view("section::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "class_list" => $class_list,
            "departments" => $departments,
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
            "name" => ["required"],

            "capacity" => "required",
        ]);

        $s_name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        if ($request->department_id) {
            $s_found = SectionModel::where("status", 1)
                ->where("class_id", $request->class_id)
                ->where("name", $s_name)
                ->where("id", "!=", $id)
                ->where("department_id", $request->department_id)
                ->first();
        } else {
            $s_found = SectionModel::where("status", 1)
                ->where("class_id", $request->class_id)
                ->where("name", $s_name)
                ->where("id", "!=", $id)
                ->first();
        }

        if ($s_found) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "exception_error",
                    "This Section has already in this Class"
                );
        }
        $school_type = LclassModel::find($request->class_id)->school_type_id;
        $obj = SectionModel::find($id);
        $obj->name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        $obj->class_id = $request->class_id;
        $obj->capacity = $request->capacity;
        $obj->department_id = $request->department_id;
        $obj->school_type = $school_type;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("section.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_section)) {
            $delObj = new SectionModel();
            foreach ($request->selected_section as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $class_teacher = ClassteacherModel::where(
                "section_id",
                $id
            )->count();
            $students = StudentsModel::where("section_id", $id)->count();

            // dd($students);
            if ($class_teacher || $students) {
                return redirect()
                    ->back()
                    ->with(
                        "exception_error",
                        "Can't Delete Section !! This Section has Assigened Class teacher or some students"
                    );
            }
            DB::statement("SET FOREIGN_KEY_CHECKS=0;");
            $delObj = new SectionModel();
            $delItem = $delObj->find($id);
            $delItem->forceDelete();
            DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("section.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-section");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = SectionModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "section.id as id",
            "section.name as name",
            "capacity",
            "school_type",

            "lclass.name as classname",
            "department.dept_name as deptname",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new SectionModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new SectionModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("section.status", "!=", -1)
            ->join("lclass", "section.class_id", "=", "lclass.id")
            ->leftjoin(
                "department",
                "section.department_id",
                "=",
                "department.id"
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

            ->addColumn("schooltype", function ($data) {
                if ($data->school_type != null) {
                    $schoolType = SchoolTypeModel::find($data->school_type);
                    return $schoolType ? $schoolType->school_type : "";
                }
                return "";
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
                    "route" => "section",
                ])->render();

                //return $data->id;
            });

        //return $data;
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
        CGate::authorize("edit-section");
        if ($request->ajax()) {
            SectionModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_section)) {
            $obj = new SectionModel();
            foreach ($request->selected_section as $k => $v) {
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
