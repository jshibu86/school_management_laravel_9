<?php
namespace cms\core\configurations\helpers;

//helpers
use Cms;
use Auth;
use User;
use Schema;
use Session;
use Illuminate\Http\Request;
//models
use cms\wallet\Models\WalletModel;
use cms\core\user\Models\UserModel;
use Illuminate\Support\Arr;
//others
use cms\students\Models\ParentModel;
use cms\teacher\Models\TeacherModel;
use cms\students\Models\StudentsModel;
use cms\core\module\Models\ModuleModel;
use Illuminate\Support\Facades\Notification;
use cms\subject\Models\SubjectTeacherMapping;
use cms\academicyear\Models\AcademicyearModel;
use cms\classtimetable\Models\ClasstimetableModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\user\Notifications\ActivityNotification;
use cms\core\configurations\Models\ConfigurationModel;
use cms\exam\Models\ExamTermModel;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use cms\classteacher\Models\ClassteacherModel;
use cms\mark\Models\GradeModel;
use cms\mark\Models\MarkDistributionModel;
use cms\payrool\Models\SaleryParticularsModel;
use cms\fees\Models\SchoolTypeModel;
use cms\report\Models\CertificateConfigurationsModel;
use cms\cmsmenu\Models\CmsmenuModel;

class Configurations
{
    function __construct()
    {
    }

    const EXCLUDEMODULES = [
        "candidate",
        "schoolmanagement",
        "subscription",
        "Demo",
    ];

    const DEFAULTMODULES = [
        "admin",
        "configurations",
        "layout",
        "menu",
        "user",
        "usergroup",
        "plugins",
        "academicyear",
        "gate",
        "module",
    ];

    // use for tenants migration and module  \cms\core\schoolmanagement for local /cms/core/schoolmanagement for live
    const EXCLUDEMODULESBASEBATH = [
        "\cms\core\candidate",
        "\cms\core\schoolmanagement",
        "/cms/core/schoolmanagement",
        "\cms\core\subscription",
        "/cms/core/subscription",
        "\cms\core\Demo",
        "/cms/core/Demo",
    ];

    /*
     * get module configuration parm
     * type=1 is core,type =2 is local
     */
    // ignorebranch comment added
    const DESIGNATIONTYPES = [
        "Elementary school teacher" => "Elementary school teacher",
        "Middle school teacher" => "Middle school teacher",
        "High school teacher" => "High school teacher",
        "Special education teacher" => "Special education teacher",
        "Training Teacher" => "Training Teacher",
    ];

    const REPORTTYPE = [
        "daily" => "Daily",
        "monthly" => "Monthly",
        "weekly" => "Weekly",
        "yearly" => "Yearly",
    ];

    const PERIODTYPE = [
        "monthly" => "Monthly",
        "weekly" => "Weekly",
    ];

    const SUBSCRIPTIONPLANTYPES = [
        "1" => "Term",
        "2" => "Session",
    ];

    const STUDENTPERFORMANCES = [
        "disciple_compliance" => "Disipline And Compilence",
        "sport_event" => "Sport and Event",
    ];
    const STUDENTPERFORMANCESREPORT = [
        "1" => "Automatic",
        "2" => "Manual",
    ];
    const PAYMENTYPE = [
        "1" => "Wallet",
        "2" => "Payment Gateway",
    ];

    const DELIVRY = [
        "1" => "Completed",
        "2" => "Pending",
    ];
    const PAYMENTSTATUS = [
        "1" => "Completed",
        "2" => "Pending",
    ];

    const PROMOTIONALEXAMSTATUS = [
        ["id" => "Yes", "text" => "Yes"],
        ["id" => "No", "text" => "No"],
    ];

    const ONLINEEXAMQUESTIONTYPE = [
        ["id" => "choosebest", "text" => "Choose The Best Answer"],
        ["id" => "fillintheblanks", "text" => "Fill In The Blanks"],
        ["id" => "yesorno", "text" => "Yes/No Questions"],
    ];

    const HOMEWORKEXAMQUESTIONTYPE = [
        ["id" => "homework", "text" => "Assign Question"],
    ];

    const MEETINGTYPES = [
        ["id" => 0, "text" => "Class Meeting"],
        ["id" => 1, "text" => "PTA Meeting"],
    ];

    const PTAMEETINGGROUPS = [
        "2" => "Teacher",
        "3" => "Parent",
    ];
    const PTAMEETINGGROUPSARRAY = [
        ["id" => 2, "text" => "Teacher"],
        ["id" => 3, "text" => "Parent"],
    ];

    const GENDER = [
        "male" => "Male",
        "female" => "Female",
        "other" => "Other",
    ];

    const RELIGION = [
        "hinduism" => "Hinduism",
        "islam" => "Islam",
        "christianity" => "Christianity",
        "buddhism" => "Buddhism",
    ];
    const BLOODGROUPS = [
        "A+" => "A+",
        "A-" => "A-",
        "B+" => "B+",
        "B-" => "B-",
        "O+" => "O+",
        "O-" => "O-",
        "AB+" => "AB+",
    ];

    const MOBILEAPPROLES = ["Student", "Teacher", "Super Admin", "Driver"];

    const VEHICLETYPE = [
        "petrol" => "Petrol",
        "diecel" => "Diesel",
    ];

    const MARITIALSTATUS = [
        "single" => "Single",
        "married" => "Married",
        "divorced" => "Divorced",
    ];

    const SUPER_ADMIN = 1;
    const TEACHER = 3;
    const STUDENT = 4;

    const WALLETPAYMENT = 1;
    const FLUTERWAVE = 2;

    const SHOPPRODUCT = 1;

    const SUBJECT_TYPES = ["theory" => "Theory", "practical" => "Practical"];

    const STUDENTTYPES = [
        "hostel" => "Hostel",
        "dayscholar" => "Day",
    ];

    const TRANSPORTZONE = [
        "lagos" => "Lagos, Lagos.",
        "kano" => "Kano, Kano.",
        "benincity" => "Benin City, Edo",
    ];

    const CERTIFICATETYPES = [
        "birth_certificate" => "birth_certificate",
        "tranfer_certificate" => "tranfer_certificate",
        "mark_sheet" => "mark_sheet",
        "national_id_certificate" => "national_id_certificate",
    ];

    const ROLES = [
        "student" => "Student",
        "staff" => "Staff",
    ];

    const STATUS = [
        "2" => "Pending",
        "-1" => "Rejected",
        "1" => "Approved",
    ];

    const CHAPTERTOPICTYPE = [
        ["id" => "image", "text" => "Image"],
        ["id" => "video", "text" => "Video"],
        ["id" => "document", "text" => "Document"],
    ];

    const WALLETTYPE = [
        "direct" => "Direct Payment",
        "challan" => "E-Payment",
    ];
    const ORDERSTATUS = [
        "1" => "Processing",
        "3" => "Complete Order",
        "-1" => "Cancel",
    ];

    const TIMEZONES = [
        "default" => "Africa/Lagos",
        "Africa/Lagos" => "Africa/Lagos",
        "Asia/Kolkata" => "Asia/Kolkata",
    ];

    const TIMEFORMAT = "d-m-Y h:i";

    const COLORS = ["info", "wall", "rose", "danger", "primary", "success"];
    const ADMIN_NAME = "SUPER_ADMIN_MANAGEMENT_SYSTEM";
    const LOGO_PATH = "https://schoolmanagegit.webbazaardevelopment.com/school/profiles/1748204780466456.png";
    const WELCOME_EMAIL_TITLE = "Welcome Message - Online School Management System";
    const WELCOME_EMAIL = " Congratulations on your subscription to School Management Online system ! 
                             We are so glad to have you on board.
                             
                             Log in to your account and explore the various features. 
                             
                             We have also included helpful tutorials in our knowledge base to guide you.

                             If you need personalized assistance, our support team is just a message away.";
    const WELCOME_EMAIL_ONBOARD = "Onboarded Sucessfully - Online School Management System";
    const WELCOME_ONBOARD = "  Welcome to Online School Management System. Super happy to see you on board!

                                We are sure that this product will help you overcome difficult in manage schools. thanks to providing [key benefits of product/service].

                                Get to know more about [product/service] by watching [title] video.

                                You will be guided through our expert in order to ensure that you get the very best out of our service.

                                More insightful resources like our guides or video tutorials are at your disposal.

                                Take care!";

    const WEEKENDS = [
        "1" => "Monday",
        "2" => "Tuesday",
        "3" => "Wednesday",
        "4" => "Thursday",
        "5" => "Friday",
        "6" => "Saturday",
        "7" => "Sunday",
    ];

    const BREAK = [
        0 => "Teaching",
        1 => "Lunch Break",
        2 => "Break",
    ];

    const WEEKDAYS = [
        "1" => "Monday",
        "2" => "Tuesday",
        "3" => "Wednesday",
        "4" => "Thursday",
        "5" => "Friday",
        "6" => "Saturday",
        "7" => "Sunday",
    ];
    const WEEKLYDAYS = [
        "Monday" => "1",
        "Tuesday" => "2",
        "Wednesday" => "3",
        "Thursday" => "4",
        "Friday" => "5",
        "Saturday" => "6",
        "Sunday" => "7",
    ];

    const WEEK_DAYS = [
        "0" => "Monday",
        "1" => "Tuesday",
        "2" => "Wednesday",
        "3" => "Thursday",
        "4" => "Friday",
        "5" => "Saturday",
        "6" => "Sunday",
    ];

    const RCOLORS = [
        "#3FC1B2" => "#F2FFF7",
        "#5585FF" => "#F2F6FF",
        "#FF55CF" => "#FFF1FB",
        "#FF8855" => "#FFF5F1",
        "#55D6FF" => "#F2F6FF",
        "#FFE142" => "#FFFCF8",
        "#5F6379" => "#dfe0e4",
    ];

    const SCHOOLTYPES = [
        1 => "Secondary",
        2 => "Primary",
        3 => "Kindergarten",
    ];

    const ATNTYPES = [
        0 => "Absent",
        1 => "Present",
        2 => "Late",
    ];
    const ATTENDANCETYPES = [
        1 => "Hourly Attendance",
        2 => "Daily Attendance",
    ];

    const FEEPAYMENTTYPES = [
        0 => "Monthly",
        1 => "TermWise",
        2 => "One Payment",
    ];

    const IDCARDFEILDS = [
        1 => "Date of Birth",
        2 => "Blood group",
        3 => "Phone Number",
        4 => "Class",
        5 => "Roll Number",
    ];
    const FEESPLIT = [
        0 => "30",
        1 => "30",
        2 => "40",
    ];

    const FEEPAYMENTMETHOD = [
        0 => "Cash",
        1 => "Demand Draft",
        2 => "Online",
        3 => "Wallet",
    ];

    const FEETYPES = [
        1 => "School Fees",
        2 => "Manual Income",
        3 => "Tuck Shop",
        4 => "Admission Fees",
        5 => "Library Fees",
    ];

    const EXPENCEFEETYPES = [
        1 => "Payroll",
        2 => "Manual Expense",
    ];

    const WALLETPAYTYPES = [
        1 => "Payment for stock shop",
    ];
    const PERIODCATEGORIES = [
        "examination" => "Examination",
        "lunch_break" => "Lunch break",
        "break" => "Break",
    ];

    const CLASSPERIODCATEGORIES = [
        "0" => "Teaching",
        "1" => "Lunch break",
        "2" => "Break",
    ];

    const IDCARDTEMPLATES = [
        1 => "Front Page",
        2 => "Back Page",
    ];
    const ALLOWEDGROUPS = [
        1 => "Super Admin",
        2 => "Prinicipal",
        3 => "Teacher",
    ];

    const TERMNAMES = [
        1 => "First Term",
        2 => "Second Term",
        3 => "Third Term",
    ];

    public static function getRecomandationText()
    {
        $recomandation_text = [
            [
                "id" => "poor",
                "text" =>
                    self::getConfig("site")->poor_recomendation_text ?? null,
            ],
            [
                "id" => "avarage",
                "text" =>
                    self::getConfig("site")->avarage_recomendation_text ?? null,
            ],
            [
                "id" => "good",
                "text" =>
                    self::getConfig("site")->good_recomendation_text ?? null,
            ],
            [
                "id" => "execellent",
                "text" =>
                    self::getConfig("site")->execellent_recomendation_text ??
                    null,
            ],
        ];
        return $recomandation_text;
    }

    public static function getCoreModuleMigrationPath($basepath = true)
    {
        $cms = Cms::allModulesPath(false);

        $CorePaths = [];
        foreach ($cms as $module) {
            if (!in_array($module, self::EXCLUDEMODULESBASEBATH)) {
                if (
                    \File::exists(
                        base_path() .
                            $module .
                            DIRECTORY_SEPARATOR .
                            "Database" .
                            DIRECTORY_SEPARATOR .
                            "Migration" .
                            DIRECTORY_SEPARATOR
                    )
                ) {
                    $CorePaths[] =
                        base_path() .
                        $module .
                        DIRECTORY_SEPARATOR .
                        "Database" .
                        DIRECTORY_SEPARATOR .
                        "Migration";
                }
            }
        }
        return $CorePaths;
    }

    public static function GetActiveGroupwithInfo($id)
    {
        $data = UserModel::with("group")->find($id);
        if ($data->group) {
            if ($data->group[0]->group == "Student") {
                $student = StudentsModel::where("user_id", $id)->first();
                return [$data->group[0]->group, $student];
            } elseif ($data->group[0]->group == "Teacher") {
                $teacher = TeacherModel::where("user_id", $id)->first();
                return [$data->group[0]->group, $teacher];
            } else {
                return [$data->group[0]->group, []];
            }
        } else {
            return null;
        }
    }

    public static function GetActiveTeacherClass($id)
    {
        $class_teach = ClassteacherModel::where("teacher_id", $id)->first();

        return [$class_teach->class_id, $class_teach->section_id];
    }

    public static function CurrencyFormat($number)
    {
        return "₦" . number_format($number);
    }

    public static function GetSchoolType($type)
    {
        return Arr::get(Configurations::SCHOOLTYPES, $value);
    }

    public static function GetStudent($id)
    {
        return StudentsModel::find($id)->first_name;
    }
    public static function GetGmailRoleTypes(Request $request)
    {
        $user_id = $request->user()->id;
        $group = UserGroupMapModel::where("user_id", $user_id)
            ->pluck("group_id")
            ->first();
        $parm = ConfigurationModel::where("name", "site")
            ->select("parm")
            ->first();
        $parm_decode = json_decode($parm->parm);
        //  dd($parm_decode);
        if (isset($parm_decode->gmail_role_configurations)) {
            //    dd($parm_decode->gmail_role_configurations);
            $group_types = collect($parm_decode->gmail_role_configurations)
                ->where("role_id", $group)
                ->pluck("receptiants");

            //dd($group_types);
            if (in_array("all", $group_types[0])) {
                $user_group = UserGroupModel::select("*")->get();
                //  dd($user_group);
            } else {
                // dd($group_types);
                $flattened_group_types = array_merge(...$group_types);
                $user_group = UserGroupModel::whereIn(
                    "id",
                    $flattened_group_types
                )
                    ->where("status", "=", "1")
                    ->get();
            }
            $receptiants_group = $user_group;
            return $receptiants_group;
        } else {
            Session::flash(
                "danger",
                "Gmail Role Configurations was not created.Before create a group you should create a gmail role configurations."
            );
            return redirect()->view("configurations::admin.site");
        }
    }
    public static function GetGmailGroupEligibleRoles()
    {
        if (Schema::hasTable("configurations")) {
            $data = ConfigurationModel::where("name", "=", "site")->first();

            if (count((array) $data) > 0 && isset($data->parm)) {
                $data = json_decode($data->parm);

                if (isset($data->eligible_role_types)) {
                    return $data->eligible_role_types;
                }
            }
        }
    }
    public static function GetMonthsOfAcademicYear(
        $id,
        $reg_date = null,
        $month_only = false
    ) {
        $acyear = AcademicyearModel::find($id);

        $startDate = $reg_date
            ? $reg_date
            : Carbon::parse($acyear->start_date)->format("Y-m-d");
        $endDate = Carbon::parse($acyear->end_date)->format("Y-m-d");

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $period = Carbon::parse($startDate)->diffInMonths($endDate);

        //return $period;

        $yearsMonths = [];
        $months_half = [];
        $Month_full = [];
        for ($i = 0; $i <= $period; $i++) {
            $year = $start->year;
            $month = $start->format("F");
            $month_half_name = $start->format("M");

            $yearsMonths[] = ["year" => $year, "month" => $month];
            $months_half[] = $month_half_name;
            $months_full[] = $month;

            $start->addMonth();
        }

        if ($reg_date) {
        }
        if ($month_only) {
            return [$months_half, $months_full, $yearsMonths];
        }
        return $yearsMonths;
    }
    public static function getCalenderData($period_id, $data, $dayid)
    {
        $newdata = ClasstimetableModel::with("subject", "staff")
            ->where([
                "academic_year" => $data->academic_year,
                "class_id" => $data->class_id,
                "section_id" => $data->section_id,
                "period_id" => $period_id,
                "day" => $dayid,
            ])
            ->first();

        return $newdata;
    }

    public static function getParm($module, $type = 2)
    {
        $parm = ModuleModel::select("configuration_parm")
            ->where("name", "=", $module)
            ->where("type", "=", $type)
            ->first();
        if ($parm) {
            $parm = json_decode($parm->configuration_parm);
        }

        return $parm;
    }
    public static function getConfig($name)
    {
        $parm = ConfigurationModel::where("name", $name)
            ->select("parm")
            ->first();

        if ($parm) {
            $parm = json_decode($parm->parm);
        }

        return $parm;
    }

    public static function getAllConfig()
    {
        $parm = ConfigurationModel::pluck("parm", "name");

        foreach ($parm as $key => $value) {
            $parm[$key] = json_decode($value);
        }

        return $parm;
    }

    public static function getCurrentTheme()
    {
        if (Schema::hasTable("configurations")) {
            $data = ConfigurationModel::where("name", "=", "site")->first();

            if (count((array) $data) > 0 && isset($data->parm)) {
                $data = json_decode($data->parm);

                if (isset($data->active_theme)) {
                    return $data->active_theme;
                }
            }
        }

        return Cms::getThemeConfig()["active"];
    }

    public static function getCurentAcademicTerms()
    {
        $current_academic = Configurations::getCurrentAcademicyear();

        if ($current_academic) {
            $academic_terms = ExamTermModel::where(
                "academic_year",
                $current_academic
            )
                ->pluck("exam_term_name", "id")
                ->toArray();
        } else {
            $academic_terms = [];
        }

        return $academic_terms;
    }

    public static function getSchooltypes()
    {
        $types = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();

        return $types;
    }

    public static function getAcademicyears()
    {
        $years = AcademicyearModel::WhereNull("deleted_at")
            ->where("status", 1)
            ->pluck("year", "id")
            ->toArray();

        return $years;
    }

    public static function getCurrentAcademicyear()
    {
        if (Schema::hasTable("configurations")) {
            $data = ConfigurationModel::where("name", "=", "site")->first();

            if (count((array) $data) > 0 && isset($data->parm)) {
                $data = json_decode($data->parm);

                if (isset($data->academic_year)) {
                    return $data->academic_year;
                }
            }
        }
    }
    public static function getCurrentFeePaymentType()
    {
        if (Schema::hasTable("configurations")) {
            $data = ConfigurationModel::where("name", "=", "site")->first();

            if (count((array) $data) > 0 && isset($data->parm)) {
                $data = json_decode($data->parm);

                if (isset($data->payment_type)) {
                    return $data->payment_type;
                }
            }
        }
    }
    public static function getstudentPerformanceType()
    {
        if (Schema::hasTable("configurations")) {
            $data = ConfigurationModel::where("name", "=", "site")->first();

            if (count((array) $data) > 0 && isset($data->parm)) {
                $data = json_decode($data->parm);

                $performence = [];

                if (isset($data->discipline_and_compliance)) {
                    $performence[] = "Discipline and Compliance";
                }

                if (isset($data->sports_and_event)) {
                    $performence[] = "Sports and Event";
                }

                return $performence;
            }
        }
    }
    public static function getAcademicandTermsInfo()
    {
        $academicyears = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $examterms = Configurations::getCurentAcademicTerms();

        return [
            "academicyears" => $academicyears,
            "current_academic_year" => $current_academic_year,
            "current_academic_term" => $current_academic_term,
            "examterms" => $examterms,
        ];
    }

    public static function getCurrentAcademicterm()
    {
        if (Schema::hasTable("configurations")) {
            $data = ConfigurationModel::where("name", "=", "site")->first();

            if (count((array) $data) > 0 && isset($data->parm)) {
                $data = json_decode($data->parm);

                if (isset($data->academic_term)) {
                    return $data->academic_term;
                }
            }
        }
    }

    public static function getMarkdistribution()
    {
        if (Schema::hasTable("configurations")) {
            $data = ConfigurationModel::where("name", "=", "mark")->first();

            if (count((array) $data) > 0 && isset($data->parm)) {
                $data = json_decode($data->parm);

                if (isset($data->mark_distribution)) {
                    return $data->mark_distribution;
                }
            }
        }
    }

    public static function getGradeInfo()
    {
        if (Schema::hasTable("configurations")) {
            $data = ConfigurationModel::where("name", "=", "mark")->first();

            if (count((array) $data) > 0 && isset($data->parm)) {
                $data = json_decode($data->parm);

                if (isset($data->grade_system)) {
                    return GradeModel::where(
                        "grade_sys_name_id",
                        $data->grade_system
                    )->get();
                    return $data->mark_distribution;
                }
            }
        }
    }
    public static function Generatepassword($length)
    {
        //password generate
        $random_string = '0123456789ABCDEFGHI$#@!%^JKXYZabcdefghijklstuvwxyz';

        $password__string = substr(str_shuffle($random_string), 0, $length);

        $password = "SM" . date("Y") . $password__string;
        //password encrypt
        //$hash_password = Hash::make($password);
        return $password;
    }

    public static function GenerateUsername($data, $textstring)
    {
        if ($data) {
            $code = substr($data, strpos($data, "-") + 1);
            if ($textstring == "LB") {
                $newcode_string = str_pad(++$code, 5, "0", STR_PAD_LEFT);
            } else {
                $newcode_string = str_pad(++$code, 3, "0", STR_PAD_LEFT);
            }
            $newcode = "SM" . $textstring . date("Y") . "-" . $newcode_string;
            // dd("usr", $textstring, $newcode_string, $newcode);
            return $newcode;
        } else {
            if ($textstring == "LB") {
                $emp_code = "SM" . $textstring . date("Y") . "-00001";
            } else {
                $emp_code = "SM" . $textstring . date("Y") . "-001";
            }
            // dd("us", $emp_code);
            return $emp_code;
        }
    }

    public static function GenerateBillnumber($data, $textstring)
    {
        if ($data) {
            $code = substr($data, strpos($data, "-") + 1);
            if ($textstring == "LB") {
                $newcode_string = str_pad(++$code, 5, "0", STR_PAD_LEFT);
            } else {
                $newcode_string = str_pad(++$code, 3, "0", STR_PAD_LEFT);
            }
            $newcode = "SM" . $textstring . date("Y") . "-" . $newcode_string;

            return $newcode;
        } else {
            if ($textstring == "LB") {
                $emp_code = "SM" . $textstring . date("Y") . "-00001";
            } else {
                $emp_code = "SM" . $textstring . date("Y") . "-001";
            }

            return $emp_code;
        }
    }

    // public static function GenerateBillnumber($data, $textstring)
    // {
    //     if ($data) {
    //         $random_string = "0123456789";

    //         $Bill__string = substr(str_shuffle($random_string), 0, $length);

    //         $Bill = "SM" . date("Y") . $Bill__string;

    //         $code = substr($data, strpos($data, "-") + 1);

    //         if ($textstring == "LB") {
    //             $newcode_string = str_pad(++$code, 5, "0", STR_PAD_LEFT);
    //         } else {
    //             $newcode_string = str_pad(++$code, 3, "0", STR_PAD_LEFT);
    //         }
    //         $newcode =
    //             "SM" .
    //             $textstring .
    //             date("Y") .
    //             "-" .
    //             $newcode_string .
    //             "-" .
    //             $Bill;

    //         return $newcode;
    //     } else {
    //         if ($textstring == "LB") {
    //             $emp_code = "SM" . $textstring . date("Y") . "-00001";
    //         } else {
    //             $emp_code = "SM" . $textstring . date("Y") . "-001";
    //         }

    //         return $emp_code;
    //     }
    // }

    public static function GettingAdminUsers()
    {
        try {
            $super_admin_users = UserGroupMapModel::where(
                "group_id",
                Configurations::SUPER_ADMIN
            )
                ->pluck("user_id")
                ->toArray();
            $super_admins = UserModel::where("status", 1)
                ->whereIn("id", $super_admin_users)
                ->select("id")
                ->get();
            //dd($super_admins);
        } catch (\Exception $e) {
            $super_admins = [];
        }

        return $super_admins;

        //dd($super_admins);
    }
    public static function GettingStudentUsers($class_id)
    {
        try {
            $student_table = StudentsModel::where("class_id", $class_id)
                ->pluck("user_id")
                ->toArray();
            $students = UserModel::where("status", 1)
                ->whereIn("id", $student_table)
                ->select("id")
                ->get();
        } catch (\Exception $e) {
            $students = [];
        }

        return $students;

        //dd($super_admins);
    }

    public static function sendNotification(
        $type,
        $msg,
        $created_by,
        $class_id = null
    ) {
        if ($type == "homework") {
            $users = Configurations::GettingStudentUsers($class_id);
        } else {
            $users = Configurations::GettingAdminUsers();
        }

        //dd($users);

        Notification::send(
            $users,
            new ActivityNotification($type, $msg, $created_by)
        );

        return true;
    }

    public static function getVedioid($file)
    {
        preg_match(
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            $file,
            $match
        );
        $video_id = $match[1];

        return $video_id;
    }
    public static function getbottomtopcolor()
    {
        $bottom_top_color = CertificateConfigurationsModel::where(
            "status",
            "!=",
            -1
        )->value("bottom_top_color");

        return $bottom_top_color;
    }
    public static function getbottomcentercolor()
    {
        $bottom_center_color = CertificateConfigurationsModel::where(
            "status",
            "!=",
            -1
        )->value("bottom_center_color");

        return $bottom_center_color;
    }

    public static function getTeacherId($id)
    {
        $id_ = TeacherModel::where("user_id", $id)->first();

        return $id_->id;
    }
    public static function getTeacherSubjects($id)
    {
        return SubjectTeacherMapping::where("teacher_id", $id)
            ->pluck("subject_id")
            ->toArray();
    }

    public static function Activestudent()
    {
        $active_user = User::getuser()->id;

        return StudentsModel::where("user_id", $active_user)->first();
    }
    public static function Activeparent()
    {
        $active_user = User::getuser()->id;

        return ParentModel::where("user_id", $active_user)->first();
    }
    public static function Activeteacher()
    {
        $active_user = User::getuser()->id;

        return TeacherModel::where("user_id", $active_user)->first();
    }

    public static function carttotal($items)
    {
        $count = 0;

        foreach ($items as $item) {
            $count = $count + $item->options->total;
        }

        return $count;
    }

    public static function ActiveStudentWallet()
    {
        $active_student = Configurations::Activestudent();

        $wallet = WalletModel::where(
            "parent_id",
            $active_student->parent_id
        )->first();

        return $wallet ? $wallet->wallet_amount : 0;
    }

    public static function FindquestionAttachment($exam_id, $sec_order)
    {
        return $exam_id;
    }

    public static function getcurrentDateTime()
    {
        $date = Carbon::now(
            stripslashes(Configurations::getConfig("site")->time_zone)
        )->toDateString();
        $time = Carbon::now(
            stripslashes(Configurations::getConfig("site")->time_zone)
        )->toTimeString();

        $currentMonth = Carbon::now(
            stripslashes(Configurations::getConfig("site")->time_zone)
        )->format("F"); // Full month name, e.g., August
        $currentYear = Carbon::now(
            stripslashes(Configurations::getConfig("site")->time_zone)
        )->format("Y"); // 4-digit year, e.g., 2023

        $current_day = Carbon::now(
            stripslashes(Configurations::getConfig("site")->time_zone)
        )->format("l"); // 4-digit year, e.g., 2023

        $dayOfWeek = Carbon::now(
            stripslashes(Configurations::getConfig("site")->time_zone)
        )->dayOfWeek; // 4-digit year, e.g., 2023

        return [
            $date,
            $time,
            $currentMonth,
            $currentYear,
            $current_day,
            $dayOfWeek,
        ];
    }

    public static function getParticular($id)
    {
        return SaleryParticularsModel::find($id)->particular_name;
    }

    public static function convertRupeesToWords($number)
    {
        $ones = [
            0 => "Zero",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen",
        ];

        $tens = [
            2 => "Twenty",
            3 => "Thirty",
            4 => "Forty",
            5 => "Fifty",
            6 => "Sixty",
            7 => "Seventy",
            8 => "Eighty",
            9 => "Ninety",
        ];

        $words = "";

        if ($number < 20) {
            $words .= $ones[$number];
        } elseif ($number < 100) {
            $words .= $tens[floor($number / 10)];
            $remainder = $number % 10;
            if ($remainder > 0) {
                $words .= " " . $ones[$remainder];
            }
        } elseif ($number < 1000) {
            $words .= $ones[floor($number / 100)] . " Hundred";
            $remainder = $number % 100;
            if ($remainder > 0) {
                $words .=
                    " " . Configurations::convertRupeesToWords($remainder);
            }
        } elseif ($number < 1000000) {
            $words .=
                Configurations::convertRupeesToWords(floor($number / 1000)) .
                " Thousand";
            $remainder = $number % 1000;
            if ($remainder > 0) {
                $words .=
                    " " . Configurations::convertRupeesToWords($remainder);
            }
        } elseif ($number < 1000000000) {
            $words .=
                Configurations::convertRupeesToWords(floor($number / 1000000)) .
                " Million";
            $remainder = $number % 1000000;
            if ($remainder > 0) {
                $words .=
                    " " . Configurations::convertRupeesToWords($remainder);
            }
        } else {
            $words .=
                Configurations::convertRupeesToWords(
                    floor($number / 1000000000)
                ) . " Billion";
            $remainder = $number % 1000000000;
            if ($remainder > 0) {
                $words .=
                    " " . Configurations::convertRupeesToWords($remainder);
            }
        }

        return $words;
    }

    public static function DatepickerToMonth($date)
    {
        $date = Carbon::createFromFormat("m/d/Y", $date);
        $month = $date->format("F");
        $year = $date->format("Y");

        return [$month, $year];
    }
    public static function ordinal($number)
    {
        if ($number == "NA") {
            return "NA";
        }
        if ($number % 100 >= 11 && $number % 100 <= 13) {
            $suffix = "th";
        } else {
            switch ($number % 10) {
                case 1:
                    $suffix = "st";
                    break;
                case 2:
                    $suffix = "nd";
                    break;
                case 3:
                    $suffix = "rd";
                    break;
                default:
                    $suffix = "th";
                    break;
            }
        }
        return $number . $suffix;
    }

    public static function getFeesReminderText()
    {
        if (Schema::hasTable("configurations")) {
            $data = ConfigurationModel::where("name", "=", "site")->first();

            if (count((array) $data) > 0 && isset($data->parm)) {
                $data = json_decode($data->parm);

                if (isset($data->fees_reminder_text)) {
                    return $data->fees_reminder_text;
                }
            }
        }
    }
    public static function getFooterData()
    {
        $homepage_datalists = [];
        $homepage_record = [];
        if (Schema::hasTable("home_page_menu")) {
            $homepage_datalists = CmsmenuModel::where("type", "1")
                ->get()
                ->toArray();
            if (!empty($homepage_datalists)) {
                foreach ($homepage_datalists as $homepage_data) {
                    $homepage_record[$homepage_data["key"]] =
                        $homepage_data["value"];
                }
            }
            if (isset($homepage_record)) {
                return $homepage_record;
            }
        }
    }
}
