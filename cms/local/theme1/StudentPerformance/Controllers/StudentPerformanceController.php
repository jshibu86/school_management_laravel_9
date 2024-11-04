<?php

namespace cms\StudentPerformance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\configurations\helpers\Configurations;
use cms\shop\Models\ProductModel;
use cms\shop\Models\PurchaseOrderModel;

use Carbon\Carbon;
use cms\shop\Controllers\PurchaseOrderController;
use cms\shop\Models\OrderItemsModel;
use Yajra\DataTables\Facades\DataTables;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\subject\Models\SubjectModel;
use cms\report\Models\MarkReportModel;
use cms\StudentPerformance\Models\StudentPerformanceModel;
use cms\StudentPerformance\Models\StudentPerformanceDataModel;
use Session;
use DB;
use CGate;
use cms\students\Models\StudentsModel;

class StudentPerformanceController extends Controller
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
        $academicyears = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $school_type_info = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();

        $school_type_infos = Configurations::getSchooltypes();

        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $section_lists = [];
        $subjects = [];

        $examterms = Configurations::getCurentAcademicTerms();

        if ($request->ajax()) {
            $type = $request->query->get("type", 0);

            $academic_year = $request->query->get("academic_year", 0);
            $school_type = $request->query->get("school_type", 0);
            $term_id = $request->query->get("term", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $date = $request->query->get("date", 0);
            $period = $request->query->get("period", 0);
            // dd($date);
            if ($type == 1) {
                $sections = DB::table("section")
                    ->leftJoin(
                        "department",
                        "section.department_id",
                        "=",
                        "department.id"
                    )
                    ->where("section.class_id", $class_id)
                    ->select(
                        "section.id as id",
                        "section.name as section_name",
                        "department.dept_name as department_name"
                    )
                    ->get();

                return response()->json(["section" => $sections]);
            }
            if ($type == 2) {
                $is_exists = StudentPerformanceModel::where([
                    "school_type" => $school_type,
                    "academic_year" => $academic_year,
                    "term_id" => $term_id,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "period" => $period,
                    "month_year" => $date,
                ])->first();

                if ($is_exists) {
                    $stud_perform_id = $is_exists->id;
                    $student_performances = Configurations::getstudentPerformanceType();
                    $students = StudentsModel::where("class_id", $class_id)
                        ->where("section_id", $section_id)
                        ->where("status", 1)
                        ->get();
                    $view = view(
                        "StudentPerformance::admin.student_performance_append",
                        compact(
                            "stud_perform_id",
                            "students",
                            "student_performances"
                        )
                    )->render();
                } else {
                    $students = StudentsModel::where("class_id", $class_id)
                        ->where("section_id", $section_id)
                        ->where("status", 1)
                        ->get();
                    $student_performances = Configurations::getstudentPerformanceType();
                    // dd($student_performances);
                    $view = view(
                        "StudentPerformance::admin.student_performance_append",
                        compact(
                            "students",
                            "student_performances",
                            "term_id",
                            "academic_year",
                            "school_type",
                            "date",
                            "period",
                            "class_id",
                            "section_id"
                        )
                    )->render();
                }

                return response()->json([
                    "view" => $view,
                    "stud_perform" => $student_performances,
                ]);
            }
            if ($type == 3) {
                $academic_terms = DB::table("exam_term")
                    ->where("academic_year", $academic_year)
                    ->select("id", "exam_term_name as text")
                    ->get();
                return $academic_terms;
            }
        }

        return view("StudentPerformance::admin.index", [
            "academicyears" => $academicyears,
            "current_academic_year" => $current_academic_year,
            "current_academic_term" => $current_academic_term,
            "school_type_info" => $school_type_info,
            "school_type_infos" => $school_type_infos,
            "class_lists" => $class_lists,
            "section_lists" => $section_lists,
            "subjects" => $subjects,
            "examterms" => $examterms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("StudentPerformance::admin.edit", ["layout" => "create"]);
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
        // Check if a record already exists for the given parameters
        $existingPerformance = StudentPerformanceModel::where([
            "school_type" => $request->school_type,
            "academic_year" => $request->academic_year_grade,
            "term_id" => $request->academic_term,
            "class_id" => $request->class_id,
            "section_id" => $request->sec_dep,
            "month_year" => $request->month,
        ])->first();
        //   dd($request->all());
        if ($existingPerformance) {
            // If the record exists, update its data for each student
            foreach ($request->student_id as $key => $student) {
                $studentPerformanceData = StudentPerformanceDataModel::where(
                    "student_performance_id",
                    $existingPerformance->id
                )
                    ->where("student_id", $student)
                    ->first();

                if ($studentPerformanceData) {
                    $academicPercentage = $request->academic[$key] ?? 0; // Access academic data using current key
                    $attendancePercentage = $request->attendance[$key] ?? 0; // Access attendance data using current key
                    $disciplineCompliance =
                        $request->Discipline_and_Compliance[$key] ?? null; // Access discipline and compliance data using current key
                    $sportEvent = $request->Sports_and_Event[$key] ?? null; // Access sport and event data using current key
                    $ac_count = $disciplineCompliance !== null ? 1 : 0;
                    $sp_count = $sportEvent !== null ? 1 : 0;
                    $count = 2 + $ac_count + $sp_count;
                    $total = $count * 100;
                    $performanceScore =
                        $request->student_performance[$key] ?? 0;
                    $average = ($performanceScore / $total) * 100;
                    $studentPerformanceData->academic = $academicPercentage;
                    $studentPerformanceData->attendance = $attendancePercentage;
                    $studentPerformanceData->disciple_compliance = $disciplineCompliance;
                    $studentPerformanceData->sport_event = $sportEvent;
                    $studentPerformanceData->overall_average = $average;
                    $studentPerformanceData->save();
                }
            }

            Session::flash("success", "Updated successfully");
        } else {
            // If the record doesn't exist, create a new one and associated performance data records
            $newPerformance = new StudentPerformanceModel();
            $newPerformance->fill([
                "school_type" => $request->school_type,
                "academic_year" => $request->academic_year_grade,
                "term_id" => $request->academic_term,
                "class_id" => $request->class_id,
                "section_id" => $request->sec_dep,
                "period" => $request->period_type,
                "month_year" => $request->month,
            ]);
            $newPerformance->save();
            foreach ($request->student_id as $key => $student) {
                $academicPercentage = $request->academic[$key];
                $attendancePercentage = $request->attendance[$key];
                $disciplineCompliance =
                    $request->Discipline_and_Compliance[$key] ?? null;
                $sportEvent = $request->Sports_and_Event[$key] ?? null;
                $ac_count = $disciplineCompliance !== null ? 1 : 0;
                $sp_count = $sportEvent !== null ? 1 : 0;
                $count = 2 + $ac_count + $sp_count;
                $total = $count * 100;
                $performanceScore = $request->student_performance[$key] ?? 0;
                $studentPerformanceData = new StudentPerformanceDataModel();
                $studentPerformanceData->student_performance_id =
                    $newPerformance->id;
                $studentPerformanceData->student_id = $student;
                $studentPerformanceData->academic = $academicPercentage ?? 0;
                $studentPerformanceData->attendance =
                    $attendancePercentage ?? 0;
                $studentPerformanceData->disciple_compliance = $disciplineCompliance;
                $studentPerformanceData->sport_event = $sportEvent;
                $studentPerformanceData->overall_average =
                    ($performanceScore / $total) * 100;
                $studentPerformanceData->save();
            }
            Session::flash("success", "Saved successfully");
        }

        return redirect()->route("StudentPerformance.index");
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
        $data = StudentPerformanceModel::find($id);
        return view("StudentPerformance::admin.edit", [
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
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new StudentPerformanceModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);
        $obj = StudentPerformanceModel::find($id);
        $obj->name = $request->name;
        $obj->desc = $request->desc;
        $obj->status = $request->status;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("StudentPerformance.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_StudentPerformance)) {
            $delObj = new StudentPerformanceModel();
            foreach ($request->selected_StudentPerformance as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("StudentPerformance.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-StudentPerformance");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = StudentPerformanceModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new StudentPerformanceModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new StudentPerformanceModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        );

        $datatables = Datatables::of($data)
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
                return '<a class="editbutton btn btn-default" data-toggle="modal" data="' .
                    $data->id .
                    '" href="' .
                    route("StudentPerformance.edit", $data->id) .
                    '" ><i class="glyphicon glyphicon-edit"></i>&nbsp;Edit</a>';
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
        CGate::authorize("edit-StudentPerformance");

        if (!empty($request->selected_StudentPerformance)) {
            $obj = new StudentPerformanceModel();
            foreach ($request->selected_StudentPerformance as $k => $v) {
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
