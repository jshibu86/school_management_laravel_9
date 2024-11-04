<?php

namespace cms\report\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\report\Models\ReportModel;
use cms\mark\Models\MarkModel;
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

use Session;
use DB;
use CGate;
use cms\report\Models\MarkReportModel;
use Configurations;
use Auth;
use cms\mark\Traits\MarkTrait;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    use MarkTrait;
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
    public function Getmarkreport(Request $request)
    {
        [$group, $info] = Configurations::GetActiveGroupwithInfo(
            Auth::user()->id
        );

        //dd($info);

        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $examterms = Configurations::getCurentAcademicTerms();

        $exam_types = ExamTypeModel::where("status", 1)
            ->whereNull("deleted_at")
            ->pluck("exam_type_name", "id")
            ->toArray();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $students = StudentsModel::where("status", 1)->select([
            "students.id as id",
            DB::raw("CONCAT(students.username, ' - ', students.email) as text"),
        ]);

        if ($group == "Teacher") {
            [$tclass_id, $tsection_id] = Configurations::GetActiveTeacherClass(
                $info->id
            );
            $students = $students
                ->where([
                    "class_id" => $tclass_id,
                    "section_id" => $tsection_id,
                ])
                ->pluck("text", "id");
        } else {
            $students = $students->pluck("text", "id");
        }
        $class_id = 2;
        $section_id = 4;

        $terms = ExamTermModel::where("academic_year", 3)->get();

        //dd($mark_into);

        //dd($mark_data);

        if ($request->ajax()) {
            $student_id = $request->query->get("student_id");
            $term_id = $request->query->get("term");
            $acyear_id = $request->query->get("acyear");
            $exam_type = $request->query->get("exam_type");
            $type = $request->query->get("type");
            $student_info = StudentsModel::with("class", "section")->find(
                $student_id
            );
            $config = Configurations::getConfig("site");
            $selected_term = ExamTermModel::find($term_id)->exam_term_name;
            $acyear = AcademicyearModel::find($acyear_id)->year;

            // $is_promotion = ExamTypeModel::find($exam_type)->is_promotion;
            $terms = ExamTermModel::where("academic_year", $acyear_id)->get();

            $last_terms_id = ExamTermModel::where("academic_year", $acyear_id)
                ->orderBy("order", "desc")
                ->first()->id;

            // get exact that day added distribution

            $distribution = MarkModel::where([
                "academic_year" => $acyear_id,
                "term_id" => $term_id,
                "student_id" => $student_id,
                // "exam_type" => $exam_type,
            ])->first();

            $distribution = $distribution ? $distribution->distribution : [];

            // cumulative work

            $mark_into = MarkModel::with("subject")
                ->where([
                    "academic_year" => $acyear_id,
                    "term_id" => $term_id,
                    "student_id" => $student_id,
                    // "exam_type" => $exam_type,
                ])
                ->get();

            $mark_data = [];
            $ctotal = 0;
            $marks = [];
            foreach ($mark_into as $mark) {
                foreach ($terms as $term) {
                    if (!isset($mark_data[$mark->subject_id])) {
                        $mark_data[$mark->subject_id] = new \stdClass();
                    }

                    if (!isset($mark_data[$mark->subject_id]->{$term->id})) {
                        $mark_data[
                            $mark->subject_id
                        ]->{$term->id} = new \stdClass();
                    }

                    # code...
                    $mark_data[$mark->subject_id]->{$term->id}->name =
                        $term->exam_term_name;
                    $markdata = MarkModel::where([
                        "academic_year" => $acyear_id,
                        "term_id" => $term->id,
                        "student_id" => $mark->student_id,
                        "exam_type" => $mark->exam_type,
                        "subject_id" => $mark->subject_id,
                    ])->first();
                    $total = $markdata ? $markdata->total_mark : 0;
                    $ctotal = $ctotal + $total;
                    $mark_data[$mark->subject_id]->{$term->id}->mark = $markdata
                        ? $markdata->total_mark
                        : 0;

                    $mark_data[$mark->subject_id]->total = $ctotal;
                    $mark_data[$mark->subject_id]->avg = $ctotal / 3;
                    [$grade, $point, $note] = $this->Getgradefrommark($ctotal);
                    $mark_data[$mark->subject_id]->grade = $grade;
                    $mark_data[$mark->subject_id]->point = $point;
                    $mark_data[$mark->subject_id]->note = $note;
                }
                $ctotal = 0;
                $marks[] = $mark->total_mark;
            }

            $mark_exam = $mark_into->pluck("exam_id");

            //return $mark_exam;
            // dd($marks);
            // get total mark obtainable
            $total_obtainable = count($marks) * 100;
            // dd($total_obtainable, $mark_into);
            $total_obtain = array_sum($marks);

            // average mark

            $Average =
                $total_obtain != 0 && $total_obtainable != 0
                    ? round(($total_obtain / $total_obtainable) * 100)
                    : 0;

            // report information

            $reportinfo = MarkReportModel::where([
                "academic_year" => $acyear_id,
                "term_id" => $term_id,
                "student_id" => $student_id,
            ])->first();

            // cumulative

            $cumulative = MarkReportModel::where([
                "academic_year" => $acyear_id,
                "student_id" => $student_id,
            ]);

            $cumulative_count = $cumulative->count();
            $cumulative_sum = $cumulative->sum("average")
                ? $cumulative->sum("average")
                : 0;

            $cumulative_total_avg =
                $cumulative_count == 0
                    ? 0
                    : $cumulative_sum / $cumulative_count;

            $is_check = $type == "edit" ? false : true;
            $is_not_check = $reportinfo ? true : false;

            $class_student_ids = StudentsModel::where([
                "academic_year" => $acyear_id,
                "class_id" => $student_info->class_id,
                "section_id" => $student_info->section_id,
                "status" => 1,
            ])
                ->whereNull("deleted_at")
                ->pluck("id");
            $position =
                MarkReportModel::where([
                    "academic_year" => $acyear_id,
                    "term_id" => $term_id,
                ])
                    ->whereIn("student_id", $class_student_ids)
                    ->where("student_id", "=", $student_info->id)
                    ->where("total_mark_obtain", ">", $total_obtain)
                    ->count() + 1;
            $grade_info = Configurations::getGradeInfo();
            $least_grade = $grade_info->last()->grade_name;
            // dd($least_grade, $grade_info);
            $stud_grade = MarkModel::with("subject")
                ->where([
                    "academic_year" => $acyear_id,
                    "term_id" => $term_id,
                    "student_id" => $student_info->id,
                    "grade" => $least_grade,
                ])
                ->first();
            $status = $stud_grade ? "Failed" : "Passed";

            $view = view("report::admin.report.mark.getmarkreportview", [
                "config" => $config,
                "student_info" => $student_info,
                "selected_term" => $selected_term,
                "acyear" => $acyear,
                "distribution" => $distribution ? $distribution : null,
                "mark_into" => $mark_into,
                "type" => $type,
                "student_id" => $student_id,
                "term_id" => $term_id,
                "acyear_id" => $acyear_id,
                "exam_type" => $exam_type,
                "reportinfo" => $reportinfo ? $reportinfo : null,
                "total_obtain" => $total_obtain,
                // "is_promotion" => $is_promotion,
                "total_obtainable" => $total_obtainable,
                "Average" => $Average,
                "terms" => $terms,
                "mark_data" => $mark_data,
                "cumulative_total_avg" => $cumulative_total_avg,
                "is_reportinfo" => $type == "edit" ? $is_check : $is_not_check,
                "last_terms_id" => $last_terms_id,
                "position" => Configurations::ordinal($position),
                "status" => $status,
            ])->render();

            return response()->json([
                "view" => $view,
                "reportinfo" => $reportinfo ? true : false,
            ]);
        }
        return view("report::admin.report.mark.getmarkreport", [
            "sections" => [],
            "examtypes" => [],

            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
            "examterms" => $examterms,
            "exam_types" => $exam_types,
            "class_lists" => $class_lists,
            "current_academic_term" => $current_academic_term,
            "subject_lists" => [],
            "section_lists" => [],
            "students" => $students,
            "layout" => "create",
            "active_student" => $info ? $info->id : null,
        ]);
    }

    public function savereport(Request $request)
    {
        try {
            DB::beginTransaction();

            // chack previous
            $data = $request->all();

            unset($data["_token"]);
            $exists = MarkReportModel::where([
                "academic_year" => $request->academic_year,
                "exam_type" => $request->exam_type,
                "term_id" => $request->term_id,
                "student_id" => $request->student_id,
            ])->first();

            if ($exists) {
                $exists->update($data);
            } else {
                MarkReportModel::create($data);
            }

            DB::commit();

            //  dd("done");

            return redirect()
                ->route("Getmarkreport")
                ->with("success", "report generated successfully");
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
        }
    }

    public function broadsheet(Request $request)
    {
        $info = Configurations::getAcademicandTermsInfo();

        $academicyears = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $examterms = Configurations::getCurentAcademicTerms();

        $exam_types = ExamTypeModel::where("status", 1)
            ->whereNull("deleted_at")
            ->where("is_promotion", 1)
            ->pluck("exam_type_name", "id")
            ->toArray();

        if ($request->ajax()) {
            $class_id = $request->query->get("class_id", 2);
            $section_id = $request->query->get("section_id", 4);
            $acyear = $request->query->get("acyear", 3);
            $term = $request->query->get("term", 2);
            $exam_type = $request->query->get("exam_type", 1);

            $students = StudentsModel::where([
                "class_id" => $class_id,
                "section_id" => $section_id,
                "status" => 1,
            ])->get();

            $subjects = SubjectModel::where("class_id", $class_id)->get();

            //dd($subjects);

            $student_data = [];
            $ctotal = 0;

            foreach ($students as $student) {
                # code...

                if (!isset($student_data[$student->id])) {
                    $student_data[$student->id] = new \stdClass();
                    $student_data[$student->id]->student_name =
                        $student->first_name . " " . $student->last_name;
                    $student_data[$student->id]->admission_no =
                        $student->reg_no;
                    $mark = MarkModel::with([
                        "subject" => function ($q) {
                            $q->select("id", "name");
                        },
                    ])
                        ->where([
                            "academic_year" => $acyear,
                            "term_id" => $term,
                            "student_id" => $student->id,
                            "exam_type" => $exam_type,
                        ])
                        ->get();
                    $student_data[$student->id]->mark_entry = new \stdClass();

                    foreach ($subjects as $subject) {
                        if (
                            !isset(
                                $student_data[$student->id]->mark_entry
                                    ->{$subject->id}
                            )
                        ) {
                            $student_data[
                                $student->id
                            ]->mark_entry->{$subject->id} = new \stdClass();

                            $markdata = MarkModel::where([
                                "academic_year" => $acyear,
                                "term_id" => $term,
                                "student_id" => $student->id,
                                "exam_type" => $exam_type,
                                "subject_id" => $subject->id,
                            ])->first();
                            $total = $markdata ? $markdata->total_mark : 0;
                            $ctotal = $ctotal + $total;
                            $student_data[
                                $student->id
                            ]->mark_entry->{$subject->id}->subject_total_mark = $total;
                        }
                    }

                    $student_data[$student->id]->total_subjects = sizeof(
                        $subjects
                    );
                    $student_data[$student->id]->total_mark_obtainable =
                        sizeof($subjects) * 100;
                    $student_data[$student->id]->total_mark_obtain = $ctotal;
                    $total_obtainable = sizeof($subjects) * 100;
                    $Average =
                        $ctotal != 0
                            ? round(($ctotal / $total_obtainable) * 100)
                            : 0;
                    $student_data[$student->id]->avg = $Average;
                    $student_data[$student->id]->percentage =
                        round($Average) . "%";
                    $student_data[$student->id]->status = $this->avgBasedStatus(
                        round($Average)
                    );
                }
                $ctotal = 0;
            }

            //dd($student_data, sizeof($subjects));
            $term_name = ExamTermModel::find($term)->exam_term_name;
            $acyear_name = AcademicyearModel::find($acyear)->year;
            $exam_type_name = ExamTypeModel::find($exam_type)->exam_type_name;

            $class = LclassModel::find($class_id)->name;
            $section = SectionModel::find($section_id)->name;

            $view = view("report::admin.report.mark.broadsheetstudents", [
                "student_data" => $student_data,
                "subjects" => $subjects,
                "term_name" => $term_name,
                "acyear_name" => $acyear_name,
                "classsection" => $class . " " . $section,
                "exam_type_name" => $exam_type_name,
            ])->render();

            return response()->json(["view" => $view]);
        }
        return view("report::admin.report.mark.broadsheetreport", [
            "layout" => "create",
            "class_lists" => $class_lists,
            "sections" => [],
            "examterms" => $examterms,
            "students" => [],
            "academic_years" => $academicyears,
            "info" => $info,
            "exam_types" => $exam_types,
            "current_academic_year" => $current_academic_year,
            "current_academic_term" => $current_academic_term,
        ]);
    }

    public function dompdf()
    {
        //return view("report::admin.report.mark.idcard");
        $pdf = Pdf::loadView("report::admin.report.mark.idcard");
        return $pdf->download("invoice.pdf");
    }
}
