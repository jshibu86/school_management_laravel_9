<?php

namespace cms\admission\Controllers;

use DB;
use Hash;
use Mail;
use CGate;
use Session;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use cms\admission\Models\AdmissionModel;
use cms\lclass\Models\LclassModel;
use Yajra\DataTables\Facades\DataTables;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\core\user\Models\UserModel;
use App\Imports\StudentImport;
use cms\classteacher\Models\ClassteacherModel;
use cms\section\Models\SectionModel;
use cms\students\Models\ParentModel;
use cms\students\Models\StudentsModel;
use cms\students\Mail\StudentWelcomeMail;
use cms\students\Models\AttachementModel;
use phpDocumentor\Reflection\Types\Parent_;
use cms\core\usergroup\Models\UserGroupModel;
use Illuminate\Validation\ValidationException;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\configurations\helpers\Configurations;
use cms\department\Models\DepartmentModel;
use cms\transport\Models\TransportStudents;
use cms\admissionform\Models\AdmissionformModel;
use Illuminate\Support\Facades\Validator;
use cms\admission\Mail\RejectionEmail;
use cms\admission\Mail\ExamLinkEmail;
use Illuminate\Support\Facades\Log;
use cms\cmsmenu\Models\CmsmenuModel;
use cms\exam\Models\ExamModel;
use cms\exam\Models\ExamTypeModel;
use cms\core\configurations\Models\ConfigurationModel;
use cms\exam\Models\OnlineExamModel;
use cms\exam\Models\OnlineExamSubmissionModel;
use cms\core\configurations\Controllers\MailController;

class AdmissionController extends Controller
{
    use FileUploadTrait;
    protected MailController $mailController;

    public function __construct(MailController $mailController)
    {
        $this->mailController = $mailController;
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admission::admin.index");
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
        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();

        return view("admission::admin.edit", [
            "layout" => "create",
            "gender" => $gender,
            "maritialstatus" => $maritialstatus,
            "class_lists" => $class_lists ?? [],
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

        // return view('admission::admin.new', ["layout" => "create"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "first_name" => "required|string",
            "last_name" => "required|string",
            "email" => ["required", "string", Rule::unique("admission")],
            "mobile" => ["required", "string", Rule::unique("admission")],
            "gender" => "required",
            "stu_department" => "required|integer",
            "dob" => "required|date",
            "handicapped" => "required|nullable|string",
            "blood_group" => "required",
            "religion" => "required",
            "national_id" => "required|string",
            "previous_class_id" => "required|integer",
            "current_class_id" => "required|integer",
            "school_name" => "required|string",
            "parent_name" => "required|string",
            "parent_mobile" => "required|string",
            "parent_email" => "required|email",
            "house_no" => "required|string",
            "postal_code" => "required|string",
            "city" => "required|string",
            "street" => "required|string",
            "country" => "required|string",
        ];

        $messages = [
            "mobile.unique" =>
                "The given mobile number already registered. Please use another number",
            "email.unique" =>
                "The given email id already registered. Please use another email id.",
        ];

        // fetch hidden fields
        $hiddenFields = AdmissionformModel::where("is_active", "=", "0")
            ->pluck("menu_name")
            ->toArray();

        // find and check the hidden fields and unset it.
        $rules = array_diff_key($rules, array_flip($hiddenFields));

        // validate the request
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();
            //todo create student admission
            $student_user = new AdmissionModel();
            $student_user->first_name = $request->first_name;
            $student_user->last_name = $request->last_name;
            $student_user->email = $request->email;
            $student_user->mobile = $request->mobile;
            $student_user->dob = $request->dob;
            $student_user->gender = $request->gender;
            //  $student_user->departments = $request->departments;
            $student_user->blood_group = $request->blood_group;
            $student_user->handicapped = $request->handicapped;
            $student_user->national_id_number = $request->national_id;
            $student_user->parent_name = $request->parent_name;
            $student_user->parent_email = $request->parent_email;
            $student_user->parent_mobile = $request->parent_mobile;
            $student_user->house_no = $request->house_no;
            $student_user->street = $request->street;
            $student_user->country = $request->country;
            $student_user->city = $request->city;
            $student_user->postal_code = $request->postal_code;
            $student_user->previous_class_id = $request->previous_class_id;
            $student_user->current_class_id = $request->current_class_id;
            $student_user->previous_school = $request->school_name;
            $student_user->religion = $request->religion;
            $student_user->stu_department = $request->stu_department;
            $student_user->save();

            // save admission status based on site configuration
            $status_data = json_decode(
                @ConfigurationModel::where("name", "=", "site")->first()->parm,
                true
            );
            if (array_key_exists("admission_exam_status", $status_data)) {
                $student_user->admission_status = "Admission Test";
                $student_user->save();
            } else {
                $student_user->admission_status = "Pending";
                $student_user->save();
            }

            //save attachments
            if ($request->photo) {
                $student_user->image = $this->uploadImage(
                    $request->photo,
                    "image"
                );
            }
            if ($request->stu_document_upload1 != null) {
                $student_user->stu_document_upload1 = $this->uploadFile(
                    $request->stu_document_upload1,
                    "file"
                );
                $student_user->save();
            }
            if ($request->stu_document_upload2 != null) {
                $student_user->stu_document_upload2 = $this->uploadFile(
                    $request->stu_document_upload2,
                    "file"
                );
                $student_user->save();
            }

            DB::commit();

            $status_data = json_decode(
                @ConfigurationModel::where("name", "=", "site")->first()->parm,
                true
            );

            //admission exam status is set on in site config
            if (array_key_exists("admission_exam_status", $status_data)) {
                if ($request->current_class_id != null) {
                    $studentId = $student_user->id;
                    $selectedClassId = $request->current_class_id;
                    $dataRecords = $this->isExamAvailable($selectedClassId);

                    // email the exam link
                    if (!empty($dataRecords)) {
                        $dataRecords["exam_data"] = $dataRecords;
                        $student_data = AdmissionModel::where("id", $studentId)
                            ->first()
                            ->toArray();
                        $dataRecords["student_data"] = $student_data;
                        $this->emailExamLink($dataRecords);
                    } else {
                        Session::flash(
                            "success",
                            "Admission application submitted successfully!!"
                        );
                    }
                }

                Session::flash(
                    "success",
                    "Admission application submitted successfully!! Please check your registered email for exam process."
                );
            } else {
                Session::flash(
                    "success",
                    "Admission application submitted successfully!!"
                );
            }

            return redirect()
                ->route("admission")
                ->with("success_custom", "Application submitted successfully");
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

    //email admission exam link message to student
    public function emailExamLink($dataRecords)
    {
        //dd($dataRecords['student_data']);
        // $logoUrl = "https://schoolmanagement.webbazaardevelopment.com/school/profiles/1748204780466456.png";
        $logoUrl = Configurations::getConfig("site")->imagec;
        $school_name = Configurations::getConfig("site")->school_name;
        $exam_link_data = [
            "name" => $dataRecords["student_data"]["first_name"],
            "admission_id" => $dataRecords["student_data"]["id"],
            "exam_id" => $dataRecords["exam_data"]["id"],
            "class_id" => $dataRecords["exam_data"]["class_id"],
            "school" => $school_name,
            "notification_text" =>
                "Your application has been approved. Please read instruction before attempt to attend your online admission exam.",
            "logoUrl" => $logoUrl,
        ];
        $sendersemailID = $dataRecords["student_data"]["email"];
        $mail = Mail::to($sendersemailID)->send(
            new ExamLinkEmail(
                $exam_link_data["admission_id"],
                $exam_link_data["name"],
                $exam_link_data["exam_id"],
                $exam_link_data["notification_text"],
                $exam_link_data["school"],
                $exam_link_data["logoUrl"]
            )
        );
    }
    public function sendExamURLEmail($dataRecord)
    {
        $email_data = [
            "name" => $dataRecords["student_data"]["first_name"],
            "admission_id" => $dataRecords["student_data"]["id"],
            "exam_id" => $dataRecords["exam_data"]["id"],
            "class_id" => $dataRecords["exam_data"]["class_id"],
            "senderMailId" => $dataRecords["student_data"]["email"],
            "notification_text" =>
                "Your application has been approved. Please read instruction before attempt to attend your online admission exam.",
        ];

        $this->$mailController->$sendExamURLEmail($email_data);

        // $exam_link_data["admission_id"],
        // $exam_link_data["name"],
        // $exam_link_data["exam_id"],
        // $exam_link_data["notification_text"],
        // $exam_link_data["school"],
        // $exam_link_data["logoUrl"],
        //      ));
    }

    // check exam available for selected class
    public function isExamAvailable($classid)
    {
        $exam = ExamModel::with("class", "section", "subject")
            ->where("class_id", $classid)
            ->where("type_of_exam", "Admission Exam")
            ->first();

        if ($exam) {
            $examArray = $exam->toArray();
            return $examArray;
        } else {
            return [];
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admissionExamResults = [];
        $designation_list = [];
        $data = AdmissionModel::with("parent", "attachment")->find($id);
        $address_communication = json_decode($data->address_communication);
        $address_residence = json_decode($data->address_residence);
        $academic_years = Configurations::getAcademicyears();
        $gender = Configurations::GENDER;
        $maritialstatus = Configurations::MARITIALSTATUS;
        $student_types = Configurations::STUDENTTYPES;
        $transport_zones = Configurations::TRANSPORTZONE;
        $religion = Configurations::RELIGION;
        $bloodgroup = Configurations::BLOODGROUPS;

        $selected_student_lists = AdmissionModel::where("id", $id)
            ->first()
            ->toArray();

        $selected_class_id = $selected_student_lists["previous_class_id"];
        $selected_religion_id = $selected_student_lists["religion"];
        $selected_department_id = $selected_student_lists["stu_department"];
        $selected_stu_document_upload1 =
            $selected_student_lists["stu_document_upload1"];
        $selected_stu_document_upload2 =
            $selected_student_lists["stu_document_upload2"];

        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $selected_class_lists = LclassModel::whereNull("deleted_at")
            ->where("id", $selected_class_id)
            ->pluck("name", "id")
            ->toArray();

        $section_lists = SectionModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->where("class_id", $selected_class_id)
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

        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();

        $selected_Section = SectionModel::where("status", 1)
            ->where("id", $data->current_class_id)
            ->pluck("name", "id")
            ->toArray();

        // getting exam results
        $onlineexam = OnlineExamModel::where([
            "admission_id" => $id,
        ])->first();

        if ($onlineexam != null) {
            $exam = ExamModel::where([
                "id" => $onlineexam->exam_id,
            ])->first();
            $submission = OnlineExamSubmissionModel::where(
                "online_exam_id",
                $onlineexam->$id
            )
                ->where("is_correct", 1)
                ->sum("mark");
            $admissionExamResults = [
                "onlineexam" => $onlineexam,
                "submission" => intval($submission),
                "exam" => $exam,
            ];
        }

        return view("admission::admin.edit", [
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
            //"attachements" => $attachements,
            "parent_lists" => $parent_lists,
            "religion" => $religion,
            "bloodgroup" => $bloodgroup,
            //"attachements_ids" => $attachements_ids,
            "departments" => $departments,
            "selected_Section" => $selected_Section,
            "selected_class_lists" => $selected_class_lists,
            "selected_religion_id" => $selected_religion_id,
            "selected_department_id" => $selected_department_id,
            "selected_stu_document_upload1" => $selected_stu_document_upload1,
            "selected_stu_document_upload2" => $selected_stu_document_upload2,
            "admissionExamResults" => $admissionExamResults,
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
        // dd($request);
        $this->validate(
            $request,
            [
                // "email" => ["required",Rule::unique("users")->whereNull("deleted_at")],
                //"mobile" => ["required",Rule::unique("users")->whereNull("deleted_at"),],
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
                "transportation" => "required",
                //"transportation_zone" => "required",
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

                //todo create student user and store in usermodel

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

                // store in usergroupmapmodel
                if ($student_user->save()) {
                    $usertypemap = new UserGroupMapModel();
                    $usertypemap->user_id = $student_user->id;
                    $usertypemap->group_id = $student_role->id;
                    $usertypemap->save();
                }

                //todo create parent user and store in UserGroupMapModel
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
                // Store to student model
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
                $student->stu_department = $request->departments;
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

                // Store to parentmodel
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

                //send email confirmation
                // $studentId = $request->input('student_id');
                $student_data = AdmissionModel::where("id", $id)->first();

                $status_data = json_decode(
                    @ConfigurationModel::where("name", "=", "site")->first()
                        ->parm,
                    true
                );

                //admission exam status is set on in site config
                if (array_key_exists("onboard_sucess_message", $status_data)) {
                    $onboardSuccessMessages =
                        $status_data["onboard_sucess_message"];
                    $onboardSuccessMessage = strip_tags(
                        $onboardSuccessMessages
                    );
                } else {
                    $onboardSuccessMessage = "";
                }
                $emailexamscores = [];
                if (array_key_exists("emailexamscores", $status_data)) {
                    // getting exam results
                    $onlineexam = OnlineExamModel::where([
                        "admission_id" => $id,
                    ])->first();
                    if ($onlineexam != null) {
                        $exam = ExamModel::where([
                            "id" => $onlineexam->exam_id,
                        ])->first();
                        $submission = OnlineExamSubmissionModel::where(
                            "online_exam_id",
                            $onlineexam->$id
                        )
                            ->where("is_correct", 1)
                            ->sum("mark");
                        $emailexamscores = [
                            "onlineexam" => $onlineexam,
                            "exam" => $exam,
                        ];
                    }
                } else {
                    $emailexamscores = [];
                }

                //email rejection message to student &parent email ids
                $rejection_data = [];
                $school_name = Configurations::getConfig("site")->school_name;
                // $logoUrl = "https://schoolmanagement.webbazaardevelopment.com/school/profiles/1748204780466456.png";
                $logoUrl = Configurations::getConfig("site")->imagec;

                $rejection_data = [
                    "name" => $student_data->first_name,
                    "gender" => $student_data->gender,
                    "school" => $school_name,
                    "rejection_text" => $onboardSuccessMessage,
                    "logoUrl" => $logoUrl,
                    "emailexamscores" => $emailexamscores,
                ];
                $env = config("app.env");
                // dd($env);
                if ($env == "local") {
                    \CmsMail::setMailConfig();
                } else {
                    \CmsMail::setMailConfig();
                }

                $mail = Mail::to($request->email)->send(
                    new RejectionEmail(
                        $rejection_data["name"],
                        $rejection_data["gender"],
                        $rejection_data["rejection_text"],
                        $rejection_data["school"],
                        $rejection_data["logoUrl"],
                        $rejection_data
                    )
                );

                // dd($mail);
                //dd($emailexamscores);
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
            $message = "Student and Parent onboarded successfully";
            $class_name = "success";

            if ($id) {
                $delObj = new AdmissionModel();
                $delItem = $delObj->find($id);
                if ($delItem) {
                    try {
                        $delItem->delete();
                    } catch (Exception $e) {
                        Log::error(
                            "Error deleting record: " . $e->getMessage()
                        );
                    }
                }
            }

            // Session::flash("success", "data Deleted Successfully!!");
            // return redirect()->route("attendance.index");

            // if ($request->parent_id) {
            //     Session::flash("success", "Student onboarded Successfully!!");
            //     return redirect()->route("students.index");
            // } else {

            Session::flash("success", "Student onboarded Successfully!!");
            return redirect()->route("students.index");
            //}
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
        if (!empty($request->selected_admission)) {
            $delObj = new AdmissionModel();
            foreach ($request->selected_admission as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("admission.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-admission");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = AdmissionModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id as id",
            "image as image",
            DB::raw("CONCAT(first_name, last_name) AS full_name"),
            "mobile",
            "parent_name as parent_name",
            "parent_mobile as parent_mobile",
            "admission_status as admission_status"
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
            ->addColumn("image", function ($data) {
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
            ->addColumn("admission_status", function ($data) {
                $status = strtolower($data->admission_status);
                $color = "";
                if ($status == "pending" || $status == "admission test") {
                    return '<span class="badge bg-warning text-dark">' .
                        ucfirst($status) .
                        "</span>";
                } elseif ($status == "rejected") {
                    return '<span class="badge bg-danger">' .
                        ucfirst($status) .
                        "</span>";
                }
                return '<span style="color:' .
                    $color .
                    '">' .
                    ucfirst($status) .
                    "</span>";
            })
            ->addColumn("action", function ($data) {
                $status = strtolower($data->admission_status);
                if ($status == "pending") {
                    return view("layout::datatable.action", [
                        "data" => $data,
                        "route" => "admission",
                    ])->render();
                }
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["check", "image", "admission_status", "action"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-admission");

        if (!empty($request->selected_admission)) {
            $obj = new AdmissionModel();
            foreach ($request->selected_admission as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function getClassList()
    {
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $department_lists = DepartmentModel::where("status", 1)
            ->orderBy("id", "asc")
            ->pluck("dept_name", "id")
            ->toArray();

        $enabled_lists = AdmissionformModel::where("is_active", 1)
            ->pluck("menu_name")
            ->toArray();
        //dd($enabledLists);
        $alert_message = CmsmenuModel::where("key", "alert_msg")
            ->pluck("value")
            ->toArray();
        return view("website.admission", [
            "class_lists" => $class_lists,
            "enabled_lists" => $enabled_lists,
            "department_lists" => $department_lists,
            "alert_message" => $alert_message,
        ]);
    }

    function reject(Request $request)
    {
        $rejectionText = $request->input("rejection_text");
        $rejection_text = strip_tags($rejectionText);
        $studentId = $request->input("student_id");
        $student_data = AdmissionModel::where("id", $studentId)->first();

        //email rejection message to student &parent email ids
        $school_name = Configurations::getConfig("site")->school_name;

        //$logoUrl = "https://schoolmanagement.webbazaardevelopment.com/school/profiles/1748204780466456.png";
        $logoUrl = asset(Configurations::getConfig("site")->imagec);

        $rejection_data = [
            "name" => $student_data->first_name,
            "gender" => $student_data->gender,
            "school" => $school_name,
            "rejection_text" => $rejection_text,
            "logoUrl" => $logoUrl,
        ];
        Log::info("Rejection Data:", $rejection_data);
        $env = config("app.env");
        // dd($env);
        if ($env == "local") {
            \CmsMail::setMailConfig();
        } else {
            \CmsMail::setMailConfig();
        }

        $mail = Mail::to($student_data->email)->send(
            new RejectionEmail(
                $rejection_data["name"],
                $rejection_data["gender"],
                $rejection_data["rejection_text"],
                $rejection_data["school"],
                $rejection_data["logoUrl"]
            )
        );

        // Update the record with rejection message and status=rejected in admission table
        $student_data->reject_msg = $rejection_text;
        $student_data->admission_status = "Rejected";
        $student_data->save();

        //return view('admission::admin.index');

        //  $pending_records=  AdmissionformModel::where('admission_status', '!=', '1')->pluck('menu_name')->toArray();

        //  return view('admissionform::admin.index', ['items' => $tableColumns, "is_active"=>$is_active]);
        return response()->json(["success" => true]);
    }
}
