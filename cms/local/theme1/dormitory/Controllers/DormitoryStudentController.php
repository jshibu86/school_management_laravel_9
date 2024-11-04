<?php

namespace cms\dormitory\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\dormitory\Models\DormitoryRoomStudentModel;
use cms\academicyear\Models\AcademicyearModel;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;
use cms\section\Models\SectionModel;
use Yajra\DataTables\Facades\DataTables;
use Configurations;
use Session;
use DB;
use CGate;
use cms\dormitory\Models\DormitoryModel;
use cms\dormitory\Models\DormitoryRoomModel;
use cms\dormitory\Models\DormitoryStudentModel;
use cms\fees\Models\AcademicFeeModel;

class DormitoryStudentController extends Controller
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
            $type = $request->query->get("type", 0);
            $length = $request->query->get("length", 0);
            $academic_year = $request->query->get("academic_year", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $dormitory_id = $request->query->get("dormitory_id", 0);
            $room_id = $request->query->get("room_id", 0);
            $semester_id = $request->query->get("semester_id", 0);

            $dormitory = DormitoryModel::find($dormitory_id);
            $room = DormitoryRoomModel::find($room_id);

            $assign_beds = DormitoryStudentModel::where([
                "academic_year" => $academic_year,
                "dormitory_id" => $dormitory_id,
                "room_id" => $room_id,
            ])->count();

            $available_beds = $room->number_of_bed - $assign_beds;

            if ($type === "room") {
                if ($available_beds === 0) {
                    return response()->json(["count" => false]);
                }
                $available_beds_check =
                    $available_beds >= $length - $assign_beds;
                return response()->json([
                    "count" => $available_beds_check,
                ]);
            } else {
                $class_name = LclassModel::classname($class_id);
                $section_name = SectionModel::sectionname($section_id);
                $acyear = AcademicyearModel::academicyear($academic_year);
            }

            $assigenstudents = DormitoryStudentModel::where("room_id", $room_id)
                ->where("dormitory_id", $dormitory_id)
                ->where("academic_year", $academic_year)
                ->pluck("student_id")
                ->toArray();
            $alreadyassignstudents = DormitoryStudentModel::where("status", 1)
                ->where("dormitory_id", "!=", $dormitory_id)

                ->where("room_id", "!=", $room_id)
                ->orWhere("dormitory_id", "=", $dormitory_id)

                ->pluck("student_id")
                ->toArray();

            $students = StudentsModel::where([
                "status" => 1,
                "academic_year" => $academic_year,
                "class_id" => $class_id,
                "section_id" => $section_id,
            ])->whereNull("deleted_at");

            if ($dormitory->dormitory_type == "boys") {
                $students = $students->where("gender", "male")->get();
            } else {
                $students = $students->where("gender", "female")->get();
            }

            $view = view(
                "dormitory::admin.dormitorystudent.includes.studentstable",
                [
                    "class_name" => $class_name,
                    "section_name" => $section_name,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "acyear" => $acyear,
                    "academicyear_id" => $academic_year,
                    "students" => $students,
                    "dormitory" => $dormitory,
                    "room" => $room,
                    "assigenstudents" => $assigenstudents,
                    "alreadyassignstudents" => $alreadyassignstudents,
                    "available_beds" => $available_beds,
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

        $dormitory = DormitoryModel::where("status", 1)
            ->select([
                "dormitory.id as id",
                DB::raw(
                    "CONCAT(dormitory.dormitory_name, ' - ', dormitory.dormitory_type) as text"
                ),
            ])
            ->pluck("text", "id");
        return view("dormitory::admin.dormitorystudent.index", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "dormitory" => $dormitory,
            "sections" => [],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("1::admin.edit", ["layout" => "create"]);
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
            "academic_year" => "required",
            "dormitory_id" => "required",
            "room_id" => "required",
        ]);
        DB::beginTransaction();
        try {
            $count = DormitoryStudentModel::where(
                "dormitory_id",
                $request->dormitory_id
            )
                ->where("room_id", $request->room_id)
                ->where("class_id", $request->class_id)
                ->where("section_id", $request->section_id)
                ->pluck("student_id")
                ->toArray();

            $get_room_members = DormitoryStudentModel::where(
                "dormitory_id",
                $request->dormitory_id
            )
                ->where("room_id", $request->room_id)
                ->get();

            // dd($get_room_members, $request->all());
            if (sizeof($count)) {
                $difference = array_diff($count, $request->students);
                if (sizeof($difference)) {
                    DormitoryStudentModel::where(
                        "dormitory_id",
                        $request->dormitory_id
                    )
                        ->where("room_id", $request->room_id)
                        ->where("class_id", $request->class_id)
                        ->where("section_id", $request->section_id)
                        ->whereIn("student_id", $difference)
                        ->delete();

                    AcademicFeeModel::where([
                        "academic_year" => $request->academic_year,
                        "type" => "hostel",
                    ])
                        ->whereIn("student_id", $difference)
                        ->update([
                            "leaved_date" => date("Y-m-d"),
                            "status" => 0,
                        ]);
                }
            }

            $months_hostel = Configurations::GetMonthsOfAcademicYear(
                $request->academic_year,
                date("Y-m-d")
            );

            //dd(json_encode($months_transport));

            $transport_room_amount = DormitoryRoomModel::find($request->room_id)
                ->cost_per_bed;
            if ($request->students) {
                foreach ($request->students as $student) {
                    $exists = DormitoryStudentModel::where(
                        "dormitory_id",
                        $request->dormitory_id
                    )
                        ->where("room_id", $request->room_id)
                        ->where("class_id", $request->class_id)
                        ->where("section_id", $request->section_id)
                        ->where("student_id", $student)
                        ->first();
                    if (!$exists) {
                        $obj = new DormitoryStudentModel();
                        $obj->academic_year = $request->academic_year;
                        $obj->dormitory_id = $request->dormitory_id;
                        $obj->room_id = $request->room_id;
                        $obj->class_id = $request->class_id;
                        $obj->section_id = $request->section_id;

                        $obj->semester_id = $request->term_id;
                        $obj->student_id = $student;
                        $obj->date_of_reg = date("Y-m-d");
                        if ($obj->save()) {
                            $fee = new AcademicFeeModel();
                            $fee->academic_year = $request->academic_year;
                            $fee->student_id = $student;
                            $fee->model_id = $obj->id;
                            $fee->model_name = "DormitoryStudentModel";
                            $fee->added_date = date("Y-m-d");
                            $fee->type = "hostel";
                            $fee->fee_name = "Hostel Fees";
                            $fee->due_amount =
                                sizeof($months_hostel) * $transport_room_amount;
                            $fee->month_info = json_encode($months_hostel);
                            $fee->save();
                        }
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
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("dormitorystudent.index")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("dormitorystudent.index");
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
        $data = DormitoryRoomStudentModel::find($id);
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
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new DormitoryRoomStudentModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);

        try {
            $obj = DormitoryRoomStudentModel::find($id);
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
            $delObj = new DormitoryRoomStudentModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new DormitoryRoomStudentModel();
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

        $data = DormitoryRoomStudentModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new DormitoryRoomStudentModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new DormitoryRoomStudentModel())->getTable() .
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
    function statusChange(Request $request)
    {
        CGate::authorize("edit-1");
        if ($request->ajax()) {
            DormitoryRoomStudentModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new DormitoryRoomStudentModel();
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
