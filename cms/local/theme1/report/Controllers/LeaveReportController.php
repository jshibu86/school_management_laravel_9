<?php
namespace cms\report\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\leave\Models\LeaveModel;
use cms\students\Models\StudentsModel;
use Configurations;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use CGate;

class LeaveReportController extends Controller
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
        $academicyears = Configurations::getAcademicyears();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $sections = [];

        $school_type_info = SchoolTypeModel::where("status", 1)->pluck(
            "school_type",
            "id"
        );

        //$school_type_info->prepend("All", "all");

        // total leave applied

        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $examterms = Configurations::getCurentAcademicTerms();

        $leave = LeaveModel::where("group_id", Configurations::TEACHER)->get();

        //dd($leave);

        if ($request->ajax()) {
            $academic_year = $request->query->get(
                "acyear",
                Configurations::getCurrentAcademicyear()
            );
            $initial = $request->query->get("initial", 0);
            $school_type = $request->query->get("school_type", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $students = StudentsModel::where(
                "academic_year",
                $academic_year
            )->where("status", 1);
            if ($initial) {
                $data = LeaveModel::query();

                $datatables = $data
                    ->with("academicyear")
                    ->select(
                        DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                        "leave.id as id",
                        "leave.academic_year",
                        "leave_types.leave_type as type",
                        "users.name as name",
                        "user_groups.group as group",
                        "leave.from_date as from",
                        "leave.to_date as to",
                        "leave.application_status as leavestatus",
                        "leave.group_id as group_id"
                    )
                    ->join(
                        "leave_types",
                        "leave.leave_type_id",
                        "=",
                        "leave_types.id"
                    )
                    ->leftjoin("users", "users.id", "=", "leave.user_id")
                    ->join(
                        "user_groups",
                        "user_groups.id",
                        "=",
                        "leave.group_id"
                    )

                    ->where("group_id", Configurations::TEACHER)

                    ->whereIn("leave.application_status", [1, 2, -1]);

                $datatables = Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn("applicantname", function ($data) {
                        return "<div class='d-flex appname'><span>" .
                            $data->name .
                            "</span><span class='badge bg-primary'>" .
                            $data->group .
                            "</span></div>";
                    })
                    ->addColumn("fromto", function ($data) {
                        return $data->from . "-" . $data->to;
                    })
                    ->addColumn("status", function ($data) {
                        if ($data->leavestatus == -1) {
                            return "<span class='badge bg-danger'>Rejected</span>";
                        } elseif ($data->leavestatus == 1) {
                            return "<span class='badge bg-success'>Approved</span>";
                        } else {
                            return "<span class='badge bg-warning'>Pending</span>";
                        }
                    });

                //return $status_;
                // if ($status_) {
                //     return $status_;
                // }
                if (count((array) $data) == 0) {
                    return [];
                }

                return $datatables
                    ->rawColumns(["applicantname", "status"])
                    ->make(true);
            }

            if ($school_type && $school_type == "all") {
                // getting all classes students

                $students = $students->pluck("user_id");
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
                            ->pluck("user_id");
                    } elseif ($class_id && $section_id) {
                        $students = $students
                            ->where("class_id", $class_id)
                            ->where("section_id", $section_id)
                            ->pluck("user_id");
                    } else {
                        $students = $students
                            ->whereIn("class_id", $classes)
                            ->pluck("user_id");
                    }
                } else {
                    // no class available
                    $students = [];
                }
            }

            // gettign studentsfrom userid
            // return $students;

            $data = LeaveModel::query();

            $datatables = $data
                ->with("academicyear")
                ->select(
                    DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                    "leave.id as id",
                    "leave.academic_year",
                    "leave_types.leave_type as type",
                    "users.name as name",
                    "user_groups.group as group",
                    "leave.from_date as from",
                    "leave.to_date as to",
                    "leave.application_status as leavestatus",
                    "leave.group_id as group_id"
                )
                ->join(
                    "leave_types",
                    "leave.leave_type_id",
                    "=",
                    "leave_types.id"
                )
                ->leftjoin("users", "users.id", "=", "leave.user_id")
                ->join("user_groups", "user_groups.id", "=", "leave.group_id")

                ->where("group_id", Configurations::STUDENT)
                ->whereIn("user_id", $students)
                ->whereIn("leave.application_status", [1, 2, -1]);

            $datatables = Datatables::of($data)
                ->addIndexColumn()
                ->addColumn("applicantname", function ($data) {
                    return "<div class='d-flex appname'><span>" .
                        $data->name .
                        "</span><span class='badge bg-primary'>" .
                        $data->group .
                        "</span></div>";
                })
                ->addColumn("fromto", function ($data) {
                    return $data->from . "-" . $data->to;
                })
                ->addColumn("status", function ($data) {
                    if ($data->leavestatus == -1) {
                        return "<span class='badge bg-danger'>Rejected</span>";
                    } elseif ($data->leavestatus == 1) {
                        return "<span class='badge bg-success'>Approved</span>";
                    } else {
                        return "<span class='badge bg-warning'>Pending</span>";
                    }
                });

            //return $status_;
            // if ($status_) {
            //     return $status_;
            // }
            if (count((array) $data) == 0) {
                return [];
            }

            return $datatables
                ->rawColumns(["applicantname", "status"])
                ->make(true);
        }

        return view(
            "report::admin.report.leave.leavereport",
            compact(
                "academicyears",
                "class_lists",
                "sections",
                "school_type_info",
                "leave",
                "academic_years",
                "current_academic_year",
                "examterms",
                "current_academic_term"
            )
        );
        dd("leave");
    }
}
