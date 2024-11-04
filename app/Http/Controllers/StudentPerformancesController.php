<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use cms\core\configurations\helpers\Configurations;
use DB;
use cms\students\Models\StudentsModel;
use cms\classtimetable\Models\ClasstimetableModel;
use cms\classtimetable\Models\PeriodClassMappingModel;
use cms\attendance\Models\StudentAttendanceModel;
use cms\attendance\Models\AttendanceModel;
use cms\shop\Models\OrderModel;
use cms\wallet\Models\WalletModel;
use cms\teacher\Models\TeacherModel;
use cms\classteacher\Models\ClassteacherModel;
use cms\StudentPerformance\Models\StudentPerformanceModel;
use cms\StudentPerformance\Models\StudentPerformanceDataModel;
use cms\lclass\Models\LclassModel;
use Carbon\Carbon;
use App\Traits\ApiResponse;
use cms\attendance\Traits\AttendanceTrait;
use cms\academicyear\Models\AcademicyearModel;
use Barryvdh\DomPDF\Facade\Pdf;
use cms\report\Models\MarkReportModel;
use cms\exam\Models\ExamTermModel;
use cms\mark\Models\MarkModel;
use cms\mark\Traits\MarkTrait;

class StudentPerformancesController extends Controller
{
    use ApiResponse, AttendanceTrait, MarkTrait;
    public function StudentPerformanceList(Request $request, $period = null)
    {
        $academic_year = Configurations::getCurrentAcademicyear();
        $term_id = Configurations::getCurrentAcademicterm();
        $user_id = $request->user()->id;
        $timezone = Configurations::getConfig("site")->time_zone;
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $classteacher = ClassteacherModel::with("class", "section")
            ->where([
                "academic_year" => $academic_year,
                "teacher_id" => $teacher_id,
            ])
            ->first();
        $class_id = $classteacher->class_id;
        $school_type = LclassModel::where("id", $class_id)
            ->pluck("school_type_id")
            ->first();
        $section_id = $classteacher->section_id;
        $period_types = Configurations::PERIODTYPE;
        $period_type = $period ?? "monthly";
        if ($period_type == "weekly") {
            $date = [
                Carbon::parse($request->query("start_date"))->format("m/d/Y"),
                Carbon::parse($request->query("end_date"))->format("m/d/Y"),
            ];
        } else {
            // $tmpdat = "July 2023";
            $date = $request->query("date")
                ? Carbon::parse($request->query("date"))->format("F Y")
                : Carbon::now($timezone)->format("F Y");
            // dd($date);
        }
        // dd(
        //     $date,
        //     $school_type,
        //     $academic_year,
        //     $term_id,
        //     $class_id,
        //     $section_id,
        //     $period_type
        // );
        $is_exists = StudentPerformanceModel::where([
            "school_type" => $school_type,
            "academic_year" => $academic_year,
            "term_id" => $term_id,
            "class_id" => $class_id,
            "section_id" => $section_id,
            "period" => $period_type,
            "month_year" => $date,
        ])->first();
        // dd($is_exists);
        if ($is_exists) {
            $stud_perform_id = $is_exists->id;
            $student_performances = Configurations::getstudentPerformanceType();
            $students = StudentsModel::where("class_id", $class_id)
                ->where("section_id", $section_id)
                ->where("status", 1)
                ->get();
            // $data = [
            //     "id" => $stud_perform_id,
            //     "students" => $students,
            //     "student_performances" => $student_performances,
            // ];
            $stud_perform_data = StudentPerformanceDataModel::where(
                "student_performance_id",
                $stud_perform_id
            )->get();
            // dd($stud_perform_data);
            $performance_data = [];
            foreach ($students as $student) {
                $data = $stud_perform_data
                    ->where("student_id", $student->id)
                    ->first();
                $perform_data = StudentPerformanceModel::where(
                    "id",
                    $stud_perform_id
                )->first();
                $dateString = $perform_data->month_year;
                $dateParts = explode(" ", $dateString);

                $monthName = $dateParts[0]; // March
                $year = $dateParts[1]; // 2024

                // Convert month name to month number
                $monthNumber = date("m", strtotime($monthName));

                $start_date = date(
                    "m/d/Y",
                    mktime(0, 0, 0, $monthNumber, 1, $year)
                );
                $end_date = date(
                    "m/d/Y",
                    mktime(0, 0, 0, $monthNumber + 1, 0, $year)
                );

                $exams = DB::table("exam")
                    ->whereBetween("exam_date", [$start_date, $end_date])
                    ->where([
                        "academic_year" => $perform_data->academic_year,
                        "exam_term" => $perform_data->term_id,
                        "class_id" => $perform_data->class_id,
                        "section_id" => $perform_data->section_id,
                    ])
                    ->get();
                $attendance = DB::table("attendance")
                    ->where("attendance_month", $monthName)
                    ->where("attendance_year", $year)
                    ->where([
                        "academic_year" => $perform_data->academic_year,
                        "academic_term" => $perform_data->term_id,
                        "class_id" => $perform_data->class_id,
                        "section_id" => $perform_data->section_id,
                    ])
                    ->get();
                $exam_ids = $exams->pluck("id");
                $attendance_ids = $attendance->pluck("id");

                //attendance calculation
                $attendance_stud = DB::table("attendance_students")
                    ->whereIn("attendance_id", $attendance_ids)
                    ->where(["student_id" => $student->id, "attendance" => 1])
                    ->count();
                $total_attendance = $attendance_ids->count();
                if ($total_attendance !== 0 && $attendance_stud !== 0) {
                    // $attendance_percentage =
                    //     $data && $data->attendance
                    //         ? $data->attendance
                    //         : round(
                    //             ($attendance_stud / $total_attendance) * 100
                    //         );
                    $attendance_percentage = round(
                        ($attendance_stud / $total_attendance) * 100
                    );
                } else {
                    // $attendance_percentage =
                    //     $data && $data->attendance ? $data->attendance : "0";
                    $attendance_percentage = 0;
                }

                //academic calculation
                $max_mark = $exams->pluck("max_mark");
                $total = $max_mark->sum();
                // Calculate offline exam marks
                $offline_marks = DB::table("offline_exam_mark")
                    ->whereIn("exam_id", $exam_ids)
                    ->where("student_id", $student->id)
                    ->sum("score");

                // Calculate online exam marks
                $online_marks = DB::table("online_exam")
                    ->whereIn("exam_id", $exam_ids)
                    ->where("student_id", $student->id)
                    ->sum("total_marks");

                $marks = $offline_marks + $online_marks;

                if ($total !== 0 && $marks !== 0) {
                    // $percentage =
                    //     $data && $data->academic
                    //         ? $data->academic
                    //         : round(($marks / $total) * 100);
                    $percentage = round(($marks / $total) * 100);
                } else {
                    // $percentage =
                    //     $data && $data->academic ? $data->academic : "0";
                    $percentage = 0;
                }

                if ($student_performances) {
                    $student_perform = [];
                    $extra_percent = [];
                    // dd($student_performances);
                    foreach ($student_performances as $stud_perform) {
                        if ($stud_perform == "Discipline and Compliance") {
                            $student_perform[] = [
                                "title" => "Discipline and Compliance",
                                "percentage" => $data->disciple_compliance ?? 0,
                            ];
                            $extra_percent[] = [
                                $data->disciple_compliance ?? 0,
                            ];
                        } else {
                            $student_perform[] = [
                                "title" => $stud_perform,
                                "percentage" => $data->sport_event ?? 0,
                            ];
                            $extra_percent[] = [$data->sport_event ?? 0];
                        }
                    }
                }
                $total_percentage =
                    $percentage +
                    $attendance_percentage +
                    array_sum($extra_percent);
                $total_count = 2 + count($student_perform);
                $average = $total_percentage / $total_count;
                //$data->overall_average
                $performance_data[] = [
                    "student" => $student,
                    "academic_percentage" => $percentage,
                    "attendance_percentage" => $attendance_percentage,
                    "extra_activities" => $student_perform,
                    "overall_average" => $average,
                ];
            }
            // dd($performance_data);
        } else {
            $students = StudentsModel::where("class_id", $class_id)
                ->where("section_id", $section_id)
                ->where("status", 1)
                ->get();
            $student_performances = Configurations::getstudentPerformanceType();
            // dd($date);
            foreach ($students as $student) {
                if ($period == "weekly") {
                    // $dates = explode(",", $date);
                    $start_date = trim($date[0]);
                    $end_date = trim($date[1]);

                    $exams = DB::table("exam")
                        ->where([
                            "academic_year" => $academic_year,
                            "exam_term" => $term_id,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                        ])
                        ->whereBetween("exam_date", [$start_date, $end_date])
                        ->get();
                    $attendance = DB::table("attendance")
                        ->whereBetween("attendance_date", [
                            $start_date,
                            $end_date,
                        ])
                        ->where([
                            "academic_year" => $academic_year,
                            "academic_term" => $term_id,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                        ])
                        ->get();
                } else {
                    $dateString = $date;
                    $dateParts = explode(" ", $dateString);

                    $monthName = $dateParts[0]; // March
                    $year = $dateParts[1]; // 2024

                    // Convert month name to month number
                    $monthNumber = date("m", strtotime($monthName));

                    $start_date = date(
                        "m/d/Y",
                        mktime(0, 0, 0, $monthNumber, 1, $year)
                    );
                    $end_date = date(
                        "m/d/Y",
                        mktime(0, 0, 0, $monthNumber + 1, 0, $year)
                    );

                    $exams = DB::table("exam")
                        ->whereBetween("exam_date", [$start_date, $end_date])
                        ->where([
                            "academic_year" => $academic_year,
                            "exam_term" => $term_id,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                        ])
                        ->get();
                    $attendance = DB::table("attendance")
                        ->where("attendance_month", $monthName)
                        ->where("attendance_year", $year)
                        ->where([
                            "academic_year" => $academic_year,
                            "academic_term" => $term_id,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                        ])
                        ->get();
                }

                $exam_ids = $exams->pluck("id");
                $attendance_ids = $attendance->pluck("id");
                // dd($attendance_ids);
                //attendance calculation
                $attendance_stud = DB::table("attendance_students")
                    ->whereIn("attendance_id", $attendance_ids)
                    ->where(["student_id" => $student->id, "attendance" => 1])
                    ->count();
                $total_attendance = $attendance_ids->count();
                // dd($attendance_ids,$attendance_stud,$total_attendance,$attendance)
                if ($total_attendance != 0 && $attendance_stud != 0) {
                    $attendance_percentage = round(
                        ($attendance_stud / $total_attendance) * 100
                    );
                } else {
                    $attendance_percentage = 0;
                }

                //academic calculation
                $max_mark = $exams->pluck("max_mark");
                $total = $max_mark->sum();
                // Calculate offline exam marks
                $offline_marks = DB::table("offline_exam_mark")
                    ->whereIn("exam_id", $exam_ids)
                    ->where("student_id", $student->id)
                    ->sum("score");

                // Calculate online exam marks
                $online_marks = DB::table("online_exam")
                    ->whereIn("exam_id", $exam_ids)
                    ->where("student_id", $student->id)
                    ->sum("total_marks");

                $marks = $offline_marks + $online_marks;

                if ($total !== 0 && $marks !== 0) {
                    $percentage = round(($marks / $total) * 100);
                } else {
                    $percentage = "0";
                }
                $extra_percent = [];
                $student_perform = [];
                if ($student_performances) {
                    $extra_percent = [];
                    foreach ($student_performances as $stud_perform) {
                        if ($stud_perform == "Discipline and Compliance") {
                            $student_perform[] = [
                                "title" => "Discipline and Compliance",
                                "percentage" => $data->disciple_compliance ?? 0,
                            ];
                            $extra_percent[] = [
                                $data->disciple_compliance ?? 0,
                            ];
                        } else {
                            $student_perform[] = [
                                "title" => $stud_perform,
                                "percentage" => $data->sport_event ?? 0,
                            ];
                            $extra_percent[] = [$data->sport_event ?? 0];
                        }
                    }
                }
                // dd(array_sum($extra_percent));
                $total_percentage =
                    $percentage +
                    $attendance_percentage +
                    array_sum($extra_percent);
                $total_count = 2 + count($student_perform);
                $average = round($total_percentage / $total_count);
                // dd($total_percentage, $total_count,$percentage,);
                // $avarage = (100 / $total_average) * 100;
                //$data->overall_average
                $performance_data[] = [
                    "student" => $student,
                    "academic_percentage" => $percentage,
                    "attendance_percentage" => $attendance_percentage,
                    "extra_activities" => $student_perform,
                    "overall_average" => $average,
                ];
            }
        }
        $data = [
            "performance_data" => $performance_data,
            "staff" => $request->user(),
        ];
        return $this->success($data, "Data Fetched Successfully", 200);
    }
    public function CurrentAcademicYear(Request $request)
    {
        $id = Configurations::getCurrentAcademicyear();
        $term_id = Configurations::getCurrentAcademicterm();
        $academic_term = ExamTermModel::find($term_id);

        $academic_term->from_date = $academic_term->from_date
            ? Carbon::parse($academic_term->from_date)->format("d/m/Y")
            : null;
        $academic_term->to_date = $academic_term->to_date
            ? Carbon::parse($academic_term->to_date)->format("d/m/Y")
            : null;
        $academic_year = AcademicyearModel::find($id);
        $academic_year->start_date = Carbon::parse(
            $academic_year->start_date
        )->format("d/m/Y");
        $academic_year->end_date = Carbon::parse(
            $academic_year->end_date
        )->format("d/m/Y");
        $data = [
            "academic_year" => $academic_year,
            "academic_term" => $academic_term,
        ];
        return $this->success($data, "Data Feteched Successfully", 200);
    }
    public function StudentPerformance(Request $request)
    {
        $student_id = $request->user()->id;
        $student = DB::table("students")
            ->where("user_id", $student_id)
            ->first();
        $month = $request->month;
        $year = $request->year;

        $month_year = $month . " " . $year;

        $student_perform_id = DB::table("studentperformance")
            ->where([
                "academic_year" => $student->academic_year,
                "class_id" => $student->class_id,
                "section_id" => $student->section_id,
                "month_year" => $month_year,
            ])
            ->pluck("id")
            ->first();
        // $recomandation_text = [
        //     [
        //         "id" => "poor",
        //         "text" =>
        //             Configurations::getConfig("site")
        //                 ->poor_recomendation_text ?? null,
        //     ],
        //     [
        //         "id" => "avarage",
        //         "text" =>
        //             Configurations::getConfig("site")
        //                 ->avarage_recomendation_text ?? null,
        //     ],
        //     [
        //         "id" => "good",
        //         "text" =>
        //             Configurations::getConfig("site")
        //                 ->good_recomendation_text ?? null,
        //     ],
        //     [
        //         "id" => "execellent",
        //         "text" =>
        //             Configurations::getConfig("site")
        //                 ->execellent_recomendation_text ?? null,
        //     ],
        // ];
        $recomandation_text = Configurations::getRecomandationText();
        if ($student_perform_id) {
            $student_performances = DB::table("studentperformance_data")
                ->where([
                    "student_id" => $student->id,
                    "student_performance_id" => $student_perform_id,
                ])
                ->first();

            $student_performances_type = Configurations::getstudentPerformanceType();
            // dd($student_performances_type);
            $performance = [
                [
                    "name" => "Academic",
                    "percentage" => $student_performances->academic,
                ],
                [
                    "name" => "Attendance",
                    "percentage" => $student_performances->attendance,
                ],
                [
                    "name" => "Discipline and Compliance",
                    "percentage" => $student_performances->disciple_compliance,
                ],
                [
                    "name" => "Sports and Event",
                    "percentage" => $student_performances->sport_event,
                ],
            ];
            $marks = [];
            $total_percentage = 0;
            $performance_count = 0;

            foreach ($performance as $perform) {
                if (
                    in_array($perform["name"], $student_performances_type) ||
                    in_array($perform["name"], ["Academic", "Attendance"])
                ) {
                    $total_percentage += $perform["percentage"];
                    $performance_count++;
                }
            }

            $overall_percentage =
                $performance_count > 0
                    ? round($total_percentage / $performance_count)
                    : 0;

            return [
                "student_performance_types" => $student_performances_type,
                "recomandation_text" => $recomandation_text,
                "performances" => $performance,
                "OverallPercentage" => $overall_percentage,
            ];
        } else {
            $student_performances_type = Configurations::getstudentPerformanceType();
            $performance = [
                [
                    "name" => "Academic",
                    "percentage" => 0,
                ],
                [
                    "name" => "Attendance",
                    "percentage" => 0,
                ],
                [
                    "name" => "Discipline and Compliance",
                    "percentage" => 0,
                ],
                [
                    "name" => "Sports and Event",
                    "percentage" => 0,
                ],
            ];
            $OverallPercentage = 0;

            return [
                "student_performance_types" => $student_performances_type,
                "recomandation_text" => $recomandation_text,
                "performances" => $performance,
                "OverallPercentage" => $OverallPercentage,
                "message" =>
                    "you will receive your report at the end month / quarter / year",
            ];
        }
    }
    public function ClassTimetable(Request $request)
    {
        $user_id = $request->user()->id;
        $type = $request->query("type") ?? 0;
        $getWeekend = Configurations::getConfig("site")->week_end;
        if ($type == "teacher") {
            $user_id = $request->user()->id;
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $timetable = ClasstimetableModel::select(
                "id",
                "subject_id",
                "teacher_id",
                "period_id",
                "colorcode",
                "border_color",
                "no_of_days",
                "day",
                "class_id",
                "section_id"
            )
                ->with("subject", "staff", "periods", "class", "section")
                ->where([
                    "academic_year" => $current_academic_year,
                    "teacher_id" => $teacher_id,
                ])
                ->get();

            $apiData = [
                "teacher_id" => $teacher_id,
                "academic" => $current_academic_year,
                "days" => [],
            ];

            $weekdays = Configurations::WEEKDAYS;

            // Initialize an empty array for each day of the week
            foreach ($weekdays as $dayIndex => $dayName) {
                $is_weekend = 0;
                if (in_array($dayIndex, $getWeekend)) {
                    $is_weekend = 1;
                }
                $apiData["days"][$dayName] = [
                    "period" => [],
                    "break" => [],
                    "lunch" => [],
                    "is_weekend" => $is_weekend,
                ];
            }

            // Fill in the periods for each day
            foreach ($timetable as $data) {
                $dayName = $weekdays[$data->day];
                $period = [
                    "id" => $data->periods->id,
                    "period_class_id" => $data->periods->period_class_id,
                    "subject_name" => $data->subject->name,
                    "teacher_name" => $data->staff->teacher_name,
                    "bgcolor" => $data->colorcode,
                    "border_color" => $data->border_color,
                    "from" => $data->periods->from,
                    "to" => $data->periods->to,
                    "class" => $data->class,
                    "section" => $data->section,
                ];

                $apiData["days"][$dayName]["period"][] = $period;
            }

            // Assuming there's a break and lunch break for each day
            foreach ($apiData["days"] as &$dayData) {
                $timetable_first = ClasstimetableModel::select(
                    "id",
                    "subject_id",
                    "teacher_id",
                    "period_id",
                    "colorcode",
                    "border_color",
                    "no_of_days",
                    "day"
                )
                    ->with("subject", "staff", "periods", "class", "section")
                    ->where([
                        "academic_year" => $current_academic_year,
                        "teacher_id" => $teacher_id,
                    ])
                    ->first();
                $period_class_id = $timetable_first->periods->period_class_id;
                $period_data = PeriodClassMappingModel::where(
                    "period_class_id",
                    $period_class_id
                )->get();
                foreach ($period_data as $periods) {
                    if ($periods->type == 1) {
                        $dayData["lunch"][] = [
                            "from" => $periods->from,
                            "to" => $periods->to,
                            "name" => "Lunch Break",
                            "break_min" => $periods->break_min,
                        ];
                    }
                    if ($periods->type == 2) {
                        $dayData["break"][] = [
                            "from" => $periods->from,
                            "to" => $periods->to,
                            "name" => "Break",
                            "break_min" => $periods->break_min,
                        ];
                    }
                }
            }
        } else {
            $username = $request->user()->username;
            $student = StudentsModel::where("user_id", $user_id)->first();
            $timetable = ClasstimetableModel::select(
                "id",
                "subject_id",
                "teacher_id",
                "period_id",
                "colorcode",
                "border_color",
                "no_of_days",
                "day"
            )
                ->with("subject", "staff", "periods")
                ->where([
                    "academic_year" => $student->academic_year,
                    "class_id" => $student->class_id,
                    "section_id" => $student->section_id,
                ])
                ->get();

            $apiData = [
                "Student_id" => $student->id,
                "Student_name" => $username,
                "class_id" => $student->class_id,
                "section_id" => $student->section_id,
                "academic" => $student->academic_year,
                "days" => [],
            ];

            $weekdays = Configurations::WEEKDAYS;

            // Initialize an empty array for each day of the week
            foreach ($weekdays as $dayIndex => $dayName) {
                $is_weekend = 0;
                if (in_array($dayIndex, $getWeekend)) {
                    $is_weekend = 1;
                }
                $apiData["days"][$dayName] = [
                    "period" => [],
                    "break" => [],
                    "lunch" => [],
                    "is_weekend" => $is_weekend,
                ];
            }

            // Fill in the periods for each day
            foreach ($timetable as $data) {
                $dayName = $weekdays[$data->day];
                $period = [
                    "id" => $data->periods->id,
                    "period_class_id" => $data->periods->period_class_id,
                    "subject_name" => $data->subject
                        ? $data->subject->name
                        : null,
                    "teacher_name" => $data->staff
                        ? $data->staff->teacher_name
                        : null,
                    "bgcolor" => $data->colorcode,
                    "border_color" => $data->border_color,
                    "from" => $data->periods->from,
                    "to" => $data->periods->to,
                ];

                $apiData["days"][$dayName]["period"][] = $period;
            }

            // Assuming there's a break and lunch break for each day
            foreach ($apiData["days"] as &$dayData) {
                $timetable_first = ClasstimetableModel::select(
                    "id",
                    "subject_id",
                    "teacher_id",
                    "period_id",
                    "colorcode",
                    "border_color",
                    "no_of_days",
                    "day"
                )
                    ->with("subject", "staff", "periods")
                    ->where([
                        "academic_year" => $student->academic_year,
                        "class_id" => $student->class_id,
                        "section_id" => $student->section_id,
                    ])
                    ->first();
                $period_class_id = $timetable_first->periods->period_class_id;
                $period_data = PeriodClassMappingModel::where(
                    "period_class_id",
                    $period_class_id
                )->get();
                foreach ($period_data as $periods) {
                    if ($periods->type == 1) {
                        $dayData["lunch"][] = [
                            "from" => $periods->from,
                            "to" => $periods->to,
                            "name" => "Lunch Break",
                            "break_min" => $periods->break_min,
                        ];
                    }
                    if ($periods->type == 2) {
                        $dayData["break"][] = [
                            "from" => $periods->from,
                            "to" => $periods->to,
                            "name" => "Break",
                            "break_min" => $periods->break_min,
                        ];
                    }
                }
            }
        }

        return $apiData;
    }

    public function WalletHistory(Request $request)
    {
        $user_id = $request->user()->id;
        $student = StudentsModel::where("user_id", $user_id)->first();
        $walletpaytypes = Configurations::WALLETPAYTYPES;
        $history = [];
        foreach ($walletpaytypes as $key => $type) {
            if ($key = 1) {
                $order_amount = OrderModel::where([
                    "user_id" => $user_id,
                    "student_id" => $student->id,
                    "payment_type" => "wallet",
                    "payment_status" => "1",
                ])->sum("order_amount");
                $currency = OrderModel::where([
                    "user_id" => $user_id,
                    "student_id" => $student->id,
                    "payment_type" => "wallet",
                    "payment_status" => "1",
                ])
                    ->pluck("currency")
                    ->first();
                $wallet_history = [
                    "title" => $type,
                    "order_amount" => $order_amount,
                    "currency" => $currency,
                ];
                $history[] = $wallet_history;
            } else {
                return "No history found";
            }
        }
        $wallet = WalletModel::where("parent_id", $student->parent_id)->first();
        $amount = $wallet ? $wallet->wallet_amount : 0;
        $balance = number_format($amount);

        return response()->json([
            "wallettype" => $history,
            "balance" => $balance,
        ]);
    }

    public function Store(Request $request)
    {
        try {
            DB::beginTransaction();

            // dd($school_type);
            $academic_year = Configurations::getCurrentAcademicyear();
            $term_id = Configurations::getCurrentAcademicterm();
            $user_id = $request->user()->id;
            $timezone = Configurations::getConfig("site")->time_zone;
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $classteacher = ClassteacherModel::with("class", "section")
                ->where([
                    "academic_year" => $academic_year,
                    "teacher_id" => $teacher_id,
                ])
                ->first();
            $class_id = $classteacher->class_id;
            $school_type_id = LclassModel::where("id", $class_id)
                ->pluck("school_type_id")
                ->first();
            $school_type = $school_type_id ?? null;
            $section_id = $classteacher->section_id;
            $month = Carbon::parse($request->month)->format("F Y");
            $existingPerformance = StudentPerformanceModel::where([
                "school_type" => $school_type,
                "academic_year" => $academic_year,
                "term_id" => $term_id,
                "class_id" => $class_id,
                "section_id" => $section_id,
                "month_year" => $month,
            ])->first();
            // dd($existingPerformance);
            if ($existingPerformance) {
                // If the record exists, update its data for each student
                // dd($existingPerformance->id);
                foreach ($request->student_id as $key => $student) {
                    $studentPerformanceData = StudentPerformanceDataModel::where(
                        "student_performance_id",
                        $existingPerformance->id
                    )
                        ->where("student_id", $student)
                        ->first();
                    // dd($studentPerformanceData);
                    if ($studentPerformanceData) {
                        $academicPercentage = $request->academic[$key] ?? 0;
                        $attendancePercentage = $request->attendance[$key] ?? 0;
                        $disciplineCompliance =
                            $request->Disciple_and_Compliance[$key] ?? null;
                        $sportEvent = $request->Sport_and_Event[$key] ?? null;
                        $ac_count = $disciplineCompliance !== null ? 1 : 0;
                        $sp_count = $sportEvent !== null ? 1 : 0;
                        $count = 2 + $ac_count + $sp_count;
                        $total = $count * 100;
                        $performanceScore =
                            $request->overall_performance[$key] ?? 0;
                        $average = ($performanceScore / $total) * 100;
                        $studentPerformanceData->academic = $academicPercentage;
                        $studentPerformanceData->attendance = $attendancePercentage;
                        $studentPerformanceData->disciple_compliance = $disciplineCompliance;
                        $studentPerformanceData->sport_event = $sportEvent;
                        $studentPerformanceData->overall_average = $average;
                        $studentPerformanceData->save();
                    } else {
                        $studentPerformanceData = new StudentPerformanceDataModel();
                        $academicPercentage = $request->academic[$key] ?? 0;
                        $attendancePercentage = $request->attendance[$key] ?? 0;
                        $disciplineCompliance =
                            $request->Disciple_and_Compliance[$key] ?? null;
                        $sportEvent = $request->Sport_and_Event[$key] ?? null;
                        $ac_count = $disciplineCompliance !== null ? 1 : 0;
                        $sp_count = $sportEvent !== null ? 1 : 0;
                        $count = 2 + $ac_count + $sp_count;
                        $total = $count * 100;
                        $performanceScore =
                            $request->overall_performance[$key] ?? 0;
                        $average = ($performanceScore / $total) * 100;
                        $studentPerformanceData->student_performance_id =
                            $existingPerformance->id;
                        $studentPerformanceData->academic = $academicPercentage;
                        $studentPerformanceData->attendance = $attendancePercentage;
                        $studentPerformanceData->disciple_compliance = $disciplineCompliance;
                        $studentPerformanceData->sport_event = $sportEvent;
                        $studentPerformanceData->overall_average = $average;
                        $studentPerformanceData->save();
                    }
                }
            } else {
                $newPerformance = new StudentPerformanceModel();
                $newPerformance->fill([
                    "school_type" => $school_type,
                    "academic_year" => $academic_year,
                    "term_id" => $term_id,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "period" => "monthly",
                    "month_year" => $month,
                ]);
                $newPerformance->save();
                foreach ($request->student_id as $key => $student) {
                    $academicPercentage = $request->academic[$key];
                    $attendancePercentage = $request->attendance[$key];
                    $disciplineCompliance =
                        $request->Disciple_and_Compliance[$key] ?? null;
                    $sportEvent = $request->Sport_and_Event[$key] ?? null;
                    $ac_count = $disciplineCompliance !== null ? 1 : 0;
                    $sp_count = $sportEvent !== null ? 1 : 0;
                    $count = 2 + $ac_count + $sp_count;
                    $total = $count * 100;
                    $performanceScore =
                        $request->overall_performance[$key] ?? 0;
                    $studentPerformanceData = new StudentPerformanceDataModel();
                    $studentPerformanceData->student_performance_id =
                        $newPerformance->id;
                    $studentPerformanceData->student_id = $student;
                    $studentPerformanceData->academic =
                        $academicPercentage ?? 0;
                    $studentPerformanceData->attendance =
                        $attendancePercentage ?? 0;
                    $studentPerformanceData->disciple_compliance = $disciplineCompliance;
                    $studentPerformanceData->sport_event = $sportEvent;
                    $studentPerformanceData->overall_average =
                        ($performanceScore / $total) * 100;
                    $studentPerformanceData->save();
                }
            }
            DB::commit();
            return $this->success(
                "Students Performances Stored Successfully",
                200
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error("An error occurred: " . $e->getMessage(), 500);
        }
    }

    public function ReportCard(Request $request)
    {
        $user_id = $request->user()->id;
        $academic_year = $request->query("academic_year")
            ? $request->query("academic_year")
            : Configurations::getCurrentAcademicyear();
        $acyear = AcademicyearModel::find($academic_year)->year;
        $term_id = $request->query("academic_term")
            ? $request->query("academic_term")
            : Configurations::getCurrentAcademicterm();
        $last_terms_id = ExamTermModel::where("academic_year", $academic_year)
            ->orderBy("order", "desc")
            ->first()->id;
        $terms = ExamTermModel::where("academic_year", $academic_year)->get();
        $term_name = ExamTermModel::where("id", $term_id)
            ->pluck("exam_term_name")
            ->first();
        // dd($term);
        $student = StudentsModel::with("user", "class", "section")
            ->where("user_id", $user_id)
            ->first();
        $student->image = $student->image
            ? parse_url($student->image, PHP_URL_PATH)
            : parse_url($asset("assets/images/default.jpg"), PHP_URL_PATH);
        $distribution = MarkModel::where([
            "academic_year" => $academic_year,
            "term_id" => $term_id,
            "student_id" => $student->id,
        ])->first();
        $distribution = $distribution ? $distribution->distribution : [];
        $mark_info = MarkReportModel::where([
            "academic_year" => $academic_year,
            "term_id" => $term_id,
            "student_id" => $student->id,
        ])->first();
        if ($mark_info) {
            $class_student_ids = StudentsModel::where([
                "academic_year" => $academic_year,
                "class_id" => $student->class_id,
                "section_id" => $student->section_id,
                "status" => 1,
            ])
                ->whereNull("deleted_at")
                ->pluck("id");

            $position =
                MarkReportModel::where([
                    "academic_year" => $academic_year,
                    "term_id" => $term_id,
                ])
                    ->whereIn("student_id", $class_student_ids)
                    ->where("student_id", "=", $student->id)
                    ->where(
                        "total_mark_obtain",
                        ">",
                        $mark_info->total_mark_obtain
                    )
                    ->count() + 1;
            $mark_into = MarkModel::with("subject")
                ->where([
                    "academic_year" => $academic_year,
                    "term_id" => $term_id,
                    "student_id" => $student->id,
                ])
                ->get();
            $grade_info = Configurations::getGradeInfo();
            $least_grade = $grade_info->last()->grade_name;
            // dd($least_grade, $grade_info);
            $stud_grade = MarkModel::with("subject")
                ->where([
                    "academic_year" => $academic_year,
                    "term_id" => $term_id,
                    "student_id" => $student->id,
                    "grade" => $least_grade,
                ])
                ->first();
            $status = $stud_grade ? "Failed" : "Passed";
            $mark_data = [];
            $ctotal = 0;
            $marks = [];
            foreach ($mark_into as $mark) {
                foreach ($terms as $term) {
                    if (!isset($mark_data[$mark->subject_id])) {
                        $mark_data[$mark->subject_id] = new \stdClass();
                    }

                    if (!isset($mark_data[$mark->subject_id]->{$term->id})) {
                        $mark_data[
                            $mark->subject_id
                        ]->{$term->id} = new \stdClass();
                    }

                    # code...
                    $mark_data[$mark->subject_id]->{$term->id}->name =
                        $term->exam_term_name;
                    $markdata = MarkModel::where([
                        "academic_year" => $academic_year,
                        "term_id" => $term->id,
                        "student_id" => $mark->student_id,
                        "exam_type" => $mark->exam_type,
                        "subject_id" => $mark->subject_id,
                    ])->first();
                    $total = $markdata ? $markdata->total_mark : 0;
                    $ctotal = $ctotal + $total;
                    $mark_data[$mark->subject_id]->{$term->id}->mark = $markdata
                        ? $markdata->total_mark
                        : 0;

                    $mark_data[$mark->subject_id]->total = $ctotal;
                    $mark_data[$mark->subject_id]->avg = $ctotal / 3;
                    [$grade, $point, $note] = $this->Getgradefrommark($ctotal);
                    $mark_data[$mark->subject_id]->grade = $grade;
                    $mark_data[$mark->subject_id]->point = $point;
                    $mark_data[$mark->subject_id]->note = $note;
                }
                $ctotal = 0;
                $marks[] = $mark->total_mark;
            }
            // dd($mark_info);
            $promotion_type = Configurations::getConfig("site")->promotion_type;
            $mark_report_message = Configurations::getConfig("site")
                ->mark_report_message;
            $grade_info = Configurations::getGradeInfo();
            $config = Configurations::getConfig("site");
            $image = parse_url($config->imagec, PHP_URL_PATH);

            $fileName = "report_" . $student->id . $mark_info->id . ".pdf";
            $filePath = public_path("school/student_reports/");
            $fullFilePath = $filePath . $fileName;

            $pdf = Pdf::loadView("report::admin.report.mark.getmarkreportpdf", [
                "mark_info" => $mark_info,
                "student_info" => $student,
                "image" => $image,
                "config" => $config,
                "term_name" => $term_name,
                "acyear" => $acyear,
                "term_id" => $term_id,
                "last_terms_id" => $last_terms_id,
                "distribution" => $distribution,
                "terms" => $terms,
                "mark_into" => $mark_into,
                "mark_data" => $mark_data,
                "promotion_type" => $promotion_type,
                "mark_report_message" => $mark_report_message,
                "grade_info" => $grade_info,
                "position" => Configurations::ordinal($position),
                "status" => $status,
            ])->setPaper("a3", "portrait");

            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }

            // Save the generated PDF file
            $pdf->save($fullFilePath);

            $pdfUrl = asset("school/student_reports/" . $fileName);

            return $this->success(
                $pdfUrl,
                "PDF generated and saved successfully",
                200
            );
        } else {
            return $this->error(
                "Mark report was not generated. Please contact your class teacher for further details.",
                500
            );
        }
    }
}
