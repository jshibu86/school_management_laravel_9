<?php
namespace cms\report\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;
use Configurations;
use cms\report\Models\CertificateConfigurationsModel;
use Illuminate\Support\Facades\DB;
use Session;
use Image;
use cms\core\configurations\Traits\FileUploadTrait;
use CGate;
class StudentReportController extends Controller
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
        abort(404);
    }

    public function StudentIdcard(Request $request, $type = null)
    {
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $school_type_info = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();

        $academicyears = Configurations::getAcademicyears();

        if ($request->ajax()) {
        }
        if ($request->isMethod("post")) {
            if (!$request->school_type) {
                return redirect()
                    ->back()
                    ->with("error", "Please Select School Type");
            }
            $school_type = $request->school_type;
            $class_id = $request->class_id;
            $section_id = $request->section_id;
            $students = StudentsModel::where(
                "academic_year",
                $request->academic_year
            )
                ->with("class:id,name", "section:id,name")
                ->select(
                    "first_name",
                    "last_name",
                    "image",
                    "id",
                    "user_id",
                    "class_id",
                    "section_id",
                    "reg_no",
                    "dob",
                    "blood_group",
                    "mobile"
                )
                ->where("status", 1);

            if ($school_type && $school_type == "all") {
                // getting all classes students

                $students = $students->get();
            } elseif ($school_type && $school_type != "all") {
                // getting corresponding schooltype students
                if ($request->student_id && count($request->student_id)) {
                    $students = $students
                        ->whereIn("id", $request->student_id)
                        ->get();
                    if (count($students) > 0) {
                        return view("report::admin.report.student.bulkprint", [
                            "students" => $students,
                        ]);
                    } else {
                        return redirect()
                            ->back()
                            ->with("error", "No Students Found");
                    }
                }
                $classes = LclassModel::where(
                    "school_type_id",
                    $school_type
                )->pluck("id");

                if (count($classes)) {
                    // classes present

                    if ($class_id) {
                        $students = $students
                            ->where("class_id", $class_id)
                            ->get();
                    } elseif ($class_id && $section_id) {
                        $students = $students
                            ->where("class_id", $class_id)
                            ->where("section_id", $section_id)
                            ->get();
                    } else {
                        $students = $students
                            ->whereIn("class_id", $classes)
                            ->get();
                    }
                } else {
                    // no class available
                    $students = [];
                }
            }
            //dd($students);
            if (count($students) > 0) {
                return view("report::admin.report.student.bulkprint", [
                    "students" => $students,
                ]);
            } else {
                return redirect()
                    ->back()
                    ->with("error", "No Students Found");
            }
        }

        return view("report::admin.report.student.studentidcardbulk", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "students" => [],
            "school_type_info" => $school_type_info,
        ]);
    }

    public function StudentCertificate(Request $request, $type = null)
    {
        // dd($request->all());
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

        $school_type_info->prepend("All", "all");

        if ($request->isMethod("post")) {
            // dd("methodpost");
            if (!$request->school_type) {
                return redirect()
                    ->back()
                    ->with("error", "Please Select School Type");
            }
            $school_type = $request->school_type;
            $class_id = $request->class_id;
            $section_id = $request->section_id;
            $students = StudentsModel::where(
                "academic_year",
                $request->academic_year
            )
                ->with("class:id,name", "section:id,name")
                ->select(
                    "first_name",
                    "last_name",
                    "image",
                    "id",
                    "user_id",
                    "class_id",
                    "section_id",
                    "reg_no",
                    "dob",
                    "blood_group",
                    "mobile"
                )
                ->where("status", 1);
            // dd($students);
            if ($school_type && $school_type == "all") {
                // getting all classes students
                // dd("its enter if");
                $students = $students->get();
            } elseif ($school_type && $school_type != "all") {
                // dd("its enter else");
                // getting corresponding schooltype students
                if ($request->student_id && count($request->student_id)) {
                    // dd("its enter elseif -> if");
                    // dd($request->student_id);
                    if ($request->student_id[0] !== "All") {
                        $students = $students
                            ->whereIn("id", $request->student_id)
                            ->get();
                    } else {
                        $ids = StudentsModel::where([
                            "class_id" => $request->class_id,
                            "section_id" => $request->section_id,
                            "academic_year" => $request->academic_year,
                        ])->pluck("id");
                        $students = StudentsModel::with(
                            "class:id,name",
                            "section:id,name"
                        )
                            ->whereIn("id", $ids)
                            ->get();
                        // dd( $students);
                    }

                    if (isset($students)) {
                        // dd("its enter elseif -> if -> if");
                        $certificate_configurations = CertificateConfigurationsModel::where(
                            "status",
                            "!=",
                            -1
                        )
                            ->orderBy("id", "asc")
                            ->get();
                        // dd($certificate_configurations);
                        return view(
                            "report::admin.report.certificate.bulkprint",
                            [
                                "students" => $students,
                                "configurations" => $certificate_configurations,
                            ]
                        );
                    } else {
                        dd("its else");
                        return redirect()
                            ->back()
                            ->with("error", "No Students Found");
                    }
                }
                $classes = LclassModel::where(
                    "school_type_id",
                    $school_type
                )->pluck("id");

                if (count($classes)) {
                    // classes present

                    if ($class_id) {
                        $students = $students
                            ->where("class_id", $class_id)
                            ->get();
                    } elseif ($class_id && $section_id) {
                        $students = $students
                            ->where("class_id", $class_id)
                            ->where("section_id", $section_id)
                            ->get();
                    } else {
                        $students = $students
                            ->whereIn("class_id", $classes)
                            ->get();
                    }
                } else {
                    // no class available
                    $students = [];
                }
            }
            //dd($students);
            if (count($students) > 0) {
                return view("report::admin.report.certificate.bulkprint", [
                    "students" => $students,
                ]);
            } else {
                return redirect()
                    ->back()
                    ->with("error", "No Students Found");
            }

            // return view("report::admin.report.certificate.bulkprint");
        }

        return view("report::admin.report.certificate.certificatebulk", [
            "layout" => "create",
            "academicyears" => $academicyears,
            "class_lists" => $class_lists,
            "sections" => [],
            "students" => [],
            "school_type_info" => $school_type_info,
        ]);
    }

    public function StudentCertificateconfiguration(
        Request $request,
        $type = null
    ) {
        return view(
            "report::admin.report.configuration.certificateconfiguration"
        );
    }

    public function storeconfigurations(Request $request)
    {
        //dd($request->all());

        $this->validate($request, [
            "head_line" => "required",
            "tag_line1" => "required",
            "tag_line2" => "required",
            "name" => "required",
            "paragraph" => "required",
            "signature" => "required",
            "logo_image" => "required",
            "bottom_top" => "required",
            "bottom_center" => "required",
        ]);

        try {
            $count = CertificateConfigurationsModel::where(
                "status",
                "!=",
                -1
            )->count();

            if ($count) {
                $id = CertificateConfigurationsModel::where(
                    "status",
                    "!=",
                    -1
                )->value("id");
                //dd($id);

                $obj = CertificateConfigurationsModel::find($id);

                $obj->head_line = $request->head_line;
                $obj->tag_line1 = $request->tag_line1;
                $obj->tag_line2 = $request->tag_line2;
                $obj->name = $request->name;
                $obj->paragraph = $request->paragraph;

                $obj->signature = $request->signature;

                if ($request->logo_image) {
                    $obj->logo_image = $this->uploadImage(
                        $request->logo_image,
                        "image"
                    );
                }

                $obj->bottom_top_color = $request->bottom_top;
                $obj->bottom_center_color = $request->bottom_center;

                $obj->save();

                Session::flash("success", "Updated successfully");
                return redirect()
                    ->back()
                    ->withInput();
            } else {
                $obj = new CertificateConfigurationsModel();

                $obj->head_line = $request->head_line;
                $obj->tag_line1 = $request->tag_line1;
                $obj->tag_line2 = $request->tag_line2;
                $obj->name = $request->name;
                $obj->paragraph = $request->paragraph;

                $obj->signature = $request->signature;
                //  $obj->logo_image = $request->logo_image;

                if ($request->logo_image) {
                    $obj->logo_image = $this->uploadImage(
                        $request->logo_image,
                        "image"
                    );
                }

                $obj->bottom_top_color = $request->bottom_top;
                $obj->bottom_center_color = $request->bottom_center;
                //dd("OK2");
                $obj->save();

                Session::flash("success", "saved successfully");
                return redirect()
                    ->back()
                    ->withInput();
            }
        } catch (\Exception $e) {
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
    public function uploadImage($image, $type = null)
    {
        $make_name =
            hexdec(uniqid()) . "." . $image->getClientOriginalExtension();
        Image::make($image)
            ->resize(256, 256)
            ->save("school/profiles/" . $make_name);
        $uploadPath = "/school/profiles/" . $make_name;

        return $uploadPath;
    }
}
