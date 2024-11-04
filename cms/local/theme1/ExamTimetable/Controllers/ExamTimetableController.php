<?php

namespace cms\ExamTimetable\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\configurations\helpers\Configurations;
use cms\shop\Models\ProductModel;
use cms\shop\Models\PurchaseOrderModel;
use cms\exam\Models\ExamTermModel;
use Carbon\Carbon;
use cms\shop\Controllers\PurchaseOrderController;
use cms\shop\Models\OrderItemsModel;
use Yajra\DataTables\Facades\DataTables;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\subject\Models\SubjectModel;
use cms\report\Models\MarkReportModel;
use cms\section\Models\SectionModel;
use cms\StudentPerformance\Models\StudentPerformanceModel;
use cms\StudentPerformance\Models\StudentPerformanceDataModel;
use Session;
use DB;
use CGate;
use cms\students\Models\StudentsModel;
use cms\ExamTimetable\Models\ExamPeriodModel;
use cms\ExamTimetable\Models\ExamPeriodMappingModel;
use cms\ExamTimetable\Models\ExamTimetableModel;
class ExamTimetableController extends Controller
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
            $academic_year = $request->query->get("academic_year", 0);
            $term_id = $request->query->get("academic_term", 0);
            $school_type = $request->query->get("school_type", 0);
            $section_id = $request->query->get("section_id", 0);
            $class_id = $request->query->get("class_id", 0);
            $start_date = $request->query->get("start_date", 0);
            $end_date = $request->query->get("end_date", 0);
            $type = $request->query->get("type", 0);
            $period_category = [];
            if ($type == "1") {
                $view = view("ExamTimetable::admin.append", [
                    "period_category" => $period_category,
                ])->render();
                return response()->json(["view" => $view]);
            }
            if ($type == "2") {
                $id = ExamPeriodModel::where([
                    "academic_year" => $academic_year,
                    "term_id" => $term_id,
                    "school_type" => $school_type,
                    "sec_id" => $section_id,
                    "class_id" => $class_id,
                    "start_date" => $start_date,
                    "end_date" => $end_date,
                    "status" => "1",
                ])
                    ->pluck("id")
                    ->first();
                if ($id) {
                    $periods = ExamPeriodMappingModel::where(
                        "exam_period_id",
                        $id
                    )->get();
                    $view = view("ExamTimetable::admin.exam_period_append", [
                        "periods" => $periods,
                    ])->render();

                    return response()->json([
                        "view" => $view,
                        "id" => $id,
                        "periods" => $periods,
                        "academic_year" => $academic_year,
                        "term_id" => $term_id,
                        "school_type" => $school_type,
                        "sec_id" => $section_id,
                        "class_id" => $class_id,
                        "start_date" => $start_date,
                        "end_date" => $end_date,
                    ]);
                } else {
                    $message = view("ExamTimetable::admin.append", [
                        "period_category" => $period_category,
                    ])->render();
                    return response()->json(["message" => $message]);
                }
            }
            if ($type == "3") {
                $subject_id = $request->query->get("subject_id", 0);

                $sub_name = DB::table("subject")
                    ->where("id", $subject_id)
                    ->pluck("name")
                    ->first();
                $subject = isset($sub_name) ? $sub_name : "Not Assigned";
                return response()->json(["subject" => $subject]);
            }
        }
        return view("ExamTimetable::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academicyears = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $school_type_info = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();

        $school_type_infos = Configurations::getSchooltypes();

        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $section_lists = [];
        $subjects = [];
        $period_category = Configurations::PERIODCATEGORIES;
        $examterms = Configurations::getCurentAcademicTerms();

        return view("ExamTimetable::admin.edit", [
            "academicyears" => $academicyears,
            "current_academic_year" => $current_academic_year,
            "current_academic_term" => $current_academic_term,
            "school_type_info" => $school_type_info,
            "school_type_infos" => $school_type_infos,
            "class_lists" => $class_lists,
            "section_lists" => $section_lists,
            "subjects" => $subjects,
            "examterms" => $examterms,
            "period_category" => $period_category,
            "layout" => "create",
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
                "start_time" => "required|array",
                "start_time.*" => [
                    "required",
                    "regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/",
                ], // Each start time must not be null
                "end_time" => "required|array",
                "end_time.*" => [
                    "required",
                    "regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/",
                ], // Each end time must not be null
                "period_category" => "required|array",
                "period_category.*" => "required", // Each period category must not be nul
            ],
            [
                "start_time.required" => "Start time is required.",
                "start_time.*.required" => "Start time is required.",
                "start_time.*.regex" =>
                    "Invalid start time format. Time must be in HH:MM format.",
                "end_time.required" => "End time is required.",
                "end_time.*.required" => "End time is required.",
                "end_time.*.regex" =>
                    "Invalid end time format. Time must be in HH:MM format.",
                "period_category.required" => "Period category is required.",
                "period_category.*.required" => "Period category is required.",
            ]
        );
        $id = ExamPeriodModel::where([
            "academic_year" => $request->academic_year_grade,
            "term_id" => $request->academic_term,
            "school_type" => $request->school_type,
            "class_id" => $request->class_id,
            "sec_id" => $request->sec_dep,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
        ])
            ->pluck("id")
            ->first();
        // dd($id);
        if (
            isset($request->start_time) &&
            isset($request->end_time) &&
            isset($request->period_category)
        ) {
            $is_exist = ExamPeriodMappingModel::where(
                "exam_period_id",
                $id
            )->get();

            if ($is_exist->isNotEmpty()) {
                // Check if records exist
                $period = $id;
                foreach ($request->start_time as $key => $start_time) {
                    $end_time = $request->end_time[$key];

                    // Check if there is an existing record with the same start and end time
                    $existing = ExamPeriodMappingModel::where(
                        "exam_period_id",
                        $id
                    )
                        ->where("start_time", $start_time)
                        ->where("end_time", $end_time)
                        ->first();

                    if ($existing) {
                        // Update the existing record
                        $existing->period_category =
                            $request->period_category[$key];
                        $existing->save();
                    } else {
                        // Create a new record
                        $mapping = new ExamPeriodMappingModel();
                        $mapping->exam_period_id = $id;
                        $mapping->start_time = $start_time;
                        $mapping->end_time = $end_time;
                        $mapping->period_category =
                            $request->period_category[$key];
                        $mapping->save();
                    }
                }
                Session::flash("success", "Saved successfully");
                return redirect()->route("examtimetable_calender", [
                    "id" => $period,
                    "type" => "update",
                ]);
            } else {
                $obj = new ExamPeriodModel();
                $obj->academic_year = $request->academic_year_grade;
                $obj->term_id = $request->academic_term;
                $obj->school_type = $request->school_type;
                $obj->class_id = $request->class_id;
                $obj->sec_id = $request->sec_dep;
                $obj->start_date = $request->start_date;
                $obj->end_date = $request->end_date;
                $obj->save();
                if ($obj->save()) {
                    $period_id = ExamPeriodModel::where([
                        "academic_year" => $request->academic_year_grade,
                        "term_id" => $request->academic_term,
                        "school_type" => $request->school_type,
                        "class_id" => $request->class_id,
                        "sec_id" => $request->sec_dep,
                        "start_date" => $request->start_date,
                        "end_date" => $request->end_date,
                    ])
                        ->pluck("id")
                        ->first();
                    foreach ($request->start_time as $key => $start_time) {
                        $mapping = new ExamPeriodMappingModel();
                        $mapping->exam_period_id = $period_id;
                        $mapping->start_time = $start_time;
                        $mapping->end_time = $request->end_time[$key];
                        $mapping->period_category =
                            $request->period_category[$key];
                        $mapping->save();
                    }
                    $period = $period_id;
                }
                // dd($period);
                Session::flash("success", "saved successfully");
                return redirect()->route("examtimetable_calender", [
                    "id" => $period,
                    "type" => "create",
                ]);
            }
        } else {
            return back()->with("error", "Please add Periods");
        }
        //dd($id);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //dd($id);
        $exam_period = ExamPeriodModel::where("id", $id)->first();
        $term = DB::table("exam_term")
            ->where("id", $exam_period->term_id)
            ->pluck("exam_term_name")
            ->first();
        $academic_year = DB::table("academicyear")
            ->where("id", $exam_period->academic_year)
            ->pluck("year")
            ->first();
        $school_type = DB::table("school_type")
            ->where("id", $exam_period->school_type)
            ->pluck("school_type")
            ->first();
        $class = DB::table("lclass")
            ->where("id", $exam_period->class_id)
            ->pluck("name")
            ->first();
        $section = DB::table("section")
            ->where("id", $exam_period->sec_id)
            ->pluck("name")
            ->first();

        $days = [];
        $start_date = Carbon::parse($exam_period->start_date);
        $end_date = Carbon::parse($exam_period->end_date);
        $date = $start_date;
        while ($date->lte($end_date)) {
            $days[$date->format("d/m/Y")] = $date->format("l");
            $date->addDay();
        }

        //dd($days);

        $getNoofdaysTimetableadded = [];
        $getweekend = Configurations::getConfig("site")->week_end;
        $getalldays = Configurations::WEEKDAYS;
        $weekend = [];
        foreach ($getweekend as $key => $da) {
            $weekend[] = $getalldays[$da];
        }

        $exam_period_mapping = ExamPeriodMappingModel::where(
            "exam_period_id",
            $id
        )->get();
        $tim_id = ExamPeriodMappingModel::where("exam_period_id", $id)->pluck(
            "id"
        );
        $data_timetable = ExamTimeTableModel::with("subject_names")
            ->whereIn("period_id", $tim_id)
            ->get();
        //dd( $data_timetable);
        //dd($term, $academic_year,$school_type,$class,$section, $exam_period_mapping);
        return view("ExamTimetable::admin.view", [
            '$exam_period' => $exam_period,
            "term" => $term,
            "academic_year" => $academic_year,
            "school_type" => $school_type,
            "class" => $class,
            "section" => $section,
            "exam_period_mapping" => $exam_period_mapping,
            "data_timetable" => $data_timetable,
            "days" => $days,
            "weekend" => $weekend,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ExamPeriodMappingModel::where("exam_period_id", $id)->get();
        $period_id = $id;
        //dd($data);
        return view("ExamTimetable::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "id" => $period_id,
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

        $is_exist = ExamPeriodMappingModel::where("exam_period_id", $id)->get();
        if ($is_exist->isNotEmpty()) {
            // Check if records exist
            foreach ($is_exist as $key => $existingRecord) {
                // Loop through each existing record
                $existingRecord->start_time = $request->start_time[$key];
                $existingRecord->end_time = $request->end_time[$key];
                $existingRecord->period_category =
                    $request->period_category[$key];
                $existingRecord->save(); // Update the existing record
            }
        }

        Session::flash("success", "updated successfully");
        return redirect()->route("examtimetable_calender", [
            "id" => $id,
            "type" => "edit",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // dd($id);
        if ($id) {
            $delObj = ExamPeriodModel::find($id);
            $delObj->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("ExamTimetable.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-ExamTimetable");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ExamPeriodModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "examperiod.id",
            "examperiod.academic_year",
            "examperiod.term_id",
            "examperiod.class_id",
            "examperiod.sec_id",
            "examperiod.start_date",
            "examperiod.end_date",
            "academicyear.year as year",
            "exam_term.exam_term_name as academic_term",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ExamPeriodModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ExamPeriodModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->leftJoin(
                "academicyear",
                "academicyear.id",
                "=",
                "examperiod.academic_year"
            )
            ->leftJoin("exam_term", "exam_term.id", "=", "examperiod.term_id");

        $datatables = Datatables::of($data)
            ->addColumn("DT_RowIndex", function ($data) {
                return $data->rownum;
            })

            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })

            // ->addColumn("academic_term", function ($data) {
            //     if ($data->term_id != null) {
            //         $academic_term = DB::table("exam_term")
            //             ->where("id", $data->term_id)
            //             ->pluck("exam_term_name")
            //             ->first();
            //         return $academic_term;
            //     } else {
            //         return "";
            //     }
            // })
            ->addColumn("class_sec", function ($data) {
                if ($data->class_id != null && $data->sec_id != null) {
                    $class = DB::table("lclass")
                        ->where("id", $data->class_id)
                        ->pluck("name")
                        ->first();
                    $section = DB::table("section")
                        ->where("id", $data->sec_id)
                        ->pluck("name")
                        ->first();
                    $class_sec = $class . " " . $section;
                    return $class_sec;
                } else {
                    return "";
                }
            })
            ->addColumn("no_of_period", function ($data) {
                if ($data->id != null) {
                    $count = DB::table("examperiod_mapping")
                        ->where("exam_period_id", $data->id)
                        ->count();
                    $periods =
                        $count == 1 ? $count . "period" : $count . "periods";
                    return $periods;
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
                    "route" => "ExamTimetable",
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
        CGate::authorize("edit-ExamTimetable");

        if ($request->ajax()) {
            ExamPeriodModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_classtimetable)) {
            $obj = new ExamPeriodModel();
            foreach ($request->selected_classtimetable as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    // public function calender($id, $type, Request $request)
    // {
    //     // dd($id);

    //     //dd($type);
    //     $data = ExamPeriodModel::with("examperiodmapping.examtimetable.subject")
    //         ->where("id", $id)
    //         ->first();
    //     //  dd($data);
    //     $start_date = Carbon::parse($data->start_date);
    //     $end_date = Carbon::parse($data->end_date);
    //     //  $days = 7;

    //     $days = [];
    //     $date = $start_date;
    //     while ($date->lte($end_date)) {
    //         $days[$date->format("d/m/Y")] = $date->format("l");
    //         $date->addDay();
    //     }

    //     //dd($days);

    //     $getNoofdaysTimetableadded = [];
    //     $getweekend = Configurations::getConfig("site")->week_end;
    //     $getalldays = Configurations::WEEKDAYS;
    //     $weekend = [];
    //     foreach ($getweekend as $key => $da) {
    //         $weekend[] = $getalldays[$da];
    //     }

    //     //  dd($weekend,$days);
    //     // for ($i = 1; $i <= $days; $i++) {
    //     //     $getNoofdaysTimetableadded[$i] =  $days;
    //     // }
    //     $timing = ExamPeriodMappingModel::where("exam_period_id", $id)->get();
    //     // $tim_id = ExamPeriodMappingModel::where(
    //     //     "exam_period_id",
    //     //     $id
    //     // )->pluck('id');
    //     $subjects = SubjectModel::where("status", 1)
    //         ->where("class_id", $data->class_id)
    //         ->pluck("name", "id")
    //         ->toArray();

    //     $data_timetable=[];

    //     if($type == "edit")
    //         {
    //             $ids_mapping=  ExamPeriodMappingModel::where("exam_period_id", $id)->pluck("id");

    //             $data_timetable=ExamTimetableModel::whereIn("period_id", $ids_mapping)->get();
    //         }

    //    //dd($data_timetable);

    //         return view("ExamTimetable::admin.calender", [
    //             "days" => $days,
    //             "timing" => $timing,
    //             "subjects" => $subjects,
    //             "class_id" => $data->class_id,
    //             "section_id" => $data->sec_id,
    //             "data_timetable"=>$data_timetable,
    //             "data" => $data,
    //             "type" => "edit",
    //             "getweekend" => $weekend,
    //             "type" => $type,
    //         ]);

    // }
    public function calender($id, $type, Request $request)
    {
        // dd($id);
        $data = ExamPeriodModel::where("id", $id)->first();
        // dd($data);
        $start_date = Carbon::parse($data->start_date);
        $end_date = Carbon::parse($data->end_date);
        //  $days = 7;

        $days = [];
        $date = $start_date;
        while ($date->lte($end_date)) {
            $days[$date->format("d/m/Y")] = $date->format("l");
            $date->addDay();
        }

        //dd($days);

        $getNoofdaysTimetableadded = [];
        $getweekend = Configurations::getConfig("site")->week_end;
        $getalldays = Configurations::WEEKDAYS;
        $weekend = [];
        foreach ($getweekend as $key => $da) {
            $weekend[] = $getalldays[$da];
        }

        // dd($weekend);
        // for ($i = 1; $i <= $days; $i++) {
        //     $getNoofdaysTimetableadded[$i] =  $days;
        // }
        $timing = ExamPeriodMappingModel::where("exam_period_id", $id)->get();

        $subjects = SubjectModel::where("status", 1)
            ->where("class_id", $data->class_id)
            ->pluck("name", "id")
            ->toArray();

        if (isset($type) && $type == "edit") {
            $tim_id = ExamPeriodMappingModel::where(
                "exam_period_id",
                $id
            )->pluck("id");
            $data_timetable = ExamTimeTableModel::with("subject_names")
                ->whereIn("period_id", $tim_id)
                ->get();
            // dd( $data_timetable);
            if ($data_timetable) {
                return view("ExamTimetable::admin.calender", [
                    "days" => $days,
                    "timing" => $timing,
                    "subjects" => $subjects,
                    "class_id" => $data->class_id,
                    "section_id" => $data->sec_id,
                    "data_timetable" => $data_timetable,
                    "data" => $data,
                    "type" => "edit",
                    "getweekend" => $weekend,
                ]);
            }
        } elseif (isset($type) && $type == "update") {
            $tim_id = ExamPeriodMappingModel::where("exam_period_id", $id)
                ->where("period_category", "!=", "examination")
                ->pluck("id");
            //  dd($tim_id );
            $delete = ExamTimeTableModel::whereIn(
                "period_id",
                $tim_id
            )->delete();

            if (isset($delete)) {
                $find_id = ExamPeriodMappingModel::where("exam_period_id", $id)
                    ->whereNotIn("exam_period_id", $tim_id)
                    ->pluck("id");
                $data_timetable = ExamTimeTableModel::with("subject_names")
                    ->whereIn("period_id", $find_id)
                    ->get();

                return view("ExamTimetable::admin.calender", [
                    "days" => $days,
                    "timing" => $timing,
                    "subjects" => $subjects,
                    "class_id" => $data->class_id,
                    "section_id" => $data->sec_id,
                    "data_timetable" => $data_timetable,
                    "data" => $data,

                    "getweekend" => $weekend,
                    "type" => "edit",
                    "type1" => "update",
                ]);
            }
        } else {
            return view("ExamTimetable::admin.calender", [
                "days" => $days,
                "timing" => $timing,
                "subjects" => $subjects,
                "class_id" => $data->class_id,
                "section_id" => $data->sec_id,
                "data" => $data,
                "type" => "create",
                "getweekend" => $weekend,
            ]);
        }
    }

    public function ExamTimetableSave(Request $request)
    {
        //  dd($request->all());
        if ($request->type1 == "update") {
            foreach ($request->sub_id as $date => $date_data) {
                foreach ($date_data as $time => $time_data) {
                    // Insert into the examtimetable table
                    $obj = ExamTimeTableModel::find($time_data["id"]);
                    if ($obj) {
                        $obj->date = $date;
                        $obj->period_id = $time;
                        $obj->subject = $time_data["subject"];
                        $obj->bordercolor = $time_data["bordercolor"];
                        $obj->bgcolor = $time_data["bgcolor"];
                        $obj->save();
                    } else {
                        DB::table("examtimetable")->insert([
                            "date" => $date,
                            "period_id" => $time,
                            "subject" => $time_data["subject"] ?? null,
                            "bordercolor" => $time_data["bordercolor"] ?? null,
                            "bgcolor" => $time_data["bgcolor"] ?? null,
                        ]);
                    }
                }

                Session::flash("success", "Updated successfully");
            }
        } else {
            foreach ($request->sub_id as $date => $date_data) {
                foreach ($date_data as $time => $time_data) {
                    // Insert into the examtimetable table
                    if (isset($time_data["id"])) {
                        $obj = ExamTimeTableModel::find($time_data["id"]);
                        if ($obj) {
                            $obj->date = $date;
                            $obj->period_id = $time;
                            $obj->subject = $time_data["subject"] ?? null;
                            $obj->bordercolor =
                                $time_data["bordercolor"] ?? null;
                            $obj->bgcolor = $time_data["bgcolor"] ?? null;
                            $obj->save();
                        } else {
                            DB::table("examtimetable")->insert([
                                "date" => $date,
                                "period_id" => $time,
                                "subject" => $time_data["subject"] ?? null,
                                "bordercolor" =>
                                    $time_data["bordercolor"] ?? null,
                                "bgcolor" => $time_data["bgcolor"] ?? null,
                            ]);
                        }

                        Session::flash("success", "Updated successfully");
                    } else {
                        DB::table("examtimetable")->insert([
                            "date" => $date,
                            "period_id" => $time,
                            "subject" => $time_data["subject"] ?? null,
                            "bordercolor" => $time_data["bordercolor"] ?? null,
                            "bgcolor" => $time_data["bgcolor"] ?? null,
                        ]);

                        Session::flash("success", "saved successfully");
                    }
                }
            }
        }

        return redirect()->route("examtimetable");
    }

    public function ExamTimeTablePeriodDelete(Request $request)
    {
        $period = $request->query->get("id", 0);

        // Check if $period has a valid value
        if ($period) {
            // Delete records from ExamTimetableModel based on mapping IDs
            $examtimetable_deleted = ExamTimetableModel::where(
                "period_id",
                $period
            )->delete();

            // Delete records from ExamPeriodMappingModel based on the given period ID
            $examperiod_deleted = ExamPeriodMappingModel::where(
                "id",
                $period
            )->delete();

            if ($examtimetable_deleted && $examperiod_deleted) {
                $message = "Period Deleted Successfully";
                return response()->json(["message" => $message]);
            } else {
                $message = "Failed to delete period";
                return response()->json(
                    ["message" => $message, "period" => $period],
                    500
                ); // Return HTTP status code 500 for failure
            }
        } else {
            $message = "Invalid period ID";
            return response()->json(["message" => $message], 400); // Return HTTP status code 400 for bad request
        }
    }

    public function CloneExamTimetable($id)
    {
        $academicyears = Configurations::getAcademicyears();
        $exam_period_info = ExamPeriodModel::where("id", $id)->first();
        $examterms = ExamTermModel::where(
            "academic_year",
            $exam_period_info->academic_year
        )
            ->orderBy("id", "asc")
            ->pluck("exam_term_name", "id")
            ->toArray();
        $school_type_infos = Configurations::getSchooltypes();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("school_type_id", $exam_period_info->school_type)
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $section_lists = SectionModel::where(
            "class_id",
            $exam_period_info->class_id
        )
            ->pluck("name", "id")
            ->toArray();
        $data = ExamPeriodMappingModel::where("exam_period_id", $id)->get();
        $period_id = $id;
        //dd($data);
        return view("ExamTimetable::admin.edit", [
            "layout" => "clone",
            "data" => $data,
            "id" => $period_id,
            "exam_period_info" => $exam_period_info,
            "academicyears" => $academicyears,
            "examterms" => $examterms,
            "school_type_infos" => $school_type_infos,
            "class_lists" => $class_lists,
            "section_lists" => $section_lists,
        ]);
    }
}
