<?php

namespace cms\exam\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\exam\Models\OnlineExamSubmissionModel;
use cms\exam\Models\ExamQuestionModel;
use cms\exam\Models\ExamSectionModel;
use cms\exam\Models\ExamModel;
use cms\exam\Models\ExamTypeModel;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use cms\academicyear\Models\AcademicyearModel;
use cms\section\Models\SectionModel;
use cms\students\Models\StudentsModel;
use cms\subject\Models\SubjectModel;
use cms\lclass\Models\LclassModel;
use Illuminate\Support\Facades\URL;
use Session;
use DB;
use User;
use CGate;
use Configurations;
use cms\exam\Models\OnlineExamModel;

class OnlineExamController extends Controller
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
        return view("exam::admin.onlineexam.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            // case 1:getting all exam question corresponding exam
            $student_id = StudentsModel::where(
                "user_id",
                User::getUser()->id
            )->first()->id;

            $exam = ExamModel::find($request->exam_id);

            $exam_questions_all = ExamQuestionModel::where(
                "exam_id",
                $request->exam_id
            );

            $exam_questions = $exam_questions_all->get();

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
            $onlineexam->student_id = $student_id;
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
                            "student_id" => $mark->student_id,
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

            //dd($answerdquestions, $request->all());

            DB::commit();
            $url = URL::temporarySignedRoute(
                "onlineexam.results",
                now()->addMinutes(10),
                [
                    "examid" => $request->exam_id,
                    "studentid" => $student_id,
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
            //dd($e);
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
        if (Session::get("ACTIVE_GROUP") == "Student") {
            $student = StudentsModel::where(
                "user_id",
                User::getUser()->id
            )->first();

            //    check student already submit exam

            $is_exists = OnlineExamModel::where([
                "exam_id" => $id,
                "student_id" => $student->id,
            ])->first();

            if ($is_exists) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "You are Already Submiited This Exam"
                    );
            }
        }

        $exam_data = ExamModel::with("class", "section", "subject")->find($id);

        $data = ExamQuestionModel::with("subquestion")
            ->where("exam_id", $id)
            ->get();

        $hours = str_replace("hr", "", explode(":", $exam_data->timeline)[0]); // replace with the number of hours
        $minutes = str_replace(
            "min",
            "",
            explode(":", $exam_data->timeline)[1]
        ); // replace with the number of minutes

        $totalMinutes = $hours * 60 + $minutes;
        // dd( $totalMinutes);
        return view("exam::admin.onlineexam.show", [
            "questions" => $data,
            "exam" => $exam_data,
            "totalMinutes" => $totalMinutes,
        ]);
        // dd($id);
    }

    public function onlineexamResults(Request $request, $examid, $studentid)
    {
        //dd("here");

        $exam = ExamModel::with("class", "section", "subject")->find($examid);

        $student = StudentsModel::with("user")->find($studentid);

        $onlineexam = OnlineExamModel::where([
            "exam_id" => $examid,
            "student_id" => $studentid,
        ])->first();

        $submission = OnlineExamSubmissionModel::where(
            "online_exam_id",
            $onlineexam->id
        )
            ->where("is_correct", 1)
            ->sum("mark");
        //dd(intval($submission));
        return view("exam::admin.onlineexam.results", [
            "exam" => $exam,
            "student" => $student,
            "onlineexam" => $onlineexam,
            "submission" => intval($submission),
        ]);
    }

    public function mandatoryclosure(Request $request)
    {
        if ($request->isMethod("post")) {
            //dd($request->all());
        }
        return view("exam::admin.onlineexam.mandatoryclosure");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_1)) {
            $delObj = new OnlineExamSubmissionModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new OnlineExamSubmissionModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("1.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        //dd($request);
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
            "exam.exam_date",
            "exam.exam_time",
            "exam.class_id",
            "exam.section_id",
            "exam.subject_id",
            "exam.exam_submission_date",
            "exam.exam_submission_time",
            "academicyear.year as year",
            "exam_type.exam_type_name as type_name",
            "subject.name as subject_name",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ExamModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ExamModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )

            ->leftJoin(
                "academicyear",
                "academicyear.id",
                "=",
                "exam.academic_year"
            )
            ->leftJoin("exam_type", "exam_type.id", "=", "exam.exam_type")
            ->leftJoin("subject", "subject.id", "=", "exam.subject_id")
            ->where("type_of_exam", "Online")
            ->where("exam.status", "!=", -1);

        if (Session::get("ACTIVE_GROUP") == "Student") {
            $student = StudentsModel::where(
                "user_id",
                User::getUser()->id
            )->first();

            $data = $data
                ->where("exam.class_id", $student->class_id)
                ->where("exam.Section_id", $student->section_id)
                ->where("exam.academic_year", $student->academic_year)
                ->where("exam.status", "=", 1);
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
            // ->addColumn("subject", function ($data) {
            //     return SubjectModel::where(
            //         "id",
            //         $data->subject_id
            //     )->first()->name;
            // })
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
                    "subroute" => "onlineexam",
                ])->render();
            })
            ->addColumn("action", function ($data) {
                if (Session::get("ACTIVE_GROUP") == "Student") {
                    $student = StudentsModel::where(
                        "user_id",
                        User::getUser()->id
                    )->first();

                    //    check student already submit exam

                    $is_exists = OnlineExamModel::where([
                        "exam_id" => $data->id,
                        "student_id" => $student->id,
                    ])->first();
                } else {
                    $is_exists = false;
                }
                $subdate = Carbon::parse($data->exam_date)->format("Y-m-d");
                $subtime = Carbon::parse($data->exam_time)->format("H:i:s");
                $datetime = $subdate . " " . $subtime;

                // $datetime = "2023-05-03 13:25:00";

                // Convert to Carbon instance
                $examStart = Carbon::createFromFormat("Y-m-d H:i:s", $datetime);

                // Add 10 minutes
                if (
                    $data->exam_submission_date !== null &&
                    $data->exam_submission_time !== null
                ) {
                    $enddate = Carbon::parse(
                        $data->exam_submission_date
                    )->format("Y-m-d");
                    $endtime = Carbon::parse(
                        $data->exam_submission_time
                    )->format("H:i:s");
                    $enddatetime = $enddate . " " . $endtime;
                    $examEnd = Carbon::createFromFormat(
                        "Y-m-d H:i:s",
                        $enddatetime
                    );
                } else {
                    $examEnd = $examStart->copy()->addMinutes(30);
                }
                $expiration = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    $datetime
                );

                // Get current time
                $now = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    now(Configurations::getConfig("site")->time_zone)
                );

                // return view("layout::datatable.onlinexam", [
                //     "url" => URL::signedRoute("onlineexam.show", [
                //         "id" => $data->id,
                //     ]),
                // ])->render();
                if ($is_exists) {
                    return "<span class='text-success'>" .
                        "Already Completed" .
                        "</span>";
                } else {
                    if ($examEnd < $now) {
                        return "<span class='text-danger'>" .
                            "Exam Expired" .
                            "</span>";
                    } else {
                        if ($now->between($examStart, $examEnd)) {
                            return view("layout::datatable.onlinexam", [
                                "url" => URL::signedRoute(
                                    "onlineexam.show",
                                    $data->id
                                ),
                            ])->render();
                        } else {
                            if ($expiration->isFuture()) {
                                $displayStart = $examStart->format(
                                    'F j, Y \a\t g:i A'
                                );
                                return "<span class='text-info'>" .
                                    "Exam Start " .
                                    $displayStart .
                                    "</span>";
                            } else {
                                return "<span class='text-info'>" .
                                    "Exam Started" .
                                    "</span>";
                            }
                        }
                    }
                }
            });

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
    public function getQuizData(Request $request)
    {
        //dd($request);
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
            "exam.exam_date",
            "exam.exam_time",
            "exam.class_id",
            "exam.section_id",
            "exam.subject_id",
            "exam.exam_submission_date",
            "exam.exam_submission_time",
            "academicyear.year as year",
            "exam_type.exam_type_name as type_name",
            "subject.name as subject_name",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ExamModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ExamModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )

            ->leftJoin(
                "academicyear",
                "academicyear.id",
                "=",
                "exam.academic_year"
            )
            ->leftJoin("exam_type", "exam_type.id", "=", "exam.exam_type")
            ->leftJoin("subject", "subject.id", "=", "exam.subject_id")
            ->Where("type_of_exam", "Quiz")
            ->where("exam.status", "!=", -1);

        if (Session::get("ACTIVE_GROUP") == "Student") {
            $student = StudentsModel::where(
                "user_id",
                User::getUser()->id
            )->first();

            $data = $data
                ->where("exam.class_id", $student->class_id)
                ->where("exam.Section_id", $student->section_id);
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
            // ->addColumn("subject", function ($data) {
            //     return SubjectModel::where(
            //         "id",
            //         $data->subject_id
            //     )->first()->name;
            // })
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
                    "subroute" => "onlineexam",
                ])->render();
            })
            ->addColumn("action", function ($data) {
                if (Session::get("ACTIVE_GROUP") == "Student") {
                    $student = StudentsModel::where(
                        "user_id",
                        User::getUser()->id
                    )->first();

                    //    check student already submit exam

                    $is_exists = OnlineExamModel::where([
                        "exam_id" => $data->id,
                        "student_id" => $student->id,
                    ])->first();
                } else {
                    $is_exists = false;
                }
                $subdate = Carbon::parse($data->exam_date)->format("Y-m-d");
                $subtime = Carbon::parse($data->exam_time)->format("H:i:s");
                $datetime = $subdate . " " . $subtime;

                // $datetime = "2023-05-03 13:25:00";

                // Convert to Carbon instance
                $examStart = Carbon::createFromFormat("Y-m-d H:i:s", $datetime);

                // Add 10 minutes
                if (
                    $data->exam_submission_date !== null &&
                    $data->exam_submission_time !== null
                ) {
                    $enddate = Carbon::parse(
                        $data->exam_submission_date
                    )->format("Y-m-d");
                    $endtime = Carbon::parse(
                        $data->exam_submission_time
                    )->format("H:i:s");
                    $enddatetime = $enddate . " " . $endtime;
                    $examEnd = Carbon::createFromFormat(
                        "Y-m-d H:i:s",
                        $enddatetime
                    );
                } else {
                    $examEnd = $examStart->copy()->addMinutes(30);
                }
                $expiration = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    $datetime
                );

                // Get current time
                $now = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    now(Configurations::getConfig("site")->time_zone)
                );

                // return view("layout::datatable.onlinexam", [
                //     "url" => URL::signedRoute("onlineexam.show", [
                //         "id" => $data->id,
                //     ]),
                // ])->render();
                if ($is_exists) {
                    return "<span class='text-success'>" .
                        "Already Completed" .
                        "</span>";
                } else {
                    if ($examEnd < $now) {
                        return "<span class='text-danger'>" .
                            "Exam Expired" .
                            "</span>";
                    } else {
                        if ($now->between($examStart, $examEnd)) {
                            return view("layout::datatable.onlinexam", [
                                "url" => URL::signedRoute(
                                    "onlineexam.show",
                                    $data->id
                                ),
                            ])->render();
                        } else {
                            if ($expiration->isFuture()) {
                                $displayStart = $examStart->format(
                                    'F j, Y \a\t g:i A'
                                );
                                return "<span class='text-info'>" .
                                    "Exam Start " .
                                    $displayStart .
                                    "</span>";
                            } else {
                                return "<span class='text-info'>" .
                                    "Exam Started" .
                                    "</span>";
                            }
                        }
                    }
                }
            });

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
