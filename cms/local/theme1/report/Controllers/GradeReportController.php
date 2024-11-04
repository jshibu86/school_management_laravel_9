<?php
namespace cms\report\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\configurations\helpers\Configurations;
use cms\shop\Models\ProductModel;
use cms\shop\Models\PurchaseOrderModel;
use DB;
use Carbon\Carbon;
use cms\shop\Controllers\PurchaseOrderController;
use cms\shop\Models\OrderItemsModel;
use Yajra\DataTables\Facades\DataTables;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\subject\Models\SubjectModel;
use cms\report\Models\MarkReportModel;
use CGate;
class GradeReportController extends Controller
{
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
    public function index1(Request $request)
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

        $examterms = Configurations::getCurentAcademicTerms();

        if ($request->ajax()) {
            $type = $request->query->get("type", 0);

            $academic_year = $request->query->get("academic_year", 0);
            $school_type_grade = $request->query->get("school_type_grade", 0);

            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $subjects = [];

            if ($type == 2) {
                // $class_lists = LclassModel::whereNull("deleted_at")
                //     ->where("status", "!=", -1)
                //     ->where("school_type_id", $school_type_grade)
                //     ->select("id", "name as text")
                //     ->get();

                $class_lists = LclassModel::whereNull("deleted_at")
                    ->where("status", "!=", -1)
                    ->where("school_type_id", $school_type_grade)
                    ->pluck("name", "id")
                    ->toArray();

                return $class_lists;
            }
            if ($type == 1) {
                // $subjects = SubjectModel::whereNull("deleted_at")
                //     ->where("status", "!=", -1)
                //     ->where("class_id", $class_id)
                //     ->select("class_id", "name as text")
                //     ->get();

                $subjects = SubjectModel::where("status", 1)
                    ->where("class_id", $class_id)
                    ->pluck("name", "id")
                    ->toArray();

                return $subjects;
            }
            if ($type == 3) {
                $acyear = $request->query->get("acyear", 0);
                $acyear_term = $request->query->get("acyear_term", 0);
                $school_type = $request->query->get("school_type", 0);

                $subjects = DB::table("subject")
                    ->where([
                        "class_id" => $class_id,
                        "school_type" => $school_type,
                    ])
                    ->select("name")
                    ->get();
                $subject_id = DB::table("subject")
                    ->where([
                        "class_id" => $class_id,
                        "school_type" => $school_type,
                    ])
                    ->pluck("id");
                $exam = DB::table("exam")
                    ->whereIn("subject_id", $subject_id)
                    ->where([
                        "class_id" => $class_id,
                        "academic_year" => $acyear,
                        "exam_term" => $acyear_term,
                        "section_id" => $section_id,
                    ])
                    ->select("id", "type_of_exam", "min_mark", "max_mark")
                    ->get();
                // $min_mark = $exam->pluck('min_mark')->first();
                // $max_mark = $exam->pluck('max_mark')->toarray();
                // $total = array_sum($max_mark);

                $students = DB::table("students")
                    ->where([
                        "class_id" => $class_id,
                        "academic_year" => $acyear,
                        "section_id" => $section_id,
                        "status" => 1,
                    ])
                    ->get();
                $student_ids = $students->pluck("id");
                $exam_ids = $exam->pluck("id"); // Pluck only the 'id' column from the $exam collection

                $report = MarkReportModel::whereIn("student_id", $student_ids)
                    ->where([
                        "academic_year" => $acyear,
                        "term_id" => $acyear_term,
                        "status" => 1,
                    ])
                    ->get();
                $is_promoted = $report->where("is_promotion", "=", 1)->count();
                $not_promoted = $report
                    ->where("is_promotion", "!=", 1)
                    ->count();
                $result = [$is_promoted, $not_promoted];
                // $total = count($offline) + count($online);

                $view = view("report::admin.report.grade.grade_append", [
                    "students" => $students,
                    "subject_id" => $subject_id,
                    "exam_ids" => $exam_ids,
                    "academic_year" => $acyear,
                    "term" => $acyear_term,
                ])->render();

                return response()->json([
                    "subjects" => $subjects,
                    "result" => $result,
                    "exam_ids" => $exam_ids,
                    "exam" => $exam,
                    "subject_id" => $subject_id,
                    "academic_year" => $acyear,
                    "class" => $class_id,
                    "term" => $acyear_term,
                    "view" => $view,
                ]);
            }
            if ($type == 4) {
                $acyear = $request->query->get("acyear", 0);
                $acyear_term = $request->query->get("acyear_term", 0);
                $school_type = $request->query->get("school_type", 0);
                $subject_id = $request->query->get("subject", 0);

                $exam = DB::table("exam")
                    ->where([
                        "subject_id" => $subject_id,
                        "class_id" => $class_id,
                        "academic_year" => $acyear,
                        "exam_term" => $acyear_term,
                        "section_id" => $section_id,
                    ])
                    ->get();

                $min_mark = $exam->pluck("min_mark")->first();
                $max_mark = $exam->pluck("max_mark")->toarray();
                $total = array_sum($max_mark);

                $students = DB::table("students")
                    ->where([
                        "class_id" => $class_id,
                        "academic_year" => $acyear,
                        "section_id" => $section_id,
                        "status" => 1,
                    ])
                    ->get();
                $student_ids = $students->pluck("id");

                $exam_ids = $exam->pluck("id"); // Pluck only the 'id' column from the $exam collection
                $student_names = $students->pluck("first_name");
                $offline = DB::table("offline_exam_mark")
                    ->whereIn("exam_id", $exam_ids)
                    ->whereIn("student_id", $student_ids)
                    ->select("mark_status")
                    ->get();

                $online = DB::table("online_exam")
                    ->whereIn("exam_id", $exam_ids)
                    ->whereIn("student_id", $student_ids)
                    ->select("total_marks")
                    ->get();

                $passOffline = $offline->where("mark_status", 1)->count();
                $passOnline = $online
                    ->where("total_marks", ">=", $min_mark)
                    ->count();

                $FailOffline = $offline->where("mark_status", 0)->count();
                $FailOnline = $online
                    ->where("total_marks", "<", $min_mark)
                    ->count();

                // Total number of students who passed
                $pass = $passOffline + $passOnline;
                $Fail = $FailOffline + $FailOnline;
                $result = [$pass, $Fail];
                //for check sub_id ==6
                $view = view(
                    "report::admin.report.grade.grade_subject_append",
                    [
                        "students" => $students,
                        "total" => $total,
                        "subject_id" => $subject_id,
                        "exam_ids" => $exam_ids,
                        "academic_year" => $acyear,
                        "term" => $acyear_term,
                        "class_id" => $class_id,
                        "section_id" => $section_id,
                    ]
                )->render();

                return response()->json([
                    "subjects" => $student_names,
                    "result" => $result,
                    "pass" => $pass,
                    "fail" => $Fail,
                    "passOffline" => $passOffline,
                    "passOnline" => $passOnline,
                    " FailOffline" => $FailOffline,
                    "FailOnline" => $FailOnline,
                    "online" => $online,
                    "offline" => $offline,
                    "exam_ids" => $exam_ids,
                    "min_mark" => $min_mark,
                    "exam" => $exam,
                    "subject_id" => $subject_id,
                    "total" => $total,
                    "academic_year" => $acyear,
                    "class" => $class_id,
                    "student_ids" => $student_ids,
                    "term" => $acyear_term,
                    "view" => $view,
                ]);
            }
            if ($type == 5) {
                $acyear = $request->query->get("academic_year", 0);
                $acyear_term = $request->query->get("acyear_term", 0);
                $school_type = $request->query->get("school_type", 0);

                $subjects = DB::table("subject")
                    ->where([
                        "class_id" => $class_id,
                        "school_type" => $school_type,
                    ])
                    ->select("id", "name as text")
                    ->get();

                return response()->json(["subjects" => $subjects]);
            }
        }

        return view("report::admin.report.grade.gradeoverall", [
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

    public function studentReportView(Request $request)
    {
        //  dd($request->position);
        $position = $request->position ? $request->position : 0;
        if ($position !== 0) {
            $academic_year = $request->academic_year;
            $term = $request->term_id;
            $position = $request->position;
            $id = $request->id;
            $student = DB::table("students")
                ->where(["id" => $id, "status" => 1])
                ->first();
            $mark_report = MarkReportModel::where([
                "academic_year" => $academic_year,
                "term_id" => $term,
                "student_id" => $id,
            ])->first();

            $view = view(
                "report::admin.report.grade.grade_overall_student_view"
            )
                ->with([
                    "position" => $position,
                    "student" => $student,
                    "mark_report" => $mark_report,
                ])
                ->render();
            return response()->json(["viewfile" => $view]);
        } else {
            $academic_year = $request->academic_year;
            $term = $request->term_id;
            $id = $request->id;
            $student = DB::table("students")
                ->where("id", $id)
                ->first();
            $view = view(
                "report::admin.report.grade.grade_overall_student_view"
            )
                ->with(["student" => $student])
                ->render();
            return response()->json(["viewfile" => $view]);
        }
    }
    public function studentSubjectReportView(Request $request)
    {
        //  dd($request->position);
        $position = $request->position ? $request->position : 0;
        if ($position !== 0) {
            $academic_year = $request->academic_year;
            $term = $request->term_id;
            $position = $request->position;
            $id = $request->id;
            $subject_id = $request->subject;
            $section_id = $request->section;
            $class_id = $request->class;
            $student = DB::table("students")
                ->where(["id" => $id, "status" => 1])
                ->first();
            $subject_report = DB::table("mark_entry")
                ->where([
                    "academic_year" => $academic_year,
                    "term_id" => $term,
                    "student_id" => $id,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "subject_id" => $subject_id,
                ])
                ->first();

            $view = view(
                "report::admin.report.grade.grade_subject_student_view"
            )
                ->with([
                    "position" => $position,
                    "student" => $student,
                    "mark_report" => $subject_report,
                ])
                ->render();
            return response()->json(["viewfile" => $view]);
        } else {
            $academic_year = $request->academic_year;
            $term = $request->term_id;
            $id = $request->id;
            $student = DB::table("students")
                ->where("id", $id)
                ->first();
            $view = view(
                "report::admin.report.grade.grade_overall_student_view"
            )
                ->with(["student" => $student])
                ->render();
            return response()->json(["viewfile" => $view]);
        }
    }
    public function getExamResult(Request $request)
    {
        $id = $request->query->get("id", 0);
        $class_id = $request->query->get("class_id", 0);
        $acyear = $request->query->get("ac_year", 0);
        $acyear_term = $request->query->get("ac_term", 0);
        $subject_id = $request->query->get("subject_id", 0);
        // $subject_id = 6;
        if ($id) {
            $student = DB::table("students")
                ->where(["id" => $id, "status" => 1])
                ->pluck("first_name")
                ->first();
            $score = DB::table("mark_entry")
                ->where([
                    "student_id" => $id,
                    "class_id" => $class_id,
                    "subject_id" => $subject_id,
                    "academic_year" => $acyear,
                ])
                ->pluck("total_mark")
                ->first();
        } else {
            $student = null; // Set $student to null if $student_id is not found
        }

        return response()->json(["score" => $score, "student" => $student]);
    }
    public function getSubjectPercentage(Request $request)
    {
        $id = $request->query->get("id", 0);
        $class_id = $request->query->get("class_id", 0);
        $acyear = $request->query->get("ac_year", 0);
        $acyear_term = $request->query->get("ac_term", 0);
        $exam = DB::table("exam")
            ->where([
                "class_id" => $class_id,
                "academic_year" => $acyear,
                "exam_term" => $acyear_term,
                "subject_id" => $id,
            ])
            ->select("id", "type_of_exam", "min_mark", "max_mark")
            ->get();
        $min_mark = $exam->pluck("min_mark")->first();
        $max_mark = $exam->pluck("max_mark")->toarray();
        $total = array_sum($max_mark);

        $exam_ids = $exam->pluck("id"); // Pluck only the 'id' column from the $exam collection

        $offline = DB::table("offline_exam_mark")
            ->whereIn("exam_id", $exam_ids)
            ->select("mark_status")
            ->get();

        $online = DB::table("online_exam")
            ->whereIn("exam_id", $exam_ids)
            ->select("total_marks")
            ->get();

        $passOffline = $offline->where("mark_status", 1)->count();
        $passOnline = $online->where("total_marks", ">=", $min_mark)->count();

        $FailOffline = $offline->where("mark_status", 0)->count();
        $FailOnline = $online->where("total_marks", "<", $min_mark)->count();

        // Total number of students who passed
        $pass = $passOffline + $passOnline;
        $Fail = $FailOffline + $FailOnline;
        $total = $pass + $Fail;
        $result = ($pass / $total) * 100;
        $sub_percentage = round($result);
        $percentage = $sub_percentage . "%";
        return response()->json([
            "sub_percentage" => $percentage,
            "pass" => $pass,
            "fail" => $Fail,
        ]);
    }
}
