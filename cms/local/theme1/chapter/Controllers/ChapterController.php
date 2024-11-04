<?php

namespace cms\chapter\Controllers;

use DB;
use Mail;
use CGate;
use User;
use Session;
use Configurations;

use Illuminate\Http\Request;
use cms\chapter\Mail\ChapterMail;
use cms\lclass\Models\LclassModel;
use App\Http\Controllers\Controller;
use cms\chapter\Models\ChapterModel;
use cms\section\Models\SectionModel;
use cms\subject\Models\SubjectModel;
use Yajra\DataTables\Facades\DataTables;
use cms\chapter\Models\ChapterTopicModel;
use cms\department\Models\DepartmentModel;
use cms\subject\Models\SubjectTeacherMapping;
use cms\classteacher\Models\ClassteacherModel;
use cms\chapter\Models\ChapterTopicContentModel;
use cms\students\Models\StudentsModel;

class ChapterController extends Controller
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
        // abort(404);
        if (Session::get("ACTIVE_GROUP") == "Teacher") {
            $active_teacher = Configurations::Activeteacher();
            $class_teach = ClassteacherModel::where(
                "teacher_id",
                $active_teacher->id
            )->first();

            // dd($class_teach);

            $subjects = SubjectTeacherMapping::where(
                "teacher_id",
                $active_teacher->id
            )->count();

            if (!$class_teach && !$subjects) {
                return redirect()
                    ->back()
                    ->with(
                        "exception_error",
                        "You have not Assigen any Class Teacher | Any Subjects | Contact Administrator"
                    );
            }
        }
        return view("chapter::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // abort(404);
        $sections = [];
        $subjects = [];
        $departments = DepartmentModel::where("status", 1)

            ->pluck("dept_name", "id")
            ->toArray();

        $class_list = LclassModel::where("status", "!=", -1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();
        return view("chapter::admin.edit", [
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
        //dd($request->all());
        $this->validate(
            $request,
            [
                "chapter_name" => "required|min:3",
                "class_id" => "required",
                "section_id" => "required",
                "subject_id" => "required",
                "description" => "required",
            ],
            [
                "description.required" => "Please Fill out Chapter Description",
                "chapter_name.required" => "Please Enter Chapter Name",
                "chapter_name.min" => "Chapter Name Minimum 3 Characters",
            ]
        );

        try {
            $obj = new ChapterModel();
            $obj->chapter_name = $request->chapter_name;
            $obj->chapter_description = $request->description;
            $obj->class_id = $request->class_id;
            $obj->section_id = $request->section_id;
            $obj->subject_id = $request->subject_id;
            $obj->dept_id = $request->dept_id;
            $obj->created_by = User::getUser()->id;
            $obj->updated_by = User::getUser()->id;
            $obj->save();
        } catch (\Exception $e) {
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
                ->route("chapter.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("chapter.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //abort(404);

        $data = ChapterModel::with([
            "topics" => function ($query) {
                $query->select("id", "chapter_id", "topic_name", "created_at");
            },
        ])
            ->with("class", "section", "subject")
            ->find($id);

        // dd($data);
        return view("chapter::admin.show", [
            "data" => $data,
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
        $data = ChapterModel::find($id);
        $sections = SectionModel::where("status", 1)
            ->where("class_id", $data->class_id)
            ->pluck("name", "id")
            ->toArray();
        $subjects = SubjectModel::where("status", 1)
            ->where("class_id", $data->class_id)

            ->pluck("name", "id")
            ->toArray();
        $class_list = LclassModel::where("status", "!=", -1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();
        $departments = DepartmentModel::where("status", 1)

            ->pluck("dept_name", "id")
            ->toArray();
        return view("chapter::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "sections" => $sections,
            "subjects" => $subjects,
            "class_list" => $class_list,
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
                "chapter_name" => "required|min:3",
                "class_id" => "required",
                "section_id" => "required",
                "subject_id" => "required",
                "description" => "required",
            ],
            [
                "description.required" => "Please Fill out Chapter Description",
                "chapter_name.required" => "Please Enter Chapter Name",
                "chapter_name.min" => "Chapter Name Minimum 3 Characters",
            ]
        );
        try {
            $obj = ChapterModel::find($id);
            $obj->chapter_name = $request->chapter_name;
            $obj->chapter_description = $request->description;
            $obj->class_id = $request->class_id;
            $obj->section_id = $request->section_id;
            $obj->subject_id = $request->subject_id;
            $obj->dept_id = $request->dept_id;
            $obj->updated_by = User::getUser()->id;
            $obj->save();
        } catch (\Exception $e) {
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
        return redirect()->route("chapter.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_chapter)) {
            $delObj = new ChapterModel();
            foreach ($request->selected_chapter as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            DB::statement("SET FOREIGN_KEY_CHECKS=0;");
            $chapter = ChapterModel::find($id);
            ChapterTopicModel::where("chapter_id", $chapter->id)->delete();

            ChapterTopicContentModel::where(
                "chapter_id",
                $chapter->id
            )->delete();

            $chapter->delete();
            DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("chapter.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-chapter");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ChapterModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "chapter.id as id",
            "chapter_name",
            "lclass.name as classname",
            "section.name as sectionname",
            "subject.name as subjectname",
            "chapter.subject_id as csubject_id",
            "lclass.id as class_id",
            "section.id as section_id",
            "subject.id as subject_id",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ChapterModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ChapterModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->join("lclass", "lclass.id", "=", "chapter.class_id")
            ->join("section", "section.id", "=", "chapter.section_id")
            ->join("subject", "subject.id", "=", "chapter.subject_id")
            ->orderBy("chapter.id", "desc");

        if (Session::get("ACTIVE_GROUP") == "Student") {
            $active_student = Configurations::Activestudent();
            // $student = StudentsModel::where(
            //     "user_id",
            //     User::getUser()->id
            // )->first();

            $data
                ->where("chapter.class_id", $active_student->class_id)
                ->where("chapter.section_id", $active_student->section_id);
        }

        if (Session::get("ACTIVE_GROUP") == "Teacher") {
            $active_teacher = Configurations::Activeteacher();

            $subjects = SubjectTeacherMapping::where(
                "teacher_id",
                $active_teacher->id
            )->pluck("subject_id");

            if (!empty($subjects)) {
                $data = $data->whereIn("chapter.subject_id", $subjects);
            }
        }

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("count", function ($data) {
                $count = DB::table("chapter_topics")

                    ->where("chapter_topics.chapter_id", $data->id)
                    ->where("deleted_by", null)
                    ->count();

                return $count;
            })
            ->addColumn("class_section", function ($data) {
                return $data->classname . "-" . $data->sectionname;
            })

            ->addColumn("add-topics", function ($data) {
                return view("chapter::admin.topicbutton", [
                    "data" => $data,
                    "route" => "chapter",
                ])->render();
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
                    "route" => "chapter",
                ])->render();
            });

        //return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->rawColumns(["action", "add-topics"])->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-chapter");
        if ($request->ajax()) {
            ChapterModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_chapter)) {
            $obj = new ChapterModel();
            foreach ($request->selected_chapter as $k => $v) {
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
