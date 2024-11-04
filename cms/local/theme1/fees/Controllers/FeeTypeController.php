<?php

namespace cms\fees\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\fees\Models\FeeTypeModel;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use cms\fees\Models\SchoolTypeModel;
use Session;
use DB;
use CGate;

class FeeTypeController extends Controller
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
        return view("fees::feetype.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $school_type_info = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();
        return view("fees::feetype.edit", [
            "layout" => "create",
            "school_type_info" => $school_type_info,
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
        $this->validate($request, [
            "type_name" =>
                "required|min:3|max:50|unique:" .
                (new FeeTypeModel())->getTable() .
                ",type_name",
        ]);
        DB::beginTransaction();
        try {
            $obj = new FeeTypeModel();
            $obj->type_name = $request->type_name;
            $obj->type_slug = str_slug(explode(" ", $request->type_name)[0]);
            $obj->type_description = $request->type_description;

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
                ->route("feetype.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("feetype.index");
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
        $data = FeeTypeModel::find($id);
        $school_type_info = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();
        return view("fees::feetype.edit", [
            "layout" => "edit",
            "data" => $data,
            "school_type_info" => $school_type_info,
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
            "type_name" =>
                "required|min:3|max:50|unique:" .
                (new FeeTypeModel())->getTable() .
                ",type_name," .
                $id,
        ]);

        try {
            $obj = FeeTypeModel::find($id);
            $obj->type_name = $request->type_name;
            $obj->type_description = $request->type_description;

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
        return redirect()->route("feetype.index");
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
            $delObj = new FeeTypeModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new FeeTypeModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("feetype.index");
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

        $data = FeeTypeModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "type_name",
            "type_description",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new FeeTypeModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new FeeTypeModel())->getTable() .
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
                    "route" => "feetype",
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
            FeeTypeModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new FeeTypeModel();
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
