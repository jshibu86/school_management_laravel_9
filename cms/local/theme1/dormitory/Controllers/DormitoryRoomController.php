<?php

namespace cms\dormitory\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\dormitory\Models\DormitoryRoomModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\dormitory\Models\DormitoryModel;
use cms\dormitory\Models\DormitoryRoomTypeModel;
use cms\dormitory\Models\DormitoryStudentModel;

class DormitoryRoomController extends Controller
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
            $dormitory_id = $request->query->get("dormitory_id", 0);

            $rooms = DormitoryRoomModel::where("dormitory_rooms.status", 1)
                ->where("dormitory_id", $dormitory_id)
                ->select([
                    "dormitory_rooms.id as id",
                    DB::raw(
                        "CONCAT(dormitory_rooms.room_number,'-',dormitory_room_type.room_type,' ','Room') as text"
                    ),
                ])
                ->join(
                    "dormitory_room_type",
                    "dormitory_room_type.id",
                    "=",
                    "dormitory_rooms.room_type"
                )
                ->get();

            return $rooms;
        }
        return view("dormitory::admin.room.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dormitory = DormitoryModel::where("status", 1)
            ->pluck("dormitory_name", "id")
            ->toArray();
        $roomtypes = DormitoryRoomTypeModel::where("status", 1)
            ->pluck("room_type", "id")
            ->toArray();
        return view("dormitory::admin.room.edit", [
            "layout" => "create",
            "dormitory" => $dormitory,
            "roomtypes" => $roomtypes,
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
            "cost_per_bed" => "required",
            "number_of_bed" => "required",
        ]);

        $is_exists_room = DormitoryRoomModel::where([
            "dormitory_id" => $request->dormitory_id,
            "room_number" => $request->room_number,
        ])->first();

        if ($is_exists_room) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "exception_error",
                    "This Room Number Already Available in this Dormitory"
                );
        }
        DB::beginTransaction();
        try {
            $obj = new DormitoryRoomModel();
            $obj->dormitory_id = $request->dormitory_id;
            $obj->room_type = $request->room_type;
            $obj->room_number = $request->room_number;
            $obj->number_of_bed = $request->number_of_bed;
            $obj->cost_per_bed = $request->cost_per_bed;
            $obj->room_description = $request->room_description;
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
                ->route("dormitoryroom.index")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("dormitoryroom.index");
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
        $data = DormitoryRoomModel::find($id);

        $dormitory = DormitoryModel::where("status", 1)
            ->pluck("dormitory_name", "id")
            ->toArray();
        $roomtypes = DormitoryRoomTypeModel::where("status", 1)
            ->pluck("room_type", "id")
            ->toArray();
        return view("dormitory::admin.room.edit", [
            "layout" => "edit",
            "dormitory" => $dormitory,
            "roomtypes" => $roomtypes,
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
            "cost_per_bed" => "required",
            "number_of_bed" => "required",
        ]);
        try {
            $obj = DormitoryRoomModel::find($id);

            $obj->room_type = $request->room_type;

            $obj->number_of_bed = $request->number_of_bed;
            $obj->cost_per_bed = $request->cost_per_bed;
            $obj->room_description = $request->room_description;
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
        return redirect()->route("dormitoryroom.index");
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
            $delObj = new DormitoryRoomModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new DormitoryRoomModel();
            $delItem = $delObj->find($id);

            $students = DormitoryStudentModel::where("room_id", $delItem->id)
                ->where("dormitory_id", $delItem->dormitory_id)
                ->count();
            if ($students > 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "This Room Has Some students Available kindly remove before delete room"
                    );
            } else {
                $delItem->delete();
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("dormitoryroom.index");
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

        $data = DormitoryRoomModel::with("dormitory")
            ->select(
                DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                "dormitory_rooms.id as id",
                "dormitory_id",
                "dormitory.dormitory_name as dormitory_name",
                "dormitory_room_type.room_type as droom_type",
                "dormitory_rooms.room_type as room_type",
                "room_number",
                "number_of_bed",

                DB::raw(
                    "(CASE WHEN " .
                        DB::getTablePrefix() .
                        (new DormitoryRoomModel())->getTable() .
                        '.status = "0" THEN "Disabled"
            WHEN ' .
                        DB::getTablePrefix() .
                        (new DormitoryRoomModel())->getTable() .
                        '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
                )
            )
            ->join(
                "dormitory",
                "dormitory.id",
                "=",
                "dormitory_rooms.dormitory_id"
            )
            ->join(
                "dormitory_room_type",
                "dormitory_room_type.id",
                "=",
                "dormitory_rooms.room_type"
            )
            ->where("dormitory_rooms.status", "!=", -1);

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("noofstudents", function ($data) {
                $students = DormitoryStudentModel::where("room_id", $data->id)
                    ->where("dormitory_id", $data->dormitory_id)
                    ->count();
                return $students;
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
                    "route" => "dormitoryroom",
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
            DormitoryRoomModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new DormitoryRoom();
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
