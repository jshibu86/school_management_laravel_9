<?php

namespace cms\exam\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\exam\Models\ExamModel;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use cms\core\configurations\Traits\FileUploadTrait;
use Illuminate\Support\Facades\URL;
use cms\academicyear\Models\AcademicyearModel;
use cms\section\Models\SectionModel;
use cms\students\Models\StudentsModel;
use cms\subject\Models\SubjectModel;
use cms\exam\Models\OnlineExamModel;
use cms\lclass\Models\LclassModel;
use cms\department\Models\DepartmentModel;
use cms\exam\Models\ExamTypeModel;
use cms\homework\Models\HomeworkModel;
use cms\exam\Models\OfflineExamMarkEntry;
use cms\homework\Models\HomeworkSubmissionModel;
use cms\admission\Models\AdmissionModel;
use cms\exam\Models\ExamQuestionModel;
use cms\exam\Models\OnlineExamSubmissionModel;
use cms\exam\Models\ExamNotificationModel;
use cms\exam\Models\ExamTermModel;
use cms\exam\Models\ExamSectionModel;
use Session;
use DB;
use User;
use CGate;
use Configurations;

class AdmisssionExamController extends Controller
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
        return view("exam::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("exam::admin.edit", ["layout" => "create"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //  dd($request->all());
        $admission_id = $request->admission_id;
        //dd($admission_id);
        DB::beginTransaction();
        try {
            // case 1:getting all exam question corresponding exam
            // $student_id = StudentsModel::where(
            //     "user_id",
            //     User::getUser()->id
            // )->first()->id;

            $student_data = AdmissionModel::where("id", $admission_id)->first();
            //dd($student_data);
            $exam = ExamModel::find($request->exam_id);

            $exam_questions_all = ExamQuestionModel::where(
                "exam_id",
                $request->exam_id
            );

            $exam_questions = $exam_questions_all->get();
            // dd($exam_questions);
            $answerdquestions = [];

            foreach ($exam_questions as $key => $questions) {
                foreach ($request->questions as $questionid => $rquestions) {
                    if (
                        $questions->id == $questionid &&
                        $rquestions["answer"] != null
                    ) {
                        if (
                            strtolower($questions->answer) ===
                            strtolower($rquestions["answer"])
                        ) {
                            $answer = true;
                        } else {
                            $answer = false;
                        }

                        $answerdquestions[] = [
                            "question_id" => $questions->id,
                            "is_answercorrect" => $answer,
                            "correct_answer" => $questions->answer,
                            "your_answer" => $rquestions["answer"],
                            "mark" => $questions->mark,
                        ];
                    }
                }
            }

            // to save examinformation
            if (sizeof($answerdquestions)) {
                $correctquestions = array_filter($answerdquestions, function (
                    $questions
                ) {
                    return $questions["is_answercorrect"] == true;
                });
            } else {
                $correctquestions = [];
            }
            $correctquestion = collect($correctquestions);
            $date = Carbon::now(
                Configurations::getConfig("site")->time_zone
            )->toDateString();
            $time = Carbon::now(
                Configurations::getConfig("site")->time_zone
            )->toTimeString();

            $onlineexam = new OnlineExamModel();
            $onlineexam->exam_id = $request->exam_id;
            $onlineexam->academic_year = $exam->academic_year;
            $onlineexam->admission_id = $admission_id;
            $onlineexam->submit_date = $date;
            $onlineexam->submit_time = $time;
            $onlineexam->total_questions = $exam_questions_all->count();
            $onlineexam->total_answered = sizeof($answerdquestions);
            $onlineexam->total_correct = sizeof($correctquestions);
            $onlineexam->total_marks = $correctquestion->sum("mark");

            if ($onlineexam->save()) {
                $marks = OnlineExamModel::where(
                    "exam_id",
                    $request->exam_id
                )->get();
                $score = $marks->sortByDesc("total_marks")->first();

                if ($score) {
                    foreach ($marks as $mark) {
                        // Get the rank of the current student
                        $rank =
                            $marks
                                ->where("total_marks", ">", $mark->total_marks)
                                ->count() + 1;

                        // Update the position for each student in the exam
                        $position = OnlineExamModel::where([
                            "exam_id" => $request->exam_id,
                            "admission_id" => $mark->admission_id,
                        ])->first();

                        if ($position) {
                            // Update the position for the current student
                            $position->position = $rank;
                            $position->save();
                        }
                    }
                } else {
                    $rank = null;
                }

                if (sizeof($answerdquestions)) {
                    foreach ($answerdquestions as $answerquestion) {
                        $answer = new OnlineExamSubmissionModel();
                        $answer->online_exam_id = $onlineexam->id;
                        $answer->question_id = $answerquestion["question_id"];
                        $answer->correct_answer =
                            $answerquestion["correct_answer"];
                        $answer->your_answer = $answerquestion["your_answer"];
                        $answer->mark = $answerquestion["mark"];

                        $answer->is_correct =
                            $answerquestion["is_answercorrect"];
                        $answer->save();
                    }
                }
            }

            // update the student admission status to pending after the exam get over
            // update student status as Pending if obtained score above min. marks else rejected status
            if ($onlineexam->total_marks >= $exam->min_mark) {
                $student_data->admission_status = "Pending";
                $student_data->save();
            } else {
                $student_data->admission_status = "Rejected";
                $student_data->save();
            }

            //dd($answerdquestions, $request->all());

            DB::commit();
            $url = URL::temporarySignedRoute(
                "admissionexam.results",
                now()->addMinutes(10),
                [
                    "examid" => $request->exam_id,
                    "admissionid" => $admission_id,
                ]
            );
            return redirect($url);
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            // dd($e);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("1.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("1.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $message = [];
        $examId = $request->query("exam_id");
        //dd($examId);

        //check the url is valid
        if (!URL::hasValidSignature($request)) {
            abort(403, "Invalid or Expired URLL");
        }

        $student_data = AdmissionModel::where("id", $id)->first();
        //dd($student_data->id);
        //$dataRecords["student_data"] = $student_data;
        //    check student already submit exam
        $is_exists = OnlineExamModel::where([
            "exam_id" => $examId,
            "admission_id" => $id,
        ])->first();

        // dd($is_exists);
        if ($is_exists) {
            $message = "You have already submitted the Exam";
            // return redirect()
            //     ->back()
            //     ->withInput()
            //     ->with(
            //         "exception_error",
            //         "You have already submitted the Exam"
            //     );

            return view("exam::admin.admissionexam.show", [
                "error" => $message,
            ]);
        }

        $exam_data = ExamModel::with("class", "section", "subject")->find(
            $examId
        );

        $data = ExamQuestionModel::with("subquestion")
            ->where("exam_id", $examId)
            ->get();

        $hours = str_replace("hr", "", explode(":", $exam_data->timeline)[0]); // replace with the number of hours
        $minutes = str_replace(
            "min",
            "",
            explode(":", $exam_data->timeline)[1]
        ); // replace with the number of minutes

        $totalMinutes = $hours * 60 + $minutes;
        // dd( $totalMinutes);
        return view("exam::admin.admissionexam.show", [
            "questions" => $data,
            "exam" => $exam_data,
            "student_data" => $student_data,
            "totalMinutes" => $totalMinutes,
        ]);
    }

    public function onlineexamResults(Request $request, $examid, $admissionid)
    {
        $exam = ExamModel::with("class", "section", "subject")->find($examid);

        $student_data = AdmissionModel::where("id", $admissionid)
            ->first()
            ->toArray();

        // $student = StudentsModel::with("user")->find($studentid);

        $onlineexam = OnlineExamModel::where([
            "exam_id" => $examid,
            "admission_id" => $admissionid,
        ])->first();

        $submission = OnlineExamSubmissionModel::where(
            "online_exam_id",
            $onlineexam->id
        )
            ->where("is_correct", 1)
            ->sum("mark");
        // dd(intval($submission));
        return view("exam::admin.onlineexam.results", [
            "exam" => $exam,
            "student_data" => $student_data,
            "onlineexam" => $onlineexam,
            "submission" => intval($submission),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type = null)
    {
        $data = ExamModel::with(
            "notification",
            "sections.questions.subquestion"
        )->find($id);
        //dd($data);
        $maxorder = ExamQuestionModel::where("exam_id", $data->id)->max(
            "order"
        );
        $maxsectionorder = ExamSectionModel::where("exam_id", $id)->max(
            "section_order"
        );

        // dd($maxsectionorder);
        $examterms = ExamTermModel::where("status", 1)
            ->where("academic_year", $data->academic_year)
            ->pluck("exam_term_name", "id")
            ->toArray();

        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $department = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();
        $exam_types = ExamTypeModel::where("status", 1)
            ->pluck("exam_type_name", "id")
            ->toArray();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $section_lists = SectionModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->where("class_id", $data->class_id)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $subject_lists = SubjectModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->where("class_id", $data->class_id)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $students_exclude = StudentsModel::where([
            "class_id" => $data->class_id,
            "section_id" => $data->section_id,
        ])
            ->where("status", 1)
            ->select([
                "students.id as id",
                DB::raw(
                    "CONCAT(students.username, ' - ', students.email) as text"
                ),
            ])
            ->pluck("text", "id")
            ->toArray();
        $students_include = StudentsModel::where("status", 1)
            ->where("class_id", "!=", $data->class_id)
            ->where("section_id", "!=", $data->section_id)
            ->select([
                "students.id as id",
                DB::raw(
                    "CONCAT(students.username, ' - ', students.email) as text"
                ),
            ])
            ->pluck("text", "id")
            ->toArray();

        //dd($data->exclude_students);

        return view("exam::admin.edit", [
            "layout" => $type == "duplicate" ? "create" : "edit",
            "data" => $data,
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
            "exam_types" => $exam_types,
            "class_lists" => $class_lists,
            "subject_lists" => $subject_lists,
            "section_lists" => $section_lists,

            "department" => $department,
            "include_students" => $students_include,
            "exclude_students" => $students_exclude,
            "maxorder" => $maxorder,
            "examterms" => $examterms,
            "maxsectionorder" => $maxsectionorder,
            "type" => $type,
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
        $examController = new ExamController();
        $examController->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        if (!empty($request->selected_exam)) {
            $delObj = new ExamModel();
            foreach ($request->selected_exam as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = ExamModel::find($id);

            $delObj->questions()->delete();

            $delObj->delete();
        }
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("exam.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        // CGate::authorize("view-exam");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ExamModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "exam.id",
            "exam.academic_year",
            "exam.exam_type",
            "exam.exam_title",
            "exam.exam_date",
            "exam.exam_time",
            "exam.class_id",
            "exam.section_id",
            "exam.subject_id",
            "exam.exam_submission_date",
            "exam.exam_submission_time",
            "subject.name as subject_name",
            "exam_type.exam_type_name as exam_type_column",
            "academicyear.year as acyear",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ExamModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ExamModel())->getTable() .
                    '.status = "-1" THEN "Trashed" ELSE "Enabled" END) AS status'
            )
        )
            ->where("exam.type_of_exam", "Admission Exam")
            ->leftJoin(
                "academicyear",
                "academicyear.id",
                "=",
                "exam.academic_year"
            )
            // ->leftJoin("exam_term", "exam_term.id", "=", "exam.exam_term")
            ->leftJoin("subject", "subject.id", "=", "exam.subject_id")
            ->leftJoin("exam_type", "exam_type.id", "=", "exam.exam_type");

        if (Session::get("ACTIVE_GROUP") == "Student") {
            $student = StudentsModel::where(
                "user_id",
                User::getUser()->id
            )->first();

            $data = $data
                ->where("class_id", $student->class_id)
                ->where("Section_id", $student->section_id)
                ->where("academic_year", $student->academic_year);
        }

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("examdatetime", function ($data) {
                $subdate = Carbon::parse($data->exam_date)->format("Y-m-d");
                $subtime = Carbon::parse($data->exam_time)->format("H:i:s");
                $time = Carbon::parse($data->exam_time)->format("g:i A");
                $datetime = $subdate . " " . $subtime;
                //return "<span class='text-danger'>" . $datetime . "</span>";
                $expiration = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    $datetime
                );
                if ($expiration->isPast()) {
                    return "<span class='text-danger'>" .
                        $subdate .
                        "-" .
                        $time .
                        "</span>";
                } else {
                    return "<span>" . $subdate . "-" . $time . "</span>";
                }
            })
            ->addColumn("examsubmissiondatetime", function ($data) {
                if (
                    $data->exam_submission_date &&
                    $data->exam_submission_time
                ) {
                    $subdate = Carbon::parse(
                        $data->exam_submission_date
                    )->format("Y-m-d");
                    $subtime = Carbon::parse(
                        $data->exam_submission_time
                    )->format("H:i:s");
                    $time = Carbon::parse($data->exam_submission_time)->format(
                        "g:i A"
                    );
                    $datetime = $subdate . " " . $subtime;
                    //return "<span class='text-danger'>" . $datetime . "</span>";
                    $expiration = Carbon::createFromFormat(
                        "Y-m-d H:i:s",
                        $datetime
                    );
                    if ($expiration->isPast()) {
                        return "<span class='text-danger'>" .
                            $subdate .
                            "-" .
                            $time .
                            "</span>";
                    } else {
                        return "<span>" . $subdate . "-" . $time . "</span>";
                    }
                } else {
                    return "N/A";
                }
            })

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

            ->addColumn("duplicateexam", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "exam",
                    "subroute" => "admissionexam",
                ])->render();
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "admissionexam",
                ])->render();
            });

        // ->addColumn("action", function ($data) {
        //     if (Session::get("ACTIVE_GROUP") == "Student")
        //     {
        //         $student = StudentsModel::where("user_id",  User::getUser()->id)->first();
        //         //    check student already submit exam
        //         $is_exists = OnlineExamModel::where(["exam_id" => $data->id, "student_id" => $student->id,])->first();
        //     } else {
        //         $is_exists = false;
        //     }
        //     $subdate = Carbon::parse($data->exam_date)->format("Y-m-d");
        //     $subtime = Carbon::parse($data->exam_time)->format("H:i:s");
        //     $datetime = $subdate . " " . $subtime;

        //     // $datetime = "2025-05-03 13:25:00";
        //     $expiration = Carbon::createFromFormat("Y-m-d H:i:s", $datetime);

        //     // Convert to Carbon instance
        //     $examStart = Carbon::createFromFormat("Y-m-d H:i:s", $datetime);

        //     // Add 10 minutes
        //     if($data->exam_submission_date !== null && $data->exam_submission_time !== null){
        //         $enddate = Carbon::parse($data->exam_submission_date)->format("Y-m-d");
        //         $endtime = Carbon::parse($data->exam_submission_time)->format("H:i:s");
        //         $enddatetime =  $enddate . " " . $endtime;
        //         $examEnd = Carbon::createFromFormat(  "Y-m-d H:i:s",  $enddatetime);
        //     }
        //     else{
        //         $examEnd = $examStart->copy()->addMinutes(30);
        //     }

        //     $expiration = Carbon::createFromFormat("Y-m-d H:i:s", $datetime);

        //     // Get current time
        //     $now = Carbon::createFromFormat("Y-m-d H:i:s",now(Configurations::getConfig("site")->time_zone));

        //     if($is_exists){

        //         return "<span class='badge bg-success p-2' style='width:80px;'>" . "Already Completed" .
        //             "</span>". "<a href='" . route('homework_submit_view', ['id' =>$student->id ,'exam_id'=>$data->id]) . "' class='btn btn-default view_fees_unpaid' data-toggle='modal' data-student-id='$student->id'><i class='fa fa-eye'></i></button>";
        //     }
        //     else{
        //         if ($examEnd < $now) {
        //             return "<span class='p-2 badge bg-danger' style='width:80px;'>" .   "Exam Expired" . "</span>";
        //         }
        //         else{
        //             if ($now->between($examStart, $examEnd)) {
        //                 return view("layout::datatable.onlinexam", [ "url" => URL::signedRoute("onlineexam.show", $data->id),])->render();
        //             }
        //             else {
        //                 if ($expiration->isFuture()) {
        //                     $displayStart = $examStart->format('F j, Y \a\t g:i A');
        //                     return "<span class='text-secondary' style='width:80px;'>" .  "Exam start". $displayStart."</span>";
        //                 } else {
        //                     return "<span class='text-info'>" . "Exam Started" .  "</span>";
        //                 }
        //             }
        //         }
        //     }
        //  })

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns([
                "examdatetime",
                "action",
                "is_finish",
                "duplicateexam",
                "examsubmissiondatetime",
            ])
            ->make(true);
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
            OnlineExamSubmissionModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new OnlineExamSubmissionModel();
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
