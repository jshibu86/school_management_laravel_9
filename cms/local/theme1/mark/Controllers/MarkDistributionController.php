<?php

namespace cms\mark\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\mark\Models\MarkDistributionModel;
use cms\mark\Models\SchoolTypeModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use Configurations;
use Carbon\Carbon;
class MarkDistributionController extends Controller
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
        return view("mark::markdistribution.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("mark::markdistribution.edit", ["layout" => "create"]);
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
            "distribution_name" =>
                "required|max:50|unique:" .
                (new MarkDistributionModel())->getTable() .
                ",distribution_name",
            "mark" => "required|integer",
        ]);
        DB::beginTransaction();
        try {
            $obj = new MarkDistributionModel();
            $obj->distribution_name = $request->distribution_name;
            $obj->mark = $request->mark;

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
                ->route("markdistribution.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("markdistribution.index");
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
        $data = MarkDistributionModel::find($id);
        return view("mark::markdistribution.edit", [
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
            "distribution_name" =>
                "required|min:3|max:50|unique:" .
                (new MarkDistributionModel())->getTable() .
                ",distribution_name," .
                $id,
            "mark" => "required",
        ]);

        try {
            DB::beginTransaction();
            $obj = MarkDistributionModel::find($id);
            $obj->distribution_name = $request->distribution_name;
            $obj->mark = $request->mark;

            $obj->update();

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

        Session::flash("success", "saved successfully");
        return redirect()->route("markdistribution.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // dd($id);
        if (!empty($request->selected_1)) {
            $delObj = new MarkDistributionModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new MarkDistributionModel();
            $delItem = $delObj->where("school_type_id", $id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("markdistribution.index");
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
            ->addColumn("distribution", function ($data) {
                if ($data->id) {
                    $distribution = MarkDistributionModel::where(
                        "school_type_id",
                        $data->id
                    )
                        ->select("distribution_name", "mark")
                        ->where("status", 1)
                        ->get();
                    $is_exists = MarkDistributionModel::where(
                        "school_type_id",
                        $data->id
                    )
                        ->select("school_type_id")
                        ->where("status", 1)
                        ->get();
                    $dataModel = [];
                    foreach ($distribution as $key => $value) {
                        # code...
                        $dataModel[] = [
                            $value->distribution_name => $value->mark,
                        ];
                    }
                    $name = json_encode($dataModel);
                    if ($name !== "[]" && !empty($name)) {
                        // Check if the encoded JSON string is not an empty array
                        return $name;
                    } else {
                        return "NA";
                    }
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
                    "route" => "markdistribution",
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

    public function distribute_score(Request $request)
    {
        $id = $request->id;

        $school_type = SchoolTypeModel::where("id", $id)
            ->pluck("school_type", "id")
            ->first();
        //   dd( $school_type);
        $school = MarkDistributionModel::where("school_type_id", $id)
            ->select(
                "distribution_name",
                "mark",
                "status",
                "school_type_id",
                "id"
            )
            ->get();
        $is_exists = MarkDistributionModel::where(
            "school_type_id",
            $id
        )->exists();
        //   dd($is_exists);
        if ($is_exists) {
            $distribution_data = $school;
            //dd($distribution_data);

            return view("mark::markdistribution.distribute_mark", [
                "layout" => "edit",
                "distribution" => $distribution_data,
                "school_type" => $school_type,
                "id" => $id,
            ]);
        } else {
            return view("mark::markdistribution.distribute_mark", [
                "layout" => "create",
                "school_type" => $school_type,
                "id" => $id,
            ]);
        }
    }
}
