<?php

namespace cms\transport\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\transport\Models\TransportModel;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use cms\core\configurations\Traits\FileUploadTrait;
use Session;
use DB;
use CGate;
use Configurations;
use cms\transport\Models\TransportStaff;

class TransportController extends Controller
{
    use FileUploadTrait;
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
        return view("transport::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // get already assigned staffs

        $staff_ids = TransportModel::where("status", 1)->pluck("staff_id");
        $transport_staff = TransportStaff::where("status", 1)
            ->whereNotIn("id", $staff_ids)
            ->pluck("employee_name", "id")
            ->toArray();
        return view("transport::admin.edit", [
            "layout" => "create",
            "transport_staff" => $transport_staff,
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
        $this->validate($request, [
            "vehicle_reg_no" =>
                "required|unique:" .
                (new TransportModel())->getTable() .
                ",vehicle_reg_no",
            "staff_id" => "required",
            "capacity" => "required",
        ]);
        DB::beginTransaction();
        try {
            $bus_info = TransportModel::withTrashed()
                ->latest("id")
                ->first();
            $bus_no = Configurations::GenerateUsername(
                $bus_info != null ? $bus_info->bus_no : null,
                "B"
            );
            $obj = new TransportModel();
            $obj->vehicle_reg_no = $request->vehicle_reg_no;
            $obj->vehicle_name = $request->vehicle_name;
            $obj->staff_id = $request->staff_id;
            $obj->bus_no = $bus_no;
            $obj->capacity = $request->capacity;
            $obj->vehicle_description = $request->vehicle_description;
            if ($request->imagec) {
                $obj->image = $this->uploadImage($request->imagec, "image");
            }
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

        Session::flash("success", "saved successfully");
        return redirect()->route("transport.index");
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
        $data = TransportModel::find($id);
        $transport_staff = TransportStaff::where("status", 1)
            ->pluck("employee_name", "id")
            ->toArray();
        return view("transport::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "transport_staff" => $transport_staff,
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
            "vehicle_reg_no" => [
                "required",
                Rule::unique("transport_vehicle")
                    ->whereNull("deleted_at")
                    ->ignore($id),
            ],
            "staff_id" => "required",
            "capacity" => "required",
        ]);

        try {
            $obj = TransportModel::find($id);
            $obj->vehicle_reg_no = $request->vehicle_reg_no;
            $obj->vehicle_name = $request->vehicle_name;
            $obj->staff_id = $request->staff_id;

            $obj->capacity = $request->capacity;
            $obj->vehicle_description = $request->vehicle_description;
            if ($request->imagec) {
                $obj->image = $this->uploadImage($request->imagec, "image");
            }
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
        return redirect()->route("transport.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_transport)) {
            $delObj = new TransportModel();
            foreach ($request->selected_transport as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new TransportModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("transport.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-transport");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = TransportModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "bus_no",
            "vehicle_reg_no",
            "capacity",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new TransportModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new TransportModel())->getTable() .
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
                    "route" => "transport",
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
        CGate::authorize("edit-transport");
        if ($request->ajax()) {
            TransportModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_transport)) {
            $obj = new TransportModel();
            foreach ($request->selected_transport as $k => $v) {
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
