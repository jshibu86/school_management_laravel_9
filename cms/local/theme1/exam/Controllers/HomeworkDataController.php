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
use cms\exam\Models\OnlineExamModel;
use cms\lclass\Models\LclassModel;
use cms\department\Models\DepartmentModel;
use cms\core\configurations\Traits\FileUploadTrait;
use Illuminate\Support\Facades\URL;
use Session;
use DB;
use User;
use CGate;
use Configurations;
use cms\homework\Models\HomeworkModel;
use cms\exam\Models\OfflineExamMarkEntry;
use cms\homework\Models\HomeworkSubmissionModel;

class HomeworkDataController extends Controller
{
    use FileUploadTrait;
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
        return view("exam::admin.homework.index");
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
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new ExamModel())->getTable() .
                ",name",
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);
        $obj = new ExamModel();
        $obj->name = $request->name;
        $obj->desc = $request->desc;
        $obj->status = $request->status;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("exam.index");
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
        $exam = ExamModel::with("class", "section")
            ->where("id", $id)
            ->first();

        //dd($homework);
        if ($exam) {
            $info = ExamQuestionModel::where("exam_id", $id)->get();
            return view("exam::admin.homework.homeworksubmit", [
                "homework" => $exam,
                "info" => $info,
                "layout" => "create",
            ]);
        } else {
            Session::flash("error", "Homework Not Found");
            return redirect()->route("homework.index");
        }
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
                (new ExamModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);
        $obj = ExamModel::find($id);
        $obj->name = $request->name;
        $obj->desc = $request->desc;
        $obj->status = $request->status;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("exam.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_exam)) {
            $delObj = new ExamModel();
            foreach ($request->selected_exam as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("exam.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-exam");
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
            "subject.name as subject_name",
            "academicyear.year as acyear",
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
            ->where("type_of_exam", "Homework")
            ->leftJoin("subject", "subject.id", "=", "exam.subject_id")
            ->leftJoin(
                "academicyear",
                "academicyear.id",
                "=",
                "exam.academic_year"
            )
            ->where("exam.status", "=", 1);

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
            // ->addColumn("acyear", function ($data) {
            //     return AcademicyearModel::where(
            //         "id",
            //         $data->academic_year
            //     )->first()->year;
            // })
            ->addColumn("exam_type_column", function ($data) {
                return ExamTypeModel::where(
                    "id",
                    $data->exam_type
                )->first()->exam_type_name;
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
                    "subroute" => "Homework",
                ])->render();
            })

            ->addColumn("action", function ($data) {
                $student = StudentsModel::where(
                    "user_id",
                    User::getUser()->id
                )->first();
                $is_exists = HomeworkSubmissionModel::where([
                    "homework_id" => $data->id,
                    "student_id" => $student->id,
                ])->first();

                $subdate = Carbon::parse($data->exam_date)->format("Y-m-d");
                $subtime = Carbon::parse($data->exam_time)->format("H:i:s");
                $datetime = $subdate . " " . $subtime;

                // $datetime = "2023-05-03 13:25:00";

                $expiration = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    $datetime
                );

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

                // Get current time
                $now = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    now(Configurations::getConfig("site")->time_zone)
                );

                if ($is_exists) {
                    return "<span class='badge bg-success p-2' style='width:80px;'>" .
                        "Submited" .
                        "</span>" .
                        "<a href='" .
                        route("homework_submit_view", [
                            "id" => $student->id,
                            "exam_id" => $data->id,
                        ]) .
                        "' class='btn btn-default view_fees_unpaid' data-toggle='modal' data-student-id='$student->id'><i class='fa fa-eye'></i></button>";
                } else {
                    if ($examEnd < $now) {
                        return "<span class='p-2 badge bg-danger' style='width:80px;'>" .
                            "Expired" .
                            "</span>";
                    } else {
                        if ($now->between($examStart, $examEnd)) {
                            return view("layout::datatable.homework", [
                                "url" => URL::signedRoute(
                                    "homeworkdata.edit",
                                    $data->id
                                ),
                            ])->render();
                        } else {
                            if ($expiration->isFuture()) {
                                $displayStart = $examStart->format(
                                    'F j, Y \a\t g:i A'
                                );
                                return "<span class='p-2 badge bg-secondary' style='width:80px;'>" .
                                    "pending" .
                                    "</span>";
                            } else {
                                return "<span class='text-info'>" .
                                    "Homework Started" .
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
                "homework_status",
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
        CGate::authorize("edit-exam");

        if (!empty($request->selected_exam)) {
            $obj = new ExamModel();
            foreach ($request->selected_exam as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function HomeworkSubmit(Request $request)
    {
        // dd($request->all());
        $this->validate(
            $request,
            [
                "attachment" => "max:4000|mimes:pdf,jpg,jpeg,docx,png",
            ],
            [
                "attachment.mimes" =>
                    "The attachment must be a PDF, JPG, JPEG, or DOCX file.",
            ]
        );

        //    dd($request->all());
        $homework = ExamModel::where([
            "id" => $request->homework_id,
            "type_of_exam" => "Homework",
        ])->first();

        if (Session::get("ACTIVE_GROUP") == "Student") {
            $active_student = Configurations::Activestudent();

            if ($homework) {
                // CHECKING THIS HOMEWORK Already submitted this students
                // dd("it enter");
                $is_submitted = HomeworkSubmissionModel::where([
                    "homework_id" => $request->homework_id,
                    "student_id" => $active_student->id,
                    "subject_id" => $request->subject_id,
                ])->first();
                $date = Carbon::now(
                    Configurations::getConfig("site")->time_zone
                )->toDateString();
                $time = Carbon::now(
                    Configurations::getConfig("site")->time_zone
                )->toTimeString();

                if ($is_submitted) {
                    // Updated Submitted count
                    return redirect()
                        ->back()
                        ->with("error", "Already Submitted");
                } else {
                    // new homework submiited this student

                    $data = $request->all();

                    unset($data["_token"]);
                    unset($data["submit_cat"]);

                    $data["count"] = 1;
                    $data["student_id"] = $active_student->id;
                    $data["homework_status"] = 0;
                    $data["submitted_date"] = $date;
                    $data["submitted_time"] = $time;

                    if ($request->attachment) {
                        $data["attachment"] = $this->uploadAttachment(
                            $request->attachment,
                            null,
                            "school/homework/"
                        );
                    }
                    HomeworkSubmissionModel::create($data);

                    return redirect()
                        ->route("homework_index")
                        ->with("success", "Homework Submitted");
                }
            }
        } else {
            return redirect()
                ->back()
                ->with("error", "No Access");
        }
    }

    public function HomeworkEvaluate(Request $request)
    {
        $student_id = $request->query("id", 0);
        $exam_id = $request->query("exam_id", 0);
        if ($student_id !== 0 && $exam_id !== 0) {
            $exam = ExamModel::with("class", "section")
                ->where("id", $exam_id)
                ->first();
            $answer = HomeworkSubmissionModel::where([
                "student_id" => $student_id,
                "homework_id" => $exam_id,
            ])->first();
            //dd($homework);
            if ($exam) {
                $info = ExamQuestionModel::where("exam_id", $exam_id)->get();
                $view = view("exam::admin.homework.evaluate_homework_model", [
                    "homework" => $exam,
                    "info" => $info,
                    "answer" => $answer,
                    "student_id" => $student_id,
                    "layout" => "create",
                ])->render();

                return response()->json([
                    "view" => $view,
                    "homework" => $exam,
                    "info" => $info,
                    "exam_id" => $exam_id,
                    "answer" => $answer,
                    "student_id" => $student_id,
                ]);
            }
        }
    }

    public function EvaluateHomework(Request $request)
    {
        //dd($request->all());
        $evaluate = HomeworkSubmissionModel::where([
            "homework_id" => $request->homework_id,
            "student_id" => $request->student_id,
            "subject_id" => $request->subject_id,
        ])->first();
        if ($evaluate) {
            $evaluate->evaluated = 1;
            $evaluate->teacher_remark = $request->teacher_remark;
            $evaluate->save();
        }
        Session::flash("success", "Evaluated successfully");
        return redirect()->back();
    }
    public function HomeworkSubmitView(Request $request, $id, $exam_id)
    {
        // dd($id,$exam_id);

        $student_id = $id;
        $exam_id = $exam_id;
        if ($student_id !== 0 && $exam_id !== 0) {
            $exam = ExamModel::with("class", "section")
                ->where("id", $exam_id)
                ->first();
            $answer = HomeworkSubmissionModel::where([
                "student_id" => $student_id,
                "homework_id" => $exam_id,
            ])->first();
            //dd($homework);
            if ($exam) {
                $info = ExamQuestionModel::where("exam_id", $exam_id)->get();
                return view("exam::admin.homework.student_homework_view", [
                    "homework" => $exam,
                    "info" => $info,
                    "answer" => $answer,
                    "student_id" => $student_id,
                    "layout" => "create",
                ]);
            }
        }
    }
}
