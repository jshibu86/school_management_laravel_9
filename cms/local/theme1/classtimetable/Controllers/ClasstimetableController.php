<?php

namespace cms\classtimetable\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\classtimetable\Models\ClasstimetableModel;
use cms\lclass\Models\LclassModel;
use Yajra\DataTables\Facades\DataTables;
use cms\department\Models\DepartmentModel;
use Configurations;
use cms\subject\Models\SubjectModel;
use cms\section\Models\SectionModel;
use Session;
use DB;
use CGate;
use Carbon\Carbon;
use DateTime;
use cms\attendance\Models\AttendanceModel;
use cms\attendance\Models\StudentAttendanceModel;
use cms\classtimetable\Models\PeriodClassMappingModel;
use cms\classtimetable\Models\PeriodModel;
use cms\exam\Models\ExamTermModel;
use cms\ExamTimetable\Models\ExamPeriodModel;
use cms\ExamTimetable\Models\ExamPeriodMappingModel;
use cms\ExamTimetable\Models\ExamTimetableModel;
use cms\academicyear\Models\AcademicyearModel;
use cms\classteacher\Models\ClassteacherModel;
use cms\teacher\Models\TeacherModel;
use cms\students\Models\StudentsModel;
use Illuminate\Support\Facades\Route;

class ClasstimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            // $actionName = Route::currentRouteAction();
            // $actionParts = explode("\\", $actionName);
            // $controller_key = 0;
            // foreach ($actionParts as $key => $part) {
            //     if ($part == "Controllers") {
            //         $controller_key = $key;
            //     }
            // }
            // $module_key = $controller_key - 1;
            // $moduleName = $actionParts[$module_key];
            // $error = CGate::module($moduleName);
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
    private function getDays($allDays)
    {
        $weekends = Configurations::getConfig("site")->week_end;

        $days = [];
        foreach ($allDays as $key => $day) {
            if (!in_array($key, $weekends)) {
                $days[$key] = $day;
            }
        }
        return $days;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $acyear = $request->query->get("academic_year", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $dept_id = $request->query->get("dept_id", 0);
            $days = $request->query->get("days", 0);

            $type = $request->query->get("dataid", 0);
            $data_type = $request->query->get("type", 0);
            $getalldays = Configurations::WEEKDAYS;
            $getNoofdaysTimetableadded = [];
            $getweekend = Configurations::getConfig("site")->week_end;

            for ($i = 1; $i <= $days; $i++) {
                $getNoofdaysTimetableadded[$i] = $getalldays[$i];
            }

            $getperiod = PeriodModel::where([
                "academic_year" => $acyear,
                "class_id" => $class_id,
                "section_id" => $section_id,
            ])->first();
            $period_category = [];
            if ($data_type == "1") {
                $view = view("classtimetable::admin.append", [
                    "period_category" => $period_category,
                ])->render();
                return response()->json(["view" => $view]);
            }
            if ($data_type == "2") {
                $id = PeriodModel::where([
                    "academic_year" => $acyear,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "status" => "1",
                ])
                    ->pluck("id")
                    ->first();
                if ($id) {
                    $periods = PeriodClassMappingModel::where(
                        "period_class_id",
                        $id
                    )->get();
                    $periods = $periods->transform(function ($period) {
                        $period->from = Carbon::parse($period->from)->format(
                            "H:i"
                        );
                        $period->to = Carbon::parse($period->to)->format("H:i");
                        return $period;
                    });
                    // dd($periods);
                    $view = view("classtimetable::admin.class_period_append", [
                        "periods" => $periods,
                    ])->render();

                    return response()->json([
                        "view" => $view,
                        "id" => $id,
                        "periods" => $periods,
                        "academic_year" => $acyear,
                        "class_id" => $class_id,
                    ]);
                } else {
                    $period_category = [];
                    $message = view("classtimetable::admin.append", [
                        "period_category" => $period_category,
                    ])->render();
                    return response()->json(["message" => $message]);
                }
            }
            if ($getperiod) {
                $timing = PeriodClassMappingModel::where(
                    "period_class_id",
                    $getperiod->id
                )->get();

                $subjects = SubjectModel::where("status", 1)
                    ->where("class_id", $class_id)
                    ->where("department_id", $dept_id)
                    ->pluck("name", "id")
                    ->toArray();

                if ($type != 0) {
                    $data_timetable = ClasstimetableModel::where(
                        "id",
                        $type
                    )->first();
                    if ($data_timetable) {
                        $getalldays = Configurations::WEEKDAYS;

                        $view = view("classtimetable::admin.parts.calender", [
                            "days" => $getNoofdaysTimetableadded,
                            "timing" => $timing,
                            "subjects" => $subjects,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                            "data" => $data_timetable,
                            "type" => "edit",
                            "getweekend" => $getweekend,
                        ])->render();
                        return response()->json(["viewfile" => $view]);
                        //return "edit";
                    }
                } else {
                    $view = view("classtimetable::admin.parts.calender", [
                        "days" => $getNoofdaysTimetableadded,
                        "timing" => $timing,
                        "subjects" => $subjects,
                        "class_id" => $class_id,
                        "section_id" => $section_id,
                        "getweekend" => $getweekend,
                        "type" => "create",
                    ])->render();
                    return response()->json(["viewfile" => $view]);
                }
            } else {
                return response()->json(["error" => "No Period Found"]);
            }
        }
        return view("classtimetable::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd($getNoofdaysTimetableadded);

        $academicyears = Configurations::getAcademicyears();

        $info = Configurations::getAcademicandTermsInfo();

        $assigendclasses = ClasstimetableModel::where("status", 1)->pluck(
            "class_id"
        );
        $period_category = Configurations::CLASSPERIODCATEGORIES;
        //$assigendclassesSection=SectionModel::where("status",1)->where("class_id")

        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        //dd($class_lists);

        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();

        // $getalldays = Configurations::WEEKDAYS;
        // $getNoofdaysTimetableadded = [];

        // for ($i = 1; $i <= 7; $i++) {
        //     $getNoofdaysTimetableadded[$i] = $getalldays[$i];
        // }

        // $getperiod = PeriodModel::where([
        //     "academic_year" => 3,
        //     "class_id" => 2,
        // ])->first();
        // if ($getperiod) {
        //     $timing = PeriodClassMappingModel::where(
        //         "period_class_id",
        //         $getperiod->id
        //     )->get();

        //     $subjects = SubjectModel::where("status", 1)
        //         ->where("class_id", 2)
        //         ->pluck("name", "id")
        //         ->toArray();
        // }
        //dd($timing);
        return view("classtimetable::admin.edit", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "terms" => [],
            "departments" => $departments,
            "info" => $info,
            "period_category" => $period_category,
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

        // dd($request->all());
        DB::beginTransaction();
        try {
            $perioddata = [];
            $period = PeriodModel::where("id", $request->period_id)->first();
            foreach ($request->sub_id as $dayid => $periods) {
                foreach ($periods as $periodid => $data) {
                    if ($data["subject"] != null) {
                        $perioddata[] = [
                            "academic_year" => $period->academic_year,
                            "class_id" => $period->class_id,
                            "section_id" => $request->section,
                            "day" => $dayid,
                            "period_id" => $periodid,
                            "subject_id" => $data["subject"],
                            "teacher_id" => $data["teacher"],
                            "colorcode" => $data["bgcolor"],
                            "border_color" => $data["bordercolor"],
                            "no_of_days" => $request->no_of_days,
                        ];
                        // $timetable =  ClasstimetableModel::where('period_id',$periodid);
                        // $timetable->subject_id = $data["subject"];
                        // $timetable->teacher_id = $data["teacher"];
                        // $timetable->colorcode = $data["bgcolor"];
                        // $timetable->border_color = $data["bordercolor"];
                        // $timetable->day = $dayid;
                        // $timetable->save();
                    }
                }
            }
            //dd($perioddata);
            if (sizeof($perioddata) > 0) {
                ClasstimetableModel::insert($perioddata);
            } else {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "Some Subject Data not Assigned Kindly Fil All Information"
                    );
            }

            DB::commit();
            return redirect()
                ->route("classtimetable.index")
                ->with("success", "Added Successfully");
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            // dd($message);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("classtimetable.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("classtimetable.index");
    }
    public function Periods(Request $request)
    {
        // dd($request->all());
        $this->validate(
            $request,
            [
                "class_id" => "required",
                "section_id" => "required",
                "no_days" => "required",
                "academic_year" => "required",
                "period_category" => "required|array",
                "period_category.*" => "required",
            ],
            [
                "period_category.required" => "Period category is required.",
            ]
        );
        $type = $request->type ?? 0;
        // dd($request->period_category);
        if ($type == "clone") {
            $period_id = PeriodModel::where([
                "academic_year" => $request->academic_year,
                "section_id" => $request->section_id,
                "class_id" => $request->class_id,
            ])
                ->pluck("id")
                ->first();
            // dd(
            //     $request->academic_year,
            //     $request->term_id,
            //     $request->class_id,
            //     $period_id
            // );
            $is_exist = PeriodClassMappingModel::where(
                "period_class_id",
                $period_id
            )->get();
            if ($is_exist->isNotEmpty()) {
                $period = $period_id;
                foreach ($request->start_time as $key => $start_time) {
                    $end_time = $request->end_time[$key];
                    $start = date("g:i a", strtotime($start_time));
                    $end = date("g:i a", strtotime($end_time));

                    $start_datetime = new DateTime(
                        date("Y-m-d") . " " . $start_time
                    );
                    $end_datetime = new DateTime(
                        date("Y-m-d") . " " . $end_time
                    );
                    $interval = $start_datetime->diff($end_datetime);
                    $hour = $interval->format("%h hour");
                    $min = $interval->format("%i min");
                    $sec = $interval->format("%s second");
                    // Check if there is an existing record with the same start and end time
                    $existing = PeriodClassMappingModel::where(
                        "period_class_id",
                        $period
                    )
                        ->where("from", $start_time)
                        ->where("to", $end_time)
                        ->first();

                    if ($existing) {
                        // Update the existing record
                        $existing->type = $request->period_category[$key];
                        $existing->save();
                    } else {
                        // Create a new record
                        $mapping = new PeriodClassMappingModel();
                        $mapping->period_class_id = $period;
                        $mapping->from = $start_time;
                        $mapping->to = $end_time;
                        $mapping->break_min = $hour . " " . $min . " " . $sec;
                        $mapping->type = $request->period_category[$key];
                        $mapping->save();
                    }
                }
                Session::flash("success", "Saved successfully");
                return redirect()->route("classtimetablecalender", [
                    "id" => $period,
                    "type" => "update",
                    "days" => $request->no_days,
                    "section" => $request->section_id,
                ]);
            } else {
                $obj = new PeriodModel();
                $obj->academic_year = $request->academic_year;
                $obj->academic_term = $request->term_id;
                $obj->class_id = $request->class_id;
                $obj->section_id = $request->section_id;
                if ($obj->save()) {
                    foreach ($request->period_category as $key => $period) {
                        if (!empty($request->start_time[$key])) {
                            // dd($request->start_time);

                            $start = date(
                                "g:i a",
                                strtotime($request->start_time[$key])
                            );
                            $end = date(
                                "g:i a",
                                strtotime($request->end_time[$key])
                            );

                            $start_time = $request->start_time[$key];
                            $end_time = $request->end_time[$key];

                            $start_datetime = new DateTime(
                                date("Y-m-d") . " " . $start_time
                            );
                            $end_datetime = new DateTime(
                                date("Y-m-d") . " " . $end_time
                            );
                            $interval = $start_datetime->diff($end_datetime);
                            $hour = $interval->format("%h hour");
                            $min = $interval->format("%i min");
                            $sec = $interval->format("%s second");

                            // $timeData[] = [
                            //     "period_class_id" => $obj->id,
                            //     "from" => $start,
                            //     "to" => $end,
                            //     "type" => $period,
                            //     "break_min" => $hour . " " . $min . " " . $sec,
                            // ];

                            $period_map = new PeriodClassMappingModel();
                            $period_map->period_class_id = $obj->id;
                            $period_map->from = $start;
                            $period_map->to = $end;
                            $period_map->type = $period;
                            $period_map->break_min =
                                $hour . " " . $min . " " . $sec;
                            $period_map->save();
                            //    if($period_map->save()){
                            //     $timetable = new classtimetableModel();
                            //     $timetable->academic_year = $request->academic_year;
                            //     $timetable->period_id = $period_map->id;
                            //     $timetable->class_id = $request->class_id;
                            //     $timetable->section_id = $request->section_id;
                            //     $timetable->term_id = $request->term_id;
                            //     $timetable->no_of_days = $request->no_days;
                            //     $timetable->save();
                            //    }

                            // save mapping
                        } else {
                            return redirect()
                                ->back()
                                ->withInput()
                                ->with(
                                    "exception_error",
                                    "Please Add Any periods"
                                );
                        }
                    }
                }
                // dd( $timeData);

                $id = PeriodModel::where([
                    "academic_year" => $request->academic_year,
                    "academic_term" => $request->term_id,
                    "class_id" => $request->class_id,
                    "section_id" => $request->section_id,
                ])
                    ->pluck("id")
                    ->first();
                // dd($id,$request->academic_year,$request->term_id,$request->class_id);

                Session::flash("success", "saved successfully");
                return redirect()->route("classtimetablecalender", [
                    "id" => $id,
                    "type" => "create",
                    "days" => $request->no_days,
                    "section" => $request->section_id,
                ]);
            }
        } else {
            $isexist = PeriodModel::where([
                "academic_year" => $request->academic_year,
                "class_id" => $request->class_id,
                "section_id" => $request->section_id,
            ])->exists();

            if ($isexist) {
                $delexist = PeriodModel::where([
                    "academic_year" => $request->academic_year,
                    "class_id" => $request->class_id,
                    "section_id" => $request->section_id,
                ])->forceDelete();
            }
            $obj = new PeriodModel();
            $obj->academic_year = $request->academic_year;
            $obj->class_id = $request->class_id;
            $obj->section_id = $request->section_id;
            if ($obj->save()) {
                foreach ($request->period_category as $key => $period) {
                    if (!empty($request->start_time[$key])) {
                        // dd($request->start_time);

                        $start = date(
                            "g:i a",
                            strtotime($request->start_time[$key])
                        );
                        $end = date(
                            "g:i a",
                            strtotime($request->end_time[$key])
                        );

                        $start_time = $request->start_time[$key];
                        $end_time = $request->end_time[$key];

                        $start_datetime = new DateTime(
                            date("Y-m-d") . " " . $start_time
                        );
                        $end_datetime = new DateTime(
                            date("Y-m-d") . " " . $end_time
                        );
                        // dd($start_datetime, $end_datetime);
                        $interval = $start_datetime->diff($end_datetime);
                        $hour = $interval->format("%h hour");
                        $min = $interval->format("%i min");
                        $sec = $interval->format("%s second");

                        // $timeData[] = [
                        //     "period_class_id" => $obj->id,
                        //     "from" => $start,
                        //     "to" => $end,
                        //     "type" => $period,
                        //     "break_min" => $hour . " " . $min . " " . $sec,
                        // ];

                        $period_map = new PeriodClassMappingModel();
                        $period_map->period_class_id = $obj->id;
                        $period_map->from = $start;
                        $period_map->to = $end;
                        $period_map->type = $period;
                        $period_map->break_min =
                            $hour . " " . $min . " " . $sec;
                        $period_map->save();
                        //    if($period_map->save()){
                        //     $timetable = new classtimetableModel();
                        //     $timetable->academic_year = $request->academic_year;
                        //     $timetable->period_id = $period_map->id;
                        //     $timetable->class_id = $request->class_id;
                        //     $timetable->section_id = $request->section_id;
                        //     $timetable->term_id = $request->term_id;
                        //     $timetable->no_of_days = $request->no_days;
                        //     $timetable->save();
                        //    }

                        // save mapping
                    } else {
                        return redirect()
                            ->back()
                            ->withInput()
                            ->with("exception_error", "Please Add Any periods");
                    }
                }
            }
            // dd( $timeData);

            $id = PeriodModel::where([
                "academic_year" => $request->academic_year,
                "academic_term" => $request->term_id,
                "class_id" => $request->class_id,
                "section_id" => $request->section_id,
            ])
                ->pluck("id")
                ->first();
            // dd($id,$request->academic_year,$request->term_id,$request->class_id);

            Session::flash("success", "saved successfully");
            return redirect()->route("classtimetablecalender", [
                "id" => $id,
                "type" => "create",
                "days" => $request->no_days,
                "section" => $request->section_id,
            ]);
        }
    }

    public function calender($id, $type, $days, $section, Request $request)
    {
        // dd($id);
        if (is_array($id)) {
            // dd("if");
            $string_id = trim($id, "[]"); // Assuming you need the first element if it's an array
        } else {
            // dd("else");
            $string_id = trim($id, "[]");
        }
        $id = $string_id;
        // dd(  $id );
        $data = PeriodModel::where("id", $id)->first();
        // dd($id);
        //  dd($days);

        $getalldays = Configurations::WEEKDAYS;
        $getNoofdaysTimetableadded = [];
        $getweekend = Configurations::getConfig("site")->week_end;

        for ($i = 1; $i <= $days; $i++) {
            $getNoofdaysTimetableadded[$i] = $getalldays[$i];
        }

        $getperiod = PeriodModel::where([
            "academic_year" => $data->academic_year,
            "class_id" => $data->class_id,
            "section_id" => $data->section_id,
        ])->first();

        // dd($weekend);
        // for ($i = 1; $i <= $days; $i++) {
        //     $getNoofdaysTimetableadded[$i] =  $days;
        // }
        $timing = PeriodClassMappingModel::where("period_class_id", $id)->get();
        // dd($id,$timing);
        $subjects = SubjectModel::where("status", 1)
            ->where("class_id", $data->class_id)
            ->pluck("name", "id")
            ->toArray();

        if (isset($type) && $type == "edit") {
            $getalldays = Configurations::WEEKDAYS;
            $getNoofdaysTimetableadded = [];

            $nodays = ClasstimetableModel::where([
                "academic_year" => $data->academic_year,
                "class_id" => $data->class_id,
                "section_id" => $section,
            ])->count();

            for ($i = 1; $i <= $days; $i++) {
                $getNoofdaysTimetableadded[$i] = $getalldays[$i];
            }

            //  $getperiod = PeriodModel::where([
            //     "academic_year" => $data_timetable->academic_year,
            //      "class_id" => $data_timetable->class_id,
            //  ])->first();

            $period_id = ClasstimetableModel::where([
                "academic_year" => $data->academic_year,
                "class_id" => $data->class_id,
                "section_id" => $section,
            ])
                ->pluck("period_id")
                ->first();

            $getperiod = PeriodClassMappingModel::where(
                "id",
                $period_id
            )->first();

            if ($getperiod) {
                $timing = PeriodClassMappingModel::where(
                    "period_class_id",
                    $getperiod->period_class_id
                )->get();

                $subjects = SubjectModel::where("status", 1)
                    ->where("class_id", $data->class_id)
                    ->pluck("name", "id")
                    ->toArray();
            }

            $academicyears = Configurations::getAcademicyears();

            $assigendclasses = ClasstimetableModel::where("status", 1)->pluck(
                "class_id"
            );

            //$assigendclassesSection=SectionModel::where("status",1)->where("class_id")

            $class_lists = LclassModel::whereNull("deleted_at")
                ->where("status", "=", 1)
                ->orderBy("id", "asc")
                ->pluck("name", "id")
                ->toArray();

            //dd($class_lists);

            // selected sectioninfo;

            $selectedsection = SectionModel::where("id", $section)->first();

            if ($selectedsection->department_id == null) {
                $departments = [];
            } else {
                $departments = DepartmentModel::where("status", 1)
                    ->where("id", $selectedsection->department_id)
                    ->pluck("dept_name", "id")
                    ->toArray();
            }

            $sections = SectionModel::where("status", 1)
                ->where("class_id", $data->class_id)
                ->pluck("name", "id")
                ->toArray();
            $terms = ExamTermModel::where("status", 1)
                ->where("academic_year", $data->academic_year)
                ->pluck("exam_term_name", "id")
                ->toArray();
            $data_timetable = ClasstimetableModel::where([
                "academic_year" => $data->academic_year,
                "class_id" => $data->class_id,
                "section_id" => $section,
            ])->first();
            $getweekend = Configurations::getConfig("site")->week_end;
            return view("classtimetable::admin.parts.calender", [
                "days" => $getNoofdaysTimetableadded,
                "timing" => $timing,
                "subjects" => $subjects,
                "layout" => "edit",
                "type" => "edit",
                "data" => $data_timetable,
                "academicyears" => $academicyears,
                "class_lists" => $class_lists,
                "sections" => $sections,
                "terms" => $terms,
                "departments" => $departments,
                "getweekend" => $getweekend,
                "period_id" => $period_id,
                "section" => $section,
                "no_of_days" => $days,
            ]);
        } elseif (isset($type) && $type == "update") {
            $tim_id = PeriodClassMappingModel::where("period_class_id", $id)
                ->where("type", "!=", "0")
                ->pluck("id");
            //  dd($tim_id );
            $delete = ClasstimetableModel::whereIn(
                "period_id",
                $tim_id
            )->delete();

            if (isset($delete)) {
                // $find_id = PeriodClassMappingModel::where(
                //     "period_class_id",
                //     $id
                // )->whereNotIn("id",$tim_id)->pluck('id');
                // $data_timetable = ClasstimetableModel::with('subject_names')->whereIn(
                //     "period_id",
                //     $find_id
                // )->get();

                $getalldays = Configurations::WEEKDAYS;
                $getNoofdaysTimetableadded = [];

                $nodays = ClasstimetableModel::where([
                    "academic_year" => $data->academic_year,
                    "class_id" => $data->class_id,
                    "section_id" => $section,
                ])->count();

                for ($i = 1; $i <= $days; $i++) {
                    $getNoofdaysTimetableadded[$i] = $getalldays[$i];
                }

                //  $getperiod = PeriodModel::where([
                //     "academic_year" => $data_timetable->academic_year,
                //      "class_id" => $data_timetable->class_id,
                //  ])->first();

                $period_id = ClasstimetableModel::where([
                    "academic_year" => $data->academic_year,
                    "class_id" => $data->class_id,
                    "section_id" => $section,
                ])
                    ->pluck("period_id")
                    ->first();

                $getperiod = PeriodClassMappingModel::where(
                    "id",
                    $period_id
                )->first();

                if ($getperiod) {
                    $timing = PeriodClassMappingModel::where(
                        "period_class_id",
                        $getperiod->period_class_id
                    )->get();

                    $subjects = SubjectModel::where("status", 1)
                        ->where("class_id", $data->class_id)
                        ->pluck("name", "id")
                        ->toArray();
                }

                $academicyears = Configurations::getAcademicyears();

                $assigendclasses = ClasstimetableModel::where(
                    "status",
                    1
                )->pluck("class_id");

                //$assigendclassesSection=SectionModel::where("status",1)->where("class_id")

                $class_lists = LclassModel::whereNull("deleted_at")
                    ->where("status", "=", 1)
                    ->orderBy("id", "asc")
                    ->pluck("name", "id")
                    ->toArray();

                //dd($class_lists);

                // selected sectioninfo;

                $selectedsection = SectionModel::where("id", $section)->first();

                if ($selectedsection->department_id == null) {
                    $departments = [];
                } else {
                    $departments = DepartmentModel::where("status", 1)
                        ->where("id", $selectedsection->department_id)
                        ->pluck("dept_name", "id")
                        ->toArray();
                }
                $sections_ids = SectionModel::where("status", 1)
                    ->where("class_id", $data->class_id)
                    ->pluck("id");

                $existing_sections = ClasstimetableModel::where(
                    "class_id",
                    $data->class_id
                )->pluck("section_id");

                $sections = SectionModel::where("status", 1)
                    ->where("class_id", $data->class_id)
                    ->whereNotIn("id", $existing_sections)
                    ->pluck("name", "id")
                    ->toArray();
                $terms = ExamTermModel::where("status", 1)
                    ->where("academic_year", $data->academic_year)
                    ->pluck("exam_term_name", "id")
                    ->toArray();
                $data_timetable = ClasstimetableModel::where([
                    "academic_year" => $data->academic_year,
                    "class_id" => $data->class_id,
                    "section_id" => $section,
                ])->first();
                $getweekend = Configurations::getConfig("site")->week_end;

                return view("classtimetable::admin.parts.calender", [
                    "days" => $getNoofdaysTimetableadded,
                    "timing" => $timing,
                    "subjects" => $subjects,
                    "layout" => "edit",
                    "type" => "edit",
                    "data" => $data_timetable,
                    "academicyears" => $academicyears,
                    "class_lists" => $class_lists,
                    "sections" => $sections,
                    "terms" => $terms,
                    "departments" => $departments,
                    "getweekend" => $getweekend,
                    "period_id" => $period_id,
                    "section" => $section,
                    "no_of_days" => $days,
                ]);
            }
        } else {
            return view("classtimetable::admin.parts.calender", [
                "days" => $getNoofdaysTimetableadded,
                "timing" => $timing,
                "subjects" => $subjects,
                "class_id" => $data->class_id,
                "getweekend" => $getweekend,
                "period_id" => $id,
                "section" => $section,
                "no_of_days" => $days,
                "type" => "create",
            ]);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data_timetable = ClasstimetableModel::find($id);
        $getalldays = Configurations::WEEKDAYS;
        $getNoofdaysTimetableadded = [];

        $nodays = ClasstimetableModel::where([
            "academic_year" => $data_timetable->academic_year,
            "class_id" => $data_timetable->class_id,
            "section_id" => $data_timetable->section_id,
        ])->count();

        for ($i = 1; $i <= $data_timetable->no_of_days; $i++) {
            $getNoofdaysTimetableadded[$i] = $getalldays[$i];
        }

        // $getperiod = PeriodModel::where([
        //     "academic_year" => $data_timetable->academic_year,
        //     "class_id" => $data_timetable->class_id,
        // ])->first();
        $period_id = ClasstimetableModel::where([
            "academic_year" => $data_timetable->academic_year,
            "class_id" => $data_timetable->class_id,
            "section_id" => $data_timetable->section_id,
        ])
            ->pluck("period_id")
            ->first();

        $getperiod = PeriodClassMappingModel::where("id", $period_id)->first();

        if ($getperiod) {
            $timing = PeriodClassMappingModel::where(
                "period_class_id",
                $getperiod->period_class_id
            )->get();

            $subjects = SubjectModel::where("status", 1)
                ->where("class_id", $data_timetable->class_id)
                ->pluck("name", "id")
                ->toArray();
        }
        $getweekend = Configurations::getConfig("site")->week_end;
        //  dd(  $data_timetable,$getNoofdaysTimetableadded,$timing);
        return view("classtimetable::admin.parts.calender", [
            "days" => $getNoofdaysTimetableadded,
            "timing" => $timing,
            "subjects" => $subjects,
            "layout" => "show",
            "type" => "show",
            "data" => $data_timetable,
            "getweekend" => $getweekend,
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
        $data_timetable = ClasstimetableModel::where("id", $id);
        $info = Configurations::getAcademicandTermsInfo();

        //periodinfo
        $period_map_id = ClasstimetableModel::where("id", $id)
            ->pluck("period_id")
            ->first();
        // dd($period_map_id);
        $period_id = PeriodClassMappingModel::where("id", $period_map_id)
            ->pluck("period_class_id")
            ->first();

        $period_data = PeriodClassMappingModel::where(
            "period_class_id",
            $period_id
        )->get();
        // dd($period_data);
        $period_data = $period_data->transform(function ($period) {
            $period->from_time = Carbon::parse($period->from)->format("H:i");
            $period->to_time = Carbon::parse($period->to)->format("H:i");

            return $period;
        });
        //  dd($period_data);
        //

        // $getalldays = Configurations::WEEKDAYS;
        // $getNoofdaysTimetableadded = [];

        // $nodays = ClasstimetableModel::where([
        //     "academic_year" => $data_timetable->academic_year,
        //     "class_id" => $data_timetable->class_id,
        //     "section_id" => $data_timetable->section_id,
        // ])->count();

        // for ($i = 1; $i <= $data_timetable->no_of_days; $i++) {
        //     $getNoofdaysTimetableadded[$i] = $getalldays[$i];
        // }

        // //  $getperiod = PeriodModel::where([
        // //     "academic_year" => $data_timetable->academic_year,
        // //      "class_id" => $data_timetable->class_id,
        // //  ])->first();

        // $period_id = ClasstimetableModel::where([
        //     "academic_year" => $data_timetable->academic_year,
        //     "class_id" => $data_timetable->class_id,
        //     "section_id" => $data_timetable->section_id,
        // ])->pluck('period_id')->first();

        // $getperiod = PeriodClassMappingModel::where('id', $period_id)->first();

        // if ($getperiod) {

        //     $timing = PeriodClassMappingModel::where(
        //         "period_class_id",
        //         $getperiod->period_class_id
        //     )->get();

        //     $subjects = SubjectModel::where("status", 1)
        //         ->where("class_id", $data_timetable->class_id)
        //         ->pluck("name", "id")
        //         ->toArray();
        // }

        // $academicyears = Configurations::getAcademicyears();

        // $assigendclasses = ClasstimetableModel::where("status", 1)->pluck(
        //     "class_id"
        // );

        // //$assigendclassesSection=SectionModel::where("status",1)->where("class_id")

        // $class_lists = LclassModel::whereNull("deleted_at")
        //     ->where("status", "!=", -1)
        //     ->orderBy("id", "asc")
        //     ->pluck("name", "id")
        //     ->toArray();

        // //dd($class_lists);

        // // selected sectioninfo;

        // $selectedsection = SectionModel::where(
        //     "id",
        //     $data_timetable->section_id
        // )->first();

        // if ($selectedsection->department_id == null) {
        //     $departments = [];
        // } else {
        //     $departments = DepartmentModel::where("status", 1)
        //         ->where("id", $selectedsection->department_id)
        //         ->pluck("dept_name", "id")
        //         ->toArray();
        // }

        // $sections = SectionModel::where("status", 1)
        //     ->where("class_id", $data_timetable->class_id)
        //     ->pluck("name", "id")
        //     ->toArray();
        // $terms = ExamTermModel::where("status", 1)
        //     ->where("academic_year", $data_timetable->academic_year)
        //     ->pluck("exam_term_name", "id")
        //     ->toArray();

        // $getweekend = Configurations::getConfig("site")->week_end;
        return view("classtimetable::admin.edit", [
            // "days" => $getNoofdaysTimetableadded,
            // "timing" => $timing,
            // "subjects" => $subjects,
            "layout" => "edit",
            // "data" => $data_timetable,
            // "academicyears" => $academicyears,
            // "class_lists" => $class_lists,
            // "sections" => $sections,
            // "terms" => $terms,
            // "departments" => $departments,
            // "getweekend" => $getweekend,
            "period_data" => $period_data,
            "period_id" => $period_id,
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
        $is_exist = PeriodClassMappingModel::where(
            "period_class_id",
            $id
        )->get();
        $period_id = PeriodClassMappingModel::where(
            "period_class_id",
            $id
        )->pluck("id");
        $data = ClasstimetableModel::whereIn("period_id", $period_id)->first();
        if ($is_exist->isNotEmpty()) {
            // Check if records exist
            foreach ($is_exist as $key => $existingRecord) {
                // Loop through each existing record
                $existingRecord->from = $request->start_time[$key];
                $existingRecord->to = $request->end_time[$key];
                $existingRecord->type = $request->period_category[$key];
                $existingRecord->save(); // Update the existing record
            }
        }

        Session::flash("success", "updated successfully");
        return redirect()->route("classtimetablecalender", [
            "id" => $id,
            "type" => "edit",
            "days" => $data->no_of_days,
            "section" => $data->section_id,
        ]);
    }
    public function TimetableUpdate(Request $request, $id)
    {
        // dd($request->all());

        DB::beginTransaction();
        try {
            $period_id = PeriodClassMappingModel::where("id", $id)
                ->pluck("period_class_id")
                ->first();
            $period_data = PeriodModel::where("id", $period_id)->first();
            // dd($period_id, $period_data);
            $perioddata = [];
            foreach ($request->sub_id as $dayid => $periods) {
                foreach ($periods as $periodid => $data) {
                    if ($data["subject"] != null) {
                        // dd("if");
                        $perioddata[] = [
                            "day" => $dayid,
                            "period_id" => $periodid,
                            "subject_id" => $data["subject"],
                            "teacher_id" => $data["teacher"],
                            "colorcode" => $data["bgcolor"],
                            "border_color" => $data["bordercolor"],
                            "no_of_days" => $request->no_of_days,
                            "id" =>
                                isset($data["timtableid"]) &&
                                $data["timtableid"] != null
                                    ? $data["timtableid"]
                                    : 0,
                        ];
                    } else {
                        // dd("else");
                        if (
                            isset($data["timtableid"]) &&
                            $data["timtableid"] != null
                        ) {
                            ClasstimetableModel::find(
                                $data["timtableid"]
                            )->delete();

                            $attendanceIds = AttendanceModel::where("type", 1)
                                ->where("period_id", $data["timtableid"])
                                ->pluck("id")
                                ->toArray();

                            StudentAttendanceModel::whereIn(
                                "attendance_id",
                                $attendanceIds
                            )->delete();

                            AttendanceModel::where("type", 1)
                                ->where("period_id", $data["timtableid"])
                                ->delete();
                        }
                    }
                }
            }
            //   dd($request->all(),$perioddata);

            // $existing = ClasstimetableModel::where("id", 0)->first();

            // dd($existing);
            $existdata = [];
            $newdata = [];
            if (sizeof($perioddata) > 0) {
                foreach ($perioddata as $data_) {
                    // dd($data_);
                    $existing = ClasstimetableModel::where(
                        "id",
                        $data_["id"]
                    )->first();

                    if ($existing) {
                        //dd($existing);
                        //$newdata[] = $data_;
                        // update
                        $existing->no_of_days = $data_["no_of_days"];
                        $existing->subject_id = $data_["subject_id"];
                        $existing->teacher_id = $data_["teacher_id"];
                        $existing->colorcode = $data_["colorcode"];
                        $existing->border_color = $data_["border_color"];

                        $existing->save();

                        $newdata[] = $existing;
                    } else {
                        // $newdata[] = $data_;
                        $obj = new ClasstimetableModel();
                        $obj->academic_year = $period_data->academic_year;
                        $obj->class_id = $period_data->class_id;
                        $obj->section_id = $request->section;
                        $obj->no_of_days = $request->no_days;
                        $obj->day = $data_["day"];
                        $obj->period_id = $data_["period_id"];
                        $obj->subject_id = $data_["subject_id"];
                        $obj->teacher_id = $data_["teacher_id"];
                        $obj->colorcode = $data_["colorcode"];
                        $obj->border_color = $data_["border_color"];
                        $obj->save();
                    }
                }
            } else {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "System can't add Empty timetable please add any subject and staff"
                    );
            }
            // dd($newdata);

            DB::commit();
            //dd($newdata);
            return redirect()
                ->route("classtimetable.index")
                ->with("success", "Added Successfully");
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            dd($message);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("classtimetable.index");
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
        if (!empty($request->selected_classtimetable)) {
            $delObj = new ClasstimetableModel();
            foreach ($request->selected_classtimetable as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new ClasstimetableModel();
            $delItem = $delObj->find($id);
            $period_del = PeriodModel::where([
                "academic_year" => $delItem->academic_year,
                "academic_term" => $delItem->term_id,
                "class_id" => $delItem->class_id,
                "section_id" => $delItem->section_id,
            ])->forceDelete();
            if ($period_del) {
                ClasstimetableModel::where([
                    "class_id" => $delItem->class_id,
                    "section_id" => $delItem->section_id,
                ])->forceDelete();
                // $delItem->forceDelete();
            } else {
                ClasstimetableModel::where([
                    "class_id" => $delItem->class_id,
                    "section_id" => $delItem->section_id,
                ])->forceDelete();
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("classtimetable.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));
        $data = PeriodModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "period_class.id",
            "period_class.academic_year",
            "period_class.class_id",
            "period_class.section_id",
            "academicyear.year as year",
            "lclass.name as classname",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new PeriodModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new PeriodModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->leftJoin(
                "academicyear",
                "academicyear.id",
                "=",
                "period_class.academic_year"
            )
            ->leftjoin("lclass", "lclass.id", "=", "period_class.class_id");

        // $data = ClasstimetableModel::select(
        //     DB::raw("@rownum  := @rownum  + 1 AS rownum"),
        //     "classtimetable.id as id",
        //     "classtimetable.class_id as class_id",
        //     "classtimetable.section_id as section_id",
        //     "classtimetable.period_id as period_id",
        //     "classtimetable.academic_year as year",
        //     DB::raw(
        //         "(CASE WHEN " .
        //             DB::getTablePrefix() .
        //             (new ClasstimetableModel())->getTable() .
        //             '.status = "0" THEN "Disabled"
        //     WHEN ' .
        //             DB::getTablePrefix() .
        //             (new ClasstimetableModel())->getTable() .
        //             '.status = "-1" THEN "Trashed"
        //     ELSE "Enabled" END) AS status'
        //     )
        // )
        //     ->where("classtimetable.status", "!=", -1)
        //     ->leftjoin("lclass", "lclass.id", "=", "classtimetable.class_id")
        //     ->leftjoin(
        //         "section",
        //         "section.id",
        //         "=",
        //         "classtimetable.section_id"
        //     )
        //     ->groupBy([
        //         "classtimetable.id",
        //         "classtimetable.class_id",
        //         "classtimetable.section_id",
        //         "classtimetable.period_id",
        //         "classtimetable.academic_year",
        //         "classtimetable.status",
        //     ]);

        // Other logic for Teachers and Students
        if (Session::get("ACTIVE_GROUP") == "Teacher") {
            $user_id = $request->user()->id;
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $classteacher = ClassteacherModel::where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])->first();
            if ($classteacher) {
                $data = $data->where([
                    "period_class.class_id" => $classteacher->class_id,
                    "period_class.section_id" => $classteacher->section_id,
                ]);
            } else {
                return Datatables::of(collect([]))->make(true); // Return empty DataTable if no data
            }
        }

        if (Session::get("ACTIVE_GROUP") == "Student") {
            $user_id = $request->user()->id;
            $student = StudentsModel::where("user_id", $user_id)->first();
            if ($student) {
                $data = $data
                    ->where([
                        "period_class.class_id" => $student->class_id,
                        "period_class.section_id" => $student->section_id,
                    ])
                    ->where("period_class.status", "=", 1);
            }
        }

        // DataTables processing
        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                return $data->id != "1" ? $data->rownum : "";
            })
            ->addColumn("academic_year", function ($data) {
                return AcademicyearModel::where("id", $data->academic_year)
                    ->pluck("year")
                    ->first();
            })
            ->addColumn("class", function ($data) {
                return LclassModel::find($data->class_id)->name;
            })
            ->addColumn("section", function ($data) {
                return SectionModel::find($data->section_id)->name;
            })
            ->addColumn("no_of_periods", function ($data) {
                $period_map = PeriodClassMappingModel::where(
                    "period_class_id",
                    $data->id
                )->first();
                $count = $period_map
                    ? PeriodClassMappingModel::where(
                        "period_class_id",
                        $data->id
                    )->count()
                    : 0;
                return $count . " Periods";
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
                $period_map = PeriodClassMappingModel::where(
                    "period_class_id",
                    $data->id
                )->first();
                $timetable = ClasstimetableModel::where(
                    "period_id",
                    $period_map->id
                )->first();

                return view("layout::datatable.action", [
                    "data" => $timetable,
                    "route" => "classtimetable",
                ])->render();
            });

        return $datatables->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-classtimetable");
        if ($request->ajax()) {
            $obj = ClasstimetableModel::find($request->id);
            $data = ClasstimetableModel::where([
                "class_id" => $obj->class_id,
                "section_id" => $obj->section_id,
            ])->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_classtimetable)) {
            $obj = new ClasstimetableModel();
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

    public function CloneTimetable($id)
    {
        $data_timetable = ClasstimetableModel::find($id);

        $info = Configurations::getAcademicandTermsInfo();

        $getalldays = Configurations::WEEKDAYS;
        $getNoofdaysTimetableadded = [];

        $nodays = ClasstimetableModel::where([
            "academic_year" => $data_timetable->academic_year,
            "class_id" => $data_timetable->class_id,
            "section_id" => $data_timetable->section_id,
        ])->count();

        for ($i = 1; $i <= $data_timetable->no_of_days; $i++) {
            $getNoofdaysTimetableadded[$i] = $getalldays[$i];
        }

        $getperiod = PeriodModel::where([
            "academic_year" => $data_timetable->academic_year,
            "class_id" => $data_timetable->class_id,
            "academic_term" => $data_timetable->term_id,
            "section_id" => $data_timetable->section_id,
        ])->first();
        if ($getperiod) {
            $timing = PeriodClassMappingModel::where(
                "period_class_id",
                $getperiod->id
            )->get();
            //    dd($timing);
            $subjects = SubjectModel::where("status", 1)
                ->where("class_id", $data_timetable->class_id)
                ->pluck("name", "id")
                ->toArray();
        }

        $academicyears = Configurations::getAcademicyears();

        $assigendclasses = ClasstimetableModel::where("status", 1)->pluck(
            "class_id"
        );

        //$assigendclassesSection=SectionModel::where("status",1)->where("class_id")

        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        //dd($class_lists);

        // selected sectioninfo;

        $selectedsection = SectionModel::where(
            "id",
            $data_timetable->section_id
        )->first();

        if ($selectedsection->department_id == null) {
            $departments = DepartmentModel::where("status", 1)
                ->pluck("dept_name", "id")
                ->toArray();
        } else {
            $departments = DepartmentModel::where("status", 1)
                ->where("id", $selectedsection->department_id)
                ->pluck("dept_name", "id")
                ->toArray();
        }

        $sections = SectionModel::where("status", 1)
            ->where("class_id", $data_timetable->class_id)
            ->pluck("name", "id")
            ->toArray();
        $terms = ExamTermModel::where("status", 1)
            ->where("academic_year", $data_timetable->academic_year)
            ->pluck("exam_term_name", "id")
            ->toArray();
        $getweekend = Configurations::getConfig("site")->week_end;
        return view("classtimetable::admin.edit", [
            "days" => $getNoofdaysTimetableadded,
            "timing" => $timing,
            "subjects" => $subjects,
            "layout" => "clone",
            "data" => $data_timetable,
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => $sections,
            "terms" => $terms,
            "departments" => $departments,
            "getweekend" => $getweekend,
            "info" => $info,
        ]);
    }

    public function TimetableClone(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            "class_id" => "required",
            "section_id" => "required",
            "no_days" => "required",
            "term_id" => "required",
            "academic_year" => "required",
        ]);
        $department_id = $request->dept_id ?? null;
        $is_exist = ClasstimetableModel::where([
            "class_id" => $request->class_id,
            "section_id" => $request->section_id,
            "term_id" => $request->term_id,
            "academic_year" => $request->academic_year,
            "dept_id" => $department_id,
        ])->exists();
        if ($is_exist) {
            //dd($request->all());

            DB::beginTransaction();
            try {
                $perioddata = [];
                foreach ($request->sub_id as $dayid => $periods) {
                    foreach ($periods as $periodid => $data) {
                        if ($data["subject"] != null) {
                            $perioddata[] = [
                                "academic_year" => $request->academic_year,
                                "class_id" => $request->class_id,
                                "section_id" => $request->section_id,
                                "dept_id" => $request->dept_id,
                                "term_id" => $request->term_id,
                                "day" => $dayid,
                                "period_id" => $periodid,
                                "subject_id" => $data["subject"],
                                "teacher_id" => $data["teacher"],
                                "colorcode" => $data["bgcolor"],
                                "border_color" => $data["bordercolor"],
                                "no_of_days" => $request->no_of_days,
                                "id" =>
                                    isset($data["timtableid"]) &&
                                    $data["timtableid"] != null
                                        ? $data["timtableid"]
                                        : 0,
                            ];
                        } else {
                            if (
                                isset($data["timtableid"]) &&
                                $data["timtableid"] != null
                            ) {
                                ClasstimetableModel::find(
                                    $data["timtableid"]
                                )->delete();

                                $attendanceIds = AttendanceModel::where(
                                    "type",
                                    1
                                )
                                    ->where("period_id", $data["timtableid"])
                                    ->pluck("id")
                                    ->toArray();

                                StudentAttendanceModel::whereIn(
                                    "attendance_id",
                                    $attendanceIds
                                )->delete();

                                AttendanceModel::where("type", 1)
                                    ->where("period_id", $data["timtableid"])
                                    ->delete();
                            }
                        }
                    }
                }
                //  dd($perioddata);

                // $existing = ClasstimetableModel::where("id", 0)->first();

                // dd($existing);
                $existdata = [];
                $newdata = [];
                if (sizeof($perioddata) > 0) {
                    foreach ($perioddata as $data_) {
                        // dd($data_);
                        $existing = ClasstimetableModel::where(
                            "id",
                            $data_["id"]
                        )->first();

                        if ($existing) {
                            //dd($existing);
                            //$newdata[] = $data_;
                            // update
                            $existing->term_id = $request->term_id;
                            $existing->dept_id = $data_["dept_id"];
                            $existing->no_of_days = $data_["no_of_days"];
                            $existing->subject_id = $data_["subject_id"];
                            $existing->teacher_id = $data_["teacher_id"];
                            $existing->colorcode = $data_["colorcode"];
                            $existing->border_color = $data_["border_color"];

                            $existing->save();

                            $newdata[] = $existing;
                        } else {
                            // $newdata[] = $data_;
                            $obj = new ClasstimetableModel();
                            $obj->academic_year = $request->academic_year;
                            $obj->class_id = $request->class_id;
                            $obj->section_id = $request->section_id;
                            $obj->term_id = $request->term_id;
                            $obj->dept_id = $request->dept_id;
                            $obj->no_of_days = $request->no_days;
                            $obj->day = $data_["day"];
                            $obj->period_id = $data_["period_id"];
                            $obj->subject_id = $data_["subject_id"];
                            $obj->teacher_id = $data_["teacher_id"];
                            $obj->colorcode = $data_["colorcode"];
                            $obj->border_color = $data_["border_color"];
                            $obj->save();
                        }
                    }
                } else {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            "exception_error",
                            "System can't add Empty timetable please add any subject and staff"
                        );
                }
                // dd($newdata);

                DB::commit();
                //dd($newdata);
                return redirect()
                    ->route("classtimetable.index")
                    ->with("success", "Added Successfully");
            } catch (\Exception $e) {
                DB::rollback();
                $message = str_replace(
                    ["\r", "\n", "'", "`"],
                    " ",
                    $e->getMessage()
                );
                dd($message);
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", $message);
            }

            Session::flash("success", "updated successfully");
            return redirect()->route("classtimetable.index");
        } else {
            // dd($request->all());
            DB::beginTransaction();
            try {
                $perioddata = [];
                foreach ($request->sub_id as $dayid => $periods) {
                    foreach ($periods as $periodid => $data) {
                        if ($data["subject"] != null) {
                            $perioddata[] = [
                                "academic_year" => $request->academic_year,
                                "class_id" => $request->class_id,
                                "section_id" => $request->section_id,
                                "dept_id" => $request->dept_id,
                                "term_id" => $request->term_id,
                                "day" => $dayid,
                                "period_id" => $periodid,
                                "subject_id" => $data["subject"],
                                "teacher_id" => $data["teacher"],
                                "colorcode" => $data["bgcolor"],
                                "border_color" => $data["bordercolor"],
                                "no_of_days" => $request->no_days,
                            ];
                        }
                    }
                }
                //dd($perioddata);
                if (sizeof($perioddata) > 0) {
                    ClasstimetableModel::insert($perioddata);
                } else {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with(
                            "exception_error",
                            "Some Subject Data not Assigned Kindly Fil All Information"
                        );
                }

                DB::commit();
                return redirect()
                    ->route("classtimetable.index")
                    ->with("success", "Added Successfully");
            } catch (\Exception $e) {
                DB::rollback();
                $message = str_replace(
                    ["\r", "\n", "'", "`"],
                    " ",
                    $e->getMessage()
                );
                // dd($message);
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", $message);
            }

            if ($request->has("submit_cat_continue")) {
                return redirect()
                    ->route("classtimetable.create")
                    ->with("success", "Saved Successfully");
            }

            Session::flash("success", "saved successfully");
            return redirect()->route("classtimetable.index");
        }
    }

    public function TimeTablePeriodDelete(Request $request)
    {
        $period = $request->query->get("id", 0);

        // Check if $period has a valid value
        if ($period) {
            // Delete records from ExamTimetableModel based on mapping IDs
            $timetable_deleted = ClasstimetableModel::where(
                "period_id",
                $period
            )->delete();

            // Delete records from ExamPeriodMappingModel based on the given period ID
            $period_deleted = PeriodClassMappingModel::where(
                "id",
                $period
            )->delete();

            if ($timetable_deleted && $period_deleted) {
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
}
