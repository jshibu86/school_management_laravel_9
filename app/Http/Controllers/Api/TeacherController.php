<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use cms\attendance\Traits\AttendanceTrait;
use cms\event\Models\EventModel;
use Illuminate\Http\Request;
use cms\core\user\Models\UserModel;
use cms\core\user\Mail\PasswordMail;
use cms\teacher\Models\TeacherModel;
use cms\teacher\Models\DesignationModel;
use Yajra\DataTables\Facades\DataTables;
use cms\students\Models\AttachementModel;
use cms\department\Models\DepartmentModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\subject\Models\SubjectTeacherMapping;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\payrool\Models\SaleryPayrollPayment;
use cms\teacher\Models\DepartmentMappingModel;
use cms\classteacher\Models\ClassteacherModel;
use cms\classtimetable\Models\ClasstimetableModel;
use cms\classtimetable\Models\PeriodClassMappingModel;
use cms\classtimetable\Models\PeriodModel;
use cms\section\Models\SectionModel;
use cms\lclass\Models\LclassModel;
use cms\virtualcomunication\Models\VirtualcomunicationModel;
use cms\virtualcomunication\Models\VirtualCommunicationMappingModel;
use cms\students\Models\StudentsModel;
use cms\attendance\Models\StudentAttendanceModel;
use cms\attendance\Models\AttendanceModel;
use cms\subject\Models\SubjectModel;
use cms\exam\Models\OnlineExamSubmissionModel;
use cms\exam\Models\ExamQuestionModel;
use cms\exam\Models\ExamSectionModel;
use cms\exam\Models\ExamModel;
use cms\exam\Models\ExamTypeModel;
use cms\exam\Models\OnlineExamModel;
use Configurations;
use DB;
use File;
use Carbon\Carbon;
use User;
use DateTime;

class TeacherController extends Controller
{
    use ApiResponse, AttendanceTrait;
    //

    public function TeacherLandingPage(Request $request)
    {
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $events = EventModel::where("status", 1)
            ->orderBy("event_date", "desc")
            ->get();
        $days = Configurations::WEEK_DAYS;
        $timezone = Configurations::getConfig("site")->time_zone;
        $dayname = Carbon::now($timezone)->format("l");
        $dayNumber = Configurations::WEEKLYDAYS[$dayname];
        $period_ids = ClasstimetableModel::where([
            "teacher_id" => $teacher_id,
            "day" => $dayNumber,
            "academic_year" => $current_academic_year,
        ])->pluck("period_id");
        // dd($period_ids);
        $time = Carbon::now($timezone)->format("h:i a");
        if ($period_ids) {
            $period_map = PeriodClassMappingModel::with("period")
                ->whereIn("id", $period_ids)
                ->get()
                ->filter(function ($period) use ($time) {
                    return Carbon::parse($period->from)->greaterThan(
                        Carbon::parse($time)
                    );
                })
                ->sortBy("from")
                ->first();
        }
        // dd($period_map);
        $upcomming_class = "";
        if (isset($period_map)) {
            $upcomming_class = ClasstimetableModel::with(
                "subject",
                "class",
                "section",
                "periods"
            )
                ->where([
                    "teacher_id" => $teacher_id,
                    "day" => $dayNumber,
                    "academic_year" => $current_academic_year,
                    "period_id" => $period_map->id,
                ])
                ->first();
        }
        // dd($upcomming_class);
        //virtual meet
        $moderator_ids = VirtualcomunicationModel::where(
            "moderator",
            $user_id
        )->pluck("id");
        $member_ids = VirtualCommunicationMappingModel::where(
            "participants",
            $user_id
        )->pluck("virtual_comunication_list_id");
        $merged_ids = $moderator_ids->merge($member_ids)->unique();
        $meeting_ids = $merged_ids->toArray();
        $date = Carbon::now($timezone)->format("m/d/Y");
        $time1 = Carbon::now($timezone)->format("H:i:s");

        $meeting = VirtualcomunicationModel::with("creater")
            ->whereIn("id", $meeting_ids)
            ->where("meeting_date", $date)
            ->whereTime("time", ">", $time1)
            ->where("meeting_type", 0)
            ->orderBy("time", "asc")
            ->first();
        $upcomming_virtual_meeting = [];
        if ($meeting) {
            $participants_list = VirtualCommunicationMappingModel::with("user")
                ->where("participants", "!=", $user_id)
                ->where("virtual_comunication_list_id", $meeting->id)
                ->get();
            $upcomming_virtual_meeting = [
                "meeting" => $meeting,
                "meeting_date" => Carbon::parse($meeting->meeting_date)->format(
                    "d/m/Y"
                ),
                "meeting_time" => Carbon::parse($meeting->time)->format(
                    "g:i a"
                ),
                "participants" => $participants_list,
            ];
        }

        // dd($upcomming_virtual_meeting);
        $pta_meeting = VirtualcomunicationModel::with("creater")
            ->whereIn("id", $meeting_ids)
            ->where("meeting_date", $date)
            ->whereTime("time", ">", $time1)
            ->where("meeting_type", 1)
            ->orderBy("time", "asc")
            ->first();
        $upcomming_pta_meeting = [];
        if ($pta_meeting) {
            $pta_participants_list = VirtualCommunicationMappingModel::with(
                "user"
            )
                ->where("participants", "!=", $user_id)
                ->where("virtual_comunication_list_id", $pta_meeting->id)
                ->get();
            $upcomming_pta_meeting = [
                "meeting" => $pta_meeting,
                "meeting_date" => Carbon::parse(
                    $pta_meeting->meeting_date
                )->format("d/m/Y"),
                "meeting_time" => Carbon::parse($pta_meeting->time)->format(
                    "g:i a"
                ),
                "participants" => $pta_participants_list,
            ];
        }
        $data = [
            "events" => $events,
            "upcomming_class" => $upcomming_class
                ? $upcomming_class
                : "No Upcomming Class",
            "upcomming_virtual_meeting" => $upcomming_virtual_meeting
                ? $upcomming_virtual_meeting
                : "No Upcomming Meetings",
            "upcomming_pta_meeting" => $upcomming_pta_meeting
                ? $upcomming_pta_meeting
                : "No Upcomming PTA Meetings",
        ];
        return $this->success($data, "Successfully Data Fetched", 200);
    }

    public function ClassAttendance(Request $request, $filter_date = null)
    {
        $type = $request->query("type") ?? 0;
        // dd($type);
        $user_id = $request->user()->id;
        $timezone = Configurations::getConfig("site")->time_zone;
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $attendance_type = Configurations::ATNTYPES;
        $attendance_types = [];

        foreach ($attendance_type as $key => $attend_type) {
            $attendance_types[] = ["id" => $key, "type" => $attend_type];
        }

        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();
        $date = Carbon::Now($timezone)->format("Y-m-d");
        $attendence_id = [];
        $students = [];

        if ($type == "hourly") {
            $subject_id = $request->query("subject_id");
            $period_id = $request->query("period_id");
            $days = Configurations::WEEKLYDAYS;
            $dayname = Carbon::now($timezone)->format("l");
            $dayNumber = Configurations::WEEKLYDAYS[$dayname];

            $maping = ClasstimetableModel::where([
                "teacher_id" => $teacher_id,
                "day" => $dayNumber,
                "subject_id" => $subject_id,
                "academic_year" => $current_academic_year,
                "period_id" => $period_id,
            ])->first();
            // dd($maping);
            if ($maping) {
                $students = StudentsModel::with("user")
                    ->where([
                        "academic_year" => $current_academic_year,
                        "class_id" => $maping->class_id,
                        "section_id" => $maping->section_id,
                        "status" => 1,
                    ])
                    ->whereNull("deleted_at")
                    ->get();
                // dd(
                //     $current_academic_year,
                //     $maping->class_id,
                //     $maping->section_id
                // );
                $total_count = $students->count();
                $students_ids = $students->pluck("id");
                if ($filter_date) {
                    $formated_date = Carbon::parse($filter_date)->format(
                        "Y-m-d"
                    );
                    $attendence_id = AttendanceModel::where([
                        "class_id" => $maping->class_id,
                        "section_id" => $maping->section_id,
                        "academic_year" => $current_academic_year,
                        "subject_id" => $subject_id,
                        "academic_term" => $current_academic_term,
                        "type" => 1,
                        "attendance_date" => $formated_date,
                    ])
                        ->pluck("id")
                        ->first();
                } else {
                    $attendence_id = AttendanceModel::where([
                        "class_id" => $maping->class_id,
                        "section_id" => $maping->section_id,
                        "academic_year" => $current_academic_year,
                        "attendance_date" => $date,
                        "subject_id" => $subject_id,
                        "academic_term" => $current_academic_term,
                        "type" => 1,
                    ])
                        ->pluck("id")
                        ->first();
                }

                // dd($attendence_id);
            }
        } else {
            $classteacher = ClassteacherModel::with("class", "section")
                ->where([
                    "academic_year" => $current_academic_year,
                    "teacher_id" => $teacher_id,
                ])
                ->first();

            $students = StudentsModel::with("user")
                ->where([
                    "academic_year" => $current_academic_year,
                    "class_id" => $classteacher->class_id,
                    "section_id" => $classteacher->section_id,
                    "status" => 1,
                ])
                ->whereNull("deleted_at")
                ->get();
            $total_count = $students->count();
            $students_ids = $students->pluck("id");
            if ($filter_date) {
                $formated_date = Carbon::parse($filter_date)->format("Y-m-d");
                $attendence_id = AttendanceModel::where([
                    "class_id" => $classteacher->class_id,
                    "section_id" => $classteacher->section_id,
                    "academic_year" => $current_academic_year,
                    "academic_term" => $current_academic_term,
                    "type" => 2,
                    "attendance_date" => $formated_date,
                ])
                    ->pluck("id")
                    ->first();
                // dd($attendence_id, $formated_date, $filter_date);
            } else {
                $attendence_id = AttendanceModel::where([
                    "class_id" => $classteacher->class_id,
                    "section_id" => $classteacher->section_id,
                    "academic_year" => $current_academic_year,
                    "attendance_date" => $date,
                    "academic_term" => $current_academic_term,
                    "type" => 2,
                ])
                    ->pluck("id")
                    ->first();
            }
        }

        $students_list = [];
        if ($attendence_id) {
            // dd($students);
            foreach ($students as $student) {
                $attendance = StudentAttendanceModel::where([
                    "attendance_id" => $attendence_id,
                    "student_id" => $student->id,
                ])
                    ->pluck("attendance")
                    ->first();
                if ($attendance == 1) {
                    $students_list[] = [
                        "student_info" => $student,
                        "attendance" => "Present",
                    ];
                } elseif ($attendance == 2) {
                    $students_list[] = [
                        "student_info" => $student,
                        "attendance" => "Late",
                    ];
                } elseif ($attendance == 0) {
                    $students_list[] = [
                        "student_info" => $student,
                        "attendance" => "Absent",
                    ];
                } else {
                    $students_list[] = [
                        "student_info" => $student,
                        "attendance" => "Not Taken",
                    ];
                }
            }
            $present = collect($students_list)
                ->where("attendance", "Present")
                ->count();
            $absent = collect($students_list)
                ->where("attendance", "Absent")
                ->count();
            $late = collect($students_list)
                ->where("attendance", "Late")
                ->count();
            $not_taken = collect($students_list)
                ->where("attendance", "Not Taken")
                ->count();
        } else {
            if ($students) {
                foreach ($students as $student) {
                    $students_list[] = [
                        "student_info" => $student,
                        "attendance" => "Not Taken",
                    ];
                }
                $present = 0;
                $absent = 0;
                $late = 0;
                $not_taken = $total_count;
            }
        }
        // dd(
        //     $request->user(),
        //     $classteacher,
        //     $type,
        //     $students,
        //     $teacher_id,
        //     $current_academic_year
        // );
        if (empty($students_list)) {
            return $this->error("No Data Found", 500);
        }
        if ($type == "hourly") {
            $data = [
                "student_list" => $students_list,
                "total_count" => $total_count,
                "present" => $present,
                "absent" => $absent,
                "late" => $late,
                "not_taken" => $not_taken,
                "attendance_types" => $attendance_types,
                "subject_id" => $subject_id,
                "period_id" => $period_id,
            ];
        } else {
            $data = [
                "class_info" => $classteacher,
                "student_list" => $students_list,
                "total_count" => $total_count,
                "present" => $present,
                "absent" => $absent,
                "late" => $late,
                "not_taken" => $not_taken,
                "attendance_types" => $attendance_types,
            ];
        }

        return $this->success($data, "Successfully Data Fetched", 200);
    }

    public function DailyAttendance(Request $request)
    {
        try {
            DB::beginTransaction();

            $user_id = $request->user()->id;
            $timezone = Configurations::getConfig("site")->time_zone;
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $current_academic_term = Configurations::getCurrentAcademicterm();
            $classteacher = ClassteacherModel::where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])->first();
            $date = Carbon::Now($timezone)->format("Y-m-d");

            $already_added = AttendanceModel::where([
                "class_id" => $classteacher->class_id,
                "section_id" => $classteacher->section_id,
                "academic_year" => $current_academic_year,
                "attendance_date" => $date,
                "academic_term" => $current_academic_term,
                "type" => 2,
            ])->first();

            //dd($already_added);

            if ($already_added) {
                foreach ($request->attendences as $student_id => $attend) {
                    if ($attend == "0") {
                        $parent_id = StudentsModel::where("id", $student_id)
                            ->pluck("parent_id")
                            ->first();
                        $device_user = UserModel::where("id", $parent_id)
                            ->pluck("device_token", "name")
                            ->first();

                        $messaging = app("firebase.messaging");
                        if ($device_user) {
                            if ($device_user->device_token !== null) {
                                $result = $messaging->validateRegistrationTokens(
                                    $device_user->device_token
                                );

                                if ($result && sizeof($result["valid"])) {
                                    $success = Notification::sendNow(
                                        $user,
                                        new FirebasePushNotification(
                                            "Not Attend Notification",
                                            "Your son/daughter was not attend the class",
                                            [
                                                "route" => "notification",
                                                "type" => "global",

                                                "student_name" =>
                                                    $device_user->username,
                                            ],
                                            null
                                        )
                                    );
                                }
                            }
                        }
                    }
                    StudentAttendanceModel::updateOrCreate(
                        [
                            "attendance_id" => $already_added->id,
                            "student_id" => $student_id,
                        ],
                        [
                            "attendance" => $attend,
                        ]
                    );
                }
            } else {
                $attendance = new AttendanceModel();
                $attendance->academic_year = $current_academic_year;
                $attendance->class_id = $classteacher->class_id;
                $attendance->section_id = $classteacher->section_id;
                $attendance->academic_term = $current_academic_term;
                $attendance->teacher_id = $teacher_id;
                $attendance->type = 2;
                $attendance->attendance_date = $date;
                $attendance->attendance_month = Carbon::parse($date)->format(
                    "F"
                );
                $attendance->attendance_year = Carbon::parse($date)->format(
                    "Y"
                );
                $attendance->attendance_time = Carbon::now(
                    Configurations::getConfig("site")->time_zone
                )->toTimeString();
                $attendance->attendance_taken_by = $user_id;
                if ($attendance->save()) {
                    foreach ($request->attendences as $student_id => $attend) {
                        if ($attend == "0") {
                            $parent_id = StudentsModel::where("id", $student_id)
                                ->pluck("parent_id")
                                ->first();
                            $device_user = UserModel::where("id", $parent_id)
                                ->pluck("device_token", "name")
                                ->first();

                            $messaging = app("firebase.messaging");

                            if ($device_user) {
                                if ($device_user->device_token !== null) {
                                    $result = $messaging->validateRegistrationTokens(
                                        $device_user->device_token
                                    );

                                    if ($result && sizeof($result["valid"])) {
                                        $success = Notification::sendNow(
                                            $user,
                                            new FirebasePushNotification(
                                                "Not Attend Notification",
                                                "Your son/daughter was not attend the class",
                                                [
                                                    "route" => "notification",
                                                    "type" => "global",

                                                    "student_name" =>
                                                        $device_user->username,
                                                ],
                                                null
                                            )
                                        );
                                    }
                                }
                            }
                        }
                        $s_attendance = new StudentAttendanceModel();
                        $s_attendance->attendance_id = $attendance->id;
                        $s_attendance->student_id = $student_id;
                        $s_attendance->attendance = $attend;
                        $s_attendance->save();
                    }
                }
            }

            DB::commit();
            return $this->success("Attendance Added Successfully", 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function HourlyAttendance(Request $request)
    {
        try {
            DB::beginTransaction();

            $user_id = $request->user()->id;
            $subject_id = $request->subject_id;
            $period_id = $request->period_id;
            $timezone = Configurations::getConfig("site")->time_zone;
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $current_academic_term = Configurations::getCurrentAcademicterm();

            $date = Carbon::Now($timezone)->format("Y-m-d");
            $days = Configurations::WEEK_DAYS;
            $dayname = Carbon::now($timezone)->format("l");
            $dayNumber = Configurations::WEEKLYDAYS[$dayname];
            $period = ClasstimetableModel::where([
                "teacher_id" => $teacher_id,
                "day" => $dayNumber,
                "subject_id" => $subject_id,
                "academic_year" => $current_academic_year,
                "period_id" => $period_id,
            ])->first();
            // dd(
            //     $teacher_id,
            //     $dayNumber,
            //     $subject_id,
            //     $current_academic_year,
            //     $period_id
            // );
            if ($period) {
                $day = Carbon::parse($date)->format("l");

                $daymformat = Carbon::parse($date)->format("d F Y");
                $year = Carbon::parse($date)->format("F");

                $students = StudentsModel::where([
                    "status" => 1,
                    "academic_year" => $period->academic_year,
                    "class_id" => $period->class_id,
                    "section_id" => $period->section_id,
                ])->whereNull("deleted_at");

                $period_with_attendance = AttendanceModel::where([
                    "period_id" => $period->period_id,
                    "attendance_date" => $date,
                ])->first();

                $already_added = AttendanceModel::where([
                    "class_id" => $period->class_id,
                    "section_id" => $period->section_id,
                    "period_id" => $period->period_id,
                    "academic_term" => $current_academic_term,
                    "academic_year" => $period->academic_year,
                    "attendance_date" => $date,
                    "type" => 1,
                ])->first();

                if ($already_added) {
                    foreach ($request->attendences as $student_id => $attend) {
                        if ($attend == "0") {
                            $parent_id = StudentsModel::where("id", $student_id)
                                ->pluck("parent_id")
                                ->first();
                            $device_user = UserModel::where("id", $parent_id)
                                ->pluck("device_token", "name")
                                ->first();

                            $messaging = app("firebase.messaging");
                            if ($device_user) {
                                if ($device_user->device_token !== null) {
                                    $result = $messaging->validateRegistrationTokens(
                                        $device_user->device_token
                                    );

                                    if ($result && sizeof($result["valid"])) {
                                        $success = Notification::sendNow(
                                            $user,
                                            new FirebasePushNotification(
                                                "Not Attend Notification",
                                                "Your son/daughter was not attend the class",
                                                [
                                                    "route" => "notification",
                                                    "type" => "global",

                                                    "student_name" =>
                                                        $device_user->username,
                                                ],
                                                null
                                            )
                                        );
                                    }
                                }
                            }
                        }
                        StudentAttendanceModel::updateOrCreate(
                            [
                                "attendance_id" => $already_added->id,
                                "student_id" => $student_id,
                            ],
                            [
                                "attendance" => $attend,
                            ]
                        );
                    }
                } else {
                    $attendance = new AttendanceModel();
                    $attendance->academic_year = $period->academic_year;
                    $attendance->class_id = $period->class_id;
                    $attendance->section_id = $period->section_id;
                    $attendance->academic_term = $current_academic_term;
                    $attendance->period_id = $period->period_id;
                    $attendance->subject_id = $period->subject_id;
                    $attendance->teacher_id = $teacher_id;
                    $attendance->type = 1;
                    $attendance->attendance_date = $date;
                    $attendance->attendance_month = Carbon::parse(
                        $date
                    )->format("F");
                    $attendance->attendance_year = Carbon::parse($date)->format(
                        "Y"
                    );
                    $attendance->attendance_time = Carbon::now(
                        Configurations::getConfig("site")->time_zone
                    )->toTimeString();
                    $attendance->attendance_taken_by = $user_id;
                    if ($attendance->save()) {
                        foreach (
                            $request->attendences
                            as $student_id => $attend
                        ) {
                            if ($attend == "0") {
                                $parent_id = StudentsModel::where(
                                    "id",
                                    $student_id
                                )
                                    ->pluck("parent_id")
                                    ->first();
                                $device_user = UserModel::where(
                                    "id",
                                    $parent_id
                                )
                                    ->pluck("device_token", "name")
                                    ->first();

                                $messaging = app("firebase.messaging");
                                if ($device_user) {
                                    if ($device_user->device_token !== null) {
                                        $result = $messaging->validateRegistrationTokens(
                                            $device_user->device_token
                                        );

                                        if (
                                            $result &&
                                            sizeof($result["valid"])
                                        ) {
                                            $success = Notification::sendNow(
                                                $user,
                                                new FirebasePushNotification(
                                                    "Not Attend Notification",
                                                    "Your son/daughter was not attend the class",
                                                    [
                                                        "route" =>
                                                            "notification",
                                                        "type" => "global",

                                                        "student_name" =>
                                                            $device_user->username,
                                                    ],
                                                    null
                                                )
                                            );
                                        }
                                    }
                                }
                            }
                            $s_attendance = new StudentAttendanceModel();
                            $s_attendance->attendance_id = $attendance->id;
                            $s_attendance->student_id = $student_id;
                            $s_attendance->attendance = $attend;
                            $s_attendance->save();
                        }
                    }
                }
                $msg = "Attendance Added Successfully";
            } else {
                $msg = "No Periods Found";
            }
            DB::commit();
            return $this->success($msg, 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function SubjectList(Request $request, $type = null)
    {
        $user_id = $request->user()->id;
        $type = $type ?? 0;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        if ($type == "quiz" || $type == "exam") {
            $classteacher = ClassteacherModel::with("class", "section")
                ->where([
                    "academic_year" => $current_academic_year,
                    "teacher_id" => $teacher_id,
                ])
                ->first();
            $class_subject_mapping = SubjectModel::where([
                "class_id" => $classteacher->class_id,
                "status" => 1,
            ])
                ->select("id", "name")
                ->get();
            $class_subjects = [];
            // dd($class_subject_mapping);
            if (isset($class_subject_mapping)) {
                foreach ($class_subject_mapping as $subject) {
                    $text =
                        $subject->name .
                        "/" .
                        $classteacher->class->name .
                        "/" .
                        $classteacher->section->name;
                    $class_subjects[] = ["id" => $subject->id, "text" => $text];
                }
            }
        }

        $subject_map = SubjectTeacherMapping::with(
            "subject",
            "class",
            "section"
        )
            ->where([
                "teacher_id" => $teacher_id,
                "academic_year" => $current_academic_year,
            ])
            ->get();
        $subject_List = [];
        if (!isset($subject_map)) {
            return $this->error("Cannot Assign any Subjects", 500);
        }
        foreach ($subject_map as $data) {
            $text =
                $data->subject->name .
                "/" .
                $data->class->name .
                "/" .
                $data->section->name;
            $subject_List[] = ["id" => $data->subject->id, "text" => $text];
        }
        // dd($type, $class_subjects, $subject_List);
        if ($type == "quiz" || $type == "exam") {
            $subjects = array_merge($class_subjects, $subject_List);
            $data = [];
            foreach ($subjects as $subject) {
                $data[$subject["id"]] = $subject;
            }
            $data = array_values($data);
        } else {
            $data = $subject_List;
        }
        return $this->success($data, "Successfully Data Fetched", 200);
    }

    public function SubjectStudentList(Request $request)
    {
        $subject_id = $request->query("subject_id");
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $class_section = SubjectTeacherMapping::with("class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
                "subject_id" => $subject_id,
            ])
            ->first();
        $students = StudentsModel::where([
            "academic_year" => $current_academic_year,
            "class_id" => $class_section->class_id,
            "section_id" => $class_section->section_id,
            "status" => 1,
        ])
            ->whereNull("deleted_at")
            ->select("id", DB::raw("CONCAT(first_name, last_name) as name"))
            ->get();
        return $this->success($students, "Successfully Data Fetched", 200);
    }

    public function PeriodList(Request $request)
    {
        $user_id = $request->user()->id;
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();

        $subject_id = $request->query("subject_id");
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $timezone = Configurations::getConfig("site")->time_zone;
        $date = Carbon::Now($timezone)->format("Y/m/d");
        $days = Configurations::WEEK_DAYS;
        $dayname = Carbon::now($timezone)->format("l");
        $dayNumber = Configurations::WEEKLYDAYS[$dayname];
        $period_ids = ClasstimetableModel::where([
            "teacher_id" => $teacher_id,
            "day" => $dayNumber,
            "subject_id" => $subject_id,
            "academic_year" => $current_academic_year,
        ])->pluck("period_id");
        // dd($current_academic_year, $teacher_id, $subject_id, $period_ids);
        if ($period_ids) {
            $periods = PeriodClassMappingModel::whereIn("id", $period_ids)
                ->select("id", DB::raw("CONCAT(`from`, ' - ', `to` ) as text"))
                ->get();
            $data = [
                "subject_id" => $subject_id,
                "periods" => $periods,
            ];

            return $this->success($data, "Successfully Data Fetched", 200);
        } else {
            return $this->error("No Periods Found", 500);
        }
    }
    public function ClassTimetableExist(Request $request)
    {
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $classteacher = ClassteacherModel::with("class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])
            ->first();
        $timetable_data = ClasstimetableModel::Where([
            "academic_year" => $current_academic_year,
            "class_id" => $classteacher->class_id,
            "section_id" => $classteacher->section_id,
        ])->first();
        if ($timetable_data) {
            $exist = 1;
            $msg = "Timetable Exist.";
        } else {
            $exist = 0;
            $msg = "Timetable Not Exist.";
        }
        return $this->success(["exist" => $exist], $msg, 200);
    }

    public function ClassTimeTableCreate(Request $request)
    {
        $type = $request->query("type", 0);
        $user_id = $request->user()->id;
        $timezone = Configurations::getConfig("site")->time_zone;
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $classteacher = ClassteacherModel::with("class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])
            ->first();

        if ($type == "edit") {
            $no_of_days = ClasstimetableModel::where([
                "academic_year" => $current_academic_year,
                "class_id" => $classteacher->class_id,
                "section_id" => $classteacher->section_id,
            ])
                // ->latest("created_at")
                ->pluck("no_of_days")
                ->first();
            // dd($no_of_days);
            $data = [
                "no_of_days" => $no_of_days,
                "class_info" => $classteacher,
            ];
        } else {
            $data = ["class_info" => $classteacher];
        }
        return $this->success($data, "Successfully Data Fetched", 200);
    }

    public function PeriodsExist(Request $request)
    {
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $classteacher = ClassteacherModel::with("class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])
            ->first();
        $period_class_id = PeriodModel::where([
            "academic_year" => $current_academic_year,
            "class_id" => $classteacher->class_id,
            "section_id" => $classteacher->section_id,
        ])
            ->pluck("id")
            ->first();

        if ($period_class_id) {
            $periods = PeriodClassMappingModel::where(
                "period_class_id",
                $period_class_id
            )->get();
            $periods = $periods->transform(function ($period) {
                $period->period_category =
                    Configurations::CLASSPERIODCATEGORIES[$period->type];
                $period->from_time = Carbon::parse($period->from)->format(
                    "g:i a"
                );
                $period->to_time = Carbon::parse($period->to)->format("g:i a");
                return $period;
            });
            $categories = Configurations::CLASSPERIODCATEGORIES;
            return $this->success(
                [
                    "exist" => 1,
                    "periods" => $periods,
                    "categories" => $categories,
                ],
                "Periods Exist",
                200
            );
        } else {
            return $this->success(["exist" => 0], "Periods Not Exist", 200);
        }
    }

    public function PeriodCatogories(Request $request)
    {
        $categories = Configurations::CLASSPERIODCATEGORIES;
        $data = [];
        foreach ($categories as $key => $catogory) {
            $data[] = ["id" => $key, "text" => $catogory];
        }
        return $this->success($data, "Successfully Data Fetched", 200);
    }

    public function CreatePeriods(Request $request)
    {
        // Validate the request
        // $this->validate($request, [
        //     'no_days' => 'required|integer',
        //     'period_category' => 'required|array',
        //     'period_category.*' => 'required',
        //     'start_time' => 'required|array',
        //     'start_time.*' => 'required',
        //     'end_time' => 'required|array',
        //     'end_time.*' => 'required',
        // ], [
        //     'period_category.required' => 'Period category is required.',
        // ]);

        DB::beginTransaction();
        try {
            $user_id = $request->user()->id;
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $classteacher = ClassteacherModel::with("class", "section")
                ->where([
                    "academic_year" => $current_academic_year,
                    "teacher_id" => $teacher_id,
                ])
                ->firstOrFail();

            $period_class_id = PeriodModel::where([
                "academic_year" => $current_academic_year,
                "class_id" => $classteacher->class_id,
                "section_id" => $classteacher->section_id,
            ])
                ->pluck("id")
                ->first();

            $is_exist = !is_null($period_class_id);
            $obj = null;

            if ($is_exist) {
                $period_id = PeriodClassMappingModel::where([
                    "period_class_id" => $period_class_id,
                    "type" => 0,
                ])
                    ->pluck("id")
                    ->first();

                $timetable_exist = ClasstimetableModel::where(
                    "period_id",
                    $period_id
                )->first();

                if (!$timetable_exist) {
                    PeriodModel::where([
                        "academic_year" => $current_academic_year,
                        "class_id" => $classteacher->class_id,
                        "section_id" => $classteacher->section_id,
                    ])->forceDelete();
                }

                $obj = PeriodModel::find($period_class_id);
            }

            if (!$obj) {
                $obj = new PeriodModel();
            }

            $obj->academic_year = $current_academic_year;
            $obj->class_id = $classteacher->class_id;
            $obj->section_id = $classteacher->section_id;

            if ($obj->save()) {
                // Handle existing period category
                if (
                    $request->exist_period_category &&
                    is_array($request->exist_period_category)
                ) {
                    $period_map = PeriodClassMappingModel::where(
                        "period_class_id",
                        $period_class_id
                    )->get();

                    foreach (
                        $request->exist_period_category
                        as $key => $period
                    ) {
                        if (
                            isset($request->exist_start_time[$key]) &&
                            !empty($request->exist_start_time[$key])
                        ) {
                            $start = Carbon::parse(
                                $request->exist_start_time[$key]
                            )->format("g:i a");
                            $end = Carbon::parse(
                                $request->exist_end_time[$key]
                            )->format("g:i a");

                            $start_datetime = new DateTime(
                                date("Y-m-d") .
                                    " " .
                                    $request->exist_start_time[$key]
                            );
                            $end_datetime = new DateTime(
                                date("Y-m-d") .
                                    " " .
                                    $request->exist_end_time[$key]
                            );
                            $interval = $start_datetime->diff($end_datetime);

                            $break_min = $interval->format(
                                "%h hour %i min %s second"
                            );

                            if (isset($period_map[$key])) {
                                $current_period_map = PeriodClassMappingModel::find(
                                    $period_map[$key]->id
                                );

                                if ($current_period_map->type == $period) {
                                    $current_period_map->period_class_id =
                                        $obj->id;
                                    $current_period_map->from = $start;
                                    $current_period_map->to = $end;
                                    $current_period_map->type = $period;
                                    $current_period_map->break_min = $break_min;
                                    $current_period_map->save();
                                } else {
                                    $timetable_entries = ClassTimetableModel::where(
                                        "period_id",
                                        $period_map[$key]->id
                                    )->exists();

                                    if ($timetable_entries) {
                                        ClassTimetableModel::where(
                                            "period_id",
                                            $period_map[$key]->id
                                        )->forceDelete();
                                    }

                                    PeriodClassMappingModel::find(
                                        $period_map[$key]->id
                                    )->forceDelete();

                                    $current_period_map = new PeriodClassMappingModel();
                                }
                            } else {
                                $current_period_map = new PeriodClassMappingModel();
                            }

                            $current_period_map->period_class_id = $obj->id;
                            $current_period_map->from = $start;
                            $current_period_map->to = $end;
                            $current_period_map->type = $period;
                            $current_period_map->break_min = $break_min;
                            $current_period_map->save();
                        } else {
                            return $this->error(
                                "Required fields were empty",
                                500
                            );
                        }
                    }
                }

                // Handle new period category
                if (
                    $request->period_category &&
                    is_array($request->period_category)
                ) {
                    foreach ($request->period_category as $key => $period) {
                        if (
                            isset($request->start_time[$key]) &&
                            !empty($request->start_time[$key])
                        ) {
                            $start = Carbon::parse(
                                $request->start_time[$key]
                            )->format("g:i a");
                            $end = Carbon::parse(
                                $request->end_time[$key]
                            )->format("g:i a");

                            $start_datetime = new DateTime(
                                date("Y-m-d") . " " . $request->start_time[$key]
                            );
                            $end_datetime = new DateTime(
                                date("Y-m-d") . " " . $request->end_time[$key]
                            );
                            $interval = $start_datetime->diff($end_datetime);

                            $break_min = $interval->format(
                                "%h hour %i min %s second"
                            );

                            $new_period_map = new PeriodClassMappingModel();
                            $new_period_map->period_class_id = $obj->id;
                            $new_period_map->from = $start;
                            $new_period_map->to = $end;
                            $new_period_map->type = $period;
                            $new_period_map->break_min = $break_min;
                            $new_period_map->save();
                        } else {
                            return $this->error(
                                "Required fields were empty",
                                500
                            );
                        }
                    }
                }

                // Prepare data for the response
                $id = $obj->id;
                $timing = PeriodClassMappingModel::where(
                    "period_class_id",
                    $id
                )->get();
                $timing = $timing->transform(function ($data) {
                    $data->period_type =
                        Configurations::CLASSPERIODCATEGORIES[$data->type];
                    return $data;
                });

                $subjects = SubjectModel::where("status", 1)
                    ->where("class_id", $obj->class_id)
                    ->pluck("name", "id")
                    ->toArray();
                $getalldays = Configurations::WEEKDAYS;
                $getNoofdays = [];
                $no_of_days = $request->no_days;
                // dd($no_of_days);
                for ($i = 1; $i <= $no_of_days; $i++) {
                    $getNoofdays[$i] = $getalldays[$i];
                }
                $days = [];
                foreach ($getNoofdays as $key => $day) {
                    $days[] = ["id" => $key, "day" => $day];
                }
                $data = [
                    "days" => $days,
                    "timing" => $timing,
                    "class_id" => $classteacher->class,
                    "getweekend" => Configurations::getConfig("site")->week_end,
                    "period_class_id" => $id,
                    "section" => $classteacher->section,
                    "no_of_days" => $request->no_days,
                ];

                DB::commit();

                return $this->success(
                    $data,
                    "Successfully Period Created",
                    200
                );
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error("An error occurred: " . $e->getMessage(), 500);
        }
    }

    public function ClassSubjects(Request $request)
    {
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $classteacher = ClassteacherModel::with("class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])
            ->first();
        $subjects = SubjectModel::where([
            "class_id" => $classteacher->class_id,
            "status" => 1,
        ])
            ->select("id", "name")
            ->get();

        return $this->success($subjects, "Successfully Data Fetched", 200);
    }

    public function SubjectTeachers(Request $request)
    {
        $subject_id = $request->query("subject_id");
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $classteacher = ClassteacherModel::with("class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])
            ->first();
        $teacher_ids = SubjectTeacherMapping::where([
            "subject_id" => $subject_id,
            "class_id" => $classteacher->class_id,
            "section_id" => $classteacher->section_id,
            "academic_year" => $current_academic_year,
            "status" => 1,
        ])->pluck("teacher_id");

        $teachers = TeacherModel::whereIn("id", $teacher_ids)
            ->select("id", "teacher_name as name")
            ->get();
        return $this->success(
            ["subject_id" => $subject_id, "teachers" => $teachers],
            "Successfully Data Fetched",
            200
        );
    }

    public function AssignSubjectTeacher(Request $request)
    {
        $subject_id = $request->subject_id;
        $teacher_id = $request->teacher_id;
        $bg_color = $request->bg_color;
        $period_id = $request->period_id;

        $subject = SubjectModel::where("id", $subject_id)
            ->select("id", "name")
            ->first();
        $teacher = TeacherModel::where("id", $teacher_id)
            ->select("id", "teacher_name as name")
            ->first();

        $data = [
            "period_id" => $period_id,
            "subject" => $subject,
            "teacher" => $teacher,
            "bgcolor" => $bg_color,
        ];
        return $this->success($data, "Successfully Assigned", 200);
    }

    public function StoreTimetable(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();

            $periodClassId = $request->input("period_class_id");
            $user_id = $request->user()->id;
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $classteacher = ClassteacherModel::with("class", "section")
                ->where([
                    "academic_year" => $current_academic_year,
                    "teacher_id" => $teacher_id,
                ])
                ->first();
            // dd($request->input("data"));
            $dataPart = $request->input("data");

            // Check if '_parts' exists within 'data'
            if (!isset($dataPart["_parts"])) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Data parts not found in the request",
                    ],
                    400
                );
            }

            // Initialize variables to store extracted data
            $periodClassId = null;
            $dataArray = null;

            // Loop through _parts to find period_class_id and data
            foreach ($dataPart["_parts"] as $part) {
                if ($part[0] === "period_class_id") {
                    $periodClassId = $part[1];
                }
                if ($part[0] === "data") {
                    $dataArray = $part[1];
                }
            }

            if (is_null($dataArray)) {
                return response()->json(
                    [
                        "status" => "error",
                        "message" => "Data not found in the request",
                    ],
                    400
                );
            }

            // $dataarray = json_decode($request->input("data"), true);
            // return $this->success($request->data, "Request Data", 200);
            $transformedArray = [];
            // dd($request->json()->all());
            foreach ($dataArray as $entry) {
                foreach ($entry as $key => $value) {
                    foreach ($value as $innerKey => $innerValue) {
                        foreach ($innerValue as $id => $data) {
                            $transformedArray[$key][$id] = [
                                "subject" => $data[0]["subject_id"],
                                "teacher" => $data[1]["teacher_id"],
                                "bgcolor" => $data[2]["bgcolor"],
                            ];
                        }
                    }
                }
            }
            // dd($transformedArray);
            // return $this->success(
            //     $transformedArray,
            //     "Data transfered successfully",
            //     200
            // );
            $post_data = $transformedArray;
            $perioddata = [];
            $period = PeriodModel::where("id", $request->period_id)->first();
            $period_ids = [];
            $day_ids = [];
            foreach ($post_data as $dayid => $periods) {
                $day_ids[] = [$dayid];
                foreach ($periods as $periodid => $data) {
                    $period_ids[] = [$periodid];
                    $existingTimetable = ClasstimetableModel::where([
                        "academic_year" => $current_academic_year,
                        "class_id" => $classteacher->class_id,
                        "section_id" => $classteacher->section_id,
                        "day" => $dayid,
                        "period_id" => $periodid,
                    ])->first();

                    if ($existingTimetable) {
                        if ($data["subject"] == "NA") {
                            $existingTimetable->forceDelete();
                        } else {
                            if ($data["subject"] != null) {
                                $existingTimetable->update([
                                    "subject_id" => $data["subject"],
                                ]);
                            }
                            if ($data["teacher"] != null) {
                                $existingTimetable->update([
                                    "teacher_id" => $data["teacher"],
                                ]);
                            }
                            if ($data["bgcolor"] != null) {
                                $existingTimetable->update([
                                    "colorcode" => $data["bgcolor"],
                                ]);
                            }
                        }

                        $existingTimetable->update([
                            "no_of_days" => count($post_data),
                        ]);
                    } else {
                        if ($data["subject"] && $data["teacher"]) {
                            $perioddata[] = [
                                "academic_year" => $current_academic_year,
                                "class_id" => $classteacher->class_id,
                                "section_id" => $classteacher->section_id,
                                "day" => $dayid,
                                "period_id" => $periodid,
                                "subject_id" => $data["subject"],
                                "teacher_id" => $data["teacher"],
                                "colorcode" => $data["bgcolor"] ?? null,
                                "border_color" => $data["bordercolor"] ?? null,
                                "no_of_days" => count($post_data),
                            ];
                        }
                    }
                }
            }
            $existTimetabledltday = ClasstimetableModel::where([
                "academic_year" => $current_academic_year,
                "class_id" => $classteacher->class_id,
                "section_id" => $classteacher->section_id,
            ])
                ->whereNotIn("day", $day_ids)
                ->forceDelete();
            $existTimetabledltperiod = ClasstimetableModel::where([
                "academic_year" => $current_academic_year,
                "class_id" => $classteacher->class_id,
                "section_id" => $classteacher->section_id,
            ])
                ->whereNotIn("period_id", $period_ids)
                ->forceDelete();

            // return $this->success($perioddata, "period data", 200);
            if (!empty($perioddata)) {
                ClasstimetableModel::insert($perioddata);
            }
            // else {
            //     return $this->error(
            //         "Some Subject Data not Assigned Kindly Fill All Information",
            //         500
            //     );
            // }

            DB::commit();
            return $this->success("TimeTable Created Successfully", 200);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function ViewClassTimetable(Request $request)
    {
        $user_id = $request->user()->id;
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $classteacher = ClassteacherModel::with("class", "section")
            ->where([
                "academic_year" => $current_academic_year,
                "teacher_id" => $teacher_id,
            ])
            ->first();
        $timetable_data = ClasstimetableModel::with(
            "subject",
            "class",
            "periods",
            "section",
            "staff"
        )
            ->where([
                "academic_year" => $current_academic_year,
                "class_id" => $classteacher->class_id,
                "section_id" => $classteacher->section_id,
            ])
            ->get();
        // $for_timetable_data = ClasstimetableModel::with(
        //     "subject",
        //     "class",
        //     "periods",
        //     "section",
        //     "staff"
        // )
        //     ->where([
        //         "academic_year" => $current_academic_year,
        //         "class_id" => $classteacher->class_id,
        //         "section_id" => $classteacher->section_id,
        //     ])
        //     ->orderBy("created_at", "desc")
        //     ->get();

        // $print = [
        //     "userid" => $user_id,
        //     "academic_year" => $current_academic_year,
        //     "teacher_id" => $teacher_id,
        //     "classteacher" => $classteacher,
        //     "timetable_tmpdata" => $timetable_data,
        // ];
        $getWeekend = Configurations::getConfig("site")->week_end;
        $getalldays = Configurations::WEEKDAYS;
        $getNoofdaysTimetableadded = [];
        $no_of_days = $timetable_data->pluck("no_of_days")->first();
        $format_period_class_id = $timetable_data->pluck("period_id")->first();
        // dd($no_of_days);
        // dd($no_of_days);
        for ($i = 1; $i <= $no_of_days; $i++) {
            $getNoofdaysTimetableadded[$i] = $getalldays[$i];
        }
        $timetable = [];
        foreach ($getNoofdaysTimetableadded as $key => $day) {
            $period_id = $timetable_data
                ->where("day", $key)
                ->pluck("period_id")
                ->first();
            $map_id = $period_id ? $period_id : $format_period_class_id;
            $period_class_id = PeriodClassMappingModel::where("id", $map_id)
                ->pluck("period_class_id")
                ->first();
            $periods = PeriodClassMappingModel::where(
                "period_class_id",
                $period_class_id
            )->get();
            $periods = $periods->transform(function ($period) {
                $period->from_time = Carbon::parse($period->from)->format(
                    "g:i a"
                );
                $period->to_time = Carbon::parse($period->to)->format("g:i a");
                return $period;
            });
            $period_data = [];
            $dayid = $key;
            foreach ($periods as $period) {
                $data = $timetable_data
                    ->where("day", $dayid)
                    ->where("period_id", $period->id)
                    ->first();
                $period_catogory =
                    Configurations::CLASSPERIODCATEGORIES[$period->type];
                $period_data[] = [
                    "period" => $period,
                    "period_catogory" => $period_catogory,
                    "data" => $data,
                ];
            }
            $is_weekend = 0;
            if (in_array($key, $getWeekend)) {
                $is_weekend = 1;
            }
            $timetable[] = [
                "id" => $key,
                "day" => $day,
                "is_weekend" => $is_weekend,
                "period_data" => $period_data,
            ];
        }

        return $this->success($timetable, "Data Fetched Successfully", 200);
    }

    public function PeriodDelete(Request $request)
    {
        $period_id = $request->query("period_id");

        $timetabledelt = ClasstimetableModel::where(
            "period_id",
            $period_id
        )->get();

        if ($timetabledelt->isNotEmpty()) {
            foreach ($timetabledelt as $timetable) {
                $timetable->forceDelete();
            }
        }

        $delt_period = PeriodClassMappingModel::where(
            "id",
            $period_id
        )->forceDelete();

        return $this->success("Period Deleted Successfully", 200);
    }

    public function MyInfo(Request $request)
    {
        $user_id = $request->user()->id;
        $timezone = Configurations::getConfig("site")->time_zone;
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $data = TeacherModel::with("attachment", "designation")->find(
            $teacher_id
        );

        $gender = Configurations::GENDER[$data->gender];
        $maritialstatus =
            Configurations::MARITIALSTATUS[$data->maritial_status];
        $religion = $data->religion
            ? Configurations::RELIGION[$data->religion]
            : $data->religion;
        $bloodgroup = $data->blood_group
            ? Configurations::BLOODGROUPS[$data->blood_group]
            : $data->blood_group;
        $handicapped = $data->handicapped == "1" ? "Yes" : "No";

        $info = [
            "info" => $data,
            "gender" => $gender,
            "maritialstatus" => $maritialstatus,
            "religion" => $religion,
            "bloodgroup" => $bloodgroup,
            "handicapped" => $handicapped,
        ];

        return $this->success($info, "Successfully Data Fetched", 200);
    }

    public function EditProfile(Request $request)
    {
        $user_id = $request->user()->id;
        $timezone = Configurations::getConfig("site")->time_zone;
        $teacher_id = TeacherModel::where("user_id", $user_id)
            ->pluck("id")
            ->first();
        $data = TeacherModel::with("attachment")->find($teacher_id);

        // dd($data);

        $department_ids = DepartmentMappingModel::where("teacher_id", $data->id)
            ->pluck("department_id")
            ->toArray();

        $selected_departments = DepartmentModel::whereIn("id", $department_ids)
            ->pluck("dept_name", "id")
            ->toArray();

        //dd($selected_departments);
        $attachements = [];
        foreach ($data->attachment as $attach) {
            $name = Str::slug(strtolower($attach->attachment_name), "_");

            $attachements[$name][$attach->id] = $attach->attachment_url;
        }

        $designation_list = [];
        $gender = Configurations::GENDER;
        $maritialstatus = Configurations::MARITIALSTATUS;
        $religion = Configurations::RELIGION;
        $bloodgroup = Configurations::BLOODGROUPS;
        $data_list = DesignationModel::whereNull("deleted_at")
            ->where("status", 1)
            ->get();
        if (!empty($data_list)) {
            foreach ($data_list as $designation) {
                $designation_list[$designation->id] =
                    $designation->designation_name . "-" . $designation->type;
            }
        }

        $departments = DepartmentModel::where("status", 1)

            ->pluck("dept_name", "id")
            ->toArray();

        $address_communication = json_decode($data->address_communication);
        // $address_residence = json_decode($data->address_residence);
        $profile_info = [
            "teacher_info" => $data,
        ];

        return $this->success($profile_info, "Successfully Data Fetched", 200);
    }
}
