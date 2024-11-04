<?php

namespace cms\transport\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\transport\Models\TransportRoute;
use cms\transport\Models\TransportModel;
use cms\transport\Models\TransportRouteBusMapping;
use cms\transport\Models\TransportRouteStopMapping;
use Illuminate\Support\Facades\Artisan;
use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\transport\Models\TransportStop;
use cms\transport\Models\TransportStudents;

class TransportRouteController extends Controller
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
        return view("transport::admin.route.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stops = TransportStop::where("status", 1)
            ->pluck("stop_name", "id")
            ->toArray();
        $vehicle = TransportModel::where("status", 1)
            ->select([
                "transport_vehicle.id as id",
                DB::raw(
                    "CONCAT(transport_vehicle.bus_no, ' - ', transport_vehicle.vehicle_name) as text"
                ),
            ])
            ->pluck("text", "id")
            ->toArray();
        return view("transport::admin.route.edit", [
            "layout" => "create",
            "stops" => $stops,
            "vehicle" => $vehicle,
            "stops_selected" => [],
            "bus_selected" => [],
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

        DB::beginTransaction();
        try {
            // save route info

            $route = new TransportRoute();
            $route->from = $request->from;
            $route->to = $request->to;
            $route->route_description = $request->route_description;
            if ($route->save()) {
                // save bus and stop mapping information
                if (count($request->vehicle)) {
                    foreach ($request->vehicle as $bus_data) {
                        $bus_info = new TransportRouteBusMapping();
                        $bus_info->transport_route_id = $route->id;
                        $bus_info->transport_vehicle_id = $bus_data;
                        $bus_info->save();
                    }
                }
                if (count($request->stops)) {
                    foreach ($request->stops as $stop_data) {
                        $stop_info = new TransportRouteStopMapping();
                        $stop_info->transport_route_id = $route->id;
                        $stop_info->transport_stop_id = $stop_data;
                        $stop_info->save();
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            dd($e);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("transportroute.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("transportroute.index");
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
        $data = TransportRoute::find($id);

        $stops = TransportStop::where("status", 1)
            ->pluck("stop_name", "id")
            ->toArray();

        $vehicle = TransportModel::where("status", 1)
            ->select([
                "transport_vehicle.id as id",
                DB::raw(
                    "CONCAT(transport_vehicle.bus_no, ' - ', transport_vehicle.vehicle_name) as text"
                ),
            ])
            ->pluck("text", "id")
            ->toArray();
        // dd($vehicle);

        $stops_selected = TransportRouteStopMapping::where(
            "transport_route_id",
            $data->id
        )
            ->pluck("transport_stop_id")
            ->toArray();

        $bus_selected = TransportRouteBusMapping::where(
            "transport_route_id",
            $data->id
        )
            ->pluck("transport_vehicle_id")
            ->toArray();

        return view("transport::admin.route.edit", [
            "layout" => "edit",
            "data" => $data,
            "stops" => $stops,
            "vehicle" => $vehicle,
            "stops_selected" => $stops_selected,
            "bus_selected" => $bus_selected,
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
        try {
            $route = TransportRoute::find($id);

            $route->from = $request->from;
            $route->to = $request->to;
            $route->route_description = $request->route_description;
            if ($route->save()) {
                // save bus and stop mapping information
                if (count($request->vehicle)) {
                    foreach ($request->vehicle as $bus_data) {
                        $bus_info = TransportRouteBusMapping::updateOrCreate(
                            [
                                //Add unique field combo to match here
                                //For example, perhaps you only want one entry per user:
                                "transport_route_id" => $route->id,
                                "transport_vehicle_id" => $bus_data,
                            ],
                            [
                                "transport_route_id" => $route->id,
                                "transport_vehicle_id" => $bus_data,
                            ]
                        );
                    }
                }
                if (count($request->stops)) {
                    foreach ($request->stops as $stop_data) {
                        $bus_info = TransportRouteStopMapping::updateOrCreate(
                            [
                                //Add unique field combo to match here
                                //For example, perhaps you only want one entry per user:
                                "transport_route_id" => $route->id,
                                "transport_stop_id" => $stop_data,
                            ],
                            [
                                "transport_route_id" => $route->id,
                                "transport_stop_id" => $stop_data,
                            ]
                        );
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            dd($e);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("transportroute.index");
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
            $delObj = new TransportRoute();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new TransportRoute();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("transportroute.index");
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

        $data = TransportRoute::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "from",
            "to",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new TransportRoute())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new TransportRoute())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )->where("status", "!=", -1);

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("noofstops", function ($data) {
                $stops = TransportRouteStopMapping::where(
                    "transport_route_id",
                    $data->id
                )->count();

                return $stops;
            })

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
                    "route" => "transportroute",
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
            TransportRoute::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new TransportRoute();
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
