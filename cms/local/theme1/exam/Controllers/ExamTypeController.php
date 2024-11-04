<?php

namespace cms\exam\Controllers;

use DB;
use CGate;

use Session;
use Configurations;

use Illuminate\Http\Request;
use cms\exam\Models\ExamTypeModel;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Validation\Rule;

class ExamTypeController extends Controller
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
        return view("exam::admin.examtype.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years = Configurations::getAcademicyears();
        $info = Configurations::getAcademicandTermsInfo();
        return view("exam::admin.examtype.edit", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "info" => $info,
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
        $examTypeModel = new ExamTypeModel();
        $examTypeTable = $examTypeModel->getTable();
        $this->validate($request, [
            "exam_type_name" => [
                "required",
                "min:3",
                Rule::unique($examTypeTable, "exam_type_name")->whereNull(
                    "deleted_by"
                ),
            ],
        ]);
        DB::beginTransaction();
        try {
            $obj = new ExamTypeModel();
            $obj->exam_type_name = mb_convert_case(
                $request->exam_type_name,
                MB_CASE_TITLE,
                "UTF-8"
            );
            $obj->academy_year = $request->academy_year;
            $obj->is_promotion = $request->is_promotion ? 1 : 0;

            $obj->save();

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
                ->route("examtype.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("examtype.index");
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
        $academic_years = Configurations::getAcademicyears();
        $info = Configurations::getAcademicandTermsInfo();

        $data = ExamTypeModel::find($id);
        return view("exam::admin.examtype.edit", [
            "layout" => "edit",
            "academic_years" => $academic_years,
            "info" => $info,
            "data" => $data,
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
        $examTypeModel = new ExamTypeModel();
        $examTypeTable = $examTypeModel->getTable();
        $this->validate($request, [
            "exam_type_name" =>
                "required|min:3|unique:" .
                $examTypeTable .
                ",exam_type_name," .
                $id .
                ",id,deleted_by,NULL",
        ]);

        try {
            $obj = ExamTypeModel::find($id);
            $obj->exam_type_name = mb_convert_case(
                $request->exam_type_name,
                MB_CASE_TITLE,
                "UTF-8"
            );
            $obj->academy_year = $request->academy_year;
            $obj->is_promotion = $request->is_promotion ? 1 : 0;

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

        Session::flash("success", "Updated Successfully");
        return redirect()->route("examtype.index");
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
            $delObj = new ExamTypeModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new ExamTypeModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("examtype.index");
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

        $data = ExamTypeModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "exam_type_name",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ExamTypeModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ExamTypeModel())->getTable() .
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
                if (
                    $data->exam_type_name == "Home Work" ||
                    $data->exam_type_name == "Class Test" ||
                    $data->exam_type_name == "Admission Exam" ||
                    $data->exam_type_name == "Exam"
                ) {
                    return "";
                } else {
                    return view("layout::datatable.action", [
                        "data" => $data,
                        "route" => "examtype",
                    ])->render();
                }
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
            ExamTypeModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new ExamTypeModel();
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
