<?php
namespace cms\report\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\classteacher\Models\ClassteacherModel;
use cms\core\configurations\Models\ConfigurationModel;
use cms\fees\Models\AcademicFeeModel;
use cms\fees\Models\FeeSetupModel;
use cms\fees\Models\FeesModel;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;
use cms\subject\Models\SubjectModel;
use Configurations;
use CGate;

class ClassReportController extends Controller
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

        $school_type_info->prepend("All", "all");

        //dd($classteacher);

        //dd($students);

        if ($request->ajax()) {
            $academic_year = $request->query->get("acyear", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $school_type = $request->query->get("school_type", 0);

            $students = StudentsModel::where("status", 1)
                ->where("academic_year", $academic_year)
                ->where(["class_id" => $class_id, "section_id" => $section_id])
                ->get();

            $feesetup = FeeSetupModel::with("feelists")
                ->where([
                    "class_id" => $class_id,
                    "academic_year" => $academic_year,
                ])
                ->first();

            $feedata = json_decode(
                @ConfigurationModel::where("name", "=", "feestructure")->first()
                    ->parm
            );

            $subjects = SubjectModel::with(
                "subjectmapping.Teacher:id,teacher_name"
            )
                ->where("class_id", $class_id)
                ->get();

            $classteacher = ClassteacherModel::with("Teacher")
                ->where([
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                ])
                ->first();

            $students_total_fees = 0;
            $students_paid_fees = 0;

            if ($feesetup && $feedata) {
                foreach ($students as $student) {
                    $academic_fee_info_sum = AcademicFeeModel::where(
                        "student_id",
                        $student->id
                    )->sum("due_amount");

                    $academic_fee_info = AcademicFeeModel::where(
                        "student_id",
                        $student->id
                    )->get();

                    $scholarship = StudentsModel::find($student->id)
                        ->scholarship;

                    if ($scholarship) {
                        $schamount =
                            ($scholarship / 100) * $feesetup->total_amount +
                            $academic_fee_info_sum;

                        $total_amount =
                            $feesetup->total_amount + $academic_fee_info_sum;
                        $grand_total = $total_amount - $schamount;
                    } else {
                        $grand_total =
                            $feesetup->total_amount + $academic_fee_info_sum;
                    }

                    $students_total_fees += $grand_total;

                    $paid = FeesModel::where([
                        "academic_year" => $academic_year,
                        "class_id" => $class_id,
                        "section_id" => $section_id,
                        "student_id" => $student->id,
                    ])->sum("paid_amount");

                    $students_paid_fees += $paid;
                    # code...
                }
            }

            // fee calculation

            $view = view(
                "report::admin.report.class.classfullereport",
                compact(
                    "students",
                    "subjects",
                    "classteacher",
                    "students_total_fees",
                    "students_paid_fees"
                )
            )->render();

            return response()->json(["view" => $view]);
            return "ok";
        }

        return view(
            "report::admin.report.class.classreport",
            compact(
                "academicyears",
                "class_lists",
                "sections",
                "school_type_info"
            )
        );
    }
}
