<?php

namespace cms\teacher\Controllers;

use DB;
use Hash;
use Mail;
use CGate;
use Session;
use Configurations;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use App\Imports\TeacherImport;
use cms\core\user\helpers\User;
use Illuminate\Validation\Rule;
use cms\core\user\Models\UserModel;
use App\Http\Controllers\Controller;
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
use Carbon\Carbon;

class TeacherController extends Controller
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
            CGate::resouce("teacher");
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
            $class_id = $request->query->get("class", 0);
            $teachers = TeacherModel::select("id", "teacher_name as text")

                ->where("status", 1)
                ->orderBy("teacher_name", "asc")
                ->get();
            return $teachers;
        }
        return view("teacher::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $designation_list = [];
        $gender = Configurations::GENDER;
        $religion = Configurations::RELIGION;
        $bloodgroup = Configurations::BLOODGROUPS;
        $maritialstatus = Configurations::MARITIALSTATUS;
        $data = DesignationModel::whereNull("deleted_at")
            ->where("status", 1)
            ->get();
        if (!empty($data)) {
            foreach ($data as $designation) {
                $designation_list[$designation->id] =
                    $designation->designation_name . "-" . $designation->type;
            }
        }

        $departments = DepartmentModel::where("status", 1)

            ->pluck("dept_name", "id")
            ->toArray();

        //dd($designation);
        return view("teacher::admin.edit", [
            "layout" => "create",
            "designation_list" => $designation_list,
            "gender" => $gender,
            "maritialstatus" => $maritialstatus,
            "religion" => $religion,
            "bloodgroup" => $bloodgroup,
            "departments" => $departments,
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
        // dd($request->all());
        $this->validate(
            $request,
            [
                "email" => "required|unique:users,email",

                "mobile" => "required|numeric|unique:users,mobile",

                "teacher_name" => [
                    "required",
                    "min:2",
                    "max:190",
                    "regex:/^[a-zA-Z\s]*$/",
                ],

                "gender" => "required",
                "dob" => [
                    "required",
                    "date",
                    "before_or_equal:" . now()->format("Y-m-d"),
                ],

                "qualification" => "required",
                "guardian_name" => ["required", "regex:/^[a-zA-Z\s]*$/"],
                "stu_department" => "required",
                "relation" => ["required", "regex:/^[a-zA-Z\s]*$/"],
                "blood_group" => "required",
                // "work_exp" => ["required", "regex:/^[a-zA-Z0-9\s]*$/"],
                // "kin_fullname" => ["regex:/^[a-zA-Z\s]*$/"],
                // "qualification" => ["required", "regex:/^[a-zA-Z\s]*$/"],
                // "kin_phonenumber" => "numeric",
                "guardian_mobile" => "required|numeric",
                // "start_date" => [
                //     "required",
                //     "date",
                //     "before_or_equal:" . now()->format("Y-m-d"),
                // ],
                // "end_date" => [
                //     "required",
                //     "date",
                //     "before_or_equal:" . now()->format("Y-m-d"),
                // ],
            ],
            [
                "stu_department.required" =>
                    "Please Select Atleast One Department",
                "work_exp.regex" =>
                    "Work experience should not contain special characters.",
                "kin_fullname.regex" =>
                    "Kin fullname should only contain letters and spaces.",
                "guardian_name.regex" =>
                    "Guardian name should only contain letters and spaces.",
                "relation.regex" =>
                    "Relation should only contain letters and spaces.",
                "qualification.regex" =>
                    "Qualification should only contain letters and spaces.",
                "teacher_name.regex" =>
                    "Staff Name should only contain letters and spaces.",
                "kin_phonenumber.numeric" =>
                    "Kin phonenumber should only contain numbers.",
                "mobile.numeric" =>
                    "Mobile Number should only contain numbers.",
                "guardian_mobile.numeric" =>
                    "Guardian phonenumber should only contain numbers.",
                "start_date.before_or_equal" =>
                    "Start date should be in the past or today.",
                "end_date.before_or_equal" =>
                    "End date should be in the past or today.",
                "dob.before_or_equal" => "DOB should be in the past or today.",
            ]
        );
        DB::beginTransaction();
        try {
            $role = UserGroupModel::where("group", "Teacher")->first();
            if (empty($role)) {
                $message = "Create a Teacher role and Assigen Permissions";
                $route = route("usergroup.create");
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error_link", $message)
                    ->with("link", $route);
                //throw new Exception("Create a Customer Role First");
            } else {
                $teacher_info = TeacherModel::withTrashed()
                    ->latest("id")
                    ->first();
                // dd($teacher_info);
                $password = Configurations::Generatepassword(4);
                $teacher_username = Configurations::GenerateUsername(
                    $teacher_info != null ? $teacher_info->employee_code : null,
                    "T"
                );

                //dd($teacher_username);

                $user = new UserModel();
                $user->name = $request->teacher_name;
                $user->username = $teacher_username;
                $user->email = $request->email;
                $user->mobile = $request->mobile;
                $Hash = Hash::make($password);
                $user->password = $Hash;
                if ($request->imagec) {
                    $user->images = $this->uploadImage(
                        $request->imagec,
                        "image"
                    );
                }
                if ($user->save()) {
                    //user role set
                    $usertypemap = new UserGroupMapModel();
                    $usertypemap->user_id = $user->id;
                    $usertypemap->group_id = $role->id;
                    $usertypemap->save();

                    //save Teacher

                    $teacher = new TeacherModel();
                    $teacher->user_id = $user->id;
                    $teacher->designation_id = $request->designation_id;
                    $teacher->email = $request->email;
                    $teacher->mobile = $request->mobile;
                    $teacher->work_exp = $request->work_exp;
                    $teacher->employee_code = $teacher_username;
                    $teacher->teacher_name = $request->teacher_name;
                    $teacher->gender = $request->gender;
                    $teacher->dob = $request->dob;
                    $teacher->national_id_number = $request->national_id_number;
                    $teacher->qualification = $request->qualification;
                    if ($request->imagec) {
                        $teacher->image = $user->images;
                    }
                    $teacher->date_ofjoin = $request->date_ofjoin;
                    $teacher->date_ofreleave = $request->date_ofreleave;
                    $teacher->reason_forleave = $request->reason_forleave;
                    $teacher->guardian_name = $request->guardian_name;
                    $teacher->relation = $request->relation;
                    $teacher->guardian_mobile = $request->guardian_mobile;
                    $teacher->blood_group = $request->blood_group;
                    $teacher->blood_group = $request->blood_group;
                    $teacher->handicapped = $request->handicapped;
                    $teacher->maritial_status = $request->maritial_status;
                    $teacher->religion = $request->religion;
                    $teacher->emp_name = $request->emp_name;
                    $teacher->job_role = $request->job_role;
                    $teacher->net_pay = $request->net_pay;
                    $teacher->location = $request->location;
                    $teacher->start_date = $request->start_date;
                    $teacher->end_date = $request->end_date;
                    $teacher->job_description = $request->job_description;
                    $teacher->kin_fullname = $request->kin_fullname;
                    $teacher->kin_relationship = $request->kin_relationship;
                    $teacher->kin_phonenumber = $request->kin_phonenumber;
                    $teacher->kin_email = $request->kin_email;
                    $teacher->kin_occupation = $request->kin_occupation;
                    $teacher->kin_religion = $request->kin_religion;
                    $teacher->kin_address = $request->kin_address;
                    $teacher->work_exp = $request->work_exp;
                    $teacher->work_expdetail = $request->work_expdetail;
                    //getting address communication
                    $address_communication = [
                        "house_no" => $request->house_no,
                        "street_name" => $request->street_name,
                        "postal_code" => $request->postal_code,
                        "province" => $request->province,
                        "country" => $request->country,
                    ];
                    $teacher->address_communication = json_encode(
                        $address_communication
                    );

                    //getting address residence

                    // if ($request->address__check) {
                    //     $teacher->address_residence = json_encode(
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
                    //     $teacher->address_residence = json_encode(
                    //         $address_residence
                    //     );
                    // }

                    if ($teacher->save()) {
                        // teacher department mapping

                        if (sizeof($request->stu_department)) {
                            foreach ($request->stu_department as $department) {
                                $dept = new DepartmentMappingModel();

                                $dept->department_id = $department;
                                $dept->teacher_id = $teacher->id;

                                $dept->save();
                            }
                        }
                        if ($request->birth_certificate != null) {
                            $attachment = new AttachementModel();
                            $attachment->attachment_name = "Birth Certificate";

                            $attachment->attachment_url = $this->uploadFile(
                                $request->birth_certificate,
                                "file"
                            );

                            $attachment->teacher_id = $teacher->id;
                            $attachment->save();
                        }
                        if ($request->tranfer_certificate != null) {
                            $attachment = new AttachementModel();
                            $attachment->attachment_name =
                                "Tranfer Certificate";
                            $attachment->attachment_url = $this->uploadFile(
                                $request->tranfer_certificate,
                                "file"
                            );

                            $attachment->teacher_id = $teacher->id;
                            $attachment->save();
                        }
                        if ($request->mark_sheet != null) {
                            $attachment = new AttachementModel();
                            $attachment->attachment_name = "Mark Sheet";
                            $attachment->attachment_url = $this->uploadFile(
                                $request->mark_sheet,
                                "file"
                            );

                            $attachment->teacher_id = $teacher->id;
                            $attachment->save();
                        }
                        if ($request->national_id_certificate != null) {
                            $attachment = new AttachementModel();
                            $attachment->attachment_name =
                                "National Id Certificate";
                            $attachment->attachment_url = $this->uploadFile(
                                $request->national_id_certificate,
                                "file"
                            );

                            $attachment->teacher_id = $teacher->id;
                            $attachment->save();
                        }
                    }

                    DB::commit();

                    if (config("app.env") == "production") {
                        \CmsMail::setMailConfig();

                        Mail::to($request->email)->send(
                            new PasswordMail($user, $password)
                        );
                    }

                    //Created Notifications send to Admins
                    $msg =
                        $teacher->teacher_name .
                        " New Teacher added by " .
                        User::getUser()->name;
                    $notification = Configurations::sendNotification(
                        "info",
                        $msg,
                        User::getUser()->id
                    );

                    $message =
                        "Teacher save successfully | Password Send to Register Teacher Email | Login using these Credentials";
                    $class_name = "success";
                    return redirect()
                        ->route("teacher.index")
                        ->with("success_custom", $message)
                        ->with("username", $teacher_username)
                        ->with("password", $password);
                } else {
                    $msg = "Something went wrong !! Please try again later !!";
                    $class_name = "error";
                    Session::flash($class_name, $msg);
                    return redirect()
                        ->back()
                        ->withInput();
                }
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
        if ($id) {
            $data = TeacherModel::with("user")
                ->with([
                    "classteacher" => function ($query) {
                        $query
                            ->select(
                                "classteacher.id",
                                "classteacher.teacher_id",
                                "classteacher.class_id",
                                "classteacher.section_id",
                                "lclass.name as classname",
                                "section.name as sectionname"
                            )
                            ->join(
                                "lclass",
                                "lclass.id",
                                "=",
                                "classteacher.class_id"
                            )
                            ->join(
                                "section",
                                "section.id",
                                "=",
                                "classteacher.section_id"
                            );
                    },
                ])
                ->find($id);

            $payment_history = SaleryPayrollPayment::where(
                "user_id",
                $data->user_id
            )->get();

            //dd($payment_history);

            $address_communication = json_decode($data->address_communication);

            $address_residence = json_decode($data->address_residence);
            // dd($data);
            return view("teacher::admin.show", [
                "data" => $data,
                "address_communication" => $address_communication,
                "address_residence" => $address_residence,
                "payment_history" => $payment_history,
            ]);
        } else {
            $message = "Something Went wrong !!";
            return redirect()
                ->back()

                ->with("exception_error", $message);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = TeacherModel::with("attachment")->find($id);

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
        return view("teacher::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "designation_list" => $designation_list,
            "gender" => $gender,
            "maritialstatus" => $maritialstatus,
            "address_communication" => $address_communication,
            "religion" => $religion,
            "bloodgroup" => $bloodgroup,
            "attachements" => $attachements,
            "departments" => $departments,
            "selected_departments" => $department_ids,
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
        $user_id = TeacherModel::find($id);
        $this->validate($request, [
            "email" => [
                "required",
                Rule::unique("users")
                    ->whereNull("deleted_at")
                    ->ignore($user_id->user_id),
            ],
            "mobile" => [
                "required",
                Rule::unique("users")
                    ->whereNull("deleted_at")
                    ->ignore($user_id->user_id),
            ],
            "teacher_name" => "required|min:2|max:190",
            "gender" => "required",
            "dob" => "required",

            "qualification" => "required",
            "guardian_name" => "required",
            "relation" => "required",
            "guardian_mobile" => "required",
            "blood_group" => "required",
        ]);
        DB::beginTransaction();
        try {
            $role = UserGroupModel::where("group", "Teacher")->first();
            if (empty($role)) {
                $message = "Create a Teacher role and Assigen Permissions";
                $route = route("usergroup.create");
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error_link", $message)
                    ->with("link", $route);
                //throw new Exception("Create a Customer Role First");
            } else {
                //dd($request->all());

                $user = UserModel::find($user_id->user_id);
                $user->name = $request->teacher_name;
                $user->email = $request->email;
                $user->mobile = $request->mobile;

                if ($request->imagec) {
                    $this->deleteImage(
                        null,
                        $user->images ? $user->images : null
                    );
                    $user->images = $this->uploadImage(
                        $request->imagec,
                        "image"
                    );
                }

                if ($user->save()) {
                    //save Teacher

                    $teacher = TeacherModel::find($id);
                    $teacher->designation_id = $request->designation_id;
                    $teacher->email = $request->email;
                    $teacher->mobile = $request->mobile;
                    $teacher->work_exp = $request->work_exp;

                    $teacher->teacher_name = $request->teacher_name;
                    $teacher->gender = $request->gender;
                    $teacher->dob = $request->dob;
                    $teacher->national_id_number = $request->national_id_number;
                    $teacher->qualification = $request->qualification;

                    if ($request->imagec) {
                        $teacher->image = $user->images;
                    }

                    $teacher->date_ofjoin = $request->date_ofjoin;
                    $teacher->date_ofreleave = $request->date_ofreleave;
                    $teacher->reason_forleave = $request->reason_forleave;
                    $teacher->guardian_name = $request->guardian_name;
                    $teacher->relation = $request->relation;
                    $teacher->guardian_mobile = $request->guardian_mobile;
                    $teacher->blood_group = $request->blood_group;
                    $teacher->blood_group = $request->blood_group;
                    $teacher->handicapped = $request->handicapped ? 1 : 0;
                    $teacher->maritial_status = $request->maritial_status;
                    $teacher->religion = $request->religion;
                    $teacher->emp_name = $request->emp_name;
                    $teacher->job_role = $request->job_role;
                    $teacher->net_pay = $request->net_pay;
                    $teacher->location = $request->location;
                    $teacher->start_date = $request->start_date;
                    $teacher->end_date = $request->end_date;
                    $teacher->job_description = $request->job_description;
                    $teacher->kin_fullname = $request->kin_fullname;
                    $teacher->kin_relationship = $request->kin_relationship;
                    $teacher->kin_phonenumber = $request->kin_phonenumber;
                    $teacher->kin_email = $request->kin_email;
                    $teacher->kin_occupation = $request->kin_occupation;
                    $teacher->kin_religion = $request->kin_religion;
                    $teacher->kin_address = $request->kin_address;
                    $teacher->work_exp = $request->work_exp;
                    $teacher->work_expdetail = $request->work_expdetail;
                    //getting address communication
                    $address_communication = [
                        "house_no" => $request->house_no,
                        "street_name" => $request->street_name,
                        "postal_code" => $request->postal_code,
                        "province" => $request->province,
                        "country" => $request->country,
                    ];
                    $teacher->address_communication = json_encode(
                        $address_communication
                    );

                    //getting address residence

                    // if ($request->address__check) {
                    //     $teacher->address_residence = json_encode(
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
                    //     $teacher->address_residence = json_encode(
                    //         $address_residence
                    //     );
                    // }

                    if ($teacher->save()) {
                        // $old_attachment = AttachementModel::where(
                        //     "teacher_id",
                        //     $id
                        // )->get();

                        // if (!empty($old_attachment)) {
                        //     foreach ($old_attachment as $data) {
                        //         $data->delete();
                        //     }
                        // }

                        if (sizeof($request->stu_department)) {
                            // delete existing dept

                            DepartmentMappingModel::where(
                                "teacher_id",
                                $id
                            )->delete();
                            foreach ($request->stu_department as $department) {
                                $dept = new DepartmentMappingModel();

                                $dept->department_id = $department;
                                $dept->teacher_id = $teacher->id;

                                $dept->save();
                            }
                        }
                        if ($request->birth_certificate != null) {
                            $attachment = new AttachementModel();
                            $attachment->attachment_name = "Birth Certificate";

                            $attachment->attachment_url = $this->uploadFile(
                                $request->birth_certificate,
                                "file"
                            );

                            $attachment->teacher_id = $teacher->id;
                            $attachment->save();
                        }
                        if ($request->tranfer_certificate != null) {
                            $attachment = new AttachementModel();
                            $attachment->attachment_name =
                                "Tranfer Certificate";
                            $attachment->attachment_url = $this->uploadFile(
                                $request->tranfer_certificate,
                                "file"
                            );

                            $attachment->teacher_id = $teacher->id;
                            $attachment->save();
                        }
                        if ($request->mark_sheet != null) {
                            $attachment = new AttachementModel();
                            $attachment->attachment_name = "Mark Sheet";
                            $attachment->attachment_url = $this->uploadFile(
                                $request->mark_sheet,
                                "file"
                            );

                            $attachment->teacher_id = $teacher->id;
                            $attachment->save();
                        }
                        if ($request->national_id_certificate != null) {
                            $attachment = new AttachementModel();
                            $attachment->attachment_name =
                                "National Id Certificate";
                            $attachment->attachment_url = $this->uploadFile(
                                $request->national_id_certificate,
                                "file"
                            );

                            $attachment->teacher_id = $teacher->id;
                            $attachment->save();
                        }
                    }

                    DB::commit();

                    $message = "Teacher Updated successfully";
                    $class_name = "success";
                    Session::flash($class_name, $message);
                    return redirect()->route("teacher.index");
                } else {
                    $msg = "Something went wrong !! Please try again later !!";
                    $class_name = "error";
                    Session::flash($class_name, $msg);
                    return redirect()
                        ->back()
                        ->withInput();
                }
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_teacher)) {
            $delObj = new TeacherModel();
            foreach ($request->selected_teacher as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new TeacherModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("teacher.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-teacher");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = TeacherModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "teacher.id as id",
            "teacher_name",
            "employee_code",
            "email",
            "mobile",
            "designation.designation_name as designation",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new TeacherModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new TeacherModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->leftjoin(
                "designation",
                "teacher.designation_id",
                "=",
                "designation.id"
            )
            ->where("teacher.status", "!=", -1)
            ->orderBy("teacher.id", "asc");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("designation_name", function ($data) {
                if ($data->designation != null) {
                    return $data->designation;
                } else {
                    return "<span class='text-danger'>Not Assign</span>";
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
                    "route" => "teacher",
                ])->render();

                //return $data->id;
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["action", "designation_name"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-teacher");
        if ($request->ajax()) {
            $teacher = TeacherModel::find($request->id);
            UserModel::find($teacher->user_id)->update(["status" => -1]);
            $data = TeacherModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
                "data" => $data,
                "status" => $request->status,
            ]);
        }

        if (!empty($request->selected_teacher)) {
            $obj = new TeacherModel();
            foreach ($request->selected_teacher as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }
    public function designationcreate(Request $request)
    {
        if ($request->isMethod("post") || $request->isMethod("put")) {
            //  dd($request->all());
            $this->validate($request, [
                "designation_name" => "required",
                "type" => "required",
            ]);

            $insertdata = [
                "designation_name" => $request->designation_name,
                "type" => $request->type,
            ];

            if ($request->description) {
                $insertdata["description"] = $request->description;
            }

            DesignationModel::UpdateOrCreate(
                [
                    "id" => $request->id,
                ],
                $insertdata
            );

            if ($request->has("submit_cat_continue")) {
                return redirect()
                    ->route("designationcreate")
                    ->with("success", "Saved Successfully");
            }

            Session::flash("success", "saved successfully");
            return redirect()->route("designationview");
        }
        $designation_types = Configurations::DESIGNATIONTYPES;

        $random_list = DesignationModel::whereNull("deleted_at")
            ->WhereNotIn("type", $designation_types)
            ->pluck("type", "type")
            ->toArray();

        $types = array_merge($designation_types, $random_list);

        //dd($types);

        array_unshift($types, "[Type a custom Value]");

        if ($request->id) {
            $data = DesignationModel::find($request->id);
            // dd($data, $types);
            $designation_types = Configurations::DESIGNATIONTYPES;

            $random_list = DesignationModel::whereNull("deleted_at")
                ->WhereNotIn("type", $designation_types)
                ->pluck("type", "type")
                ->toArray();

            $types = array_merge($designation_types, $random_list);
            array_unshift($types, "[Type a custom Value]");
            // dd($data->type, $types);
            return view("teacher::admin.designationedit", [
                "layout" => "edit",
                "types" => $types,
                "data" => $data,
            ]);
        } else {
            return view("teacher::admin.designationedit", [
                "layout" => "create",
                "types" => $types,
            ]);
        }
    }

    public function designationview(Request $request)
    {
        //dd("view");
        return view("teacher::admin.designationview");
    }

    public function GetdesignationData(Request $request)
    {
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = DesignationModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "designation_name",
            "type",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new DesignationModel())->getTable() .
                    '.status = "0" THEN "Disabled"
        WHEN ' .
                    DB::getTablePrefix() .
                    (new DesignationModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
        ELSE "Enabled" END) AS status'
            )
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
                return '<a class="editbutton btn btn-default" data-toggle="modal" data="' .
                    $data->id .
                    '" href="' .
                    route("designationcreate", $data->id) .
                    '" ><i class="fa fa-edit"></i></a><a class="editbutton btn btn-default" data="' .
                    $data->id .
                    '" href="' .
                    route("designation_delete", $data->id) .
                    '" ><i class="fa fa-trash"></i></a>';
                //return $data->id;
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->make(true);
    }

    public function designation_delete($id, Request $request)
    {
        if (!empty($request->selected_designation)) {
            $delObj = new DesignationModel();
            foreach ($request->selected_designation as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new DesignationModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->back();
    }

    function statusChangeDesignation(Request $request)
    {
        CGate::authorize("edit-teacher");
        if ($request->ajax()) {
            DesignationModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_designation)) {
            $obj = new DesignationModel();
            foreach ($request->selected_designation as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function Getteachersubjects(Request $request, $id)
    {
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        // $teacher_id=$request->query()

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = SubjectTeacherMapping::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "subject_teachermapping.id",
            "subject_teachermapping.class_id",
            "subject_teachermapping.section_id",
            "subject_teachermapping.teacher_id",
            "subject_teachermapping.subject_id",
            "lclass.name as classname",
            "section.name as sectionname",
            "subject.name as subjectname",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new SubjectTeacherMapping())->getTable() .
                    '.status = "0" THEN "Disabled"
        WHEN ' .
                    DB::getTablePrefix() .
                    (new SubjectTeacherMapping())->getTable() .
                    '.status = "-1" THEN "Trashed"
        ELSE "Enabled" END) AS status'
            )
        )
            ->where("subject_teachermapping.teacher_id", $id)
            ->join(
                "lclass",
                "lclass.id",
                "=",
                "subject_teachermapping.class_id"
            )
            ->join(
                "section",
                "section.id",
                "=",
                "subject_teachermapping.section_id"
            )
            ->join(
                "subject",
                "subject.id",
                "=",
                "subject_teachermapping.subject_id"
            );

        $datatables = Datatables::of($data)->addIndexColumn();

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->make(true);
    }

    public function DeleteAttachmentTeacher(Request $request)
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
                return false;
            }
        }
    }

    public function Bulkupload(Request $request)
    {
        $academic_years = Configurations::getAcademicyears();

        $departments = DepartmentModel::where("status", 1)

            ->pluck("dept_name", "id")
            ->toArray();

        if ($request->isMethod("post")) {
            //dd($request->all());

            $this->validate(
                $request,
                [
                    "upload_file" => "required",
                    "stu_department" => "required",
                ],
                [
                    "upload_file.required" => "Please select CSV File",
                    "stu_department.required" =>
                        "Please Select Atleast One Department",
                ]
            );

            $staff_role = UserGroupModel::where("group", "Teacher")->first();

            if (empty($staff_role)) {
                $message = "Create a Staff and Assigen Permissions";
                $route = route("usergroup.create");
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error_link", $message)
                    ->with("link", $route);
            } else {
                try {
                    // dd("ok");
                    $import = new TeacherImport();

                    $import->department = $request->stu_department;

                    $path = request()
                        ->file("upload_file")
                        ->store("temp");
                    $data = \Excel::import(
                        $import,
                        request()->file("upload_file")
                    );

                    if (
                        $import->getcount() == 0 ||
                        $import->getcount() == null
                    ) {
                        return redirect()
                            ->back()
                            ->withInput()
                            ->with(
                                "exception_error",
                                "This CSV File is Empty || This is Not a Correct Upload Format"
                            );
                    } else {
                        $count = $import->getcount();
                        return redirect()
                            ->route("teacher.index")
                            ->with(
                                "success_default",
                                "$count Staff import Successfully | Now Assigen Designation"
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
        return view("teacher::admin.upload", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "departments" => $departments,
        ]);
    }
}
