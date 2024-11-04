<?php

namespace cms\dormitory\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\dormitory\Models\DormitoryModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\dormitory\Models\DormitoryRoomModel;

class DormitoryController extends Controller
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
        return view("dormitory::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("dormitory::admin.edit", ["layout" => "create"]);
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
            "dormitory_name" =>
                "required|min:3|max:50|unique:" .
                (new DormitoryModel())->getTable() .
                ",dormitory_name",
            "dormitory_address" => "required",
            "dormitory_type" => "required",
        ]);
        DB::beginTransaction();
        try {
            $obj = new DormitoryModel();
            $obj->dormitory_name = $request->dormitory_name;
            $obj->dormitory_address = $request->dormitory_address;
            $obj->dormitory_type = $request->dormitory_type;
            $obj->dormitory_description = $request->dormitory_description;
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
                ->route("dormitory.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("dormitory.index");
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
        $data = DormitoryModel::find($id);
        return view("dormitory::admin.edit", [
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
            "dormitory_name" =>
                "required|max:50|unique:" .
                (new DormitoryModel())->getTable() .
                ",dormitory_name," .
                $id,
            "dormitory_address" => "required",
            "dormitory_type" => "required",
        ]);

        try {
            $obj = DormitoryModel::find($id);
            $obj->dormitory_name = $request->dormitory_name;
            $obj->dormitory_address = $request->dormitory_address;
            $obj->dormitory_type = $request->dormitory_type;
            $obj->dormitory_description = $request->dormitory_description;
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
        return redirect()->route("dormitory.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_dormitory)) {
            $delObj = new DormitoryModel();
            foreach ($request->selected_dormitory as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new DormitoryModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("dormitory.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-dormitory");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = DormitoryModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "dormitory_name",
            "dormitory_type",
            "dormitory_address",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new DormitoryModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new DormitoryModel())->getTable() .
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

            ->addColumn("totalrooms", function ($data) {
                $rooms = DormitoryRoomModel::where(
                    "dormitory_id",
                    $data->id
                )->count();

                return $rooms;
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
                    "route" => "dormitory",
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
        CGate::authorize("edit-dormitory");
        if ($request->ajax()) {
            DormitoryModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_dormitory)) {
            $obj = new DormitoryModel();
            foreach ($request->selected_dormitory as $k => $v) {
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
