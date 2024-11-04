<?php
namespace cms\report\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
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
use cms\fees\Mail\FeeCollectionMail;
use cms\fees\Models\AcademicFeeModel;
use cms\fees\Models\FeeSetupModel;
use cms\fees\Models\FeesModel;
use cms\fees\Models\SchoolTypeModel;
use cms\transport\Models\TransportRoute;
use cms\transport\Models\TransportRouteBusMapping;
use cms\transport\Models\TransportRouteStopMapping;
use cms\transport\Models\TransportStop;
use Configurations;

class TransportReportController extends Controller
{
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
            $sTart = ctype_digit($request->get("start"))
                ? $request->get("start")
                : 0;

            DB::statement(DB::raw("set @rownum=" . (int) $sTart));

            $academic_year = $request->query->get(
                "acyear",
                Configurations::getCurrentAcademicyear()
            );
            //return Configurations::getCurrentAcademicyear();
            $school_type = $request->query->get("school_type", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $stop_id = $request->query->get("stop_id", 0);
            $bus_id = $request->query->get("bus_id", 0);
            $route_id = $request->query->get("route_id", 0);
            $initial = $request->query->get("initial", 0);
            //dd($request->all());
            $students = StudentsModel::where(
                "academic_year",
                $academic_year
            )->where("status", 1);

            if ($initial) {
                $data = TransportStudents::with(
                    "student:id,first_name,class_id",
                    "stop",
                    "route",
                    "bus",
                    "academicyear"
                )
                    ->select(
                        DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                        "transport_students.id as id ",
                        "transport_students.academic_year",
                        "student_id",
                        "semester_id",
                        "transport_stop_id",
                        "transport_route_id",
                        "transport_vehicle_id",
                        "date_of_reg"
                    )
                    ->where("transport_students.status", "!=", -1)
                    ->where(
                        "transport_students.academic_year",
                        Configurations::getCurrentAcademicyear()
                    );

                $datatables = Datatables::of($data)
                    ->addIndexColumn()

                    ->addColumn("check", function ($data) {
                        if ($data->id != "1") {
                            return $data->rownum;
                        } else {
                            return "";
                        }
                    })

                    ->addColumn("student_name", function ($data) {
                        return $data->student->first_name;
                    })
                    ->addColumn("feestransport", function ($data) use (
                        $academic_year
                    ) {
                        $info = $this->getFeepayment($data);
                        return $info;
                        //return $fees;
                    })
                    ->addColumn("route", function ($data) {
                        return $data->route->from . "" . $data->route->to;
                    });

                // return $data;
                if (count((array) $data) == 0) {
                    return [];
                }

                return $datatables->rawColumns(["feestransport"])->make(true);
            }

            if ($school_type && $school_type == "all") {
                // getting all classes students

                $students = $students->pluck("id");
            } elseif ($school_type && $school_type != "all") {
                // getting corresponding schooltype students

                $classes = LclassModel::where(
                    "school_type_id",
                    $school_type
                )->pluck("id");

                if (count($classes)) {
                    // classes present

                    if ($class_id) {
                        $students = $students
                            ->where("class_id", $class_id)
                            ->pluck("id");
                    } elseif ($class_id && $section_id) {
                        $students = $students
                            ->where("class_id", $class_id)
                            ->where("section_id", $section_id)
                            ->pluck("id");
                    } else {
                        $students = $students
                            ->whereIn("class_id", $classes)
                            ->pluck("id");
                    }
                } else {
                    // no class available
                    $students = [];
                }
            }

            $assignedStudents = TransportStudents::when($stop_id, function (
                $query,
                $stop_id
            ) {
                return $query->where("transport_stop_id", $stop_id);
            })
                ->when($bus_id, function ($query, $bus_id) {
                    return $query->where("transport_vehicle_id", $bus_id);
                })
                ->when($route_id, function ($query, $route_id) {
                    return $query->where("transport_route_id", $route_id);
                })

                ->whereIn("student_id", $students)
                ->pluck("student_id")
                ->toArray();

            //return $students;

            $data = TransportStudents::with(
                "student:id,first_name",
                "stop",
                "route",
                "bus",
                "academicyear"
            )
                ->select(
                    DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                    "transport_students.id as id ",
                    "transport_students.academic_year",
                    "student_id",
                    "semester_id",
                    "transport_stop_id",
                    "transport_route_id",
                    "transport_vehicle_id",
                    "date_of_reg"
                )
                ->whereIn("transport_students.student_id", $assignedStudents)
                ->where("transport_students.status", "!=", -1);

            $datatables = Datatables::of($data)
                ->addIndexColumn()

                ->addColumn("student_name", function ($data) {
                    return $data->student->first_name;
                })
                ->addColumn("feestransport", function ($data) use (
                    $academic_year
                ) {
                    $info = $this->getFeepayment($data);
                    return $info;
                    //return $fees;
                })
                ->addColumn("route", function ($data) {
                    return $data->route->from . "" . $data->route->to;
                });

            // return $data;
            if (count((array) $data) == 0) {
                return [];
            }

            return $datatables->rawColumns(["feestransport"])->make(true);

            //dd($transport_students);

            //dd($request->all());
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

        $school_type_info = SchoolTypeModel::where("status", 1)->pluck(
            "school_type",
            "id"
        );

        $school_type_info->prepend("All", "all");
        return view("report::admin.report.transport.transportreport", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "stops" => $stops,
            "school_type_info" => $school_type_info,
        ]);
    }

    public function getFeepayment($data)
    {
        $academic_fee_info_sum = AcademicFeeModel::where(
            "student_id",
            $data->student_id
        )
            ->where("academic_year", Configurations::getCurrentAcademicyear())
            ->where("type", "transport")
            ->sum("due_amount");

        $feesetup = FeeSetupModel::with("feelists")
            ->where([
                "class_id" => $data->student->class_id,
                "academic_year" => $data->academic_year,
            ])
            ->first();
        if (!$feesetup) {
            $feesetup = 0;
        } else {
            $feesetup = $feesetup->total_amount;
        }
        $grand_total = $feesetup + $academic_fee_info_sum;

        // done payment
        $fees = FeesModel::where("student_id", $data->student->id)
            ->where("academic_year", $data->academic_year)
            ->sum("paid_amount");

        if ($fees == $grand_total) {
            return "<span class='text-success'>Paid</span>";
        } else {
            return "<span class='text-danger'>Not Paid</span>";
        }
    }
}
