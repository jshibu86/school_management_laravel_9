<?php

namespace cms\classteacher\Controllers;

use DB;
use CGate;
use Session;

use Illuminate\Http\Request;

use cms\lclass\Models\LclassModel;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use cms\classteacher\Models\ClassteacherModel;
use cms\core\configurations\helpers\Configurations;
use cms\department\Models\DepartmentModel;
use cms\section\Models\SectionModel;
use cms\teacher\Models\TeacherModel;

class ClassteacherController extends Controller
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
        if ($request->ajax()) {
            $class_id = $request->query->get("class", 0);
            $section_id = $request->query->get("section", 0);
            $acyear = $request->query->get("acyear", 0);
            $count = ClassteacherModel::where("class_id", $class_id)
                ->where("section_id", $section_id)
                ->where("academic_year", $acyear)
                ->where("status", 1)
                ->whereNull("deleted_at")

                ->count();
            return $count;
        }
        return view("classteacher::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_list = [];
        $section_list = [];
        return view("classteacher::admin.edit", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "class_lists" => $class_lists,
            "teacher_list" => $teacher_list,
            "section_list" => $section_list,
            "current_academic_year" => $current_academic_year,
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
        $this->validate($request, [
            "academic_year" => "required",
            "class_id" => "required",
            "section_id" => "required",
            "teacher_id" => "required",
        ]);

        $class_id = $request->class_id;
        $section_id = $request->section_id;
        $acyear = $request->academic_year;
        $teacher_id = $request->teacher_id;
        $count = ClassteacherModel::where("class_id", $class_id)
            ->where("section_id", $section_id)
            ->where("academic_year", $acyear)
            ->where("status", 1)

            ->count();
        if ($count != 0) {
            return redirect()
                ->back()

                ->with(
                    "exception_error",
                    "This Academic year  Section has already Assign teacher"
                );
        }
        // check this teacher already assigen same class section

        $check_teacher_already = ClassteacherModel::where("class_id", $class_id)
            ->where("academic_year", $acyear)
            ->where("teacher_id", $teacher_id)
            ->where("status", 1)
            ->count();
        if ($check_teacher_already != 0) {
            return redirect()
                ->back()

                ->with(
                    "exception_error",
                    " This teacher has already assigned This Academic year with  same class in Someother section try diffrent teacher"
                );
        }
        $obj = new ClassteacherModel();
        $obj->academic_year = $request->academic_year;
        $obj->class_id = $request->class_id;
        $obj->section_id = $request->section_id;
        $obj->teacher_id = $request->teacher_id;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("classteacher.index");
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
        $data = ClassteacherModel::find($id);
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $academic_years = Configurations::getAcademicyears();

        $teacher_list = TeacherModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("teacher_name", "id")
            ->toArray();
        $section_list = SectionModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        return view("classteacher::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "class_lists" => $class_lists,
            "academic_years" => $academic_years,
            "teacher_list" => $teacher_list,
            "section_list" => $section_list,
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
            "academic_year" => "required",
            "class_id" => "required",
            "section_id" => "required",
            "teacher_id" => "required",
        ]);
        $class_id = $request->class_id;
        $section_id = $request->section_id;
        $acyear = $request->academic_year;
        $teacher_id = $request->teacher_id;
        $count = ClassteacherModel::where("class_id", $class_id)
            ->where("section_id", $section_id)
            ->where("academic_year", $acyear)
            ->where("id", "!=", $id)
            ->where("status", 1)

            ->count();
        if ($count != 0) {
            return redirect()
                ->back()

                ->with(
                    "exception_error",
                    "This Academic year  Section has already Assign teacher"
                );
        }
        // check this teacher already assigen same class section

        $check_teacher_already = ClassteacherModel::where("class_id", $class_id)
            ->where("academic_year", $acyear)
            ->where("teacher_id", $teacher_id)
            ->where("status", 1)
            ->where("id", "!=", $id)
            ->count();
        if ($check_teacher_already != 0) {
            return redirect()
                ->back()

                ->with(
                    "exception_error",
                    " This teacher has already assigned This Academic year with  same class in Someother section try diffrent teacher"
                );
        }
        $obj = ClassteacherModel::find($id);
        $obj->academic_year = $request->academic_year;
        $obj->class_id = $request->class_id;
        $obj->section_id = $request->section_id;
        $obj->teacher_id = $request->teacher_id;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("classteacher.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_classteacher)) {
            $delObj = new ClassteacherModel();
            foreach ($request->selected_classteacher as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->forceDelete();
                }
            }
        }

        if ($id) {
            $delObj = new ClassteacherModel();
            $delItem = $delObj->find($id);
            $delItem->forceDelete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("classteacher.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-classteacher");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ClassteacherModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "classteacher.id as id",
            "academicyear.year as academic_year",
            "lclass.name as class",
            "section.name as section",
            "section.id as section_id",
            "section.department_id as section_department",
            "teacher.teacher_name as teacher_name",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ClassteacherModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ClassteacherModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("classteacher.status", "!=", -1)
            ->join(
                "academicyear",
                "academicyear.id",
                "=",
                "classteacher.academic_year"
            )
            ->join("lclass", "lclass.id", "=", "classteacher.class_id")
            ->join("section", "section.id", "=", "classteacher.section_id")
            ->join("teacher", "teacher.id", "=", "classteacher.teacher_id");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("department", function ($data) {
                if ($data->section_department != null) {
                    $dept = DepartmentModel::where(
                        "id",
                        $data->section_department
                    )->first()->dept_name;

                    return $dept;
                } else {
                    return "N/A";
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
                    "route" => "classteacher",
                ])->render();

                //return $data->id;
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
        CGate::authorize("edit-classteacher");
        if ($request->ajax()) {
            ClassteacherModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_classteacher)) {
            $obj = new ClassteacherModel();
            foreach ($request->selected_classteacher as $k => $v) {
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
