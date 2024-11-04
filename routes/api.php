<?php

use App\Http\Controllers\Api\AcademicController;
use Illuminate\Http\Request;
use cms\core\user\Models\UserModel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Api\student\LibraryController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\UserController;
use App\Http\Resources\UserResource;
use cms\students\Models\StudentsModel;
use App\Http\Controllers\StudentPerformancesController;
use App\Http\Controllers\GmailComunicationAPIController;
use App\Http\Controllers\Api\student\ExamController;
use App\Http\Controllers\VirtualComunicationAPIController;
use App\Http\Controllers\Api\QuizApiController;
use App\Http\Controllers\Api\LeaveApiController;
use App\Http\Controllers\Api\SyllabusApiController;
use App\Http\Controllers\Api\PayRollApiController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get("/approles", [AuthController::class, "MobileappRoles"]);
Route::post("/login", [AuthController::class, "login"]);
Route::post("/forgotpassword", [AuthController::class, "ForgotPassword"]);
Route::post("/forgototpconfirm", [AuthController::class, "ForgotOtpConfirm"]);
Route::post("/changepassword", [AuthController::class, "ChangePassword"]);
Route::post("/resendotp", [AuthController::class, "ForgotPassword"]);

// authentication routes

Route::middleware("auth:sanctum")->group(function () {
    // student role
    Route::get("/studentlandingpage", [
        StudentController::class,
        "StudentLandingPage",
    ]);

    Route::get("/tuckshop/categories", [
        StudentController::class,
        "TuckShopCategories",
    ]);
    Route::get("/tuckshop/products", [StudentController::class, "Products"]);
    Route::post("/tuckshop/checkout", [StudentController::class, "Checkout"]);
    Route::get("/tuckshop/orders", [StudentController::class, "OrderItems"]);
    Route::post("/update_profile", [StudentController::class, "UpdateProfile"]);
    // library

    Route::get("/library/subscription/fees", [
        LibraryController::class,
        "GetSubscription",
    ]);

    Route::post("/library/subscription", [
        LibraryController::class,
        "LibrarySubscription",
    ]);

    Route::get("/library/category/{type?}", [
        LibraryController::class,
        "LibraryCategory",
    ]);

    Route::get("/library/books/{type?}", [
        LibraryController::class,
        "LibraryBooks",
    ]);

    //academics/syllabus/chapters

    Route::get("/academics/subjects", [
        AcademicController::class,
        "AcademicSubjects",
    ]);

    Route::get("/academics/subjects/chapters/{subject_id}", [
        AcademicController::class,
        "getChapterandTopics",
    ]);
    // event
    // user toek
    Route::post("/updatedevicetoken", [
        AuthController::class,
        "UpdateDeviceToken",
    ]);

    Route::get("getevents", [StudentController::class, "GetAllEvents"]);

    // hostel

    Route::get("gethostel", [StudentController::class, "GetHostelInformation"]);

    //get students
    // Route::get("getStudents", [StudentController::class, "studentsList"]);
    //Route::post("addStudent", [StudentController::class, "studentsList"]);
    //Route::get("viewStudent", [StudentController::class, "studentsList"]);

    // student attendnace

    Route::get("studentattendancehistory", [
        StudentController::class,
        "StudentAttendanceHistory",
    ]);

    Route::get("/user", [AuthController::class, "Getuser"]);

    Route::post("/logout", [AuthController::class, "logout"]);

    Route::post("/feepayment", [StudentController::class, "MakeFeePayment"]);

    Route::post("/conformfeepay", [StudentController::class, "ConformFeePay"]);

    Route::get("/academicyearlist", [
        StudentController::class,
        "AcademicYearList",
    ]);

    Route::get("/academictermlist/{id?}", [
        StudentController::class,
        "AcademicTermList",
    ]);

    Route::get("/exam/schedules/{exam_type}/{filter?}", [
        ExamController::class,
        "examschedules",
    ]);

    Route::get("/exam/quiz/questionanswer/{exam_id?}", [
        ExamController::class,
        "examquestionsanswer",
    ]);
    Route::post("/exam/quiz/storeanswer", [
        ExamController::class,
        "storequestionsanswer",
    ]);

    Route::post("/submit_exam_mark", [ExamController::class, "SubmitExamMark"]);
    Route::get("/view_assignment/{id?}", [
        ExamController::class,
        "HomeworkSubmitView",
    ]);

    Route::get("/exam_results/{exam_id?}", [
        ExamController::class,
        "ExamReport",
    ]);

    //studentperformances
    Route::post("/studentperformance", [
        StudentPerformancesController::class,
        "StudentPerformance",
    ]);

    Route::get("/studentperformance_list/{period?}", [
        StudentPerformancesController::class,
        "StudentPerformanceList",
    ]);

    Route::get("/current_academic_year", [
        StudentPerformancesController::class,
        "CurrentAcademicYear",
    ]);

    Route::get("/report_card", [
        StudentPerformancesController::class,
        "ReportCard",
    ]);

    Route::post("/studentperformance_store", [
        StudentPerformancesController::class,
        "Store",
    ]);
    //timetable
    Route::post("/timetable", [
        StudentPerformancesController::class,
        "ClassTimetable",
    ]);

    Route::get("/wallethistory", [
        StudentPerformancesController::class,
        "WalletHistory",
    ]);

    Route::get("/eligiblereceptiants", [
        GmailComunicationAPIController::class,
        "EligibleReceptiants",
    ]);

    Route::get("/inboxgmailmessages", [
        GmailComunicationAPIController::class,
        "InboxMessages",
    ]);

    Route::get("/sentgmailmessages", [
        GmailComunicationAPIController::class,
        "SentMessages",
    ]);

    Route::get("/starredgmailmessages", [
        GmailComunicationAPIController::class,
        "StarredMessages",
    ]);

    Route::get("/draftgmailmessages", [
        GmailComunicationAPIController::class,
        "DraftMessages",
    ]);

    Route::get("/bingmailmessages", [
        GmailComunicationAPIController::class,
        "BinMessages",
    ]);

    Route::post("/composegmailmessages", [
        GmailComunicationAPIController::class,
        "IndividualMessage",
    ]);

    Route::get("/gmailmessage", [
        GmailComunicationAPIController::class,
        "GmailMessage",
    ]);

    Route::post("/replaygmailmessages", [
        GmailComunicationAPIController::class,
        "ReplyMessage",
    ]);

    Route::post("/deletegmailmessages", [
        GmailComunicationAPIController::class,
        "DeleteMessages",
    ]);

    Route::post("/permenentdeletegmailmessages", [
        GmailComunicationAPIController::class,
        "DeleteMessages",
    ]);

    Route::post("/restoregmailmessages", [
        GmailComunicationAPIController::class,
        "RestoreMessages",
    ]);

    Route::get("/eligiblegmailgroupcreate", [
        GmailComunicationAPIController::class,
        "EligibleForGroupCreate",
    ]);

    Route::get("/gmailgroup", [
        GmailComunicationAPIController::class,
        "CreateGroupModel",
    ]);

    Route::post("/gmailgroupcreate", [
        GmailComunicationAPIController::class,
        "CreateGroup",
    ]);

    Route::post("/gmailgroupedit", [
        GmailComunicationAPIController::class,
        "EditGroupModel",
    ]);

    Route::post("/gmailgroupupdate", [
        GmailComunicationAPIController::class,
        "UpdateGroup",
    ]);

    Route::post("/gmailgroupdelete", [
        GmailComunicationAPIController::class,
        "DeleteGroup",
    ]);

    Route::get("/gmailgrouplist", [
        GmailComunicationAPIController::class,
        "GmailGroupList",
    ]);

    Route::post("/gmailgroupmessage", [
        GmailComunicationAPIController::class,
        "GroupMessage",
    ]);

    Route::post("/gmailgroupinfo", [
        GmailComunicationAPIController::class,
        "GroupInfo",
    ]);

    Route::post("/externalgmail", [
        GmailComunicationAPIController::class,
        "ExternalMessage",
    ]);

    Route::get("/externalsentmessages", [
        GmailComunicationAPIController::class,
        "ExternalSent",
    ]);

    Route::post("/draftgmail", [
        GmailComunicationAPIController::class,
        "Draft",
    ]);

    Route::post("/draftsendgmail", [
        GmailComunicationAPIController::class,
        "DraftSend",
    ]);

    Route::post("/starredgmail", [
        GmailComunicationAPIController::class,
        "Starred",
    ]);

    Route::post("/unstarredgmail", [
        GmailComunicationAPIController::class,
        "UnStarred",
    ]);

    Route::get("/virtualmeeting/{type?}", [
        VirtualComunicationAPIController::class,
        "VirtualMeeting",
    ]);

    Route::get("/createvirtualmeeting/{type?}", [
        VirtualComunicationAPIController::class,
        "Create",
    ]);

    Route::post("/storevirtualmeeting", [
        VirtualComunicationAPIController::class,
        "Store",
    ]);
    Route::post("/getsection", [
        VirtualComunicationAPIController::class,
        "Section",
    ]);

    Route::post("/joinvirtualmeeting", [
        VirtualComunicationAPIController::class,
        "Join",
    ]);

    Route::get("/AddParticipants/{meeting_type?}", [
        VirtualComunicationAPIController::class,
        "AddParticipantsModel",
    ]);

    Route::post("/StoreParticipants", [
        VirtualComunicationAPIController::class,
        "StoreParticipants",
    ]);

    Route::post("/getparticipants", [
        VirtualComunicationAPIController::class,
        "GetParticipants",
    ]);

    //teacherApi
    Route::get("/teacherlandingpage", [
        TeacherController::class,
        "TeacherLandingPage",
    ]);
    Route::get("/classattendance/{date?}", [
        TeacherController::class,
        "ClassAttendance",
    ]);
    Route::post("/dailyattendance", [
        TeacherController::class,
        "DailyAttendance",
    ]);
    Route::get("/subjectstudentlist", [
        TeacherController::class,
        "SubjectStudentList",
    ]);

    Route::get("/teachersubjectlist/{type?}", [
        TeacherController::class,
        "SubjectList",
    ]);

    Route::get("/teacherperiodslist", [TeacherController::class, "PeriodList"]);

    Route::post("/hourlyattendance", [
        TeacherController::class,
        "HourlyAttendance",
    ]);

    Route::get("/class_timetable_exist", [
        TeacherController::class,
        "ClassTimetableExist",
    ]);

    Route::get("/teachertimetablecreate", [
        TeacherController::class,
        "ClassTimeTableCreate",
    ]);

    Route::get("/periodsexist", [TeacherController::class, "PeriodsExist"]);

    Route::get("/periodcatogories", [
        TeacherController::class,
        "PeriodCatogories",
    ]);

    Route::post("/createclassperiods", [
        TeacherController::class,
        "CreatePeriods",
    ]);

    Route::get("/classsubjects", [TeacherController::class, "ClassSubjects"]);

    Route::get("/subjectteachers", [
        TeacherController::class,
        "SubjectTeachers",
    ]);

    Route::post("/assign_subject_teacher", [
        TeacherController::class,
        "AssignSubjectTeacher",
    ]);

    Route::post("/storetimetable", [
        TeacherController::class,
        "StoreTimetable",
    ]);

    Route::get("/teacher_view_class_timetable", [
        TeacherController::class,
        "ViewClassTimetable",
    ]);

    Route::get("/perioddelete", [TeacherController::class, "PeriodDelete"]);

    Route::get("/teachereditprofile", [
        TeacherController::class,
        "EditProfile",
    ]);
    Route::get("/teacherinfo", [TeacherController::class, "MyInfo"]);

    Route::get("/createquiz/{type?}", [QuizApiController::class, "QuizCreate"]);

    Route::post("/storequiz/{type?}", [QuizApiController::class, "Store"]);

    Route::get("/quizlist", [QuizApiController::class, "QuizList"]);

    Route::get("/quizedit/{id?}", [QuizApiController::class, "QuizEdit"]);

    Route::post("/quizupdate/{id?}/{type?}", [
        QuizApiController::class,
        "QuizUpdate",
    ]);

    Route::post("/examsubmit", [QuizApiController::class, "ExamSubmit"]);
    //leave api
    Route::get("/leave/{layout?}/{id?}", [LeaveApiController::class, "Create"]);

    Route::post("/leave_store", [LeaveApiController::class, "Store"]);

    Route::post("/leave_update/{id?}", [LeaveApiController::class, "Update"]);

    Route::get("/view_leave/{id?}", [LeaveApiController::class, "show"]);

    Route::get("/leave_list/{type?}", [LeaveApiController::class, "LeaveList"]);

    Route::get("/leave_delete/{id?}", [LeaveApiController::class, "destroy"]);
    Route::get("/leave_action/{id?}/{application_status?}", [
        LeaveApiController::class,
        "LeaveAction",
    ]);

    Route::get("/syllabus/{layout?}/{id?}", [
        SyllabusApiController::class,
        "Create",
    ]);
    Route::post("/syllabus_store", [SyllabusApiController::class, "Store"]);

    Route::get("/chapter_list", [SyllabusApiController::class, "ChapterList"]);

    Route::post("/syllabus_update/{id?}", [
        SyllabusApiController::class,
        "Update",
    ]);

    Route::get("/syllabus_view/{id?}", [
        SyllabusApiController::class,
        "ChapterView",
    ]);

    Route::get("/create_topic/{chapter_id?}", [
        SyllabusApiController::class,
        "CreateTopic",
    ]);

    Route::post("/topic_store", [SyllabusApiController::class, "TopicStore"]);

    Route::get("/edit_topic/{topic_id?}", [
        SyllabusApiController::class,
        "TopicEdit",
    ]);

    Route::post("/topic_update/{id?}", [
        SyllabusApiController::class,
        "TopicUpdate",
    ]);

    Route::get("/topic_view/{id?}", [
        SyllabusApiController::class,
        "TopicView",
    ]);

    Route::get("/topic_delete/{id?}", [
        SyllabusApiController::class,
        "TopicDelete",
    ]);

    Route::get("/content_delete/{id?}", [
        SyllabusApiController::class,
        "ContentDelete",
    ]);

    Route::get("/chapter_delete/{id?}", [
        SyllabusApiController::class,
        "ChapterDelete",
    ]);

    Route::get("/paymenthistory", [
        PayRollApiController::class,
        "PaymentHistory",
    ]);
    Route::get("/viewpayslip/{id?}", [
        PayRollApiController::class,
        "ViewPayslip",
    ]);
});

Route::get("/resource", function () {
    return UserResource::collection(StudentsModel::paginate(3));
});
