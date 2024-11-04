<?php

namespace cms\subject\Controllers;

use DB;
use CGate;
use Session;
use User;

use Illuminate\Http\Request;
use cms\department\Models\DepartmentModel;
use cms\lclass\Models\LclassModel;
use App\Http\Controllers\Controller;
use cms\academicyear\Models\AcademicyearModel;
use cms\subject\Models\SubjectModel;
use Yajra\DataTables\Facades\DataTables;
use cms\core\configurations\helpers\Configurations;
use cms\section\Models\SectionModel;
use cms\subject\Models\SubjectTeacherMapping;
use cms\teacher\Models\DesignationModel;
use cms\teacher\Models\TeacherModel;
use cms\fees\Models\SchoolTypeModel;
use Auth;

class SubjectController extends Controller
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
            [$group, $info] = Configurations::GetActiveGroupwithInfo(
                Auth::user()->id
            );
            $class_id = $request->query->get("class", 0);
            $type = $request->query->get("type");
            if ($type && $type == "homework") {
                $teacher_id = Configurations::getTeacherId(User::getUser()->id);

                $teacher_subjects = Configurations::getTeacherSubjects(
                    $teacher_id
                );
                $classes = SubjectModel::select("id", "name as text")

                    ->where("status", 1)
                    ->where("class_id", $class_id)
                    ->whereIn("id", $teacher_subjects)
                    ->orderBy("name", "asc")
                    ->get();
                return $classes;
            }

            if ($type && $type == "timetable") {
                $section_id = $request->query->get("section_id", 0);
                $subject_id = $request->query->get("subject_id", 0);

                // for edit

                //for create

                $teacher_ids = SubjectTeacherMapping::where([
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "subject_id" => $subject_id,
                ])->pluck("teacher_id");

                $teachers = TeacherModel::select("id", "teacher_name as text")

                    ->where("status", 1)

                    ->whereIn("id", $teacher_ids)

                    ->get();

                return $teachers;
                // $classes = SubjectModel::select("id", "name as text")

                //     ->where("status", 1)
                //     ->where("class_id", $class_id)

                //     ->orderBy("name", "asc")
                //     ->get();
                return $classes;
            }

            if ($type && $type == "getnames") {
                $subject_id = $request->query->get("subject_id", 0);
                $teacher_id = $request->query->get("teacher_id", 0);

                $subject = SubjectModel::where("id", $subject_id)->first();

                $teacher = TeacherModel::where("id", $teacher_id)->first();

                $data = [
                    "subject" => $subject ? $subject->name : "Not Assign",
                    "teacher" => $teacher
                        ? $teacher->teacher_name
                        : "Not Assign",
                ];

                return $data;
            }

            $classes = SubjectModel::select("id", "name as text")->where(
                "status",
                1
            );

            if ($group == "Teacher") {
                $teacherassignsubject = Configurations::getTeacherSubjects(
                    $info->id
                );

                $classes = $classes
                    ->whereIn("id", $teacherassignsubject)
                    ->where("class_id", $class_id)
                    ->orderBy("name", "asc")
                    ->get();
            } else {
                $classes = $classes
                    ->where("class_id", $class_id)
                    ->orderBy("name", "asc")
                    ->get();
            }

            return $classes;
        }
        return view("subject::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years = Configurations::getAcademicyears();
        $class_list = LclassModel::where("status", "=", 1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();
        $subject_types = Configurations::SUBJECT_TYPES;

        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();
        $school_types = SchoolTypeModel::where("status", "=", 1)
            ->pluck("school_type", "id")
            ->toArray();
        return view("subject::admin.edit", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "class_list" => $class_list,
            "subject_types" => $subject_types,
            "departments" => $departments,
            "school_types" => $school_types,
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
        $this->validate($request, [
            "name" => "required|min:3",
            "class_id" => "required",
            "type" => "required",
        ]);
        $sub_name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");

        // find existing subject

        $exists = SubjectModel::where([
            "class_id" => $request->class_id,
            "type" => $request->type,
            "name" => $sub_name,
        ])->first();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "exception_error",
                    "This Subject $sub_name Already In this Class"
                );
        }

        // dd("no");

        $obj = new SubjectModel();
        $obj->name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        if ($request->subject_code) {
            $shortcode = strtoupper($request->subject_code);
        } else {
            $Uppercase = strtoupper($request->name);
            $shortcode = substr($Uppercase, 0, 3);
        }

        $obj->subject_code = $shortcode;
        $obj->type = $request->type;
        $obj->class_id = $request->class_id;
        $obj->department_id = $request->department_id;
        $obj->school_type = $request->school_type;
        $obj->save();

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("subject.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("subject.index");
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
        $data = SubjectModel::find($id);
        $academic_years = Configurations::getAcademicyears();
        $class_list = LclassModel::where("status", "=", 1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();
        $subject_types = Configurations::SUBJECT_TYPES;
        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();
        $school_types = SchoolTypeModel::where("status", "=", 1)
            ->pluck("school_type", "id")
            ->toArray();
        return view("subject::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "academic_years" => $academic_years,
            "class_list" => $class_list,
            "subject_types" => $subject_types,
            "departments" => $departments,
            "school_types" => $school_types,
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
        $this->validate($request, [
            "name" => "required|min:3",
            "class_id" => "required",
            "type" => "required",
        ]);
        $obj = SubjectModel::find($id);
        $obj->name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        if ($request->subject_code == null) {
            $Uppercase = strtoupper($request->name);
            $shortcode = substr($Uppercase, 0, 3);
        } else {
            $shortcode = strtoupper($request->subject_code);
        }

        $obj->subject_code = $shortcode;
        $obj->type = $request->type;
        $obj->class_id = $request->class_id;
        $obj->department_id = $request->department_id;
        $obj->school_type = $request->school_type;
        $obj->save();

        Session::flash("success", "Updated successfully");
        return redirect()->route("subject.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_subject)) {
            $delObj = new SubjectModel();
            foreach ($request->selected_subject as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new SubjectModel();
            $delItem = $delObj->find($id);

            SubjectTeacherMapping::where("subject_id", $id)->delete();
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("subject.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-subject");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));
        // $data = SubjectModel::with("subjectmapping", "class")->get();
        $data = SubjectModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "subject.id as id",
            "subject.name as name",
            "subject_code",
            "type",
            "lclass.name as classname",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new SubjectModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new SubjectModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )->leftjoin("lclass", "lclass.id", "=", "subject.class_id");

        if (Session::get("ACTIVE_GROUP") == "Teacher") {
            $active_teacher = Configurations::Activeteacher();

            $subjects = SubjectTeacherMapping::where(
                "teacher_id",
                $active_teacher->id
            )->pluck("subject_id");

            if (!empty($subjects)) {
                $data = $data->whereIn("id", $subjects);
            }
        }

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("subjectmapping", function ($data) {
                if (count($data->subjectmapping) > 0) {
                    $new = [];
                    foreach ($data->subjectmapping as $attribute => $value) {
                        $new[] =
                            SectionModel::sectionname($value->section_id) .
                            "-" .
                            TeacherModel::teachername($value->teacher_id);
                    }
                    return implode("<br/>", $new);
                } else {
                    return "<p class='text-warning'>Not Assign Any teachers</p>";
                }
            })
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("actdeact", function ($data) {
                if ($data->id != "1") {
                    $statusbtnvalue =
                        $data->status == 1
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
                    "route" => "subject",
                ])->render();

                //return $data->id;
            });

        //  return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["subjectmapping", "action"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-subject");
        if ($request->ajax()) {
            SubjectModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_subject)) {
            $obj = new SubjectModel();
            foreach ($request->selected_subject as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }
    public function storesubjectteacherMapping(Request $request)
    {
        try {
            $map_data = $request->map;

            $old_data = SubjectTeacherMapping::where(
                "class_id",
                $request->class_id
            )
                ->where("section_id", $request->section_id)
                ->delete();

            //adding mapping

            foreach ($map_data as $subject_id => $teacherdata) {
                foreach ($teacherdata as $teacher_id => $teacher) {
                    if ($teacher != 0) {
                        $obj = new SubjectTeacherMapping();
                        $obj->class_id = $request->class_id;
                        $obj->section_id = $request->section_id;
                        $obj->teacher_id = $teacher_id;
                        $obj->subject_id = $subject_id;
                        $obj->academic_year = $request->academic_year;
                        $obj->save();
                    }
                }
            }
            $msg = "Subject Teacher Successfully Assigned ";
            $class_name = "success";
            Session::flash($class_name, $msg);
            return redirect()->route("subject.index");
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
    }
    public function subjectteacherMapping(Request $request, $getsubject = null)
    {
        // dd($request->all());
        $academic_years = Configurations::getAcademicyears();
        $class_list = LclassModel::where("status", "!=", -1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();
        $subject_types = Configurations::SUBJECT_TYPES;

        //for mapping save time
        if ($request->isMethod("post")) {
            // dd($request->all());
            $this->validate($request, [
                "section_id" => "required",
                "class_id" => "required",
                "academic_year" => "required",
            ]);

            $class_id = $request->class_id;
            $section_id = $request->section_id;
            $academic_year = $request->academic_year;
            $class_subjects = SubjectModel::where("class_id", $class_id)
                ->where("status", 1)
                ->get();
            $sections = SectionModel::where("status", 1)
                ->where("class_id", $class_id)
                ->pluck("name", "id")
                ->toArray();

            $teachers = TeacherModel::with("designation")
                ->where("status", 1)
                ->select("id", "teacher_name", "designation_id")
                ->get();
            //dd($teachers);

            $layout = "subject";

            $subject_mapping = SubjectTeacherMapping::where([
                "academic_year" => $academic_year,
                "class_id" => $class_id,
                "section_id" => $section_id,
            ])->get();
            $data = [];
            foreach ($subject_mapping as $datas) {
                $data[$datas->subject_id][$datas->teacher_id] =
                    $datas->teacher_id;
            }
            $class_name = LclassModel::classname($class_id);

            $section_name = SectionModel::sectionname($section_id);

            $year_name = AcademicyearModel::academicyear($academic_year);
            // dd($data);
            //SubjectTeacherMapping::create($request->all());

            return view("subject::admin.subjectmapping", [
                "layout" => "create",
                "academic_years" => $academic_years,
                "academic_year" => $academic_year,
                "class_list" => $class_list,
                "class_id" => $class_id,
                "section_id" => $section_id,
                "academic_year" => $academic_year,
                "class_subjects" => $class_subjects,
                "teachers" => $teachers,
                "sections" => $sections,
                "layout" => $layout,
                "data" => $data,
                "class_name" => $class_name,
                "section_name" => $section_name,
                "year_name" => $year_name,
            ]);
        }

        return view("subject::admin.subjectmapping", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "class_list" => $class_list,
            "sections" => [],
        ]);
    }
}
