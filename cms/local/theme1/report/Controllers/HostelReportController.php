<?php
namespace cms\report\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\configurations\helpers\Configurations;
use CGate;
use DB;
use Yajra\DataTables\Facades\DataTables;
use cms\dormitory\Models\DormitoryModel;
use cms\dormitory\Models\DormitoryStudentModel;
use cms\fees\Mail\FeeCollectionMail;
use cms\fees\Models\AcademicFeeModel;
use cms\fees\Models\FeeSetupModel;
use cms\fees\Models\FeesModel;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;
use cms\section\Models\SectionModel;

class HostelReportController extends Controller
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
            $dormitory_id = $request->query->get("dormitory_id", 0);
            $room_id = $request->query->get("room_id", 0);
            $initial = $request->query->get("initial", 0);
            //dd($request->all());
            $students = StudentsModel::where(
                "academic_year",
                $academic_year
            )->where("status", 1);

            if ($initial) {
                $data = DormitoryStudentModel::with(
                    "student:id,first_name,class_id",

                    "academicyear",
                    "room",
                    "dormitory"
                )
                    ->select(
                        DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                        "dormitory_students.id as id ",
                        "dormitory_students.academic_year",
                        "student_id",
                        "semester_id",
                        "dormitory_id",
                        "room_id",

                        "date_of_reg"
                    )
                    ->where("dormitory_students.status", "!=", -1)
                    ->where(
                        "dormitory_students.academic_year",
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
                    ->addColumn("feeshostel", function ($data) use (
                        $academic_year
                    ) {
                        $info = $this->getFeepayment($data);
                        return $info;
                        //return $fees;
                    });

                // return $data;
                if (count((array) $data) == 0) {
                    return [];
                }

                return $datatables->rawColumns(["feeshostel"])->make(true);
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

            $assignedStudents = DormitoryStudentModel::when($room_id, function (
                $query,
                $room_id
            ) {
                return $query->where("room_id", $room_id);
            })
                ->when($dormitory_id, function ($query, $dormitory_id) {
                    return $query->where("dormitory_id", $dormitory_id);
                })

                ->whereIn("student_id", $students)
                ->pluck("student_id")
                ->toArray();

            //return $students;

            $data = DormitoryStudentModel::with(
                "student:id,first_name,class_id",

                "academicyear",
                "room",
                "dormitory"
            )
                ->select(
                    DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                    "dormitory_students.id as id ",
                    "dormitory_students.academic_year",
                    "student_id",
                    "semester_id",
                    "dormitory_id",
                    "room_id",

                    "date_of_reg"
                )
                ->where("dormitory_students.status", "!=", -1)

                ->whereIn("dormitory_students.student_id", $assignedStudents);

            $datatables = Datatables::of($data)
                ->addIndexColumn()

                ->addColumn("student_name", function ($data) {
                    return $data->student->first_name;
                })
                ->addColumn("feeshostel", function ($data) use (
                    $academic_year
                ) {
                    $info = $this->getFeepayment($data);
                    return $info;
                    //return $fees;
                });

            // return $data;
            if (count((array) $data) == 0) {
                return [];
            }

            return $datatables->rawColumns(["feeshostel"])->make(true);

            //dd($transport_students);

            //dd($request->all());
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

        $school_type_info = SchoolTypeModel::where("status", 1)->pluck(
            "school_type",
            "id"
        );

        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $examterms = Configurations::getCurentAcademicTerms();

        // $school_type_info->prepend("All", "all");
        return view("report::admin.report.hostel.hostelreport", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "dormitory" => $dormitory,
            "rooms" => [],
            "school_type_info" => $school_type_info,
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
            "examterms" => $examterms,
            "current_academic_term" => $current_academic_term,
        ]);
    }

    public function getFeepayment($data)
    {
        $academic_fee_info_sum = AcademicFeeModel::where(
            "student_id",
            $data->student_id
        )
            ->where("academic_year", Configurations::getCurrentAcademicyear())
            ->where("type", "hostel")
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
