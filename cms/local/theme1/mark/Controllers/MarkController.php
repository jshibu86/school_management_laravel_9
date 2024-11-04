<?php

namespace cms\mark\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\mark\Models\MarkModel;
use cms\mark\Models\SchoolTypeModel;
use cms\exam\Models\ExamModel;
use cms\exam\Models\ExamTypeModel;
use Yajra\DataTables\Facades\DataTables;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;
use cms\section\Models\SectionModel;
use cms\academicyear\Models\AcademicyearModel;
use cms\subject\Models\SubjectModel;
use cms\department\Models\DepartmentModel;
use cms\exam\Models\ExamTermModel;
use cms\core\configurations\Models\ConfigurationModel;
use Session;
use DB;
use CGate;
use Configurations;
use Carbon\Carbon;
use cms\mark\Models\MarkDistributionModel;
use cms\mark\Traits\MarkTrait;
use Auth;
use cms\mark\Models\GradeModel;
use cms\attendance\Models\StudentAttendanceModel;

class MarkController extends Controller
{
    use MarkTrait;
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
            $type = $request->query->get("type", 0);
            $subject_id = $request->query->get("subject_id", 0);

            if ($type == 1) {
                // get exams

                $exams = ExamModel::where("subject_id", $subject_id)
                    ->where("status", 1)
                    ->pluck("exam_type");
                $exam_types = ExamTypeModel::whereIn("id", $exams)
                    ->select("id", "exam_type_name as text")
                    ->get();

                return $exam_types;
            }
            if ($type == 2) {
                // get exams
                $exam_type = $request->query->get("exam_type", 0);
                // for online offline exams
                $exam_status = $request->query->get("exam_status", "Online");

                $exams = ExamModel::select("id", "exam_title as text")
                    ->where("subject_id", $subject_id)
                    ->where("exam_type", $exam_type)
                    ->where("type_of_exam", $exam_status)
                    ->where("status", 1)
                    ->get();

                return $exams;
            }
            if ($type == 3) {
                $school_type = $request->query->get("school_type", 0);
                $classes = DB::table("lclass")
                    ->select("id", "name as text")
                    ->where("school_type_id", $school_type)
                    ->get()
                    ->toArray();
                $distributionExists = DB::table("mark_distribution")
                    ->where("school_type_id", $school_type)
                    ->get();
                if (isset($distributionExists)) {
                    $attendance = MarkDistributionModel::where([
                        "distribution_name" => "Attendance",
                        "status" => 1,
                    ])
                        ->where("school_type_id", $school_type)
                        ->first();
                    $exam = MarkDistributionModel::where([
                        "distribution_name" => "Exam",
                        "status" => 1,
                    ])
                        ->where("school_type_id", $school_type)
                        ->first();
                    return response()->json([
                        "class" => $classes,
                        "attendance" => $attendance ? 1 : 0,
                        "exam" => $exam ? 1 : 0,
                        "distributionExists" => $distributionExists,
                        "school_type" => $school_type,
                    ]);
                } else {
                    return response()->json([
                        "message" => "No distributions found",
                    ]);
                }
            }

            $class_id = $request->query->get("class", 0);
            $section_id = $request->query->get("section", 0);
            $academic_year = $request->query->get("acyear", 0);
            $term_id = $request->query->get("term", 0);
            $exam_field = $request->query->get("exam_field", 0);
            $school_type = $request->query->get("school_type", 0);
            // attdance type 2 => means manual 1=>means automatic
            $attendance_type = $request->query->get("attendance_type", 2);
            $exam_status = $request->query->get("exam_status", "Online");
            $exam_id = $request->query->get("exam_id", 0);
            $exam_entry = $request->query->get("exam_entry", 0);
            // check if any exam available in this subjetc with configurations

            // $exists = ExamModel::where([
            //     "class_id" => $class_id,
            //     "section_id" => $section_id,
            //     "subject_id" => $subject_id,
            //     "exam_type" => $exam_type,
            //     "exam_term" => $term_id,
            //     "academic_year" => $academic_year,
            // ])->first();

            if (true) {
                $exam_info = ExamModel::with(
                    "academyyear",
                    "class",
                    "section",
                    "subject"
                )->find($exam_id);

                //dd($exam_info);
                if ($exam_info) {
                    $term = ExamTermModel::where(
                        "id",
                        $exam_info->exam_term
                    )->first()->exam_term_name;

                    $exam_type = ExamTypeModel::where(
                        "id",
                        $exam_info->exam_type
                    )->first()->exam_type_name;
                }
                ///if the exam_id is not found
                $class = DB::table("lclass")
                    ->where("id", $class_id)
                    ->first();
                $section = DB::table("section")
                    ->where("id", $section_id)
                    ->first();
                $subject = DB::table("subject")
                    ->where("id", "=", $subject_id)
                    ->first();
                $academyyear = DB::table("academicyear")
                    ->where("id", $academic_year)
                    ->first();
                $term = ExamTermModel::where("id", $term_id)->first()
                    ->exam_term_name;
                $department =
                    $subject->department_id !== null
                        ? DB::table("department")
                            ->where("id", $subject->department_id)
                            ->pluck("dept_name")
                            ->first()
                        : null;

                // $term = ExamTermModel::where(
                //     "id",
                //     $exam_info->exam_term
                // )->first()->exam_term_name;

                // $exam_type = ExamTypeModel::where(
                //     "id",
                //     $exam_info->exam_type
                // )->first()->exam_type_name;

                //academic year start and end date
                $academic_dates = DB::table("academicyear")
                    ->where("id", $academic_year)
                    ->first(["start_date", "end_date"]);

                $start_date = $academic_dates->start_date;
                $end_date = $academic_dates->end_date;

                //total attendence for academic
                $attendance = DB::table("attendance")
                    ->where([
                        "class_id" => $class_id,
                        "section_id" => $section_id,
                        "subject_id" => $subject_id,
                        "academic_year" => $academic_year,
                        "academic_term" => $term_id,
                    ])
                    ->first();
                $form_start_date = Carbon::parse($start_date)->format("Y/m/d");
                $form_end_date = Carbon::parse($end_date)->format("Y/m/d");
                $dates = DB::table("attendance")
                    ->where([
                        "class_id" => $class_id,
                        "section_id" => $section_id,
                        "subject_id" => $subject_id,
                        "academic_year" => $academic_year,
                        "academic_term" => $term_id,
                    ])
                    ->whereBetween("attendance_date", [
                        $form_start_date,
                        $form_end_date,
                    ])
                    ->pluck("id");
                // dd(
                //     $dates,
                //     $class_id,
                //     $section_id,
                //     $subject_id,
                //     $academic_year,
                //     $term_id,
                //     $start_date,
                //     $end_date,
                //     $form_start_date,
                //     $form_end_date
                // );
                // dd($form_start_date, $form_end_date);
                // getting students info
                $students = StudentsModel::whereNull("deleted_at")
                    ->where("status", 1)
                    ->where([
                        "class_id" => $class_id,
                        "section_id" => $section_id,
                    ])
                    ->get();
                $students_attendance_info = [];
                if ($attendance && $dates && $dates != null) {
                    // $attendance_id = $dates->id;
                    $total_attendance = count($dates);
                    foreach ($students as $student) {
                        $count_attend = StudentAttendanceModel::whereIn(
                            "attendance_id",
                            $dates
                        )
                            ->where([
                                "student_id" => $student->id,
                                "attendance" => 1,
                            ])
                            ->count();
                        $students_attendance_info[$student->id] = $count_attend;
                    }
                    // dd(
                    //     $students_attendance_info["16"],
                    //     $students_attendance_info
                    // );
                } else {
                    $total_attendance = 0;
                    $attendance_id = 0;
                }

                // todo attendance calculation also if automatic mode
                $attendance_student = DB::table("attendance_students");
                $markdistribution = MarkDistributionModel::where(
                    "school_type_id",
                    $school_type
                )
                    ->where("status", 1)
                    ->get();

                //get the field_marks and exam_marks
                $field_mark = MarkDistributionModel::where(
                    "id",
                    $exam_field
                )->pluck("mark");
                $exam_mark = ExamModel::where("id", $exam_id)->pluck(
                    "max_mark"
                );
                //check the exam mark is greater than field mark or Already exists
                if ($field_mark && $exam_mark) {
                    if ($exam_mark > $field_mark) {
                        return response()->json([
                            "message" =>
                                "There you selected Exam's Mark is Greater than Selected Field's Max Mark, So you can Select the suitable field 
                        or try suitable exam. exam_mark is" .
                                $exam_mark .
                                "and Field Max Mark is " .
                                $field_mark,
                        ]);
                    }
                }

                ///check the subject is alreadey exists
                $isExists = MarkModel::where([
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "subject_id" => $subject_id,
                    "academic_year" => $academic_year,
                    "term_id" => $term_id,
                ])->exists();

                if ($isExists) {
                    return response()->json([
                        "message" =>
                            "The Selected subject is Already exists .You can select any other subject or Go to Update section for update this Subjet.",
                    ]);
                }
                if ($exam_entry == "2") {
                    $view = view("mark::admin.includes.markentry", [
                        "class" =>
                            $exam_info->class->name .
                            "-" .
                            $exam_info->section->name,

                        "acyear" => $exam_info->academyyear->year,
                        "subject" => $exam_info->subject->name,
                        "department" => $department,
                        "term" => $term,
                        "students" => $students,
                        "markdistribution" => $markdistribution,
                        "exam_type" => $exam_type,
                        "exam_info" => $exam_info,
                        "type" => "create",
                        "attendance_type" => $attendance_type,
                        "total_attendance" => $total_attendance,
                        "attendance_student" => $attendance_student,
                        "attendance_id" => $attendance_id ?? 0,
                        "exam_status" => $exam_status,
                        "exam_field" => $exam_field,
                        "students_attendance_info" => $students_attendance_info,
                    ])->render();
                } else {
                    $view = view("mark::admin.includes.markentry", [
                        "class" => $class->name . "-" . $section->name,

                        "acyear" => $academyyear->year,
                        "subject" => $subject->name,
                        "department" => $department,
                        "term" => $term,
                        "students" => $students,
                        "markdistribution" => $markdistribution,

                        "type" => "create",
                        "attendance_type" => $attendance_type,
                        "total_attendance" => $total_attendance,
                        "attendance_student" => $attendance_student,
                        "attendance_id" => $attendance_id ?? 0,
                        "students_attendance_info" => $students_attendance_info,
                    ])->render();
                }

                return response()->json([
                    "view" => $view,
                    "dates" => $total_attendance,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "subject_id" => $subject_id,
                    "academic_year" => $academic_year,
                    "academic_term" => $term_id,
                    "attendance_id" => $attendance_id ?? 0,
                    "exam_info" => $exam_info,
                    "exam_field" => $exam_field,
                    "exam_mark" => $exam_mark,
                    "field_mark" => $field_mark,
                    "exam_status" => $exam_status,
                ]);
            } else {
                return response()->json([
                    "message" => "No Exam Found in this Subject",
                ]);
            }
            return "ok";
        }

        return view("mark::admin.index");
    }

    public function examTitleexist(Request $request)
    {
        if ($request->ajax()) {
            $exam_id = $request->query->get("exam_id", 0);
            $type = $request->query->get("type", 0);
            $title = $request->title;

            // checking exam title exists

            $result = DB::table("exam")
                ->whereRaw("LOWER(exam_title) = ?", strtolower($title))
                ->exists();

            if ($result) {
                return response()->json([
                    "message" => "This Exam Title Already Exists",
                    "exists" => true,
                ]);
            } else {
                return response()->json([
                    "exists" => false,
                ]);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //dd($request->query->get("id", 0));

        //dd($exam_info);

        [$group, $info] = Configurations::GetActiveGroupwithInfo(
            Auth::user()->id
        );
        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $examterms = Configurations::getCurentAcademicTerms();

        $exam_types = ExamTypeModel::where("status", 1)
            ->whereNull("deleted_at")
            ->pluck("exam_type_name", "id")
            ->toArray();
        $school_types = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc");

        //mark distributions

        if ($group == "Teacher") {
            [$tclass_id] = Configurations::GetActiveTeacherClass($info->id);
            // /dd($teacherclass);
            $teacherassignclass = Configurations::getTeacherSubjects($info->id);

            $defaultcls = [$tclass_id];
            $assigncls = [];

            if (sizeof($teacherassignclass)) {
                $assigncls = SubjectModel::whereIn("id", $teacherassignclass)
                    ->pluck("class_id")
                    ->toArray();
            }

            $classlist = array_unique(array_merge($defaultcls, $assigncls));

            $class_lists = $class_lists
                ->whereIn("id", $classlist)
                ->pluck("name", "id")
                ->toArray();
        } else {
            $class_lists = $class_lists->pluck("name", "id")->toArray();
        }
        return view("mark::admin.edit", [
            "layout" => "create",
            "exams" => [],

            "sections" => [],
            "examtypes" => [],
            "exam_entry" => [],
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
            "examterms" => $examterms,
            "exam_types" => $exam_types,
            "school_types" => $school_types,
            "class_lists" => $class_lists,
            "current_academic_term" => $current_academic_term,
            "subject_lists" => [],
            "section_lists" => [],
            "markdistribution" => [],
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
        DB::beginTransaction();
        try {
            //dd($request->mark);

            // process mark with grades
            // dd($request->mark);
            $mark_data = [];

            //dd($request->mark);
            // dd($request);
            foreach ($request->mark as $studentid => $data) {
                # code...

                if (!isset($mark_data[$studentid])) {
                    $mark_data[$studentid] = new \stdClass();

                    $mark_data[$studentid]->total = $data["total"]
                        ? $data["total"]
                        : 0;
                    $mark_data[$studentid]->present = isset($data["present"])
                        ? 1
                        : 0;
                    [$grade, $point, $note] = $this->Getgradefrommark(
                        $data["total"] != null ? $data["total"] : 0
                    );
                    $mark_data[$studentid]->grade = $grade;
                    $mark_data[$studentid]->point = $point;
                    $mark_data[$studentid]->note = $note;
                    // dd($mark_data[$studentid]->grade );
                    unset($data["total"]);
                    if (isset($data["present"])) {
                        unset($data["present"]);
                    }
                    $mark_data[$studentid]->distribution = $data;
                }
            }

            // dd($mark_data);

            if (sizeof($mark_data)) {
                // entry marks
                //dd($mark_data);
                $message = $this->ProcessmarkEntry($request, $mark_data);
            }

            //dd($message);

            DB::commit();

            return redirect()
                ->route("mark.index")
                ->with("success", $message);
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

        $mark_entry_data = MarkModel::where("subject_id", $id)
            ->with("students")
            ->get();
        $mark_distribution = MarkModel::where("subject_id", $id)
            ->select("distribution")
            ->first();
        $distribution_head = json_decode($mark_distribution);

        // dd( $distribution_head);

        $get_data = MarkModel::with(
            "class",
            "subject",
            "term",
            "academicyear",
            "section"
        )
            ->where("subject_id", $id)
            ->first();
        //dd( $get_data->academicyear->year);
        $student_id = MarkModel::where("subject_id", $id)->pluck("student_id");

        $students = DB::table("students")
            ->whereIn("id", $student_id)
            ->select("first_name", "last_name", "image", "reg_no", "id")
            ->get();
        //  dd($students);
        // $distribution_head =  MarkDistributionModel::whereIn(
        //     "id",
        //     $mark_distribution->distribution
        // )->get();
        $mark_entry_data = $mark_entry_data->transform(function ($entry) {
            foreach ($entry->distribution as $key => $value) {
                $distri = $value["distributionname"];
                $entry->$distri = $value["mark"];
            }
            return $entry;
        });
        // $distr = [];
        // foreach ($mark_entry_data as $data) {
        //     $distr[] = $data->distribution;
        // }
        // dd($distribution_head, $mark_entry_data);

        return view("mark::admin.view", [
            "mark_entry_data" => $mark_entry_data,
            "data" => $get_data,
            "students" => $students,
            "distribution_head" => $distribution_head,
            "layout" => "view",
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
        // dd($id);
        $data = MarkModel::with("students")
            ->where("subject_id", $id)
            ->get();
        // dd($data);
        $student_id = $data->pluck("student_id");
        $data_first = DB::table("mark_entry")
            ->where("subject_id", $id)
            ->first();
        $students = DB::table("students")
            ->whereIn("id", $student_id)
            ->get();
        // dd( $students);
        // $exams = ExamModel::select("id", "exam_title as text")
        //     ->where("subject_id", $data->subject_id)

        //     ->where("status", 1)
        //     ->get()
        //    ;
        // $exam_id = $exams->pluck('id')->first();
        // $academic_years = Configurations::getAcademicyears();
        // $current_academic_year = Configurations::getCurrentAcademicyear();
        // $current_academic_term = Configurations::getCurrentAcademicterm();

        // $examterms = Configurations::getCurentAcademicTerms();

        // $exam_types = ExamTypeModel::where("status", 1)
        //     ->whereNull("deleted_at")
        //     ->pluck("exam_type_name", "id")
        //     ->toArray();
        // $class_lists = LclassModel::whereNull("deleted_at")
        //     ->where("status", "!=", -1)
        //     ->orderBy("id", "asc")
        //     ->pluck("name", "id")
        //     ->toArray();

        // $subject_lists = SubjectModel::where(
        //     "class_id",
        //     $data->class_id
        // )->pluck("name", "id");

        // $section_lists = SectionModel::where(
        //     "class_id",
        //     $data->class_id
        // )->pluck("name", "id");

        // if (true) {
        //     //dd($exam_id);
        //     $exam_info = ExamModel::with(
        //         "academyyear",
        //         "class",
        //         "section",
        //         "subject"
        //     )->find($exam_id);

        //     //dd($exam_info);
        //    if($exam_info->department_id !==null){
        //     $department = DepartmentModel::where(
        //         "id",
        //         $exam_info->department_id
        //     )->first()->dept_name;

        //    }
        //    else{
        //     $department = "NA";
        //    }

        //     $term = ExamTermModel::where("id", $exam_info->exam_term)->first()
        //         ->exam_term_name;

        //     $exam_type = ExamTypeModel::where(
        //         "id",
        //         $exam_info->exam_type
        //     )->first()->exam_type_name;

        //     // getting students info with mark

        //     $students_info = MarkModel::with("students")
        //         ->where("exam_id", $data->exam_id)
        //         ->get();

        //     $markdistribution = $data->distribution;

        //     dd($markdistribution);
        // }
        $class = DB::table("lclass")
            ->where("id", $data_first->class_id)
            ->pluck("name")
            ->first();
        $academic_year = DB::table("academicyear")
            ->where("id", $data_first->academic_year)
            ->pluck("year")
            ->first();
        $academic_term = DB::table("exam_term")
            ->where("id", $data_first->term_id)
            ->pluck("exam_term_name")
            ->first();
        $section = DB::table("section")
            ->where("id", $data_first->section_id)
            ->pluck("name")
            ->first();
        $subject = DB::table("subject")
            ->where("id", $data_first->subject_id)
            ->pluck("name")
            ->first();

        $department_id = DB::table("subject")
            ->where("id", $data_first->subject_id)
            ->pluck("department_id")
            ->first();
        if ($department_id !== null) {
            $department = DB::table("department")
                ->where("id", $department_id)
                ->pluck("dept_name")
                ->first();
        } else {
            $department = "NA";
        }
        $distribution = $data->pluck("distribution");
        //   dd($distribution);
        return view("mark::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "data_first" => $data_first,
            "students" => $students,
            "class" => $class,
            "academic_year" => $academic_year,
            "academic_term" => $academic_term,
            "section" => $section,
            "subject" => $subject,
            "department" => $department,
            "distribution" => $distribution,
            // "sections" => [],
            // "examtypes" => [],

            // "academic_years" => $academic_years,
            // "current_academic_year" => $current_academic_year,
            // "examterms" => $examterms,
            // "exam_types" => $exam_types,
            // "class_lists" => $class_lists,
            // "current_academic_term" => $current_academic_term,
            // "subject_lists" => $subject_lists,
            // "section_lists" => $section_lists,
            // "exams" => $exams,

            // "class" =>
            //     $exam_info->class->name . "-" . $exam_info->section->name,

            // "acyear" => $exam_info->academyyear->year,
            // "subject" => $exam_info->subject->name,
            // "department" => $department,
            // "term" => $term,
            // "students" => $students_info,
            // "markdistribution" => $markdistribution,
            // "exam_type" => $exam_type,
            // "exam_info" => $exam_info,
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
        DB::beginTransaction();
        try {
            // process mark with grades
            // dd($request->mark);

            // delete previous entry

            MarkModel::where([
                "academic_year" => $request->academic_year,
                "term_id" => $request->academic_term,
                "class_id" => $request->class_id,
                "exam_type" => $request->exam_type,
                "section_id" => $request->section_id,
                "subject_id" => $request->subject_id,
                "exam_id" => $request->exam_id,
            ])->delete();
            $mark_data = [];

            // dd($request->mark);

            foreach ($request->mark as $studentid => $data) {
                # code...
                if (!isset($mark_data[$studentid])) {
                    $mark_data[$studentid] = new \stdClass();

                    $mark_data[$studentid]->total = $data["total"]
                        ? $data["total"]
                        : 0;
                    $mark_data[$studentid]->present = isset($data["present"])
                        ? 1
                        : 0;
                    [$grade, $point, $note] = $this->Getgradefrommark(
                        $data["total"] ? $data["total"] : 0
                    );
                    $mark_data[$studentid]->grade = $grade;
                    $mark_data[$studentid]->point = $point;
                    $mark_data[$studentid]->note = $note;
                    unset($data["total"]);
                    if (isset($data["present"])) {
                        unset($data["present"]);
                    }
                    $mark_data[$studentid]->distribution = $data;
                }
            }

            if (sizeof($mark_data)) {
                // entry marks
                //dd($mark_data);
                $message = $this->ProcessmarkEntry($request, $mark_data);
            }

            //dd($message);

            DB::commit();

            return redirect()
                ->route("mark.index")
                ->with("success", $message);
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_mark)) {
            $delObj = new MarkModel();
            foreach ($request->selected_mark as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new MarkModel();
            $delItem = $delObj->where("subject_id", $id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("mark.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-mark");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        //get search value
        $searchTerm = $request->input("search.value");
        // get all mark enterd exams

        $data = MarkModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "mark_entry.id",
            "mark_entry.academic_year",
            "mark_entry.term_id",
            "mark_entry.entry_date",
            "mark_entry.class_id",
            "mark_entry.section_id",
            "mark_entry.subject_id",
            "academicyear.year as year",
            "exam_term.exam_term_name as term_name",
            "subject.name as subject",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new MarkModel())->getTable() .
                    '.status = "0" THEN "Disabled"
                WHEN ' .
                    DB::getTablePrefix() .
                    (new MarkModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
                ELSE "Enabled" END) AS status'
            )
        )
            ->leftJoin(
                "academicyear",
                "academicyear.id",
                "=",
                "mark_entry.academic_year"
            )
            ->leftJoin("exam_term", "exam_term.id", "=", "mark_entry.term_id")
            ->leftJoin("subject", "subject.id", "=", "mark_entry.subject_id")
            ->where("mark_entry.status", "!=", -1)
            ->groupBy("mark_entry.subject_id");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })

            ->addColumn("class_section", function ($data) {
                $class = LclassModel::where("id", $data->class_id)->first()
                    ->name;
                $section = SectionModel::where("id", $data->section_id)->first()
                    ->name;
                return $class . "-" . $section;
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
                    "route" => "mark",
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
        CGate::authorize("edit-mark");
        if ($request->ajax()) {
            MarkModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_mark)) {
            $obj = new MarkModel();
            foreach ($request->selected_mark as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function getappend(Request $request)
    {
        $layout = $request->layout;
        $subject_id = $request->subject_id;
        $school_type_id = $request->school_type; // Assuming this is an array
        $exam_status = $request->exam_status;

        // Fetching exams based on subject and status
        $exams = ExamModel::where("subject_id", $subject_id)
            ->where("status", 1)
            ->where("type_of_exam", $exam_status)
            ->pluck("exam_title", "id")
            ->toArray();

        // Fetching exam types
        $exam_type = ExamTypeModel::pluck("exam_type_name", "id")->toArray();

        // Fetching mark distributions based on school types
        $markdistribution = MarkDistributionModel::where(
            "school_type_id",
            $school_type_id
        )
            ->get()
            ->map(function ($item) {
                return [
                    "id" => $item->id,
                    "text" => $item->distribution_name,
                ];
            })
            ->pluck("text", "id")
            ->toArray();

        // Rendering view with fetched data
        $view = view("mark::admin.append")
            ->with([
                "layout" => $layout,
                "exam_type" => $exam_type,
                "exams" => $exams,
                "markdistribution" => $markdistribution,
            ])
            ->render();

        // Returning JSON response with view data
        return response()->json(["viewfile" => $view]);
        // return $submission;
    }
}
