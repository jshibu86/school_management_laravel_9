<?php

namespace cms\fees\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\fees\Models\SchoolTypeModel;

use Yajra\DataTables\Facades\DataTables;
use cms\lclass\Models\LclassModel;
use Session;
use DB;
use CGate;

class SchoolTypeController extends Controller
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
            $school_type = $request->query->get("school_type", null);
            $type = $request->query->get("type", 0);
            if ($school_type) {
                $class_lists = LclassModel::whereNull("deleted_at")
                    ->where("status", "!=", -1)
                    ->select("id", "name as text");
                if ($type == "is_department") {
                    $is_depart = SchoolTypeModel::where(
                        "id",
                        $school_type
                    )->pluck("is_department");
                    return response()->json([
                        "is_department" => $is_depart,
                    ]);
                }
                if ($school_type == "all") {
                    $class_lists = $class_lists->orderBy("id", "asc")->get();
                } else {
                    $class_lists = $class_lists
                        ->where("school_type_id", $school_type)
                        ->orderBy("id", "asc")
                        ->get();
                }
                $data = collect($class_lists);
                // $data->prepend("All");
                return $data;
            }
            $class_id = $request->query->get("class_id", 0);

            $is_department = LclassModel::with("schooltype")->find($class_id);

            if ($is_department->schooltype) {
                if ($is_department->schooltype->is_department == 1) {
                    return response()->json(["department" => true]);
                } else {
                    return response()->json(["department" => false]);
                }
            } else {
                return response()->json(["department" => false]);
            }
        }
        return view("fees::schooltype.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("fees::schooltype.edit", ["layout" => "create"]);
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
            "school_type" => ["required", "regex:/^[a-zA-Z\s]*$/"],
            "school_short_name" => ["required", "regex:/^[a-zA-Z\s]*$/"],
        ]);
        DB::beginTransaction();
        try {
            $obj = new SchoolTypeModel();
            $obj->school_type = $request->school_type;
            $obj->school_short_name = $request->school_short_name;
            $obj->is_department = $request->is_department ? 1 : 0;
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
                ->route("schooltype.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("schooltype.index");
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
        $data = SchoolTypeModel::find($id);
        return view("fees::schooltype.edit", [
            "layout" => "edit",
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
        $this->validate($request, [
            "school_type" => ["required", "regex:/^[a-zA-Z\s]*$/"],
            "school_short_name" => ["required", "regex:/^[a-zA-Z\s]*$/"],
        ]);
        try {
            $obj = SchoolTypeModel::find($id);

            $obj->school_type = $request->school_type;
            $obj->school_short_name = $request->school_short_name;
            $obj->is_department = $request->is_department ? 1 : 0;
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
        return redirect()->route("schooltype.index");
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
            $delObj = new SchoolTypeModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new SchoolTypeModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("schooltype.index");
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

        $data = SchoolTypeModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "school_type",
            "school_short_name",
            "is_department",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new SchoolTypeModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new SchoolTypeModel())->getTable() .
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
            ->addColumn("is_Department", function ($data) {
                if ($data->is_department == 1) {
                    return "Yes";
                } else {
                    return "No";
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
                    "route" => "schooltype",
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
            SchoolTypeModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new SchoolTypeModel();
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
