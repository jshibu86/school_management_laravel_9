<?php

namespace cms\attendance\Traits;
use Image;
use Auth;
use Session;
use File;
use Configurations;
use DB;
use CGate;
use User;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\carbon;
use cms\attendance\Models\StudentAttendanceModel;
use cms\academicyear\Models\AcademicyearModel;
use cms\students\Models\StudentsModel;
use cms\attendance\Models\AttendanceModel;
use cms\classtimetable\Models\PeriodClassMappingModel;
use cms\classtimetable\Models\PeriodModel;
trait AttendanceTrait
{
    public function createAttendanceCalenderTwoDates(
        $start_date,
        $end_date,
        $checkweekend = true,
        $weekends = [],
        $id,
        $academic_year_id,
        $multiplestudent = false
    ) {
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        //dd($start_date, $end_date);
        $dates = [];

        if ($checkweekend) {
            while ($start_date->lte($end_date)) {
                $weekend = 0;
                $dayofweek =
                    $start_date->dayOfWeek === 0 ? 7 : $start_date->dayOfWeek;
                if (in_array($dayofweek, $weekends)) {
                    $weekend = 1;
                }
                $dates[$start_date->format("Y")][$start_date->format("M")][] = [
                    "date" => $start_date->format("Y-m-d"),
                    "day" => intval($start_date->format("d")),
                    "weekend" => $weekend,
                    "attendance" => AttendanceModel::with([
                        "attendancestudents" => function ($query) use ($id) {
                            $query->where("student_id", $id);
                        },
                    ])
                        ->where("type", 2)
                        ->where("attendance_date", $start_date->format("Y-m-d"))
                        ->where("academic_year", $academic_year_id)
                        ->get()
                        ->reduce(function ($formatted, $daily_attendance) {
                            foreach (
                                $daily_attendance->attendancestudents
                                as $students
                            ) {
                                $formatted[$students->student_id] = [
                                    "present" => $students->attendance,
                                ];
                            }

                            return $formatted;
                        }, []),
                ];

                //$dates[] = $start_date->toDateString();
                $start_date->addDay();
            }
        } else {
            while ($start_date->lte($end_date)) {
                $dates[$start_date->format("Y")][$start_date->format("M")][] = [
                    "date" => $start_date->format("Y-m-d"),
                    "day" => intval($start_date->format("d")),
                ];

                //$dates[] = $start_date->toDateString();
                $start_date->addDay();
            }
        }

        return $dates;
    }

    public function getMultipleStudentsCalender(
        $checkweekend = true,
        $weekends = [],
        $monthyear,
        $students_data = [],
        $class_id,
        $section_id
    ) {
        $month = explode(" ", $monthyear)[0];
        $year = explode(" ", $monthyear)[1];

        $monthNumber = Carbon::createFromFormat(
            "F Y",
            $month . " " . $year
        )->format("n");

        $daysInMonth = Carbon::createFromDate($year, $monthNumber, 1)
            ->daysInMonth;

        $allDatesInMonth = [];

        $studentAttendance = [];

        $students = StudentsModel::query();

        if (count($students_data)) {
            $students = $students->whereIn("id", $students_data)->get();
        } else {
            $students = $students
                ->where([
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                ])
                ->get();
        }

        $students_ids = $students->pluck("id")->toArray();

        // dd($weekends);

        foreach ($students as $student) {
            # code...

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $date = Carbon::createFromDate($year, $monthNumber, $day);

                $dayofweek = $date->dayOfWeek === 0 ? 7 : $date->dayOfWeek;

                $isWeekend = in_array($dayofweek, $weekends);

                $allDates[] = $date->toDateString();

                $allDatesInMonth[$student->id][] = [
                    "date" => $date->toDateString(),
                    "is_weekend" => $isWeekend,
                    "day" => $date->dayOfWeek,
                    "attendance" => AttendanceModel::with([
                        "attendancestudents" => function ($query) use (
                            $student
                        ) {
                            $query->where("student_id", $student->id);
                        },
                    ])
                        ->where("type", 2)
                        ->where("attendance_date", $date->format("Y-m-d"))
                        ->where(
                            "academic_year",
                            Configurations::getCurrentAcademicyear()
                        )
                        ->get()
                        ->reduce(function ($formatted, $daily_attendance) {
                            foreach (
                                $daily_attendance->attendancestudents
                                as $students
                            ) {
                                $formatted[$students->student_id] = [
                                    "present" => $students->attendance,
                                ];
                            }

                            return $formatted;
                        }, []),
                ];
            }
        }

        return [$allDates, $allDatesInMonth, $students_ids];

        dd($allDates, $allDatesInMonth);
    }

    public function gethourlyAttendanceCalenderTwoDates(
        $start_date,
        $end_date,
        $subject_id,
        $weekends = [],
        $getperiod = null,
        $student_id
    ) {
        $start_date = Carbon::parse($start_date);
        $end_date = Carbon::parse($end_date);

        //dd($start_date, $end_date);
        $dates = [];
        $count = 0;

        while ($start_date->lte($end_date)) {
            $weekend = 0;

            $dayofweek =
                $start_date->dayOfWeek === 0 ? 7 : $start_date->dayOfWeek;
            $date = $start_date->format("Y-m-d");
            $dayname = $start_date->format("l");
            if (in_array($dayofweek, $weekends)) {
                $weekend = 1;
            }

            $dates[$start_date->format("M")][] = [
                "date" => $start_date->format("Y-m-d"),
                "daynum" => intval($start_date->format("d")),
                "dayname" => $start_date->format("l"),
                "day" =>
                    $start_date->dayOfWeek === 0 ? 7 : $start_date->dayOfWeek,
            ];

            if ($getperiod) {
                $timing = PeriodClassMappingModel::where(
                    "period_class_id",
                    $getperiod->id
                )->get();

                $timetablehr = PeriodClassMappingModel::with([
                    "Timetableperiod" => function ($query) use (
                        $dayofweek,
                        $date,
                        $dayname,
                        $subject_id,
                        $student_id
                    ) {
                        $query
                            ->with("staff")
                            ->with([
                                "subject" => function ($q) use ($subject_id) {
                                    $q->where("id", $subject_id);
                                },
                            ])
                            ->with([
                                "attendance" => function ($q) use (
                                    $date,
                                    $student_id
                                ) {
                                    $q->where("attendance_date", $date)->with([
                                        "attendancestudents" => function (
                                            $qatten
                                        ) use ($student_id) {
                                            $qatten->where(
                                                "student_id",
                                                $student_id
                                            );
                                        },
                                    ]);
                                },
                            ])
                            ->where(
                                "day",
                                Configurations::WEEKLYDAYS[$dayname]
                            );
                    },
                ])
                    ->where("period_class_id", $getperiod->id)
                    ->get();

                $dates[$start_date->format("M")][$count][
                    "period"
                ] = $timetablehr;
            }

            //$dates[] = $start_date->toDateString();
            $start_date->addDay();
            $count++;
        }
        return $dates;
    }

    // public function AttendanceWeekCalculation($weekends = [], $id)
    // {
    //     $currentMonth = Carbon::now();
    //     $start_date = Carbon::parse($currentMonth->startOfMonth());
    //     $end_date = Carbon::parse($currentMonth->endOfMonth());
    //     //return $startDate;

    //     $datesInMonth = [];
    //     // dd($start_date, $end_date);
    //     while ($start_date->lte($end_date)) {
    //         $weekend = 0;
    //         $dayofweek =
    //             $start_date->dayOfWeek === 0 ? 7 : $start_date->dayOfWeek;
    //         if (in_array($dayofweek, $weekends)) {
    //             $weekend = 1;
    //         }

    //         $attendance = AttendanceModel::with([
    //             "attendancestudents" => function ($query) use ($id) {
    //                 $query->where("student_id", $id);
    //             },
    //         ])
    //             ->where("type", 2)
    //             ->where("attendance_date", $start_date->format("Y-m-d"))
    //             ->where(
    //                 "academic_year",
    //                 Configurations::getCurrentAcademicyear()
    //             )
    //             ->get()
    //             ->reduce(function ($formatted, $daily_attendance) {
    //                 foreach (
    //                     $daily_attendance->attendancestudents
    //                     as $students
    //                 ) {
    //                     $formatted[$students->student_id] = [
    //                         "present" => $students->attendance,
    //                     ];
    //                 }

    //                 return $formatted;
    //             }, []);

    //         $datesInMonth[] = [
    //             "date" => $start_date->format("Y-m-d"),
    //             "day" => intval($start_date->format("d")),
    //             "weekend" => $weekend,
    //             "is_weekend" => $weekend == 1 && $attendance ? 1 : 0,
    //             "attendance" => $attendance,
    //         ];

    //         //$dates[] = $start_date->toDateString();
    //         $start_date->addDay();
    //     }
    //     // dd($datesInMonth);

    //     // divide into weeks

    //     $weeks = array_chunk($datesInMonth, 7);
    //     // dd($weeks);

    //     // [
    //     //     [],[],[]
    //     // ]

    //     $lastFormat = [];

    //     for ($i = 0; $i < sizeof($weeks); $i++) {
    //         # code...
    //         $currentChunk = $weeks[$i];

    //         $first_element = 0;
    //         $last_element = sizeof($currentChunk) - 1;

    //         $attendance = AttendanceModel::with([
    //             "attendancestudents" => function ($query) use ($id) {
    //                 $query->where("student_id", $id)->where("attendance", "1");
    //             },
    //         ])
    //             ->where("type", 2)
    //             ->whereBetween("attendance_date", [
    //                 $currentChunk[$first_element]["date"],
    //                 $currentChunk[$last_element]["date"],
    //             ])
    //             ->where(
    //                 "academic_year",
    //                 Configurations::getCurrentAcademicyear()
    //             )
    //             ->get();
    //         // dd(
    //         //     $currentChunk[$first_element]["date"],
    //         //     $currentChunk[$last_element]["date"],
    //         //     $currentChunk
    //         // );
    //         // dd($attendance);

    //         if (!empty($attendance)) {
    //             // dd($attendance);
    //             $attendance = isset($attendance)
    //                 ? $attendance->map(function ($user) {
    //                     return $user->attendancestudents;
    //                 })
    //                 : [];
    //             // dd($attendance);
    //             if (!isset($attendance)) {
    //                 dd($attendance);
    //             }
    //             $present = !empty($attendance)
    //                 ? $attendance[0]->where("attendance", "1")->count()
    //                 : 0;
    //             $form[] = isset($attendance) ? $attendance[0] : [];
    //             // dd($present);
    //             $lastFormat["week0" . ($i + 1)] = [
    //                 "present" => $present,
    //                 "absent" => collect($attendance)
    //                     ->where("attendance", "0")
    //                     ->count(),

    //                 "precentage" => round(
    //                     ($present * 100) / sizeof($currentChunk)
    //                 ),
    //             ];
    //         }
    //     }

    //     $total_percentage = 0;
    //     if (!empty($attendance)) {
    //         // dd($lastFormat);
    //         for ($j = 0; $j < sizeof($lastFormat); $j++) {
    //             # code...
    //             $total_percentage +=
    //                 $lastFormat["week0" . ($j + 1)]["precentage"];
    //         }

    //         $total_percentage = round(
    //             ($total_percentage * 100) / (sizeof($lastFormat) * 100)
    //         );

    //         // $datesInMonth now contains an array of all the dates in the current month

    //         return [$weeks, $datesInMonth, $lastFormat, $total_percentage];
    //     } else {
    //         return [];
    //     }
    // }

    public function AttendanceWeekCalculation($weekends = [], $id)
    {
        $currentMonth = Carbon::now();
        $start_date = Carbon::parse($currentMonth->startOfMonth());
        $end_date = Carbon::parse($currentMonth->endOfMonth());
        $datesInMonth = [];

        while ($start_date->lte($end_date)) {
            $weekend = 0;
            $dayofweek =
                $start_date->dayOfWeek === 0 ? 7 : $start_date->dayOfWeek;
            if (in_array($dayofweek, $weekends)) {
                $weekend = 1;
            }

            $attendance = AttendanceModel::with([
                "attendancestudents" => function ($query) use ($id) {
                    $query->where("student_id", $id);
                },
            ])
                ->where("type", 2)
                ->where("attendance_date", $start_date->format("Y-m-d"))
                ->where(
                    "academic_year",
                    Configurations::getCurrentAcademicyear()
                )
                ->get()
                ->reduce(function ($formatted, $daily_attendance) {
                    foreach (
                        $daily_attendance->attendancestudents
                        as $students
                    ) {
                        $formatted[$students->student_id] = [
                            "present" => $students->attendance,
                        ];
                    }
                    return $formatted;
                }, []);

            $datesInMonth[] = [
                "date" => $start_date->format("Y-m-d"),
                "day" => intval($start_date->format("d")),
                "weekend" => $weekend,
                "is_weekend" => $weekend == 1 && $attendance ? 1 : 0,
                "attendance" => $attendance,
            ];

            $start_date->addDay();
        }

        // divide into weeks
        $weeks = array_chunk($datesInMonth, 7);
        $lastFormat = [];

        for ($i = 0; $i < sizeof($weeks); $i++) {
            $currentChunk = $weeks[$i];
            $first_element = 0;
            $last_element = sizeof($currentChunk) - 1;

            $attendance = AttendanceModel::with([
                "attendancestudents" => function ($query) use ($id) {
                    $query->where("student_id", $id)->where("attendance", "1");
                },
            ])
                ->where("type", 2)
                ->whereBetween("attendance_date", [
                    $currentChunk[$first_element]["date"],
                    $currentChunk[$last_element]["date"],
                ])
                ->where(
                    "academic_year",
                    Configurations::getCurrentAcademicyear()
                )
                ->get();

            if (!empty($attendance)) {
                $attendance = $attendance->map(function ($user) {
                    return $user->attendancestudents;
                });

                $present = !empty($attendance)
                    ? $attendance
                        ->flatten()
                        ->where("attendance", "1")
                        ->count()
                    : 0;

                $absent = !empty($attendance)
                    ? $attendance
                        ->flatten()
                        ->where("attendance", "0")
                        ->count()
                    : 0;

                $lastFormat["week0" . ($i + 1)] = [
                    "present" => $present,
                    "absent" => $absent,
                    "percentage" =>
                        sizeof($currentChunk) > 0
                            ? round(($present * 100) / sizeof($currentChunk))
                            : 0,
                ];
            }
        }

        $total_percentage = 0;
        if (!empty($lastFormat)) {
            foreach ($lastFormat as $weekData) {
                $total_percentage += $weekData["percentage"];
            }

            $total_percentage =
                sizeof($lastFormat) > 0
                    ? round($total_percentage / sizeof($lastFormat))
                    : 0;

            return [$weeks, $datesInMonth, $lastFormat, $total_percentage];
        } else {
            return [];
        }
    }
}
