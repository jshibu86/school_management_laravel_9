<?php

namespace cms\mark\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\mark\Models\GradeModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use Carbon\Carbon;
use cms\mark\Models\GradeSystemModel;
use Configurations;
class GradeController extends Controller
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
        return view("mark::grade.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("mark::grade.edit", ["layout" => "create"]);
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
        $this->validate($request, [
            "grade_sys_name" =>
                "required|max:50|unique:" .
                (new GradeSystemModel())->getTable() .
                ",grade_sys_name",
        ]);
        DB::beginTransaction();
        try {
            // add grade system
            if (!$request->grade_name) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", "Please add Any Grades");
            }
            $grade_system = new GradeSystemModel();
            $grade_system->grade_sys_name = $request->grade_sys_name;
            if ($grade_system->save()) {
                for ($i = 0; $i < sizeof($request->grade_name); $i++) {
                    $obj = new GradeModel();
                    $obj->grade_sys_name_id = $grade_system->id;
                    $obj->grade_name = isset($request->grade_name[$i])
                        ? $request->grade_name[$i]
                        : "No";
                    $obj->grade_point = $request->grade_point[$i];
                    $obj->mark_from = $request->mark_from[$i];
                    $obj->mark_upto = $request->mark_upto[$i];
                    $obj->grade_note = $request->grade_note[$i];
                    $obj->save();
                }
            }

            DB::commit();
            return redirect()
                ->route("grade.index")
                ->with("success", "Grade System Added");
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
                ->route("grade.create")
                ->with("success", "Saved Successfully");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $grades_data = GradeSystemModel::with("grades")
            ->where("id", $id)
            ->first();

        // dd($grades_data);

        return view("mark::grade.show", compact("grades_data"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = GradeSystemModel::with("grades")->find($id);

        return view("mark::grade.edit", ["layout" => "edit", "data" => $data]);
    }

    public function deletegrade(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->query->get("id", 0);

            GradeModel::find($id)->delete();

            return response()->json(["success" => "deleted"]);
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
        //dd($request->all());
        $this->validate($request, [
            "grade_sys_name" =>
                "required|min:3|max:50|unique:" .
                (new GradeSystemModel())->getTable() .
                ",grade_sys_name," .
                $id,
        ]);

        try {
            DB::beginTransaction();

            if (!$request->grade_name) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", "Please Add Grades");
            }
            $grade_system = GradeSystemModel::find($id);
            $grade_system->grade_sys_name = $request->grade_sys_name;
            if ($grade_system->update()) {
                for ($i = 0; $i < sizeof($request->grade_name); $i++) {
                    if (isset($request->grade_id[$i])) {
                        $exists = GradeModel::find($request->grade_id[$i]);

                        if ($exists) {
                            $exists->grade_name = isset(
                                $request->grade_name[$i]
                            )
                                ? $request->grade_name[$i]
                                : "No";
                            $exists->grade_point = $request->grade_point[$i];
                            $exists->mark_from = $request->mark_from[$i];
                            $exists->mark_upto = $request->mark_upto[$i];
                            $exists->grade_note = $request->grade_note[$i];
                            $exists->update();
                        }
                    } else {
                        $obj = new GradeModel();
                        $obj->grade_sys_name_id = $grade_system->id;
                        $obj->grade_name = isset($request->grade_name[$i])
                            ? $request->grade_name[$i]
                            : "No";
                        $obj->grade_point = $request->grade_point[$i];
                        $obj->mark_from = $request->mark_from[$i];
                        $obj->mark_upto = $request->mark_upto[$i];
                        $obj->grade_note = $request->grade_note[$i];
                        $obj->save();
                    }
                }
            }
            DB::commit();
            return redirect()
                ->route("grade.index")
                ->with("success", "Grade System Updated");
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
        //dd($id);
        if (!empty($request->selected_1)) {
            $delObj = new GradeModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new GradeSystemModel();
            $delItem = $delObj->find($id);
            GradeModel::where("grade_sys_name_id", $id)->delete();
            $delItem->delete();
        }

        Session::flash("success", "Grade System Deleted Successfully!!");
        return redirect()->route("grade.index");
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

        $data = GradeSystemModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "grade_sys_name",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new GradeSystemModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new GradeSystemModel())->getTable() .
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
            ->addColumn("grades", function ($data) {
                return GradeModel::where(
                    "grade_sys_name_id",
                    $data->id
                )->count();
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
                    "route" => "grade",
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
            GradeSystemModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new GradeSystemModel();
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
