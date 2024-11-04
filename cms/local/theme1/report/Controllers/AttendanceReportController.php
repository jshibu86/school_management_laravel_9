<?php
namespace cms\report\Controllers;

use App\Exports\AttendanceReportExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\academicyear\Models\AcademicyearModel;
use cms\attendance\Traits\AttendanceTrait;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use Configurations;
use DateTime;
use DatePeriod;
use DateInterval;
use PDF;
use Carbon\carbon;
use Maatwebsite\Excel\Facades\Excel;
use CGate;
class AttendanceReportController extends Controller
{
    use AttendanceTrait;
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
        $student_id = [];
        $result = [];
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $school_type_info = SchoolTypeModel::where("status", 1)->pluck(
            "school_type",
            "id"
        );
        $academicyears = Configurations::getAcademicyears();

        //dd($allDatesInMonth);

        //$school_type_info->prepend("All", "all");

        if ($request->ajax()) {
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $academic_year = $request->query->get("academic_year", 0);
            $month = $request->query->get("month", 0);

            if ($request->query->get("student_id")) {
                $student_id = explode(",", $request->query->get("student_id"));
            } else {
                $student_id = [];
            }

            $current_academic_year_info = AcademicyearModel::find(
                Configurations::getCurrentAcademicyear()
            );
            $weekends = Configurations::getConfig("site")->week_end;

            $start_date_month = Carbon::parse(
                $current_academic_year_info->start_date
            )

                ->startOfMonth()
                ->copy()
                ->format("Y-m-d");

            [
                $allDates,
                $allDatesInMonth,
                $students_ids,
            ] = $this->getMultipleStudentsCalender(
                true,
                $weekends,
                $month,
                $student_id,
                $class_id,
                $section_id
            );
            $dates = array_unique($allDates);

            $students_present_list = [];
            $students_absent_list = [];
            $result = [$allDates, $allDatesInMonth];

            foreach ($allDatesInMonth as $student_id => $attendanceData) {
                foreach ($attendanceData as $attend) {
                    if (sizeof($attend["attendance"])) {
                        # code...
                        if (
                            $attend["attendance"][$student_id]["present"] ==
                                1 ||
                            $attend["attendance"][$student_id]["present"] == 2
                        ) {
                            $students_present_list[$student_id][] = [
                                "date" => $attend["date"],
                                "present" =>
                                    $attend["attendance"][$student_id][
                                        "present"
                                    ],
                            ];
                        } elseif (
                            $attend["attendance"][$student_id]["present"] == 0
                        ) {
                            $students_absent_list[$student_id][] = [
                                "date" => $attend["date"],
                                "present" =>
                                    $attend["attendance"][$student_id][
                                        "present"
                                    ],
                            ];
                        }
                    }
                }
                # code...
            }

            $total_present_percentage = 0;
            $total_absent_percentage = 0;

            foreach ($students_ids as $id) {
                # code...

                if (isset($students_present_list[$id])) {
                    $percentagePresent = $this->calculatePercentage(
                        sizeof($students_present_list[$id]),
                        sizeof($dates)
                    );

                    $total_present_percentage += $percentagePresent;
                }

                if (isset($students_absent_list[$id])) {
                    $percentageAbsent = $this->calculatePercentage(
                        sizeof($students_absent_list[$id]),
                        sizeof($dates)
                    );

                    $total_absent_percentage += $percentageAbsent;
                }
            }

            $view = view("report::admin.report.attendance.calender", [
                "allDates" => $allDates,
                "calender" => $allDatesInMonth,
                "current_academic_year_info" => $current_academic_year_info,
            ])->render();

            return response()->json([
                "viewfile" => $view,
                "allDates" => $allDates,
                "calender" => $allDatesInMonth,
                "current_academic_year_info" => $current_academic_year_info,
                "total_percent_present" => $total_present_percentage,
                "total_percent_absent" => $total_absent_percentage,
                "total_user" => count($students_ids),
            ]);

            // return $view;

            // dd($calender);
        }

        if ($request->isMethod("post")) {
            // dd($request);
            $class_id = $request->get("class_id", 0);
            $section_id = $request->get("section_id", 0);
            $academic_year = $request->get("academic_year", 0);
            $month = $request->get("month", 0);

            if ($request->get("student_id")) {
                $student_id_data = implode(",", $request->get("student_id"));
                $student_id = explode(",", $student_id_data);
            } else {
                $student_id = [];
            }

            $current_academic_year_info = AcademicyearModel::find(
                Configurations::getCurrentAcademicyear()
            );
            $weekends = Configurations::getConfig("site")->week_end;

            $start_date_month = Carbon::parse(
                $current_academic_year_info->start_date
            )

                ->startOfMonth()
                ->copy()
                ->format("Y-m-d");

            [$allDates, $allDatesInMonth] = $this->getMultipleStudentsCalender(
                true,
                $weekends,
                $month,
                $student_id,
                $class_id,
                $section_id
            );

            //$total_users = count($student_id);
            //dd($total_users);

            if ($request->type == "excel") {
                $response = Excel::download(
                    new AttendanceReportExport(
                        $allDates,
                        $allDatesInMonth,
                        $current_academic_year_info
                    ),
                    "attendance-" . time() . ".xlsx"
                );

                ob_end_clean();

                return $response;

                // excel export
            }

            if ($request->type == "print") {
                return view("report::admin.report.attendance.printcalender", [
                    "allDates" => $allDates,
                    "calender" => $allDatesInMonth,
                    "current_academic_year_info" => $current_academic_year_info,
                    "total_user" => count($student_id),
                ]);
            }
        }

        return view("report::admin.report.attendance.index", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "students" => [],
            "result" => [],
            "school_type_info" => $school_type_info,
        ]);
    }

    function calculatePercentage($count, $total)
    {
        return $total > 0 ? round(($count / $total) * 100, 2) : 0;
    }
}
