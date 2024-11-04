<?php

namespace cms\attendance\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\attendance\Models\AttendanceModel;
use cms\classtimetable\Models\ClasstimetableModel;
use Yajra\DataTables\Facades\DataTables;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;
use cms\section\Models\SectionModel;
use cms\academicyear\Models\AcademicyearModel;
use cms\classtimetable\Models\PeriodClassMappingModel;
use cms\classtimetable\Models\PeriodModel;
use cms\subject\Models\SubjectModel;
use Configurations;
use Session;
use DB;
use CGate;
use User;
use DateTime;
use DatePeriod;
use DateInterval;
use Carbon\carbon;
use cms\attendance\Models\StudentAttendanceModel;
use cms\attendance\Traits\AttendanceTrait;
use cms\exam\Models\ExamTermModel;
use Facade\FlareClient\Http\Response;
use cms\core\user\Models\UserModel;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    use AttendanceTrait;
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
            $term_id = $request->query->get("term_id", 0);
            $type = $request->query->get("dataid", 0);

            $class_name = LclassModel::classname($class_id);
            $section_name = SectionModel::sectionname($section_id);
            $acyear = AcademicyearModel::academicyear($academic_year);

            $date = Carbon::now(
                Configurations::getConfig("site")->time_zone
            )->toDateString();

            $day = Carbon::parse($date)->format("l");

            $daymformat = Carbon::parse($date)->format("d F Y");

            if ($type == "hourly") {
                $getperiod = PeriodModel::where([
                    "academic_year" => $academic_year,
                    "class_id" => $class_id,
                ])->first();
                if ($getperiod) {
                    $timing = PeriodClassMappingModel::where(
                        "period_class_id",
                        $getperiod->id
                    )->get();

                    $timetablehr = PeriodClassMappingModel::with([
                        "Timetableperiod" => function ($query) use (
                            $day,
                            $date
                        ) {
                            $query
                                ->with("subject", "staff")
                                ->with([
                                    "attendance" => function ($q) use ($date) {
                                        $q->where("attendance_date", $date);
                                    },
                                ])
                                ->where(
                                    "day",
                                    Configurations::WEEKLYDAYS[$day]
                                );
                        },
                    ])
                        ->where("period_class_id", $getperiod->id)
                        ->get();
                } else {
                    return response()->json([
                        "error" => "No periods found",
                    ]);
                }
            }

            $getweekend = Configurations::getConfig("site")->week_end;
            //dd($timetablehr);

            if ($type === "daily") {
                $students = StudentsModel::where([
                    "status" => 1,
                    "academic_year" => $academic_year,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                ])->whereNull("deleted_at");

                // get added attendnace info
                $period_with_attendance = AttendanceModel::where([
                    "attendance_date" => $date,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "type" => 2,
                ])->first();

                //dd($period_with_attendance);

                if ($period_with_attendance) {
                    $students = $students
                        ->with([
                            "attendance" => function ($q) use (
                                $period_with_attendance
                            ) {
                                $q->where([
                                    "attendance_id" =>
                                        $period_with_attendance->id,
                                ]);
                            },
                        ])
                        ->get();
                } else {
                    $students = $students->get();
                }
                $view = view(
                    "attendance::admin.parts.dailyattendancestudents",
                    [
                        "class_name" => $class_name,
                        "section_name" => $section_name,
                        "class_id" => $class_id,
                        "term_id" => $term_id,
                        "section_id" => $section_id,
                        "acyear" => $acyear,
                        "academicyear_id" => $academic_year,
                        "date" => $date,
                        "day" => $day,

                        "daymformat" => $daymformat,
                        "type" => $type,
                        "students" => $students,
                        "period_with_attendance" => $period_with_attendance
                            ? true
                            : false,
                    ]
                )->render();
            } else {
                $view = view("attendance::admin.parts.attendancecalender", [
                    "class_name" => $class_name,
                    "section_name" => $section_name,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "acyear" => $acyear,
                    "academicyear_id" => $academic_year,
                    "date" => $date,
                    "day" => $day,
                    "timetablehr" => $timetablehr,
                    "daymformat" => $daymformat,
                    "term_id" => $term_id,
                ])->render();
            }

            return response()->json([
                "viewfile" => $view,
            ]);
        }
        $academicyears = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $examterms = Configurations::getCurentAcademicTerms();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $total_students = StudentsModel::where("status", 1)
            ->whereNull("deleted_at")
            ->count();
        $date = Carbon::now(
            Configurations::getConfig("site")->time_zone
        )->toDateString();
        $student_data = [];

        $attendance_info_ = AttendanceModel::where([
            "type" => 2,
            "attendance_date" => $date,
        ])->pluck("id");

        //dd($attendance_info_);

        $attendance_info = StudentAttendanceModel::whereIn(
            "attendance_id",
            $attendance_info_
        )->get();

        //dd($attendance_info);

        return view("attendance::admin.index", [
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "type" => "daily",
            "total_students" => $total_students,
            "attendance_info" => $attendance_info,
            "current_academic_year" => $current_academic_year,
            "current_academic_term" => $current_academic_term,
            "examterms" => $examterms,
        ]);
    }

    public function attendancedailycount(Request $request)
    {
        if ($request->ajax()) {
            $academic_year = $request->query->get("academic_year", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $type = $request->query->get("dataid", 0);

            $total_students = StudentsModel::where("status", 1)
                ->whereNull("deleted_at")
                ->where([
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                ])
                ->count();
            $date = Carbon::now(
                Configurations::getConfig("site")->time_zone
            )->toDateString();
            $student_data = [];

            $attendance_info = AttendanceModel::with("attendancestudents")
                ->where([
                    "type" => 2,
                    "attendance_date" => $date,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                ])
                ->first();
            return response()->json([
                "totalstudents" => $total_students,
                "totalpresent" => $attendance_info
                    ? sizeof(
                        $attendance_info->attendancestudents->where(
                            "attendance",
                            1
                        )
                    )
                    : 0,
                "totalabsent" => $attendance_info
                    ? sizeof(
                        $attendance_info->attendancestudents->where(
                            "attendance",
                            0
                        )
                    )
                    : 0,
            ]);
        }
    }

    public function hourlyindex(Request $request)
    {
        $academicyears = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $examterms = Configurations::getCurentAcademicTerms();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        return view("attendance::admin.index", [
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "type" => "hourly",
            "current_academic_year" => $current_academic_year,
            "current_academic_term" => $current_academic_term,
            "examterms" => $examterms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type = null)
    {
        $info = Configurations::getAcademicandTermsInfo();
        // $info = DB::table('exam_term')->select('id','exam_term_name as text')->get();
        // dd($info);

        $academicyears = Configurations::getAcademicyears();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $date = Carbon::now(
            Configurations::getConfig("site")->time_zone
        )->toDateString();

        //dd($date);

        return view("attendance::admin.edit", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "date" => $date,
            "info" => $info,

            "attendance_type" => $type ? $type : "hourly",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addhourlyattendance(Request $request)
    {
        // dd($request->all());
        // dd($request->attendences);

        $academic_year = $request->query->get("acyear", $request->acyear);
        $class_id = $request->query->get("class", $request->class_id);
        $term_id = $request->query->get("term", $request->term_id);
        $section_id = $request->query->get("section", $request->section_id);
        $period_id = $request->query->get("period", $request->period_id);
        $type = $request->query->get("type", $request->type);
        $subject_id = $request->query->get("subject", $request->subject);
        $teacher_id = $request->query->get("teacher", $request->teacher);
        $hr = $request->query->get("hr", "0:00");
        //dd($hr);
        $date = Carbon::now(
            Configurations::getConfig("site")->time_zone
        )->toDateString();
        if ($hr != "0:00") {
            // dd($hr);
            [$start, $end] = explode("-", $hr);

            $startTimeString = Carbon::parse($start)->format("h:i a");

            $currentTimeString = Carbon::now(
                Configurations::getConfig("site")->time_zone
            )->format("h:i a");
            $startTime = Carbon::createFromFormat("g:i a", $startTimeString);
            $currentTime = Carbon::createFromFormat(
                "g:i a",
                $currentTimeString
            );
            // dd($startTime, $currentTime);
            if ($startTime->greaterThan($currentTime)) {
                return redirect()
                    ->back()
                    ->with(
                        "error",
                        "You Can't add the attendance to Future Period."
                    );
            }
        }
        if ($request->isMethod("post") && $type == "daily") {
            try {
                // already added attendance

                DB::beginTransaction();

                $already_added = AttendanceModel::where([
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "academic_year" => $academic_year,
                    "attendance_date" => $date,
                    "academic_term" => $term_id,
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
                    $attendance->academic_year = $request->acyear;
                    $attendance->class_id = $request->class_id;
                    $attendance->section_id = $request->section_id;
                    $attendance->academic_term = $request->term_id;
                    $attendance->period_id = $request->period_id;
                    $attendance->subject_id = $request->subject;
                    $attendance->teacher_id = $request->teacher;
                    $attendance->type = 2;
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
                    $attendance->attendance_taken_by = User::getuser()->id;
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

                DB::commit();
                return redirect()
                    ->route("attendance.index")
                    ->with("success", "Attendance Added Successfully");
            } catch (\Exception $e) {
                DB::rollback();
                //dd($e);
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
        }

        if ($type === "hourly") {
            if (
                $academic_year &&
                $class_id &&
                $section_id &&
                $period_id &&
                $subject_id &&
                $term_id
            ) {
                if (!$teacher_id) {
                    return redirect()
                        ->route("attendance.create", $type)

                        ->with(
                            "exception_error",
                            "Please Assigen Teacher to the Timetable"
                        );
                }
                $timetable = ClasstimetableModel::find($period_id);

                if ($timetable) {
                    // todo work

                    $day = Carbon::parse($date)->format("l");

                    $daymformat = Carbon::parse($date)->format("d F Y");
                    $year = Carbon::parse($date)->format("F");

                    $class_name = LclassModel::classname($class_id);
                    $section_name = SectionModel::sectionname($section_id);
                    $acyear = AcademicyearModel::academicyear($academic_year);
                    $term_info = ExamTermModel::find($term_id)->exam_term_name;

                    $students = StudentsModel::where([
                        "status" => 1,
                        "academic_year" => $academic_year,
                        "class_id" => $class_id,
                        "section_id" => $section_id,
                    ])->whereNull("deleted_at");

                    // get added attendnace info
                    $period_with_attendance = AttendanceModel::where([
                        "period_id" => $period_id,
                        "attendance_date" => $date,
                    ])->first();

                    //dd($period_with_attendance);

                    if ($period_with_attendance) {
                        $students = $students
                            ->with([
                                "attendance" => function ($q) use (
                                    $period_with_attendance
                                ) {
                                    $q->where([
                                        "attendance_id" =>
                                            $period_with_attendance->id,
                                    ]);
                                },
                            ])
                            ->get();
                    } else {
                        $students = $students->get();
                    }

                    //dd($students);
                    // if ($period_with_attendance) {
                    //     $attendances = StudentAttendanceModel::where(
                    //         "attendance_id",
                    //         $period_with_attendance->id
                    //     )
                    //         ->pluck("student_id", "attendance")
                    //         ->toArray();
                    // } else {
                    //     $attendances = [];
                    // }
                    //dd($students);
                    if ($request->isMethod("post")) {
                        // dd($year);
                        //dd($request->all());
                        $validator = Validator::make(
                            $request->all(),
                            [
                                "attendences" => "required|array",
                                "attendences" => function (
                                    $attribute,
                                    $value,
                                    $fail
                                ) {
                                    // Check if any of the attendances are null
                                    foreach ($value as $attendance) {
                                        if (is_null($attendance)) {
                                            return $fail(
                                                "Attendance is required for all students."
                                            );
                                        }
                                    }
                                },
                            ],
                            [
                                "attendences.required" =>
                                    "Attendance is required for all students.",
                            ]
                        );

                        if ($validator->fails()) {
                            return redirect()
                                ->back()
                                ->withErrors($validator)
                                ->withInput();
                        }

                        try {
                            // already added attendance

                            DB::beginTransaction();

                            $already_added = AttendanceModel::where([
                                "class_id" => $class_id,
                                "section_id" => $section_id,
                                "period_id" => $period_id,
                                "academic_term" => $term_id,
                                "academic_year" => $academic_year,
                                "attendance_date" => $date,
                                "type" => 1,
                            ])->first();

                            if ($already_added) {
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
                                        if (
                                            $device_user->device_token !== null
                                        ) {
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
                                    StudentAttendanceModel::updateOrCreate(
                                        [
                                            "attendance_id" =>
                                                $already_added->id,
                                            "student_id" => $student_id,
                                        ],
                                        [
                                            "attendance" => $attend,
                                        ]
                                    );
                                }
                            } else {
                                $attendance = new AttendanceModel();
                                $attendance->academic_year = $request->acyear;
                                $attendance->class_id = $request->class_id;
                                $attendance->section_id = $request->section_id;
                                $attendance->academic_term = $request->term_id;
                                $attendance->period_id = $request->period_id;
                                $attendance->subject_id = $request->subject;
                                $attendance->teacher_id = $request->teacher;
                                $attendance->type = 1;
                                $attendance->attendance_date = $date;
                                $attendance->attendance_month = Carbon::parse(
                                    $date
                                )->format("F");
                                $attendance->attendance_year = Carbon::parse(
                                    $date
                                )->format("Y");
                                $attendance->attendance_time = Carbon::now(
                                    Configurations::getConfig("site")->time_zone
                                )->toTimeString();
                                $attendance->attendance_taken_by = User::getuser()->id;
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

                                            $messaging = app(
                                                "firebase.messaging"
                                            );
                                            if (
                                                $device_user->device_token !==
                                                null
                                            ) {
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
                                                                "type" =>
                                                                    "global",

                                                                "student_name" =>
                                                                    $device_user->username,
                                                            ],
                                                            null
                                                        )
                                                    );
                                                }
                                            }
                                        }
                                        $s_attendance = new StudentAttendanceModel();
                                        $s_attendance->attendance_id =
                                            $attendance->id;
                                        $s_attendance->student_id = $student_id;
                                        $s_attendance->attendance = $attend;
                                        $s_attendance->save();
                                    }
                                }
                            }

                            DB::commit();
                            return redirect()
                                ->route("attendance.hourlyindex")
                                ->with(
                                    "success",
                                    "Attendance Added Successfully"
                                );
                        } catch (\Exception $e) {
                            DB::rollback();
                            //dd($e);
                            return redirect()
                                ->back()
                                ->withInput()
                                ->with("exception_error", $message);
                        }
                    } else {
                        return view("attendance::admin.parts.addattendance", [
                            "class_name" => $class_name,
                            "term_info" => $term_info,
                            "section_name" => $section_name,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                            "subject" => $subject_id,
                            "term_id" => $term_id,
                            "teacher" => $teacher_id,
                            "acyear" => $acyear,
                            "academicyear_id" => $academic_year,
                            "date" => $date,
                            "day" => $day,
                            "type" => $type,
                            "period_id" => $period_id,
                            "daymformat" => $daymformat,
                            "students" => $students,
                            "period_with_attendance" => $period_with_attendance
                                ? true
                                : false,
                            "hr" => $hr,
                        ]);
                    }
                } else {
                    return redirect()
                        ->route("attendance.create", $type)

                        ->with(
                            "exception_error",
                            "We can't find information about this period"
                        );
                }
            } else {
                return redirect()
                    ->route("attendance.create", $type)

                    ->with(
                        "exception_error",
                        "Whoops  !! Your parameter missing some data"
                    );
            }
        } else {
            return redirect()
                ->route("attendance.create", $type)

                ->with(
                    "exception_error",
                    "System can enter only hourly or daily attendance only"
                );
        }

        // dd("here");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getdailyattendance(Request $request)
    {
        if ($request->ajax()) {
            $student_id = $request->query->get("student_id", 0);
            $academic_year_id = DB::table("students")
                ->where("id", $student_id)
                ->pluck("academic_year")
                ->first();
            $current_academic_year_info = AcademicyearModel::find(
                $academic_year_id
            );
            $weekends = Configurations::getConfig("site")->week_end;

            $start_date_month = Carbon::parse(
                $current_academic_year_info->start_date
            )

                ->startOfMonth()
                ->copy()
                ->format("Y-m-d");

            $calender = $this->createAttendanceCalenderTwoDates(
                $start_date_month,
                $current_academic_year_info->end_date,
                true,
                $weekends,
                $student_id,
                $academic_year_id
            );
            // get particular students attendanceids to get type 2 (Daily) attendance data

            $attendance_ids = StudentAttendanceModel::where(
                "student_id",
                $student_id
            )
                ->pluck("attendance_id")
                ->toArray();

            $data = StudentsModel::find($student_id);
            // dd($calender);
            $view = view("attendance::admin.report.dailyattendance", [
                "data" => $data,
                "calender" => $calender,
                "current_academic_year_info" => $current_academic_year_info,
            ])->render();

            return response()->json([
                "viewfile" => $view,
                "calender" => $calender,
            ]);
        }
    }

    public function gethourlyattendance(Request $request)
    {
        $subject_id = $request->query->get("subject_id", 0);
        $student_id = $request->query->get("student_id", 0);
        $academic_year = $request->query->get("academic_year", 0);
        $monthstring = $request->query->get("month", "April 2023");
        $weekends = Configurations::getConfig("site")->week_end;

        $month = Carbon::createFromFormat("M Y", $monthstring)->timezone(
            Configurations::getConfig("site")->time_zone
        );

        $student_info = StudentsModel::find($student_id);

        // dd($academic_year, $student_info);

        $getperiod = PeriodModel::where([
            "academic_year" => $academic_year,
            "class_id" => $student_info->class_id,
            "section_id" => $student_info->section_id,
        ])->first();

        // dd($getperiod);
        if (!$getperiod) {
            return response()->json([
                "error" => "No Periods found .",
            ]);
        }
        //dd($month);
        $monthStart = $month->startOfMonth()->copy();
        $monthEnd = $month->endOfMonth()->copy();
        $start_date = $monthStart->format("Y-m-d");
        $end_date = $monthEnd->format("Y-m-d");

        $calender = $this->gethourlyAttendanceCalenderTwoDates(
            $start_date,
            $end_date,
            $subject_id,
            $weekends,
            $getperiod,
            $student_id
        );

        // dd($calender);

        $view = view("attendance::admin.report.hourlyattendance", [
            "month" => $monthstring,
            "start_date" => $start_date,
            "end_date" => $end_date,
            "calender" => $calender,
            "subject_id" => $subject_id,
        ])->render();
        // dd($calender);
        return response()->json([
            "viewfile" => $view,
        ]);

        //dd($calender);

        //dd($start_date);
    }
    public function show(Request $request, $id, $type)
    {
        // dd($id);

        // get particular students attendanceids to get type 2 (Daily) attendance data

        $attendance_ids = StudentAttendanceModel::where("student_id", $id)
            ->pluck("attendance_id")
            ->toArray();

        $data = StudentsModel::find($id);

        $attendance_type = $type;
        //dd($type);

        $subject_lists = SubjectModel::where("class_id", $data->class_id)
            ->pluck("name", "id")
            ->toArray();
        $academicyears = Configurations::getAcademicyears();
        $academic_year = AcademicyearModel::where(
            "id",
            $data->academic_year
        )->first();
        //dd($attendance_type);
        //dd($calender);

        return view("attendance::admin.show", [
            "data" => $data,
            "attendance_type" => $attendance_type,
            "subject_lists" => $subject_lists,
            "academicyears" => $academicyears,
            "academic_year" => $academic_year,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd($id);
        $data = AttendanceModel::find($id);
        // dd($id, $data);
        $academicyears = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();
        $info = Configurations::getAcademicandTermsInfo();
        // dd($info);
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "=", 1)
            ->orderBy("id", "asc")
            ->pluck("name as text", "id")
            ->toArray();

        $sections = DB::table("section")
            ->whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name as text", "id")
            ->toArray();
        $attendance_type = $data->type == 1 ? "hourly" : "daily";
        $date = Carbon::now()->format("Y-m-d");
        //  dd( $data, $academicyears,$class_lists,$sections);
        return view("attendance::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "academicyears" => $academicyears,
            "info" => $info,
            "class_lists" => $class_lists,
            "sections" => $sections,
            "attendance_type" => $attendance_type,
            "date" => $date,
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
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new AttendanceModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);

        try {
            $obj = AttendanceModel::find($id);
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
        return redirect()->route("attendance.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $attendance = null, Request $request)
    {
        if (!empty($request->selected_attendance)) {
            $delObj = new AttendanceModel();
            foreach ($request->selected_attendance as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new AttendanceModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("attendance.index");
    }
    public function deleteAttendance($id, $attendance = null, Request $request)
    {
        // dd($id, $attendance);

        if ($attendance != 0) {
            $delObj = StudentAttendanceModel::where([
                "student_id" => $id,
                "attendance_id" => $attendance,
            ])->forceDelete();
            Session::flash("success", "data Deleted Successfully!!");
        } else {
            Session::flash("error", "Attendance Not Taken!!");
        }

        return redirect()->route("attendance.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-attendance");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        $class_id = $request->query->get("class_id");
        $section_id = $request->query->get("section_id");
        $academic_year = $request->query->get("acyear");
        $type = $request->query->get("type");
        $attend_type = $type == "hourly" ? 1 : 2;
        $attendance_ids = AttendanceModel::where([
            "type" => $attend_type,
            "status" => 1,
        ])
            ->whereNull("deleted_at")
            ->pluck("id");
        // dd($attendance_ids);
        //return $type;
        // dd($request->all());
        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = StudentsModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "students.id",
            "students.first_name",
            "students.last_name",
            "students.image",
            "students.reg_no",
            "students.class_id",
            "students.section_id",
            "students.academic_year",
            "attendance_students.attendance_id",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new StudentsModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new StudentsModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->Join(
                "attendance_students",
                "students.id",
                "=",
                "attendance_students.student_id"
            )
            ->whereIn("attendance_students.attendance_id", $attendance_ids)
            ->where("students.status", "=", 1)
            ->whereNull("students.deleted_at")
            ->groupBy("students.id");

        if ($class_id && $section_id) {
            $data = $data->where([
                "class_id" => $class_id,
                "section_id" => $section_id,
                "academic_year" => $academic_year,
            ]);
        }
        // dump($data);
        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("attendance", function ($data) {
                // check today attendance
                $date = Carbon::now(
                    Configurations::getConfig("site")->time_zone
                )->toDateString();

                $attendance = AttendanceModel::where([
                    "academic_year" => $data->academic_year,
                    "class_id" => $data->class_id,
                    "section_id" => $data->section_id,
                    "type" => 2,
                    "attendance_date" => $date,
                ])->first();

                if ($attendance) {
                    $student = StudentAttendanceModel::where(
                        "attendance_id",
                        $attendance->id
                    )
                        ->where("student_id", $data->id)
                        ->first();
                    if ($student) {
                        if ($student->attendance == 0) {
                            return "<span class='atn_absent atn_width'>Absent</span>";
                        } elseif ($student->attendance == 1) {
                            return "<span class='atn_present atn_width'>Present</span>";
                        } else {
                            return "<span class='atn_late atn_width'>Late</span>";
                        }
                    } else {
                        return "<span class='not_taken atn_width'>Not Taken</span>";
                    }
                } else {
                    return "<span class='not_taken atn_width'>Not Taken</span>";
                }
            })
            ->addColumn("pimage", function ($data) {
                if ($data->image != null) {
                    $url = asset($data->image);
                    return '<img src="' .
                        $url .
                        '" border="0" width="40" class="img-rounded" align="center" />';
                } else {
                    $url = asset("assets/images/default.jpg");
                    return '<img src="' .
                        $url .
                        '" border="0" width="40" class="img-rounded" align="center" />';
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
            ->addColumn("action", function ($data) use ($type) {
                $date = Carbon::now(
                    Configurations::getConfig("site")->time_zone
                )->toDateString();
                $attendance = AttendanceModel::where([
                    "academic_year" => $data->academic_year,
                    "class_id" => $data->class_id,
                    "section_id" => $data->section_id,
                    "type" => 2,
                    "attendance_date" => $date,
                ])->first();
                if ($attendance) {
                    $id = $attendance->id;
                } else {
                    $id = 0;
                }

                return view("layout::datatable.action", [
                    "data" => $data,
                    "type" => $type,
                    "attendance_id" => $id,
                    "route" => "attendance",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["pimage", "action", "attendance"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-attendance");
        if ($request->ajax()) {
            AttendanceModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_attendance)) {
            $obj = new AttendanceModel();
            foreach ($request->selected_attendance as $k => $v) {
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
