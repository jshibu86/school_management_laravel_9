<?php

namespace cms\homework\Controllers;

use DB;
use User;
use CGate;
use Session;
use stdClass;

use Carbon\Carbon;

use Configurations;
use Illuminate\Http\Request;
use cms\lclass\Models\LclassModel;
use App\Http\Controllers\Controller;
use cms\section\Models\SectionModel;
use cms\subject\Models\SubjectModel;
use cms\homework\Models\HomeworkModel;
use cms\students\Models\StudentsModel;
use Yajra\DataTables\Facades\DataTables;
use cms\department\Models\DepartmentModel;
use cms\homework\Models\HomeworkSubmissionModel;
use cms\core\configurations\Traits\FileUploadTrait;

class HomeworkController extends Controller
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $class_id = $request->query->get("class", 0);
            $section_id = $request->query->get("section", 0);
            $subject = $request->query->get("subject", 0);

            $subject_name = SubjectModel::subjectname($subject);

            $homework_lists = HomeworkModel::with([
                "submissions" => function ($query) {
                    $query->where(
                        "student_id",
                        Configurations::Activestudent()->id
                    );
                },
            ])
                ->where("class_id", $class_id)
                ->where("section_id", $section_id)
                ->where("subject_id", $subject)
                ->where("status", 1)
                ->get();
            $view = view("homework::admin.listhomework", [
                "homework_lists" => $homework_lists,
                "subject_name" => $subject_name,
            ])->render();

            return response()->json(["viewfile" => $view]);
        }

        $sections = [];
        $subjects = [];
        $user = User::getUser();
        $fillter = $request->query->get("fillter");
        if (Session::get("ACTIVE_GROUP") == "Teacher") {
            $class_list = LclassModel::where("status", "!=", -1)
                ->whereNull("deleted_at")
                ->pluck("name", "id")
                ->toArray();
        } else {
            $class_list = LclassModel::where("status", "!=", -1)
                ->whereNull("deleted_at")
                ->pluck("name", "id")
                ->toArray();
        }

        if ($fillter == "fillter") {
            $class_id = $request->query->get("class_id");
            $section_id = $request->query->get("section_id");
            $subject_id = $request->query->get("subject_id");

            if (!$class_id || !$section_id || !$subject_id) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", "Missing some values to select");
            } else {
                $subjects = SubjectModel::where("class_id", $class_id)
                    ->where("status", 1)
                    ->pluck("name", "id")
                    ->toArray();
                $sections = SectionModel::where("status", 1)
                    ->where("class_id", $class_id)
                    ->pluck("name", "id")
                    ->toArray();
                return view("homework::admin.index", [
                    "class_list" => $class_list,
                    "sections" => $sections,
                    "subjects" => $subjects,
                    "homeworks" => true,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "subject_id" => $subject_id,
                ]);
            }
            //dd("here");
        }
        if (Session::get("ACTIVE_GROUP") == "Student") {
            $active_student = Configurations::Activestudent();

            $subjects = SubjectModel::where(
                "class_id",
                $active_student->class_id
            )->get();

            // $homework_lists = HomeworkModel::where(
            //     "class_id",
            //     $active_student->class_id
            // )
            //     ->where("section_id", $active_student->section_id)
            //     ->where("status", 1)
            //     ->get();
        } else {
            $homework_lists = [];
        }

        // getting subject with homeworks

        $subject_list = [];
        $colors = Configurations::COLORS;

        foreach ($subjects as $subject) {
            if (!isset($subject_list[$active_student->class_id])) {
                $subject_list[$active_student->class_id] = new stdClass();
            }

            if (
                !isset(
                    $subject_list[$active_student->class_id]
                        ->{$active_student->section_id}
                )
            ) {
                $subject_list[
                    $active_student->class_id
                ]->{$active_student->section_id} = new stdClass();
            }

            if (
                !isset(
                    $subject_list[$active_student->class_id]
                        ->{$active_student->section_id}->subjects
                )
            ) {
                $subject_list[
                    $active_student->class_id
                ]->{$active_student->section_id}->subjects = new stdClass();
            }

            $subject_list[
                $active_student->class_id
            ]->{$active_student->section_id}->subjects->{$subject->id} = new stdClass();

            $lists = HomeworkModel::where([
                "class_id" => $active_student->class_id,
                "section_id" => $active_student->section_id,
                "subject_id" => $subject->id,
                "status" => 1,
            ])->pluck("id");

            $subject_list[
                $active_student->class_id
            ]->{$active_student->section_id}->subjects->{$subject->id}->homework = count(
                $lists
            );

            $lists_submit = HomeworkSubmissionModel::whereIn(
                "homework_id",
                $lists
            )
                ->where([
                    "student_id" => $active_student->id,
                    "subject_id" => $subject->id,
                    "status" => 1,
                ])
                ->pluck("id");

            $subject_list[
                $active_student->class_id
            ]->{$active_student->section_id}->subjects->{$subject->id}->homeworksubmissions = count(
                $lists_submit
            );
        }
        //dd($subject_list);
        // dd($fillter);
        return view("homework::admin.index", [
            "class_list" => $class_list,
            "sections" => $sections,
            "subjects" => $subjects,
            "homeworks" => false,
            "homework_lists" => $subject_list,
            "colors" => $colors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = [];
        $subjects = [];
        $departments = DepartmentModel::where("status", 1)

            ->pluck("dept_name", "id")
            ->toArray();
        $user = User::getUser();
        if (Session::get("ACTIVE_GROUP") == "Teacher") {
            $class_list = LclassModel::where("status", "!=", -1)
                ->whereNull("deleted_at")
                ->pluck("name", "id")
                ->toArray();
        } else {
            $class_list = LclassModel::where("status", "!=", -1)
                ->whereNull("deleted_at")
                ->pluck("name", "id")
                ->toArray();
        }

        return view("homework::admin.edit", [
            "layout" => "create",
            "class_list" => $class_list,
            "sections" => $sections,
            "subjects" => $subjects,
            "departments" => $departments,
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
        $this->validate(
            $request,
            [
                "title" => "required|min:3",
                "homework_date" => "required",
                "submission_date" => "required",
                "class_id" => "required",
                "section_id" => "required",
                "subject_id" => "required",
            ],
            ["title.min" => "Homework Title Greaterthan 3 characters"]
        );
        DB::beginTransaction();
        try {
            $obj = new HomeworkModel();
            $obj->title = $request->title;
            $obj->class_id = $request->class_id;
            $obj->user_id = User::getUser()->id;
            $obj->section_id = $request->section_id;
            $obj->subject_id = $request->subject_id;
            $obj->homework_description = $request->homework_description;
            $obj->homework_date = $request->homework_date;
            $obj->submission_date = $request->submission_date;
            $obj->dept_id = $request->dept_id;
            if ($request->attachments) {
                $obj->attachment = $this->uploadAttachment(
                    $request->attachments,
                    null,
                    "school/homework/"
                );
            }

            if ($obj->save()) {
                //send notifications to students

                //Created Notifications send to Admins
                $msg =
                    $obj->title .
                    " New Homework added by " .
                    User::getUser()->name;
                $notification = Configurations::sendNotification(
                    "homework",
                    $msg,
                    User::getUser()->id,
                    $obj->class_id
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("homework.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("homework.index");
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
        $data = HomeworkModel::find($id);
        $subjects = SubjectModel::where("class_id", $data->class_id)
            ->where("status", 1)
            ->pluck("name", "id")
            ->toArray();
        $sections = SectionModel::where("status", 1)
            ->where("class_id", $data->class_id)
            ->pluck("name", "id")
            ->toArray();
        $user = User::getUser();
        if (Session::get("ACTIVE_GROUP") == "Teacher") {
            $class_list = LclassModel::where("status", "!=", -1)
                ->whereNull("deleted_at")
                ->pluck("name", "id")
                ->toArray();
        } else {
            $class_list = LclassModel::where("status", "!=", -1)
                ->whereNull("deleted_at")
                ->pluck("name", "id")
                ->toArray();
        }
        $departments = DepartmentModel::where("status", 1)

            ->pluck("dept_name", "id")
            ->toArray();
        return view("homework::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "class_list" => $class_list,
            "sections" => $sections,
            "subjects" => $subjects,
            "departments" => $departments,
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
        $this->validate(
            $request,
            [
                "title" => "required|min:3",
                "homework_date" => "required",
                "submission_date" => "required",
                "class_id" => "required",
                "section_id" => "required",
                "subject_id" => "required",
            ],
            ["title.min" => "Homework Title Greaterthan 3 characters"]
        );

        try {
            $obj = HomeworkModel::find($id);
            $obj->title = $request->title;
            $obj->class_id = $request->class_id;
            $obj->user_id = User::getUser()->id;
            $obj->section_id = $request->section_id;
            $obj->subject_id = $request->subject_id;
            $obj->homework_description = $request->homework_description;
            $obj->homework_date = $request->homework_date;
            $obj->submission_date = $request->submission_date;
            $obj->dept_id = $request->dept_id;
            if ($request->attachments) {
                $this->deleteImage(
                    null,
                    $obj->attachment ? $obj->attachment : null
                );
                $obj->attachment = $this->uploadAttachment(
                    $request->attachments,
                    null,
                    "school/homework/"
                );
            }

            $obj->save();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("homework.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_homework)) {
            $delObj = new HomeworkModel();
            foreach ($request->selected_homework as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new HomeworkModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("homework.index");
    }
    /*
     * get data
     */
    public function getData(
        Request $request,
        $class_id,
        $section_id,
        $subject_id
    ) {
        // dd("yes");
        CGate::authorize("view-homework");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = HomeworkModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),

            "homework.id as id",
            "title",
            "homework.submission_date as submission_date",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new HomeworkModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new HomeworkModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("homework.class_id", $class_id)
            ->where("homework.section_id", $section_id)
            ->where("homework.subject_id", $subject_id)

            ->where("homework.status", "!=", -1);

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("evaluation", function ($data) {
                $submit = HomeworkSubmissionModel::where(
                    "homework_id",
                    $data->id
                )->count();

                if ($submit) {
                    return "<a href=" .
                        route("homeworkevaluations", $data->id) .
                        " class='badge bg-info eval'><i class='fa fa-hand-pointer-o' aria-hidden='true'></i> Evaluation</a>";
                } else {
                    return "<span class='badge bg-rose'>No Homework Submitted</span>";
                }
            })
            ->addColumn("submission", function ($data) {
                $date = Carbon::now()->toDateString();
                $subdate = Carbon::parse($data->submission_date)->format(
                    "Y-m-d"
                );

                if ($date == $subdate) {
                    return "<span class='text-danger'>" . $subdate . "</span>";
                } else {
                    return "<span>" . $subdate . "</span>";
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
                    "route" => "homework",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["submission", "action", "evaluation"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-homework");
        if ($request->ajax()) {
            HomeworkModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_homework)) {
            $obj = new HomeworkModel();
            foreach ($request->selected_homework as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function homeworksubmissions(Request $request, $id)
    {
        $homework = HomeworkModel::with("class", "section")
            ->where("id", $id)
            ->first();
        //dd($homework);
        if ($homework) {
            return view("homework::admin.submithomework", [
                "homework" => $homework,
                "layout" => "create",
            ]);
        } else {
            Session::flash("error", "Homework Not Found");
            return redirect()->route("homework.index");
        }
    }

    public function homeworksubmissionsSubmit(Request $request)
    {
        $this->validate($request, [
            "attachment" => "max:4000",
        ]);

        $homework = HomeworkModel::where("id", $request->homework_id)->first();

        if (Session::get("ACTIVE_GROUP") == "Student") {
            $active_student = Configurations::Activestudent();
            if ($homework) {
                // CHECKING THIS HOMEWORK Already submitted this students

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
                        ->route("homework.index")
                        ->with("success", "Homework Submitted");
                }
            }
        } else {
            return redirect()
                ->back()
                ->with("error", "No Access");
        }
    }

    public function homeworkevaluations(Request $request, $id, $student = null)
    {
        if ($request->ajax()) {
            $student_id = $request->query->get("student");
            $homeworksub_id = $request->query->get("id");
            $student_ = StudentsModel::where("id", $student_id)->first();

            $homework_submission = HomeworkSubmissionModel::where(
                "id",
                $homeworksub_id
            )->first();

            $view = view("homework::admin.showhomeworksubmission", [
                "student" => $student_,
                "homework_submission" => $homework_submission,
            ])->render();
            return response()->json(["viewfile" => $view]);
        }
        if ($request->isMethod("post")) {
            $date = Carbon::now("Asia/Kolkata")->toDateString();
            HomeworkSubmissionModel::where("id", $request->homesub_id)->update([
                "evaluated" => 1,
                "teacher_remark" => $request->feedback,
                "evaluation_date" => $date,
                "homework_status" => 1,
            ]);

            return redirect()
                ->back()
                ->with("success", "Evaluated successfuly");
        }
        $data = HomeworkModel::where("id", $id)->first();
        return view("homework::admin.evaluation", ["data" => $data]);
    }

    public function getDataEvaluation(Request $request, $homework_id)
    {
        CGate::authorize("view-homework");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = HomeworkSubmissionModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),

            "homework_submissions.id as id",
            "students.reg_no as reg_no",
            "homework.title as homeworktitle",
            "subject.name as subject",
            "homework_submissions.submitted_date as subdate",
            "homework_submissions.student_id as student_id",
            "homework_submissions.evaluated as evaluated",
            "homework_submissions.teacher_remark as teacher_remark",
            "students.first_name as sfirst_name",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new HomeworkSubmissionModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new HomeworkSubmissionModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("homework_submissions.homework_id", $homework_id)

            ->join(
                "students",
                "students.id",
                "=",
                "homework_submissions.student_id"
            )
            ->join(
                "homework",
                "homework.id",
                "=",
                "homework_submissions.homework_id"
            )
            ->join(
                "subject",
                "subject.id",
                "=",
                "homework_submissions.subject_id"
            )

            ->where("homework_submissions.status", "!=", -1);

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("evaluation", function ($data) {
                if ($data->evaluated == 0) {
                    return "<button type='button' class='btn btn-outline-danger m-1 evaluation' id=" .
                        $data->id .
                        " onclick='AcademicConfig.Viewevaluation(this.id,this)' data-student=" .
                        $data->student_id .
                        "><i class='bx bx-blanket'></i>
                </button>";
                } else {
                    return "<span class='badge rounded-pill bg-success'>Evaluated</span>";
                }
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->rawColumns(["evaluation"])->make(true);
    }
}
