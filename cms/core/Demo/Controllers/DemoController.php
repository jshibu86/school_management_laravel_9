<?php

namespace cms\core\Demo\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\Demo\Models\DemoModel;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use cms\core\configurations\helpers\Configurations;
use cms\core\configurations\Controllers\MailController;
use cms\core\configurations\Models\ConfigurationModel;
use Carbon\Carbon;
use cms\core\Demo\Mail\DemoScheduleEmail;

use Session;
use DB;
use CGate;

class DemoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ConfigurationModel::where("name", "demo")
            ->pluck("parm")
            ->first();
        $shedule_request = DemoModel::where("status", 0)->count();
        $sheduled = DemoModel::whereIn("status", [1, -1])->count();
        $attend = DemoModel::whereIn("status", [2, 3])->count();
        return view("Demo::admin.index", [
            "data" => $data,
            "shedule_request" => $shedule_request,
            "sheduled" => $sheduled,
            "attend" => $attend,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Demo::admin.edit", ["layout" => "create"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                "email" => ["required", Rule::unique("demos")],
                "phoneno" => ["required", Rule::unique("demos")],
                "school_name" => "required",
                "contact_name" => "required",
                "role" => "required",
                "address" => "required",
                "city" => "required",
                "pincode" => "required",
                "country" => "nullable",
            ],
            [
                "phoneno.unique" => "Mobile Number Already Registered",
                "email.unique" => "Email Already Registered",
            ]
        );

        //dd($validator);
        DB::beginTransaction();
        try {
            $demoUser = new DemoModel();
            $demoUser->school_name = $request->school_name;
            $demoUser->contact_name = $request->contact_name;
            $demoUser->email = $request->email;
            $demoUser->phoneno = $request->phoneno;
            $demoUser->role = $request->role;
            $demoUser->gender = $request->gender;
            $demoUser->address = $request->address;
            $demoUser->city = $request->city;
            $demoUser->pincode = $request->pincode;
            $demoUser->country = $request->country;
            $timezone = Configurations::TIMEZONES["default"];
            $demoUser->created_at = Carbon::now($timezone)->toDateString();

            $demoUser->demo_id = DemoController::generateDemoNumber();
            $demoUser->save();

            $sendersemailID = $request->email;
            $senderName = $request->contact_name;
            // $mailRequest = new MailController;
            // $mailRequest->sendWelcomeMessageEmail($request);

            DB::commit();

            Session::flash("success", "Demo user created successfully");
            return redirect()->route("Demo.index");
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
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
        $data = DemoModel::find($id);
        return view("Demo::admin.edit", ["layout" => "edit", "data" => $data]);
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
        $demoUser = DemoModel::find($id);

        DB::beginTransaction();
        try {
            $demoUser->school_name = $request->school_name;
            $demoUser->contact_name = $request->contact_name;
            $demoUser->email = $request->email;
            $demoUser->phoneno = $request->phoneno;
            $demoUser->role = $request->role;
            $demoUser->gender = $request->gender;
            $demoUser->address = $request->address;
            $demoUser->city = $request->city;
            $demoUser->pincode = $request->pincode;
            $demoUser->country = $request->country;
            $timezone = Configurations::TIMEZONES["default"];
            // $currentDate = Carbon::now()
            //                     ->setTimezone($timezone)
            //                     ->format(Configurations::TIMEFORMAT);
            // $demoUser->updated_at = $currentDate;
            $demoUser->save();

            DB::commit();

            Session::flash("success", "Data updated successfully");
            return redirect()->route("Demo.index");
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($id) {
            DemoModel::where("id", $id)->delete();
        }

        Session::flash("success", "Data deleted Successfully!!");
        return redirect()->route("Demo.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-Demo");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        $data = DemoModel::select(
            "id",
            "demo_id",
            "school_name",
            "contact_name",
            "phoneno",
            "email",
            "role",
            "status",
            "created_at"
        )->whereIn("status", [0]);
        $datatables = Datatables::of($data)
            ->addColumn("joindate", function ($data) {
                $created_at = Carbon::parse($data->created_at)->format(
                    "jS F Y"
                );
                return "<span class='text-secondary'>" .
                    $created_at .
                    "</span>";
                //$subtime = Carbon::parse($data->exam_time)->format("H:i:s");
                //$date = $subdate . " " . $subtime;
            })
            ->addColumn("status", function ($data) {
                $status = strtolower($data->status);
                switch ($status) {
                    case "-1":
                        return '<span class="text-danger"> Expired </span>';
                    case "0":
                        return '<span class="text-warning"> Pending </span>';
                    case "1":
                        return '<span class="text-success">Scheduled</span>';
                    case "2":
                        return '<span class="text-sucess"><b>Attend</b></span>';
                    case "3":
                        return '<span class="text-sucess">Customer</span>';
                    default:
                        return '<span class="text-danger">Unknown</span>';
                }
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "id" => $data->id,
                    "route" => "Demo",
                    "showEdit" => true,
                    "showDelete" => true,
                    "showView" => false,
                    "editRoute" => "Demo.edit",
                    "deleteRoute" => "Demo.destroy",
                    "viewRoute" => "Demo.show",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["joindate", "schedule", "status", "action"])
            ->make(true);
    }

    public function getScheduleData(Request $request)
    {
        CGate::authorize("view-Demo");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        $data = DemoModel::select(
            "id",
            "demo_id",
            "school_name",
            "contact_name",
            "phoneno",
            "email",
            "role",
            "status",
            "demo_date",
            "demo_time",
            DB::raw("DATE_FORMAT(created_at, '%e %b %Y ') AS created_at")
        )->whereIn("status", [1, -1]);

        $datatables = Datatables::of($data)
            ->addColumn("joindate", function ($data) {
                $created_at = Carbon::parse($data->created_at)->format(
                    "jS F Y"
                );

                return "<span class='text-secondary'>" .
                    $created_at .
                    "</span>";
            })
            ->addColumn("status", function ($data) {
                $status = strtolower($data->status);
                $demo_date = Carbon::parse($data->demo_date);

                $timezone = Configurations::TIMEZONES["default"];
                $now = Carbon::now($timezone)->toDateString();

                if ($demo_date < $now) {
                    $selectedDemo = DemoModel::find($data->id);
                    $selectedDemo->status = "-1";
                    $selectedDemo->save();
                    $status = -1;
                }
                switch ($status) {
                    case "-1":
                        return '<span class="text-danger"> Expired </span>';
                    case "0":
                        return '<span class="text-warning"> Pending </span>';
                    case "1":
                        return '<span class="text-success">Scheduled</span>';
                    case "2":
                        return '<span class="text-sucess"><b>Attend</b></span>';
                    case "3":
                        return '<span class="text-sucess">Customer</span>';
                    default:
                        return '<span class="text-danger">Unknown</span>';
                }
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "id" => $data->id,
                    "route" => "Demo",
                    "showEdit" => true,
                    "showDelete" => true,
                    "showView" => false,
                    "editRoute" => "Demo.edit",
                    "deleteRoute" => "Demo.destroy",
                    "viewRoute" => "Demo.show",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["joindate", "schedule", "status", "action"])
            ->make(true);
    }

    public function getAttendantData(Request $request)
    {
        CGate::authorize("view-Demo");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        $data = DemoModel::select(
            "id",
            "demo_id",
            "school_name",
            "contact_name",
            "phoneno",
            "email",
            "role",
            "status",
            "demo_date",
            "demo_time",
            DB::raw("DATE_FORMAT(created_at, '%e %b %Y ') AS created_at")
        )->whereIn("status", [2, 3]);

        $datatables = Datatables::of($data)
            ->addColumn("joindate", function ($data) {
                $created_at = Carbon::parse($data->created_at)->format(
                    "jS F Y"
                );

                return "<span class='text-secondary'>" .
                    $created_at .
                    "</span>";
            })
            ->addColumn("status", function ($data) {
                $status = strtolower($data->status);
                switch ($status) {
                    case "-1":
                        return '<span class="text-danger"> Expired </span>';
                    case "0":
                        return '<span class="text-warning"> Pending </span>';
                    case "1":
                        return '<span class="text-success">Scheduled</span>';
                    case "2":
                        return '<span class="text-success"><b>Attend</b></span>';
                    case "3":
                        return '<span class="text-sucess">Customer</span>';
                    default:
                        return '<span class="text-danger">Unknown</span>';
                }
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "id" => $data->id,
                    "route" => "Demo",
                    "showEdit" => true,
                    "showDelete" => true,
                    "showView" => false,
                    "editRoute" => "Demo.edit",
                    "deleteRoute" => "Demo.destroy",
                    "viewRoute" => "Demo.show",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["joindate", "schedule", "status", "action"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-Demo");

        if (!empty($request->selected_Demo)) {
            $obj = new DemoModel();
            foreach ($request->selected_Demo as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    function generateDemoNumber()
    {
        $lastDemoNo = DemoModel::orderBy("demo_id", "desc")->first();
        if ($lastDemoNo) {
            $lastDemoNo = (int) substr($lastDemoNo->demo_id, 2);
        } else {
            $lastDemoNo = 0;
        }
        $newNumber = $lastDemoNo + 1;
        $formattedNumber = "DM" . str_pad($newNumber, 5, "0", STR_PAD_LEFT);

        return $formattedNumber;
    }

    function scheduleDemo(Request $request)
    {
        $demo_id = $request->input("demoId");
        $demo_date = $request->input("demoDate");
        $demo_time = $request->input("demoTime");
        $demo_text = $request->input("demoText");

        $selectedDemo = DemoModel::find($demo_id);

        \Log::debug("Schedule Demo:", [
            "selectedDemo" => $selectedDemo,
        ]);

        if (!$selectedDemo) {
            return response->json(
                ["success" => false, "message" => "Selected Demo not found"],
                404
            );
        } else {
            $selectedDemo->demo_date = $demo_date;
            $selectedDemo->demo_time = $demo_time;
            $selectedDemo->status = "1";
            $selectedDemo->save();

            $mailRequest = new MailController();
            $sendEmail = $selectedDemo->email;
            $contact_name = $selectedDemo->contact_name;
            $mailRequest->sendDemoScheduleEmail(
                $demo_date,
                $demo_time,
                $sendEmail,
                $demo_text,
                $contact_name
            );
            Session::flash(
                "success",
                "Demo scheduled successfully. Email notification sent to the contact person."
            );
            return response()->json([
                "success" => true,
                "message" => "schedule Updated",
                "redirect" => route("Demo.index"),
            ]);
        }
    }

    function saveAttendance(Request $request)
    {
        $demo_id = $request->input("demoId");
        $selectedDemo = DemoModel::find($demo_id);

        \Log::debug("Approve Schedule Demo:", [
            "selectedDemo" => $selectedDemo,
        ]);

        if (!$selectedDemo) {
            return response->json(
                ["success" => false, "message" => "Selected Demo not found"],
                404
            );
        } else {
            $selectedDemo->status = "2";
            $selectedDemo->save();

            Session::flash("success", "Attendance updated successfully.");
            return response()->json([
                "success" => true,
                "message" => "Attendance Updated",
                "redirect" => route("Demo.index"),
            ]);
        }
    }

    function saveSettingMessage(Request $request)
    {
        $demo_msg = $request->input("demoSettingMsg");

        $obj = ConfigurationModel::where("name", "=", "demo")->first();
        if (count((array) $obj) == 0) {
            $obj = new ConfigurationModel();
        }

        $obj->name = "demo";
        $obj->parm = $demo_msg;
        $obj->save();

        Session::flash("success", "Setting Message updated successfully.");
        return response()->json([
            "success" => true,
            "message" => "Settings Updated",
            "redirect" => route("Demo.index"),
        ]);
    }
}
