<?php

namespace cms\lclass\Controllers;

use DB;
use Mail;
use CGate;
use Session;
use Configurations;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use cms\lclass\Models\LclassModel;
use App\Http\Controllers\Controller;
use cms\fees\Models\SchoolTypeModel;
use cms\section\Models\SectionModel;
use cms\students\Models\StudentsModel;
use Yajra\DataTables\Facades\DataTables;
use cms\subject\Models\SubjectTeacherMapping;

class LclassController extends Controller
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
        return view("lclass::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years = Configurations::getAcademicyears();
        $types = SchoolTypeModel::where("status", 1)->pluck(
            "school_type",
            "id"
        );
        return view("lclass::admin.edit", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "types" => $types,
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
        $this->validate(
            $request,
            [
                "name" => [
                    "required",
                    Rule::unique("lclass", "name")->whereNull("deleted_at"),
                ],
            ],
            ["name.unique" => "This class Already Taken"]
        );
        $obj = new LclassModel();
        $obj->name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        $obj->school_type_id = $request->school_type_id;
        if ($request->note) {
            $obj->note = $request->note;
        }

        $obj->save();
        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("lclass.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("lclass.index");
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
        $data = LclassModel::find($id);
        $types = SchoolTypeModel::where("status", 1)->pluck(
            "school_type",
            "id"
        );

        return view("lclass::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "types" => $types,
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
            "name" =>
                "required|unique:" .
                (new LclassModel())->getTable() .
                ",name," .
                $id,
        ]);

        $obj = LclassModel::find($id);

        $obj->name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        $obj->school_type_id = $request->school_type_id;

        $obj->note = $request->note;

        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("lclass.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_lclass)) {
            $delObj = new LclassModel();
            foreach ($request->selected_lclass as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $sections_have = SectionModel::where("class_id", $id)->count();

            $students_have = StudentsModel::where("class_id", $id)->count();

            if ($sections_have || $students_have) {
                return redirect()
                    ->back()
                    ->with(
                        "exception_error",
                        "Can't Delete Class !! This class Associate wit some  section or students"
                    );
            }
            DB::statement("SET FOREIGN_KEY_CHECKS=0;");
            $delObj = new LclassModel();
            $delItem = $delObj->find($id);
            $delItem->forcedelete();
            DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("lclass.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-lclass");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = LclassModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "lclass.id as id",
            "name",
            "school_type_id",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new LclassModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new LclassModel())->getTable() .
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
            ->addColumn("schooltype", function ($data) {
                if ($data->school_type != null) {
                    $schoolType = SchoolTypeModel::find($data->school_type);
                    return $schoolType ? $schoolType->school_type : "";
                }
                return "";
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
                    "route" => "lclass",
                ])->render();

                //return $data->id;
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
        CGate::authorize("edit-lclass");

        if ($request->ajax()) {
            LclassModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_lclass)) {
            $obj = new LclassModel();
            foreach ($request->selected_lclass as $k => $v) {
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
