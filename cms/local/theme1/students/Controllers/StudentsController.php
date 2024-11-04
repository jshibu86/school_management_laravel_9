<?php

namespace cms\students\Controllers;

use DB;
use Hash;
use Mail;
use CGate;

use Session;

use Illuminate\Support\Str;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use cms\lclass\Models\LclassModel;
use cms\core\user\Models\UserModel;
use App\Http\Controllers\Controller;
use App\Imports\StudentImport;
use cms\classteacher\Models\ClassteacherModel;
use cms\section\Models\SectionModel;
use cms\students\Models\ParentModel;
use cms\students\Models\StudentsModel;
use Yajra\DataTables\Facades\DataTables;
use cms\students\Mail\StudentWelcomeMail;
use cms\students\Models\AttachementModel;
use phpDocumentor\Reflection\Types\Parent_;
use cms\core\usergroup\Models\UserGroupModel;
use Illuminate\Validation\ValidationException;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\configurations\helpers\Configurations;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\department\Models\DepartmentModel;
use cms\transport\Models\TransportStudents;
use cms\core\user\Mail\ForgetPasswordMail;
use cms\core\user\Mail\PasswordMail;
use Illuminate\Support\Facades\Log;

class StudentsController extends Controller
{
    use FileUploadTrait;
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
            $class_id = $request->query->get("class_id");
            $section_id = $request->query->get("section_id");
            $school_type = $request->query->get("school_type");

            if ($class_id && $section_id) {
                $students = StudentsModel::where([
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                ])
                    ->where("status", 1)
                    ->select([
                        "students.id as id",
                        DB::raw(
                            "CONCAT(students.username, ' - ', students.email) as text"
                        ),
                    ])
                    ->get();

                return $students;
            }

            $students = StudentsModel::where(
                "academic_year",
                $request->query->get("academic_year")
            );

            if ($school_type && $school_type == "all") {
                $students = $students
                    ->where("status", 1)
                    ->select([
                        "students.id as id",
                        DB::raw(
                            "CONCAT(students.username, ' - ', students.email) as text"
                        ),
                    ])
                    ->get();

                return $students;
            } else {
                $classes = LclassModel::where(
                    "school_type_id",
                    $school_type
                )->pluck("id");

                //return $classes;

                if (count($classes)) {
                    // classes present

                    if ($class_id) {
                        $students = $students
                            ->where("class_id", $class_id)
                            ->select([
                                "students.id as id",
                                DB::raw(
                                    "CONCAT(students.username, ' - ', students.email) as text"
                                ),
                            ])
                            ->get();
                        $students->prepend("All");
                        return $students;
                    } elseif ($class_id && $section_id) {
                        $students = $students
                            ->where("class_id", $class_id)
                            ->where("section_id", $section_id)
                            ->select([
                                "students.id as id",
                                DB::raw(
                                    "CONCAT(students.username, ' - ', students.email) as text"
                                ),
                            ])
                            ->get();
                        return $students;
                    } else {
                        $students = $students
                            ->whereIn("class_id", $classes)
                            ->select([
                                "students.id as id",
                                DB::raw(
                                    "CONCAT(students.username, ' - ', students.email) as text"
                                ),
                            ])
                            ->get();
                        return $students;
                    }
                } else {
                    // no class available
                    $students = [];
                    return $students;
                }
            }

            $parent_id = $request->query->get("parent", 0);
            if ($parent_id) {
                $parent = ParentModel::find($parent_id);
                $address_communication = json_decode(
                    $parent->address_communication
                );
                $address_residence = json_decode($parent->address_residence);
                return response()->json([
                    "address_communication" => $address_communication,
                    "address_residence" => $address_residence,
                    "address_check" => $parent->address_check,
                    "religion" => $parent->religion,
                    "father_name" => $parent->father_name,
                    "father_email" => $parent->father_email,
                    "father_mobile" => $parent->father_mobile,
                    "father_image" => $parent->father_image,
                ]);
            }
        }

        if (Session::get("ACTIVE_GROUP") == "Teacher") {
            $active_teacher = Configurations::Activeteacher();
            $class_teach = ClassteacherModel::where(
                "teacher_id",
                $active_teacher->id
            )->first();

            if (!$class_teach) {
                return redirect()
                    ->back()
                    ->with(
                        "exception_error",
                        "You have not Assigen any Class Tecaher | Contact Administrator"
                    );
            }
        }
        $parent_lists = [];
        $data = ParentModel::whereNull("deleted_at")
            ->where("status", 1)
            ->get();
        if (!empty($data)) {
            foreach ($data as $parent) {
                $parent_lists[$parent->id] =
                    $parent->username .
                    "-" .
                    $parent->father_name .
                    "-" .
                    $parent->father_email;
            }
        }
        return view("students::admin.index", [
            "parent_lists" => $parent_lists,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $designation_list = [];
        $academic_years = Configurations::getAcademicyears();
        $gender = Configurations::GENDER;
        $maritialstatus = Configurations::MARITIALSTATUS;
        $student_types = Configurations::STUDENTTYPES;
        $transport_zones = Configurations::TRANSPORTZONE;
        $religion = Configurations::RELIGION;
        $bloodgroup = Configurations::BLOODGROUPS;
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $parent_lists = [];
        $data = ParentModel::whereNull("deleted_at")
            ->where("status", 1)
            ->get();
        if (!empty($data)) {
            foreach ($data as $parent) {
                $parent_lists[$parent->id] =
                    $parent->username .
                    "-" .
                    $parent->father_name .
                    "-" .
                    $parent->father_email;
            }
        }

        // departments

        //dd($types);

        $departments = DepartmentModel::where("status", 1)

            ->pluck("dept_name", "id")
            ->toArray();
        //dd($departments);

        //array_unshift($departments, "[Type a custom Value]");

        //dd($random_list);

        return view("students::admin.edit", [
            "layout" => "create",
            "gender" => $gender,
            "maritialstatus" => $maritialstatus,
            "class_lists" => $class_lists,
            "section_lists" => [],
            "academic_years" => $academic_years,
            "transport_zones" => $transport_zones,
            "student_types" => $student_types,
            "parent_lists" => $parent_lists,
            "religion" => $religion,
            "bloodgroup" => $bloodgroup,
            "departments" => $departments,
            "selected_department" => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $this->validate(
            $request,
            [
                "email" => [
                    "required",
                    Rule::unique("users")->whereNull("deleted_at"),
                ],
                "mobile" => [
                    "required",
                    Rule::unique("users")->whereNull("deleted_at"),
                ],

                "class_id" => "required",
                "section_id" => "required",
                "first_name" => "required|min:2|max:190",
                "last_name" => "required",
                "gender" => "required",
                "dob" => "required",
                "blood_group" => "required",
                "student_type" => "required",
                "admission_date" => "required",
                "national_id_number" => "nullable",
                "blood_group" => "required",
                "handicapped" => "nullable",
                "religion" => "required",
            ],
            [
                "mobile.unique" => "Student Mobile Number Already Registered",
                "email.unique" => "Student Email Already Registered",
            ]
        );

        if (!$request->parent_id) {
            $this->validate(
                $request,
                [
                    "father_email" => [
                        "required",
                        Rule::unique("users", "email")->whereNull("deleted_at"),
                    ],
                ],
                [
                    "father_email.unique" => "Father Email Already Registered",
                    "father_email.required" => "Please Fillout Parent Info",
                ]
            );
        }
        if (!$request->parent_id) {
            if (!$request->father_email) {
                throw ValidationException::withMessages([
                    "father_email" => [
                        "Please provided Father Email or Guardian Email for Generating user",
                    ],
                ]);
            }
            if (!$request->father_mobile) {
                throw ValidationException::withMessages([
                    "father_mobile" => [
                        "The provided Father Mobile or Guardian Mobile for Generating User",
                    ],
                ]);
            }
        }

        // dd($request->all());
        DB::beginTransaction();
        try {
            $student_role = UserGroupModel::where("group", "Student")->first();
            $parent_role = UserGroupModel::where("group", "Parent")->first();
            if (empty($student_role) || empty($parent_role)) {
                $message =
                    "Create a Student and Parent role and Assigen Permissions";
                $route = route("usergroup.create");
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error_link", $message)
                    ->with("link", $route);
            } else {
                $student_info = StudentsModel::withTrashed()
                    ->latest("id")
                    ->first();

                $student_password = Configurations::Generatepassword(4);

                $student_username = Configurations::GenerateUsername(
                    $student_info != null ? $student_info->username : null,
                    "S"
                );
                // dd($student_info);

                //todo create student user

                $student_user = new UserModel();
                $student_name =
                    $request->first_name . " " . $request->last_name;
                $student_user->name = $student_name;
                $student_user->username = $student_username;
                $student_user->email = $request->email;
                $student_user->mobile = $request->mobile;
                $Hash = Hash::make($student_password);
                $student_user->password = $Hash;

                if ($request->imagec) {
                    $student_user->images = $this->uploadImage(
                        $request->imagec,
                        "image"
                    );
                }

                if ($student_user->save()) {
                    $usertypemap = new UserGroupMapModel();
                    $usertypemap->user_id = $student_user->id;
                    $usertypemap->group_id = $student_role->id;
                    $usertypemap->save();
                }

                //todo create parent user
                if (!$request->parent_id) {
                    $parent_info = ParentModel::withTrashed()
                        ->latest()
                        ->first();
                    $parent_username = Configurations::GenerateUsername(
                        $parent_info != null ? $parent_info->username : null,
                        "P"
                    );
                    $parent_password = Configurations::Generatepassword(4);
                    $parent_user = new UserModel();
                    $parent_name = "";

                    if ($request->father_name) {
                        $parent_name = $request->father_name;
                    } else {
                        $parent_name = $parent_username;
                    }
                    $parent_user->name = $parent_name;
                    $parent_user->username = $parent_username;
                    $parent_user->email = $request->father_email;

                    $parent_user->mobile = $request->father_mobile;

                    $Hash = Hash::make($parent_password);
                    $parent_user->password = $Hash;
                    if ($request->father_image) {
                        $parent_user->images = $this->uploadImage(
                            $request->father_image,
                            "image"
                        );
                    }
                    if ($parent_user->save()) {
                        $usertypemap = new UserGroupMapModel();
                        $usertypemap->user_id = $parent_user->id;
                        $usertypemap->group_id = $parent_role->id;
                        $usertypemap->save();
                    }
                }

                $student = new StudentsModel();
                $student->user_id = $student_user->id;
                $student->username = $student_username;
                $student->academic_year = $request->academic_year;
                $student->class_id = $request->class_id;
                $student->section_id = $request->section_id;
                $student->reg_no = $student_username;
                $student->roll_no = $request->roll_no;
                $student->first_name = $request->first_name;
                $student->last_name = $request->last_name;
                $student->email = $request->email;
                $student->stu_department = $request->stu_department;
                $student->mobile = $request->mobile;
                $student->gender = $request->gender;
                $student->dob = $request->dob;
                $student->blood_group = $request->blood_group;
                $student->student_type = $request->student_type;
                $student->admission_date = $request->admission_date;
                $student->passport_no = $request->passport_no;
                $student->national_id_number = $request->national_id_number;
                if ($request->imagec) {
                    $student->image = $student_user->images;
                }
                $student->handicapped = $request->handicapped;
                $student->transportation = $request->transportation;
                $student->transportation_zone = $request->transportation_zone;
                $student->vechicle_no = $request->vechicle_no;
                $student->yearly_income = $request->yearly_income;
                $student->house_name = $request->house_name;
                $student->previous_ins_percentage =
                    $request->previous_ins_percentage;
                $student->address_check = $request->address_check ? 1 : 0;
                $student->religion = $request->religion;
                $student->scholarship = $request->scholarship;
                $student->scholarship_note = $request->scholarship_note;
                //getting address communication
                $address_communication = [
                    "house_no" => $request->house_no,
                    "street_name" => $request->street_name,
                    "postal_code" => $request->postal_code,
                    "province" => $request->province,
                    "country" => $request->country,
                ];
                $student->address_communication = json_encode(
                    $address_communication
                );

                //getting address residence

                // if ($request->address_check) {
                //     $student->address_residence = json_encode(
                //         $address_communication
                //     );
                // } else {
                //     $address_residence = [
                //         "building_name" => $request->building_name_res,
                //         "subbuilding_name" => $request->subbuilding_name_res,
                //         "house_no" => $request->house_no_res,
                //         "street_name" => $request->street_name_res,
                //         "postal_code" => $request->postal_code_res,
                //         "province" => $request->province_res,
                //         "country" => $request->country_res,
                //     ];
                //     $student->address_residence = json_encode(
                //         $address_residence
                //     );
                // }

                if ($student->save()) {
                    //todo save parent details
                    if (!$request->parent_id) {
                        $parent = new ParentModel();
                        $parent->username = $parent_username;
                        $parent->user_id = $parent_user->id;
                        $parent->student_id = $student->id;
                        //save father details
                        $parent->father_name = $request->father_name;
                        $parent->father_email = $request->father_email;
                        $parent->father_mobile = $request->father_mobile;
                        $parent->father_occupation =
                            $request->father_occupation;
                        if ($request->father_image) {
                            $parent->father_image = $parent_user->images;
                        }

                        $parent->fathernat_id = $request->fathernat_id;

                        //save mother details
                        $parent->mother_name = $request->mother_name;
                        $parent->mother_email = $request->mother_email;
                        $parent->mother_mobile = $request->mother_mobile;
                        $parent->mother_occupation =
                            $request->mother_occupation;
                        $parent->mother_image = $request->mother_image;
                        $parent->mothernat_id = $request->mothernat_id;
                        //save guardian details
                        $parent->guardian_name = $request->guardian_name;
                        $parent->guardian_email = $request->guardian_email;
                        $parent->guardian_mobile = $request->guardian_mobile;
                        $parent->guardian_occupation =
                            $request->guardian_occupation;
                        $parent->guardian_image = $request->guardian_image;
                        $parent->guardiannat_id = $request->guardiannat_id;

                        $parent->yearly_income = $request->yearly_income;
                        $parent->wallet_amount = $request->wallet_amount;
                        $parent->address_check = $request->address_check
                            ? 1
                            : 0;
                        $parent->religion = $request->father_religion;
                        $address_communication = [
                            "house_no" => $request->house_no,
                            "street_name" => $request->street_name,
                            "postal_code" => $request->postal_code,
                            "province" => $request->province,
                            "country" => $request->country,
                        ];
                        $parent->address_communication = json_encode(
                            $address_communication
                        );

                        //getting address residence

                        // if ($request->address_check) {
                        //     $parent->address_residence = json_encode(
                        //         $address_communication
                        //     );
                        // } else {
                        //     $address_residence = [
                        //         "building_name" => $request->building_name_res,
                        //         "subbuilding_name" =>
                        //             $request->subbuilding_name_res,
                        //         "house_no" => $request->house_no_res,
                        //         "street_name" => $request->street_name_res,
                        //         "postal_code" => $request->postal_code_res,
                        //         "province" => $request->province_res,
                        //         "country" => $request->country_res,
                        //     ];
                        //     $parent->address_residence = json_encode(
                        //         $address_residence
                        //     );
                        // }
                        $parent->save();
                    }
                }

                DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                $find_student = StudentsModel::find($student->id)->update([
                    "parent_id" => $request->parent_id
                        ? $request->parent_id
                        : $parent->id,
                ]);
                DB::statement("SET FOREIGN_KEY_CHECKS=1;");

                //save attachments

                if ($request->birth_certificate != null) {
                    $attachment = new AttachementModel();
                    $attachment->attachment_name = "Birth Certificate";

                    $attachment->attachment_url = $this->uploadFile(
                        $request->birth_certificate,
                        "file"
                    );

                    $attachment->student_id = $student->id;
                    $attachment->save();
                }
                if ($request->tranfer_certificate != null) {
                    $attachment = new AttachementModel();
                    $attachment->attachment_name = "Tranfer Certificate";
                    $attachment->attachment_url = $this->uploadFile(
                        $request->tranfer_certificate,
                        "file"
                    );

                    $attachment->student_id = $student->id;
                    $attachment->save();
                }
                if ($request->mark_sheet != null) {
                    $attachment = new AttachementModel();
                    $attachment->attachment_name = "Mark Sheet";
                    $attachment->attachment_url = $this->uploadFile(
                        $request->mark_sheet,
                        "file"
                    );

                    $attachment->student_id = $student->id;
                    $attachment->save();
                }
                if ($request->national_id_certificate != null) {
                    $attachment = new AttachementModel();
                    $attachment->attachment_name = "National Id Certificate";
                    $attachment->attachment_url = $this->uploadFile(
                        $request->national_id_certificate,
                        "file"
                    );

                    $attachment->student_id = $student->id;
                    $attachment->save();
                }
            }
            DB::commit();
            $message =
                "Student and Parent save successfully | Login using these Credentials";
            $class_name = "success";

            if ($request->parent_id) {
                return redirect()
                    ->route("students.index")
                    ->with("success_custom", "Student Created Successfully")
                    ->with("username", $student_username)
                    ->with("password", $student_password);
            } else {
                return redirect()
                    ->route("students.index")
                    ->with("success_student", $message)
                    ->with("username", $student_username)
                    ->with("parent_username", $parent_username)
                    ->with("password", $student_password)
                    ->with("parent_password", $parent_password);
            }
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
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = StudentsModel::with(
            "parent",
            "class",
            "section",
            "user",
            "attachment"
        )->find($id);

        $transport = TransportStudents::with("stop", "route", "bus")
            ->where("student_id", $id)
            ->first();

        // dd($transport);

        $address_communication = json_decode($data->address_communication);

        //  dd($data);

        return view("students::admin.show", [
            "data" => $data,
            "address_communication" => $address_communication,
            "transport" => $transport,
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
        $data = StudentsModel::with("parent", "attachment")->find($id);

        //  dd($data);

        $attachements = [];
        $attachements_ids = [];

        $type = Configurations::CERTIFICATETYPES;

        foreach ($data->attachment as $attach) {
            $name = Str::slug(strtolower($attach->attachment_name), "_");

            $attachements[$name][$attach->id] = $attach->attachment_url;
        }

        // dd($attachements, $attachements_ids);

        //dd($attachements["mark_sheet"]);

        $address_communication = json_decode($data->address_communication);
        $address_residence = json_decode($data->address_residence);

        $designation_list = [];
        $academic_years = Configurations::getAcademicyears();
        $gender = Configurations::GENDER;
        $maritialstatus = Configurations::MARITIALSTATUS;
        $student_types = Configurations::STUDENTTYPES;
        $transport_zones = Configurations::TRANSPORTZONE;
        $religion = Configurations::RELIGION;
        $bloodgroup = Configurations::BLOODGROUPS;
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $section_lists = SectionModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->where("class_id", $data->class_id)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $parent_lists = [];
        $data_ = ParentModel::whereNull("deleted_at")
            ->where("status", 1)
            ->get();
        if (!empty($data_)) {
            foreach ($data_ as $parent) {
                $parent_lists[$parent->id] =
                    $parent->username .
                    "-" .
                    $parent->father_name .
                    "-" .
                    $parent->father_email;
            }
        }
        // dd($data);
        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();
        //dd($departments);

        $selected_Section = SectionModel::where("status", 1)
            ->where("id", $data->section_id)
            ->first();
        if ($selected_Section->department_id == null) {
            $department = [];
        } else {
            $department = DepartmentModel::where(
                "id",
                $selected_Section->department_id
            )
                ->where("status", 1)
                ->pluck("dept_name", "id")
                ->toArray();
        }

        return view("students::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "gender" => $gender,
            "maritialstatus" => $maritialstatus,
            "class_lists" => $class_lists,
            "section_lists" => $section_lists,
            "academic_years" => $academic_years,
            "transport_zones" => $transport_zones,
            "student_types" => $student_types,
            "address_communication" => $address_communication,
            "address_residence" => $address_residence,
            "attachements" => $attachements,
            "parent_lists" => $parent_lists,
            "religion" => $religion,
            "bloodgroup" => $bloodgroup,
            "attachements_ids" => $attachements_ids,
            "departments" => $departments,
            "selected_department" => $department,
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
        //dd($request->all());

        $user_id = StudentsModel::find($id);
        $parent = ParentModel::where("id", $user_id->parent_id)->first();
        $this->validate(
            $request,
            [
                "email" => [
                    "required",
                    Rule::unique("users")
                        ->whereNull("deleted_at")
                        ->ignore($user_id->user_id),
                ],
                "father_email" => [
                    "required",
                    Rule::unique("users", "email")
                        ->whereNull("deleted_at")
                        ->ignore($parent->user_id),
                ],
                "mobile" => [
                    "required",
                    Rule::unique("users")
                        ->whereNull("deleted_at")
                        ->ignore($user_id->user_id),
                ],
                "class_id" => "required",
                "section_id" => "required",
                "first_name" => "required|min:2|max:190",
                "last_name" => "required",
                "gender" => "required",
                "dob" => "required",
                "blood_group" => "required",
                "student_type" => "required",
                "admission_date" => "required",
                "national_id_number" => "nullable",
                "blood_group" => "required",
                "handicapped" => "nullable",
            ]
            // [
            //     "mobile.unique" => "Student Mobile Number Already Registered",
            //     "email.unique" => "Student Email Already Registered",
            //     "father_email.unique" =>
            //         "Father/Guardian Email Already Registered",
            // ]
        );

        // if ($request->guardian_name) {
        //     $this->validate($request, [
        //         "guardian_relation" => "required",
        //         "guardian_mobile" => "required",
        //         "guardian_email" => "required",
        //     ]);
        // }

        if (!$request->father_email) {
            throw ValidationException::withMessages([
                "father_email" => [
                    "Please provided Father Email or Guardian Email for Generating user",
                ],
            ]);
        }
        if (!$request->father_mobile) {
            throw ValidationException::withMessages([
                "father_mobile" => [
                    "The provided Father Mobile or Guardian Mobile for Generating User",
                ],
            ]);
        }
        DB::beginTransaction();
        try {
            $student_role = UserGroupModel::where("group", "Student")->first();
            $parent_role = UserGroupModel::where("group", "Parent")->first();
            if (empty($student_role) || empty($parent_role)) {
                $message =
                    "Create a Student and Parent role and Assigen Permissions";
                $route = route("usergroup.create");
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error_link", $message)
                    ->with("link", $route);
            } else {
                //todo create student user

                $student_user = UserModel::find($user_id->user_id);
                $student_name =
                    $request->first_name . " " . $request->last_name;
                $student_user->name = $student_name;
                $student_user->email = $request->email;
                $student_user->mobile = $request->mobile;

                if ($request->imagec) {
                    $this->deleteImage(
                        null,
                        $student_user->images ? $student_user->images : null
                    );
                    $student_user->images = $this->uploadImage(
                        $request->imagec,
                        "image"
                    );
                }

                $student_user->save();

                //todo create parent user
                $get_parent = ParentModel::find($user_id->parent_id);
                $parent_user = UserModel::find($get_parent->user_id);
                $parent_name = "";

                $parent_user->name = $request->father_name;

                $parent_user->email = $request->father_email;

                $parent_user->mobile = $request->father_mobile;

                if ($request->father_image) {
                    $this->deleteImage(
                        null,
                        $parent_user->images ? $parent_user->images : null
                    );
                    $parent_user->images = $this->uploadImage(
                        $request->father_image,
                        "image"
                    );
                }

                if ($parent_user->save()) {
                    $student = StudentsModel::find($id);

                    $student->academic_year = $request->academic_year;
                    $student->class_id = $request->class_id;
                    $student->section_id = $request->section_id;

                    $student->roll_no = $request->roll_no;
                    $student->first_name = $request->first_name;
                    $student->last_name = $request->last_name;
                    $student->email = $request->email;
                    $student->stu_department = $request->stu_department;
                    $student->mobile = $request->mobile;
                    $student->gender = $request->gender;
                    $student->dob = $request->dob;
                    $student->blood_group = $request->blood_group;
                    $student->student_type = $request->student_type;
                    $student->admission_date = $request->admission_date;
                    $student->passport_no = $request->passport_no;
                    $student->national_id_number = $request->national_id_number;
                    $student->house_name = $request->house_name;
                    if ($request->imagec) {
                        $student->image = $student_user->images;
                    } else {
                        $student->image = $student->image;
                    }
                    $student->handicapped = $request->handicapped;
                    $student->transportation = $request->transportation;
                    $student->transportation_zone =
                        $request->transportation_zone;
                    $student->vechicle_no = $request->vechicle_no;
                    $student->yearly_income = $request->yearly_income;
                    $student->previous_ins_percentage =
                        $request->previous_ins_percentage;
                    $student->address_check = $request->address_check ? 1 : 0;
                    $student->religion = $request->religion;
                    $student->scholarship = $request->scholarship;
                    $student->scholarship_note = $request->scholarship_note;
                    //getting address communication
                    $address_communication = [
                        "house_no" => $request->house_no,
                        "street_name" => $request->street_name,
                        "postal_code" => $request->postal_code,
                        "province" => $request->province,
                        "country" => $request->country,
                    ];
                    $student->address_communication = json_encode(
                        $address_communication
                    );

                    //getting address residence

                    // if ($request->address_check) {
                    //     $student->address_residence = json_encode(
                    //         $address_communication
                    //     );
                    // } else {
                    //     $address_residence = [
                    //         "building_name" => $request->building_name_res,
                    //         "subbuilding_name" =>
                    //             $request->subbuilding_name_res,
                    //         "house_no" => $request->house_no_res,
                    //         "street_name" => $request->street_name_res,
                    //         "postal_code" => $request->postal_code_res,
                    //         "province" => $request->province_res,
                    //         "country" => $request->country_res,
                    //     ];
                    //     $student->address_residence = json_encode(
                    //         $address_residence
                    //     );
                    // }
                }

                if ($student->save()) {
                    //todo save parent details

                    $parent = ParentModel::find($user_id->parent_id);

                    //save father details
                    $parent->father_name = $request->father_name;
                    $parent->father_email = $request->father_email;
                    $parent->father_mobile = $request->father_mobile;
                    $parent->father_occupation = $request->father_occupation;
                    if ($request->father_image) {
                        $parent->father_image = $parent_user->images;
                    } else {
                        $parent->father_image = $parent->father_image;
                    }
                    $parent->fathernat_id = $request->fathernat_id;

                    //save mother details
                    $parent->mother_name = $request->mother_name;
                    $parent->mother_email = $request->mother_email;
                    $parent->mother_mobile = $request->mother_mobile;
                    $parent->mother_occupation = $request->mother_occupation;
                    $parent->mother_image = $request->mother_image;
                    $parent->mothernat_id = $request->mothernat_id;
                    //save guardian details
                    $parent->guardian_name = $request->guardian_name;
                    $parent->guardian_email = $request->guardian_email;
                    $parent->guardian_mobile = $request->guardian_mobile;
                    $parent->guardian_occupation =
                        $request->guardian_occupation;
                    $parent->guardian_image = $request->guardian_image;
                    $parent->guardiannat_id = $request->guardiannat_id;

                    $parent->yearly_income = $request->yearly_income;
                    $parent->wallet_amount = $request->wallet_amount;
                    $parent->religion = $request->religion;
                    $address_communication = [
                        "house_no" => $request->house_no,
                        "street_name" => $request->street_name,
                        "postal_code" => $request->postal_code,
                        "province" => $request->province,
                        "country" => $request->country,
                    ];
                    $parent->address_communication = json_encode(
                        $address_communication
                    );
                }

                if ($parent->save()) {
                    //save attachments

                    //delete old attachement

                    $old_attachment = AttachementModel::where(
                        "student_id",
                        $id
                    )->get();

                    if (!empty($old_attachment)) {
                        foreach ($old_attachment as $data) {
                            $data->delete();
                        }
                    }

                    try {
                        if ($request->birth_certificate != null) {
                            //dd("ge");
                            $attachment = new AttachementModel();
                            $attachment->attachment_name = "Birth Certificate";

                            $attachment->attachment_url = $this->uploadFile(
                                $request->birth_certificate,
                                "file"
                            );

                            $attachment->student_id = $id;
                            $attachment->save();
                        }
                        if ($request->tranfer_certificate != null) {
                            //dd("ge");
                            $attachment = new AttachementModel();
                            $attachment->attachment_name =
                                "Tranfer Certificate";

                            $attachment->attachment_url = $this->uploadFile(
                                $request->tranfer_certificate,
                                "file"
                            );

                            $attachment->student_id = $id;
                            $attachment->save();
                        }
                        if ($request->mark_sheet != null) {
                            //dd("ge");
                            $attachment = new AttachementModel();
                            $attachment->attachment_name = "Mark Sheet";
                            $attachment->attachment_url = $this->uploadFile(
                                $request->mark_sheet,
                                "file"
                            );

                            $attachment->student_id = $id;
                            $attachment->save();
                        }
                        if ($request->nat_id != null) {
                            $attachment = new AttachementModel();
                            $attachment->attachment_name =
                                "National Id Certificate";
                            $attachment->attachment_url = $this->uploadFile(
                                $request->national_id_certificate,
                                "file"
                            );

                            $attachment->student_id = $id;
                            $attachment->save();
                        }

                        //dd($request->all());
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
            }
            DB::commit();
            // dd($old_attachment);
            $message = "Student and Parent Update successfully";
            $class_name = "success";
            Session::flash("success", $message);
            return redirect()->route("students.index");
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_students)) {
            $delObj = new StudentsModel();
            foreach ($request->selected_students as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new StudentsModel();
            $delItem = $delObj->find($id);
            $user = $delItem->user_id;
            UserModel::find($user)->delete();
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("students.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-students");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = StudentsModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "students.id as id",
            "students.reg_no as reg_no",
            "students.image as image",
            DB::raw("CONCAT(first_name, last_name) AS full_name"),
            "email",
            "mobile",
            "parent.father_name as parentname",
            "parent.guardian_name as guardianname",
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
        )->leftJoin("parent", "parent.id", "=", "students.parent_id");
        if (Session::get("ACTIVE_GROUP") == "Teacher") {
            $active_teacher = Configurations::Activeteacher();
            $class_teach = ClassteacherModel::where(
                "teacher_id",
                $active_teacher->id
            )->first();

            $data = $data->where([
                "students.class_id" => $class_teach->class_id,
                "students.section_id" => $class_teach->section_id,
            ]);
        } else {
            $data = $data;
        }
        $data = $data->orderBy("students.reg_no", "desc")->get();

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("parent", function ($data) {
                if ($data->parentname != null) {
                    return $data->parentname;
                } else {
                    return '<span class="badge bg-danger assigen_parent" id="' .
                        $data->id .
                        '" onclick="parentassign(this.id)">Assign Parent</span>';
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
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
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
            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "students",
                ])->render();

                //return $data->id;
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["pimage", "action", "parent"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-students");
        if ($request->ajax()) {
            $student = StudentsModel::find($request->id);

            // return $student;

            if ($student->user_id) {
                $user = UserModel::find($student->user_id);
                if ($user) {
                    $user->update([
                        "status" => $request->status,
                    ]);
                }
            }

            $data = StudentsModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
                "data" => $data,
                "status" => $request->status,
            ]);
        }

        if (!empty($request->selected_students)) {
            $obj = new StudentsModel();
            foreach ($request->selected_students as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function DeleteAttachment(Request $request)
    {
        if ($request->ajax()) {
            $attach_id = $request->query->get("content", 0);

            $attachment = AttachementModel::find($attach_id);

            if ($attachment) {
                $attach_url = $attachment->attachment_url;

                $delete_file = $this->deleteImage(null, $attach_url);

                $attachment->delete();
                return true;
            } else {
                return "no attachment";
            }
        }
    }

    public function Bulkupload(Request $request)
    {
        $sections = [];
        $academic_years = Configurations::getAcademicyears();
        $class_list = LclassModel::where("status", "!=", -1)
            ->whereNull("deleted_at")
            ->pluck("name", "id")
            ->toArray();
        if ($request->isMethod("post")) {
            //dd($request->all());

            $this->validate(
                $request,
                [
                    "class_id" => "required",
                    "section_id" => "required",
                    "upload_file" => "required",
                ],
                [
                    "upload_file.required" => "Please select CSV File",
                    "class_id.required" => "Please Select Class",
                    "section_id.required" => "Please Select Section",
                ]
            );

            $student_role = UserGroupModel::where("group", "Student")->first();
            $parent_role = UserGroupModel::where("group", "Parent")->first();
            if (empty($student_role) || empty($parent_role)) {
                $message =
                    "Create a Student and Parent role and Assigen Permissions";
                $route = route("usergroup.create");
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error_link", $message)
                    ->with("link", $route);
            } else {
                try {
                    $import = new StudentImport();
                    $import->class_id = $request->class_id;
                    $import->section_id = $request->section_id;
                    $path = request()
                        ->file("upload_file")
                        ->store("temp");
                    $data = \Excel::import(
                        $import,
                        request()->file("upload_file")
                    );

                    if ($import->getcount() == 0) {
                        return redirect()
                            ->back()
                            ->withInput()
                            ->with("exception_error", "This CSV File is Empty");
                    } else {
                        return redirect()
                            ->route("students.index")
                            ->with(
                                "success_default",
                                "Student import Successfully | Now Assigen parents to Particular Student"
                            );
                    }
                } catch (\Throwable $e) {
                    $message = str_replace(
                        ["\r", "\n", "'", "`"],
                        " ",
                        $e->getMessage()
                    );
                    return back()->with("exception_error", $message);
                }
            }
        }
        return view("students::admin.upload", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "class_list" => $class_list,
            "sections" => $sections,
        ]);
    }

    public function Assigenparent(Request $request)
    {
        if ($request->ajax()) {
            $student_id = $request->query->get("studentid", 0);

            $student = StudentsModel::where("id", $student_id)->first();

            if ($student) {
                $parent_lists = [];
                $data = ParentModel::whereNull("deleted_at")
                    ->where("status", 1)
                    ->get();
                if (!empty($data)) {
                    foreach ($data as $parent) {
                        $parent_lists[$parent->id] =
                            $parent->username .
                            "-" .
                            $parent->father_name .
                            "-" .
                            $parent->father_email;
                    }
                }

                $view = view("students::admin.assigenparent", [
                    "parent_lists" => $parent_lists,
                    "student" => $student,
                ])->render();
                return response()->json([
                    "message" => "student found",
                    "viewfile" => $view,
                ]);
            } else {
                return response()->json(["message" => "student not found"]);
            }
        }

        if ($request->isMethod("post")) {
            $student = StudentsModel::where(
                "id",
                $request->student_id
            )->first();
            StudentsModel::where("id", $request->student_id)->update([
                "parent_id" => $request->parent_id,
            ]);

            $message = "Successfully Assigend parent to $student->username";

            return redirect()
                ->route("students.index")
                ->with("success_default", $message);
        }
    }

    public function Printidcard(Request $request, $student_id)
    {
        $student = StudentsModel::with("user", "class", "section")->find(
            $student_id
        );

        if ($student) {
            //dd($student);
            return view("students::idcard.html", ["student" => $student]);
        } else {
            return redirect()
                ->back()
                ->with("error", "Student Not Found");
        }

        // dd($student_id);
    }

    public function forgetPassword(Request $request)
    {
        $new_password = "";
        $user = UserModel::where("id", $request->student_user_id)->first();

        if ($user) {
            // $user_group = User::getUserGroup($users->id);
            // if (in_array(1, $user_group)) {
            //     return ["status" => 0, "message" => "Restricted Area"];
            // }
            $new_password = Configurations::Generatepassword(4);
            $Hash = Hash::make($new_password);
            $user->password = $Hash;
            $user->save();
            \CmsMail::setMailConfig();
            Mail::to($user->email)->queue(
                new PasswordMail($user, $new_password)
            );
            return response()->json(["success" => true]);

            if ($request->ajax()) {
                return ["status" => 1, "message" => "Please Check Your Mail"];
            }
        } else {
            Session::flash("error", "Wrong Email");
            return ["status" => 0, "message" => "Wrong Email"];
        }

        return redirect()->route("home");
    }
}
