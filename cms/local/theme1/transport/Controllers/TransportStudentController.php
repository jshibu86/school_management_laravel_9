<?php

namespace cms\transport\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\transport\Models\TransportStudents;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;
use cms\section\Models\SectionModel;
use Yajra\DataTables\Facades\DataTables;
use cms\academicyear\Models\AcademicyearModel;
use cms\transport\Models\TransportModel;
use Session;
use DB;
use CGate;
use cms\fees\Models\AcademicFeeModel;
use cms\transport\Models\TransportRoute;
use cms\transport\Models\TransportRouteBusMapping;
use cms\transport\Models\TransportRouteStopMapping;
use cms\transport\Models\TransportStop;
use Configurations;

class TransportStudentController extends Controller
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
            $academic_year = $request->query->get("academic_year", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $stop_id = $request->query->get("stop_id", 0);
            $bus_id = $request->query->get("bus_id", 0);
            $route_id = $request->query->get("route_id", 0);

            $class_name = LclassModel::classname($class_id);
            $section_name = SectionModel::sectionname($section_id);
            $stop_name = TransportStop::stopname($stop_id);
            $route_name = TransportRoute::routename($route_id);
            $bus_name = TransportModel::transportname($bus_id);
            $acyear = AcademicyearModel::academicyear($academic_year);

            $assigenstudents = TransportStudents::where(
                "transport_stop_id",
                $stop_id
            )
                ->where("transport_vehicle_id", $bus_id)
                ->where("transport_route_id", $route_id)
                ->pluck("student_id")
                ->toArray();
            $alreadyassignstudents = TransportStudents::where("status", 1)
                ->where("transport_stop_id", "!=", $stop_id)

                ->where("transport_vehicle_id", "!=", $bus_id)
                ->orWhere("transport_vehicle_id", "=", $bus_id)
                ->where("transport_route_id", "!=", $route_id)
                ->orWhere("transport_route_id", "=", $route_id)
                ->pluck("student_id")
                ->toArray();

            $students = StudentsModel::where([
                "status" => 1,
                "academic_year" => $academic_year,
                "class_id" => $class_id,
                "section_id" => $section_id,
            ])
                ->whereNull("deleted_at")
                ->get();

            $view = view(
                "transport::admin.assignstudents.includes.studentstable",
                [
                    "class_name" => $class_name,
                    "section_name" => $section_name,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "acyear" => $acyear,
                    "academicyear_id" => $academic_year,
                    "students" => $students,
                    "bus_id" => $bus_id,
                    "assigenstudents" => $assigenstudents,
                    "alreadyassignstudents" => $alreadyassignstudents,
                    "stop_name" => $stop_name,
                    "route_name" => $route_name,
                    "bus_name" => $bus_name,
                ]
            )->render();

            return response()->json([
                "viewfile" => $view,
                "students" => $students,
                "alreadyassignstudents" => $alreadyassignstudents,
            ]);
        }
        $academicyears = Configurations::getAcademicyears();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $stops = TransportStop::where("status", 1)
            ->pluck("stop_name", "id")
            ->toArray();
        return view("transport::admin.assignstudents.index", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "stops" => $stops,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academicyears = Configurations::getAcademicyears();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        return view("transport::admin.assignstudents.edit", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
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
        // dd($request->all());
        $this->validate($request, [
            "academic_year" => "required",
            "transport_stop_id" => "required",
        ]);
        DB::beginTransaction();
        try {
            $count = TransportStudents::where(
                "transport_stop_id",
                $request->transport_stop_id
            )
                ->where("transport_vehicle_id", $request->transport_vehicle_id)
                ->pluck("student_id")
                ->toArray();
            //dd($count, $request->students);
            if (sizeof($count) > 0) {
                $difference = array_diff($count, $request->students);

                //dd($difference);

                if (sizeof($difference)) {
                    TransportStudents::where(
                        "transport_stop_id",
                        $request->transport_stop_id
                    )
                        ->where(
                            "transport_vehicle_id",
                            $request->transport_vehicle_id
                        )
                        ->whereIn("student_id", $difference)
                        ->delete();

                    AcademicFeeModel::where([
                        "academic_year" => $request->academic_year,
                        "type" => "transport",
                    ])
                        ->whereIn("student_id", $difference)
                        ->update([
                            "leaved_date" => date("Y-m-d"),
                            "status" => 0,
                        ]);
                }
            }

            //dd($count);

            // save fee_info

            $months_transport = Configurations::GetMonthsOfAcademicYear(
                $request->academic_year,
                date("Y-m-d")
            );

            //dd(json_encode($months_transport));

            $transport_stop_amount = TransportStop::find(
                $request->transport_stop_id
            )->fare_amount;

            if ($request->students) {
                foreach ($request->students as $student) {
                    $exists = TransportStudents::where(
                        "transport_stop_id",
                        $request->transport_stop_id
                    )
                        ->where(
                            "transport_vehicle_id",
                            $request->transport_vehicle_id
                        )
                        ->where("student_id", $student)
                        ->first();
                    if (!$exists) {
                        $obj = new TransportStudents();
                        $obj->academic_year = $request->academic_year;
                        $obj->transport_stop_id = $request->transport_stop_id;
                        $obj->transport_vehicle_id =
                            $request->transport_vehicle_id;
                        $obj->transport_route_id = $request->transport_route_id;
                        $obj->semester_id = $request->term_id;
                        $obj->student_id = $student;
                        $obj->date_of_reg = date("Y-m-d");
                        if ($obj->save()) {
                            // save academic fee

                            $fee = new AcademicFeeModel();
                            $fee->academic_year = $request->academic_year;
                            $fee->student_id = $student;
                            $fee->model_id = $obj->id;
                            $fee->model_name = "TransportStudents";
                            $fee->added_date = date("Y-m-d");
                            $fee->type = "transport";
                            $fee->fee_name = "Transport Fees";
                            $fee->due_amount =
                                sizeof($months_transport) *
                                $transport_stop_amount;
                            $fee->month_info = json_encode($months_transport);
                            $fee->save();
                        }
                    }
                }
            }

            // dd($months_transport);
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
                ->route("transportstudent.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("transportstudent.index");
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
        $data = TransportStudents::find($id);
        return view("1::admin.edit", ["layout" => "edit", "data" => $data]);
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
        dd($request->all());
        $this->validate($request, [
            "academic_year" => "required",
            "transport_stop_id" => "required",
        ]);

        try {
            $obj = TransportStudents::find($id);
            $obj->name = $request->name;
            $obj->desc = $request->desc;
            $obj->status = $request->status;
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
        if (!empty($request->selected_1)) {
            $delObj = new TransportStudents();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new TransportStudents();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("1.index");
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

        $data = TransportStudents::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new TransportStudents())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new TransportStudents())->getTable() .
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
                    "route" => "1",
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

    public function getstopvehicle(Request $request)
    {
        if ($request->ajax()) {
            $stop_id = $request->query->get("stop_id", 0);

            //getting route ids

            $route_ids = TransportRouteStopMapping::where(
                "transport_stop_id",
                $stop_id
            )->pluck("transport_route_id");

            //getting vehicle ids

            $vehicle_ids = TransportRouteBusMapping::whereIn(
                "transport_route_id",
                $route_ids
            )->pluck("transport_vehicle_id");

            $routes = TransportRoute::where("status", 1)

                ->whereIn("id", $route_ids)
                ->select([
                    "transport_route.id as id",
                    DB::raw(
                        "CONCAT(transport_route.from, ' to ', transport_route.to) as text"
                    ),
                ])
                ->get();

            $vehicles = TransportModel::whereIn("id", $vehicle_ids)
                ->select([
                    "transport_vehicle.id as id",
                    DB::raw(
                        "CONCAT(transport_vehicle.bus_no, ' - ', transport_vehicle.vehicle_name) as text"
                    ),
                ])
                ->get();

            return response()->json([
                "routes" => $routes,
                "vehicles" => $vehicles,
            ]);
        }
    }
    function statusChange(Request $request)
    {
        CGate::authorize("edit-1");
        if ($request->ajax()) {
            TransportStudents::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new TransportStudents();
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
