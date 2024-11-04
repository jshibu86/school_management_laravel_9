<?php

namespace cms\classtimetable\Controllers;

use DB;
use CGate;

use Session;
use Configurations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\classtimetable\Models\PeriodClassMappingModel;
use Yajra\DataTables\Facades\DataTables;
use cms\classtimetable\Models\PeriodModel;
use cms\lclass\Models\LclassModel;
use cms\academicyear\Models\AcademicyearModel;
use cms\classtimetable\Models\ClasstimetableModel;
use cms\exam\Models\ExamTermModel;
use DateTime;

class PeriodController extends Controller
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
            $period_id = $request->query->get("period_id", 0);
            $academic_year = $request->query->get("academic_year", 0);
            $class_id = $request->query->get("class_id", 0);
            $type = $request->query->get("type", 0);

            $exist_timetable = ClasstimetableModel::where(
                "period_id",
                $period_id
            )->count();

            if ($exist_timetable) {
                if ($type == 1) {
                    PeriodClassMappingModel::where([
                        "id" => $period_id,
                    ])->delete();

                    ClasstimetableModel::where([
                        "academic_year" => $academic_year,
                        "period_id" => $period_id,
                        "class_id" => $class_id,
                    ])->delete();

                    return response()->json(["success" => "deleted"]);
                }
                return response()->json(["error" => "found"]);
            } else {
                PeriodClassMappingModel::where(["id" => $period_id])->delete();

                return response()->json(["success" => "deleted"]);
            }
        }
        return view("classtimetable::admin.period.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academicyears = Configurations::getAcademicyears();
        $info = Configurations::getAcademicandTermsInfo();
        $added_classes = PeriodModel::where("status", 1)
            ->pluck("class_id")
            ->toArray();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->whereNotIn("id", $added_classes)
            ->pluck("name", "id")
            ->toArray();
        //dd($info);
        return view("classtimetable::admin.period.edit", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "addperiod" => true,
            "info" => $info,
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
        $this->validate(
            $request,
            [
                "academic_year" => "required",
                "academic_term" => "required|array",
                "class_id" => "required|array",
                "starttime" => "required|array",
                "endtime" => "required|array",
                "periodtype" => "required|array",
            ],
            [
                "academic_term.required" => "Academic term is required.",
                "class_id.required" => "Class is required.",
                "starttime.required" => "Start Time is required.",
                "endtime.required" => "End Time is required.",
                "periodtype.required" => "Period Type is required.",
            ]
        );
        DB::beginTransaction();
        try {
            ini_set("max_execution_time", 180);
            // convert array form start and endtime basis

            $timeData = [];
            foreach ($request->class_id as $classid) {
                $obj = new PeriodModel();
                $obj->academic_year = $request->academic_year;
                $obj->academic_term = $request->academic_term;
                $obj->class_id = $classid;
                if ($obj->save()) {
                    if (!empty($request->starttime)) {
                        for ($i = 0; $i < sizeof($request->starttime); $i++) {
                            $start = date(
                                "g:i a",
                                strtotime($request->starttime[$i])
                            );
                            $end = date(
                                "g:i a",
                                strtotime($request->endtime[$i])
                            );

                            $start_time = $request->starttime[$i];
                            $end_time = $request->endtime[$i];

                            $start_datetime = new DateTime(
                                date("Y-m-d") . " " . $start_time
                            );
                            $end_datetime = new DateTime(
                                date("Y-m-d") . " " . $end_time
                            );
                            $interval = $start_datetime->diff($end_datetime);
                            $hour = $interval->format("%h hour");
                            $min = $interval->format("%i min");
                            $sec = $interval->format("%s second");

                            $timeData[] = [
                                "period_class_id" => $obj->id,
                                "from" => $start,
                                "to" => $end,
                                "type" => $request->periodtype[$i],
                                "break_min" => $hour . " " . $min . " " . $sec,
                            ];
                        }

                        // save mapping
                    } else {
                        return redirect()
                            ->back()
                            ->withInput()
                            ->with("exception_error", "Please Add Any periods");
                    }
                }
            }

            PeriodClassMappingModel::insert($timeData);

            // dd($minutes);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            //dd($message);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("period.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("period.index");
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
        $data = PeriodModel::with("periods")->find($id);

        $acyear = AcademicyearModel::where("id", $data->academic_year)->first()
            ->year;
        $term = ExamTermModel::where("id", $data->academic_term)->first()
            ->exam_term_name;
        $class = LclassModel::where("id", $data->class_id)->first()->name;

        return view("classtimetable::admin.period.edit", [
            "layout" => "edit",
            "data" => $data,
            "acyear" => $acyear,
            "class" => $class,
            "term" => $term,
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
        //dd($request->all());
        DB::beginTransaction();
        try {
            ini_set("max_execution_time", 180);
            // convert array form start and endtime basis

            $obj = PeriodModel::find($id);

            //PeriodClassMappingModel::where("period_class_id", $id)->delete();

            $timeData = [];
            if (!empty($request->starttime)) {
                for ($i = 0; $i < sizeof($request->starttime); $i++) {
                    $start = date("g:i a", strtotime($request->starttime[$i]));
                    $end = date("g:i a", strtotime($request->endtime[$i]));

                    $start_time = $request->starttime[$i];
                    $end_time = $request->endtime[$i];

                    $start_datetime = new DateTime(
                        date("Y-m-d") . " " . $start_time
                    );
                    $end_datetime = new DateTime(
                        date("Y-m-d") . " " . $end_time
                    );
                    $interval = $start_datetime->diff($end_datetime);
                    $hour = $interval->format("%h hour");
                    $min = $interval->format("%i min");
                    $sec = $interval->format("%s second");

                    if (isset($request->map_id[$i])) {
                        // exists data
                        $exists = PeriodClassMappingModel::find(
                            $request->map_id[$i]
                        );
                        if ($exists) {
                            $exists->from = $start;
                            $exists->to = $end;
                            $exists->type = $request->periodtype[$i];
                            $exists->break_min =
                                $hour . " " . $min . " " . $sec;
                            $exists->update();
                        }
                    } else {
                        // mew data
                        $pdata = new PeriodClassMappingModel();
                        $pdata->period_class_id = $id;
                        $pdata->from = $start;
                        $pdata->to = $end;
                        $pdata->type = $request->periodtype[$i];
                        $pdata->break_min = $hour . " " . $min . " " . $sec;
                        $pdata->save();
                    }

                    // $timeData[] = [
                    //     "period_class_id" => $id,
                    //     "from" => $start,
                    //     "to" => $end,
                    //     "type" => $request->periodtype[$i],
                    //     "break_min" => $hour . " " . $min . " " . $sec,
                    // ];
                }

                // save mapping
                //PeriodClassMappingModel::insert($timeData);
            } else {
                DB::rollback();

                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", "Something Went Wrong");
            }

            // dd($minutes);

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
        return redirect()->route("period.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //dd("here");
        if (!empty($request->selected_1)) {
            $delObj = new PeriodModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new PeriodModel();
            $delItem = $delObj->find($id);

            $assigendperiods = PeriodClassMappingModel::where(
                "period_class_id",
                $id
            )
                ->pluck("id")
                ->toArray();

            $exist_timetable = ClasstimetableModel::WhereIn(
                "period_id",
                $assigendperiods
            )->count();
            if ($exist_timetable) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "This Period Already Assigned Timetables"
                    );
            } else {
                PeriodClassMappingModel::where(
                    "period_class_id",
                    $id
                )->delete();
                $delItem->delete();

                Session::flash("success", "data Deleted Successfully!!");
                return redirect()->route("period.index");
            }
        }
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

        $data = PeriodModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "period_class.id as id",
            "academic_year",
            "class_id",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new PeriodModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new PeriodModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->join("lclass", "lclass.id", "=", "period_class.class_id")
            ->where("period_class.status", "!=", -1);

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("academicyear", function ($data) {
                return AcademicyearModel::where(
                    "id",
                    $data->academic_year
                )->first()->year;
            })
            ->addColumn("class", function ($data) {
                return LclassModel::where("id", $data->class_id)->first()->name;
            })
            ->addColumn("periods", function ($data) {
                $count = PeriodClassMappingModel::where(
                    "period_class_id",
                    $data->id
                )->count();
                return $count . " " . "Periods Added";
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
                    "route" => "period",
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
            PeriodModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new PeriodModel();
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
