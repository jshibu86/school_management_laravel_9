<?php

namespace cms\mark\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\mark\Models\PromotionModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\exam\Models\ExamTypeModel;
use cms\lclass\Models\LclassModel;
use cms\section\Models\SectionModel;
use cms\subject\Models\SubjectModel;
use cms\fees\Models\SchoolTypeModel;
use Configurations;
use Auth;
use cms\academicyear\Models\AcademicyearModel;
use cms\exam\Models\ExamTermModel;
use cms\mark\Models\PromotionHistory;
use cms\report\Models\MarkReportModel;
use cms\students\Models\StudentsModel;

class PromotionController extends Controller
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
            $type = $request->query->get("type", 0);

            $academic_year = $request->query->get("academic_year", 0);
            $school_type_from = $request->query->get("school_type_from", 0);

            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);

            if ($type == 0) {
                // academic_year

                $academic_year_data = AcademicyearModel::select(
                    "id",
                    "year as text"
                )
                    ->where("id", "!=", $academic_year)

                    ->where("status", 1)

                    ->get();

                return $academic_year_data;
            }

            if ($type == 1) {
                // class info
                $class_lists = LclassModel::whereNull("deleted_at")
                    ->where("status", "!=", -1)
                    ->where("id", "!=", $class_id)
                    ->select("id", "name as text")

                    ->get();

                return $class_lists;
            }
            if ($type == 2) {
                $class_lists = LclassModel::whereNull("deleted_at")
                    ->where("status", "!=", -1)
                    ->where("school_type_id", $school_type_from)
                    ->select("id", "name as text")

                    ->get();

                return $class_lists;
            }
        }

        return view("1::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function getpromotstudents(Request $request)
    {
        // getpromote studnets
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $percentage = Configurations::getConfig("site")->promotion_percentage;

        $last_terms = ExamTermModel::where(
            "academic_year",
            $request->academic_year_from
        )
            ->orderBy("order", "desc")
            ->first();

        if ($percentage) {
            $students = StudentsModel::where([
                "class_id" => $request->class_id_from,
                "section_id" => $request->section_id_from,
            ]);

            $students_info = $students->get();

            $students_ids = $students->pluck("id")->toArray();

            $section_lists = SectionModel::whereNull("deleted_at")
                ->where("status", "!=", -1)
                ->where("class_id", $request->class_id_to)
                ->select("id", "name")
                ->get();

            // for third term alone

            $className = LclassModel::where("id", $request->class_id_to)
                ->whereNull("deleted_at")
                ->where("status", "!=", -1)
                ->value("name");

            // $school_type_info = SchoolTypeModel::where("status", "!=", -1)
            //     ->select("school_type", "id as number")
            //     ->get();

            $mark_report_with_percentage = MarkReportModel::whereIn(
                "student_id",
                $students_ids
            )
                ->where("is_promotion", 1)
                ->where("term_id", $last_terms->id)
                ->where("average", ">=", $percentage)
                ->get();
            // end third term alone

            $promotion_cumulative = [];

            $terms = ExamTermModel::where(
                "academic_year",
                $request->academic_year_from
            )
                ->orderBy("order", "desc")
                ->pluck("id")
                ->toArray();

            foreach ($students_info as $key => $student) {
                if (!isset($mark_data[$student->id])) {
                    $promotion_cumulative[$student->id] = new \stdClass();

                    $promotion_cumulative[$student->id]->studentname =
                        $student->first_name . " " . $student->last_name;

                    $promotion_cumulative[$student->id]->id = $student->id;

                    $promotion_cumulative[$student->id]->reg_no =
                        $student->reg_no;
                    $promotion_cumulative[$student->id]->email =
                        $student->email;
                    $promotion_cumulative[$student->id]->image =
                        $student->image;

                    $cumulative_avg = MarkReportModel::where(
                        "student_id",
                        $student->id
                    )
                        ->where("is_promotion", 1)
                        ->whereIn("term_id", $terms)
                        ->select(DB::raw("avg(average) c"))
                        ->first();

                    //cumulative average
                    $promotion_cumulative[$student->id]->cumulative_avg =
                        $cumulative_avg->c;

                    $last_term_average = MarkReportModel::where(
                        "student_id",
                        $student->id
                    )
                        ->where("is_promotion", 1)
                        ->where("term_id", $last_terms->id)

                        ->first();

                    //cumulative average
                    $promotion_cumulative[
                        $student->id
                    ]->last_avg = $last_term_average
                        ? $last_term_average->average
                        : 0;
                }
            }

            if (Configurations::getConfig("site")->promotion_type == 0) {
                $student_data = collect($promotion_cumulative)->where(
                    "cumulative_avg",
                    ">=",
                    $percentage
                );
            } else {
                $student_data = collect($promotion_cumulative)->where(
                    "last_avg",
                    ">=",
                    $percentage
                );
            }
            $data = [
                "academic_year_from" => $request->academic_year_from,
                "class_id_from" => $request->class_id_from,
                "section_id_from" => $request->section_id_from,
                "academic_year_to" => $request->academic_year_to,
                "class_id_to" => $request->class_id_to,
                "section_id_to" => $request->section_id_to,
                "promotion_type" => Configurations::getConfig("site")
                    ->promotion_type,
                "selectedClassId" => $request->input("class_id_to"),
                "className" => $className,
            ];

            $view = view("mark::promotion.includes.promotionstudent", [
                "students" => $student_data,
                "data" => $data,
                "section_lists" => $section_lists,
            ])->render();

            return response()->json(["viewfile" => $view]);

            // for cumulative alone
        }
    }
    public function create()
    {
        [$group, $info] = Configurations::GetActiveGroupwithInfo(
            Auth::user()->id
        );
        $academic_years = Configurations::getAcademicyears();

        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $academic_years_to = AcademicyearModel::where(
            "id",
            "!=",
            $current_academic_year
        )
            ->pluck("year", "id")
            ->toArray();

        $examterms = Configurations::getCurentAcademicTerms();

        $exam_types = ExamTypeModel::where("status", 1)
            ->whereNull("deleted_at")
            ->pluck("exam_type_name", "id")
            ->toArray();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc");

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

        //dd(collect($promotion_cumulative));
        //dd($current_academic_term);
        $last_terms_id = ExamTermModel::where(
            "academic_year",
            $current_academic_year
        )
            ->orderBy("order", "desc")
            ->first()->id;

        $if_last_term = $last_terms_id == $current_academic_term ? true : false;

        $school_type_info = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();

        $school_type_infos = Configurations::getSchooltypes();
        // $school_type_infos = Configurations::SCHOOLTYPES;

        //dd($if_last_term);
        return view("mark::promotion.edit", [
            "layout" => "create",
            "exams" => [],

            "sections" => [],
            "examtypes" => [],

            "academic_years" => $academic_years,
            "school_type_info" => $school_type_info,
            "school_type_infos" => $school_type_infos,

            "current_academic_year" => $current_academic_year,
            "examterms" => $examterms,
            "exam_types" => $exam_types,
            "class_lists" => $class_lists,
            "current_academic_term" => $current_academic_term,
            "subject_lists" => [],
            "section_lists" => [],
            "academic_years_to" => $academic_years_to,
            "if_last_term" => $if_last_term,
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

        DB::beginTransaction();
        try {
            $obj = new PromotionHistory();

            if (sizeof($request->students)) {
                foreach ($request->students as $student) {
                    # code...
                    $obj->student_id = $student;
                    $obj->academic_year_from = $request->academic_year_from;
                    $obj->class_id_from = $request->class_id_from;
                    $obj->section_id_from = $request->section_id_from;
                    $obj->academic_year_to = $request->academic_year_to;
                    $obj->class_id_to = $request->class_id_to;
                    $obj->section_id_to = $request->section_id_to;
                    $obj->promotion_type = $request->promotion_type;
                    if ($obj->save()) {
                        // update student table

                        StudentsModel::where("id", $student)->update([
                            "class_id" => $request->class_id_to,
                            "section_id" => $request->section_id_to,
                            "academic_year" => $request->academic_year_to,
                        ]);
                    }
                }

                DB::commit();
            } else {
                DB::rollback();
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", "Please Select Any Students");
            }
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
        return redirect()->back();
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
        $data = PromotionModel::find($id);
        return view("1::admin.edit", ["layout" => "edit", "data" => $data]);
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
                (new PromotionModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);

        try {
            $obj = PromotionModel::find($id);
            $obj->name = $request->name;
            $obj->desc = $request->desc;
            $obj->status = $request->status;
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
        return redirect()->route("1.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_1)) {
            $delObj = new PromotionModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new PromotionModel();
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
        CGate::authorize("view-1");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = PromotionModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new PromotionModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new PromotionModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )->where("status", "!=", -1);

        $datatables = Datatables::of($data)
            ->addIndexColumn()
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
                    "route" => "1",
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
        CGate::authorize("edit-1");
        if ($request->ajax()) {
            PromotionModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new PromotionModel();
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
