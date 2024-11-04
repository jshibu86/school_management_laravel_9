<?php

namespace cms\core\schoolmanagement\Controllers;

use DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use cms\core\schoolmanagement\Models\SchoolmanagementModel;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\core\configurations\helpers\Configurations;
use cms\core\subscription\Models\ModuleModel;
use Illuminate\Support\Facades\Validator;
use cms\core\subscription\Models\SubscriptionModel;
use cms\core\subscription\Models\PlanPriceModel;
use cms\core\schoolmanagement\Models\SchoolProfile;
use cms\core\schoolmanagement\Models\SchoolContact;
use cms\core\schoolmanagement\Models\SchoolPlanPayment;
use Carbon\Carbon;
use cms\core\configurations\Controllers\MailController;
use cms\core\schoolmanagement\Models\SchoolApproval;
use cms\core\user\Models\UserModel;
use cms\core\user\helpers\User;

use Yajra\DataTables\Facades\DataTables;
use Mail;
use cms\core\schoolmanagement\Mail\WelcomeMessageEmail;
use Session;
use CGate;
use App\Jobs\SchoolOnboardJob;
use App\Models\MultiTenant;
use App\Models\DomainModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Cms;

class SchoolmanagementController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    public $detail;

    public function setDetails($key, $value)
    {
        $this->detail[$key] = $value;
    }
    public function index(Request $request)
    {
        \Log::debug("Index Method, All Input:", $request->all());

        $hasApprovalAccess = User::getUser()->approval_process;

        $actvstatus = $request->query->get("all_status");
        $subscstatus = $request->query->get("subscription_status");
        $apprstatus = $request->query->get("approval_status");
        $fromdate = $request->query->get("fromdate");
        $todate = $request->query->get("todate");
        $IsFilterEnable = $request->query->get("filterenable");
        //dd($request);

        $schoolcount = SchoolProfile::count();
        $activecount = SchoolProfile::where("status", 1)->count();
        $inactivecount = SchoolProfile::where("status", 0)->count();

        return view("schoolmanagement::admin.index", [
            "schoolcount" => $schoolcount,
            "activecount" => $activecount,
            "inactivecount" => $inactivecount,
            "actvstatus" => $actvstatus,
            "subscstatus" => $subscstatus,
            "apprstatus" => $apprstatus,
            "fromdate" => $fromdate,
            "todate" => $todate,
            "hasApprovalAccess" => $hasApprovalAccess,
        ]);
    }
    public function revenue()
    {
        return view("schoolmanagement::admin.revenue");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $selectedModuleList = [];
        $planList = SubscriptionModel::where("status", 1)
            ->pluck("plan_name", "id")
            ->toArray();
        $moduleList = ModuleModel::where("status", 1)
            ->pluck("module_name", "id")
            ->toArray();

        return view("schoolmanagement::admin.edit", [
            "layout" => "create",
            "planList" => $planList,
            "moduleList" => $moduleList,
            "selectedModuleList" => $selectedModuleList,
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
        // dd(config("app.env"));
        // dd(Configurations::LOGO_PATH);
        // dd($request->all());
        ini_set("max_execution_time", 600);
        $this->validate(
            $request,
            [
                "email" => ["required", Rule::unique("school_profile")],
                "phoneno" => ["required", Rule::unique("school_profile")],
                "domain" => [
                    "required",
                    'regex:/^[a-zA-Z0-9\-_]+$/',
                    Rule::unique("domains", "tenant_id"),
                ],
                "school_name" => "required",
                "student_count" => "required",
                "billing_cycle" => "required",
                "subscription_plan" => "required",
                "discount" => "required",
                "school_address" => "required",
                "school_city" => "required",
                "postal_code" => "required",
                "school_country" => "nullable",
                "first_name" => "required",
                "last_name" => "required",
                "contact_person_email" => [
                    "required",
                    Rule::unique("school_contact", "email"),
                ],
                "contact_person_phoneno" => [
                    "required",
                    Rule::unique("school_contact", "phoneno"),
                ],
                "contact_person_gender" => "required",
                "contact_person_address" => "required",
                "contact_person_city" => "required",
                "contact_person_postcode" => "required",
                "contact_person_country" => "required",
            ],
            [
                "email.required" => "The email field is required.",
                "email.unique" => "This email is already taken.",

                "phoneno.required" => "The phone number field is required.",
                "phoneno.unique" => "This phone number is already taken.",

                "domain.required" => "The domain field is required.",
                "domain.regex" =>
                    "The domain can only contain letters, numbers, dashes, and underscores.",
                "domain.unique" => "This domain is already in use.",

                "school_name.required" => "The school name field is required.",
                "student_count.required" =>
                    "The student count field is required.",
                "billing_cycle.required" =>
                    "The billing cycle field is required.",
                "subscription_plan.required" =>
                    "The subscription plan field is required.",
                "discount.required" => "The discount field is required.",
                "school_address.required" =>
                    "The school address field is required.",
                "school_city.required" => "The city field is required.",
                "postal_code.required" => "The postal code field is required.",
                "school_country.nullable" => "The country field is optional.",

                "first_name.required" => "The first name field is required.",
                "last_name.required" => "The last name field is required.",

                "contact_person_email.required" =>
                    'The contact person\'s email is required.',
                "contact_person_email.unique" =>
                    'The contact person\'s email is already in use.',

                "contact_person_phoneno.required" =>
                    'The contact person\'s phone number is required.',
                "contact_person_phoneno.unique" =>
                    'The contact person\'s phone number is already in use.',

                "contact_person_gender.required" =>
                    'The contact person\'s gender is required.',
                "contact_person_address.required" =>
                    'The contact person\'s address is required.',
                "contact_person_city.required" =>
                    'The contact person\'s city is required.',
                "contact_person_postcode.required" =>
                    'The contact person\'s postcode is required.',
                "contact_person_country.required" =>
                    'The contact person\'s country is required.',
            ]
        );

        //dd($validator);
        //DB::beginTransaction();
        $tenant = null;
        try {
            $schoolProfileDB = new SchoolProfile();
            $schoolProfileDB->school_name =
                $request->school_name . "-" . $request->school_name_abbr;
            $schoolProfileDB->email = $request->school_email;
            $schoolProfileDB->phoneno = $request->school_phoneno;
            $schoolProfileDB->student_count = $request->student_count;
            $schoolProfileDB->plan_id = $request->subscription_plan;
            $schoolProfileDB->billing_id = $request->billing_cycle;
            $schoolProfileDB->discount = $request->discount;
            $schoolProfileDB->address = $request->school_address;
            $schoolProfileDB->city = $request->school_city;
            $schoolProfileDB->pincode = $request->postal_code;
            $schoolProfileDB->country = $request->school_country;
            $timezone = Configurations::TIMEZONES["default"];
            $currentDate = Carbon::now()->setTimezone($timezone);
            $schoolProfileDB->join_date = $currentDate;
            $schoolProfileDB->reg_no = SchoolmanagementController::generateRegisterNumber();

            //save photo
            if ($request->photo) {
                $schoolProfileDB->image = $this->uploadImage(
                    $request->photo,
                    "image"
                );
            }

            if ($schoolProfileDB->save()) {
                $filterList = PlanPriceModel::where(
                    "plan_id",
                    $schoolProfileDB->plan_id
                )
                    ->get("modules")
                    ->first();
                $modulesArray = json_decode($filterList->modules, true);
                $moduleList = ModuleModel::where("status", 1)
                    ->whereIn("id", $modulesArray)
                    ->pluck("module_name")
                    ->toArray();
                Session::put(["module_list" => $moduleList]);

                // $allmodules = Cms::allModules();
                // foreach ($allmodules as $key => $value) {
                //     if (!in_array($value["name"], Session("module_list"))) {
                //         unset($allmodules[$key]);
                //     }
                // }
                // dd($allmodules, Session("module_list"));
                $schoolContactDB = new SchoolContact();
                $schoolContactDB->school_id = $schoolProfileDB->id;
                $schoolContactDB->first_name = $request->first_name;
                $schoolContactDB->last_name = $request->last_name;
                $schoolContactDB->email = $request->contact_person_email;
                $schoolContactDB->phoneno = $request->contact_person_phoneno;
                $schoolContactDB->role = $request->contact_person_role;
                $schoolContactDB->gender = $request->contact_person_gender;
                $schoolContactDB->address = $request->contact_person_address;
                $schoolContactDB->city = $request->contact_person_city;
                $schoolContactDB->pincode = $request->contact_person_postcode;
                $schoolContactDB->country = $request->contact_person_country;
                $schoolContactDB->save();
            }
            if ($schoolContactDB->save()) {
                $schoolPlanPayDB = new SchoolPlanPayment();
                $schoolPlanPayDB->school_id = $schoolProfileDB->id;
                $schoolPlanPayDB->bill_amount = $request->sub_total;
                $schoolPlanPayDB->due_amount = $request->total_due;
                $schoolPlanPayDB->discount = $request->discount;
                $schoolPlanPayDB->save();
            }

            $sendersemailID = $request->school_email;
            $senderName = $request->first_name;

            $mailRequest = new MailController();
            $mailRequest->sendWelcomeMessageEmail($request);
            $schoolID = $schoolProfileDB->id;
            $this->requestOnboarding($schoolID);

            $tenantId = Str::uuid();
            $tenant = MultiTenant::create([
                "tenant_username" => $request->domain,
            ]);

            if ($tenant) {
                $schoolProfileDB->tenant_id = $tenant->id;
                $schoolProfileDB->save();
            }

            $tenant->domains()->create([
                "domain" => $request->domain . "." . config("app.domain"),
            ]);
            $domain_name = $request->domain . "." . config("app.domain");
            $domain = DomainModel::where("domain", $domain_name)->first();

            // $tenantData = [];
            // $tenantData["tenancy_db_name"] = $tenant->tenancy_db_name;
            $tenancy_db_password =
                $request->db_password != null
                    ? bcrypt($request->db_password)
                    : null;
            $tenancy_db_user = $request->db_username
                ? $request->db_username
                : "root";

            if ($tenancy_db_password && $tenancy_db_user) {
                $tenant->update([
                    "tenancy_db_password" => $tenancy_db_password,
                    "tenancy_db_user" => $tenancy_db_user,
                ]);
            }

            dispatch(
                new SchoolOnboardJob(
                    $tenant,
                    $schoolContactDB,
                    $schoolProfileDB
                )
            );

            Session::flash(
                "success",
                "Onboarding request sent to super admins"
            );
            Session::flash("email_success", "Email sent to your emailID");
            return redirect()->route("schoolmanagement.index");
        } catch (\Exception $e) {
            // if (DB::transactionLevel() > 0) {
            //     DB::rollback();
            // }
            // dd($e);
            if ($tenant) {
                $tenant->domains()->delete();
                $tenant->delete();
            }

            if (isset($schoolProfileDB->id)) {
                SchoolProfile::where("id", $schoolProfileDB->id)->delete();
            }

            if (isset($schoolContactDB->id)) {
                SchoolContact::where("id", $schoolContactDB->id)->delete();
            }

            if (isset($schoolPlanPayDB->id)) {
                SchoolPlanPayment::where("id", $schoolPlanPayDB->id)->delete();
            }
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            // dd($message);
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

    public function requestOnboarding($schoolid)
    {
        //\Log::debug("requestOnboarding Method called, Parameter:");
        // Get the list of super admins

        $superAdmins = UserModel::where("approval_process", "1")->get();

        // Create approval requests for each super admin
        foreach ($superAdmins as $admin) {
            $userid = $admin->id;

            SchoolApproval::create([
                "school_id" => $schoolid,
                "user_id" => $userid,
            ]);
        }
        //dd("end of request");
    }

    public function show($id)
    {
        $data = SchoolProfile::with("contacts")->find($id);

        $planList = SubscriptionModel::where("status", 1)
            ->pluck("plan_name", "id")
            ->toArray();
        $moduleList = ModuleModel::where("status", 1)
            ->pluck("module_name", "id")
            ->toArray();
        $mdata = PlanPriceModel::where("plan_id", $data->plan_id)
            ->get()
            ->first();
        if ($data) {
            $selectedModuleList = json_decode($mdata->modules, true);
        }
        return view("schoolmanagement::admin.show", [
            "layout" => "edit",
            "data" => $data,
            "planList" => $planList,
            "moduleList" => $moduleList,
            "selectedModuleList" => $selectedModuleList,
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
        $moduleList = [];

        $data = SchoolProfile::with("contacts")->find($id);
        $cdata = SchoolContact::where("school_id", $data->id)
            ->get()
            ->first();
        $planList = SubscriptionModel::where("status", 1)
            ->pluck("plan_name", "id")
            ->toArray();
        $moduleList = ModuleModel::where("status", 1)
            ->pluck("module_name", "id")
            ->toArray();
        $mdata = PlanPriceModel::where("plan_id", $data->plan_id)
            ->get()
            ->first();
        if ($data) {
            $selectedModuleList = json_decode($mdata->modules, true);
        }
        //dd($selectedModuleList);
        return view("schoolmanagement::admin.edit", [
            "layout" => "edit",
            "cdata" => $cdata,
            "data" => $data,
            "planList" => $planList,
            "moduleList" => $moduleList,
            "selectedModuleList" => $selectedModuleList,
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
        // $this->validate($request,[
        //     'name' => 'required|min:3|max:50|unique:'.(new SchoolmanagementModel())->getTable().',name,'.$id,
        //     'desc' => 'required|min:3|max:190',
        //     'status' => 'required',
        // ]);
        $schoolProfileDB = SchoolProfile::with("contacts")->find($id);

        DB::beginTransaction();
        try {
            $schoolProfileDB->school_name =
                $request->school_name . "-" . $request->school_name_abbr;
            $schoolProfileDB->email = $request->school_email;
            $schoolProfileDB->phoneno = $request->school_phoneno;
            $schoolProfileDB->student_count = $request->student_count;
            $schoolProfileDB->plan_id = $request->subscription_plan;
            $schoolProfileDB->billing_id = $request->billing_cycle;
            $schoolProfileDB->discount = $request->discount;
            $schoolProfileDB->address = $request->school_address;
            $schoolProfileDB->city = $request->school_city;
            $schoolProfileDB->pincode = $request->postal_code;
            $schoolProfileDB->country = $request->school_country;
            // $schoolProfileDB->join_date = Carbon::now()->format('d/m/Y');
            // $schoolProfileDB->reg_no = SchoolmanagementController::generateRegisterNumber();

            //save photo
            if ($request->photo) {
                $schoolProfileDB->image = $this->uploadImage(
                    $request->photo,
                    "image"
                );
            }

            if ($schoolProfileDB->save()) {
                $schoolContactDB = new SchoolContact();
                $schoolContactDB->school_id = $schoolProfileDB->id;
                $schoolContactDB->first_name = $request->first_name;
                $schoolContactDB->last_name = $request->last_name;
                $schoolContactDB->email = $request->contact_person_email;
                $schoolContactDB->phoneno = $request->contact_person_phoneno;
                $schoolContactDB->role = $request->contact_person_role;
                $schoolContactDB->gender = $request->contact_person_gender;
                $schoolContactDB->address = $request->contact_person_address;
                $schoolContactDB->city = $request->contact_person_city;
                $schoolContactDB->pincode = $request->contact_person_postcode;
                $schoolContactDB->country = $request->contact_person_country;
                $schoolContactDB->save();
            }
            //  dd($request);
            DB::commit();
            Session::flash("success", "Updated successfully");
            return redirect()->route("schoolmanagement.index");
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
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
        if ($id) {
            // delete the primary key exist in the subscription table
            SchoolProfile::where("id", $id)->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("schoolmanagement.index");
    }
    /*
     * get data
     */

    public function getData(Request $request)
    {
        //\Log::debug("New Data Request:", $request->all());

        // CGate::authorize('view-subscription');
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = SchoolProfile::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "school_profile.id as id",
            "school_profile.reg_no as reg_no",
            DB::raw(
                "SUBSTRING_INDEX(school_profile.school_name, '-', 1) as school_name"
            ),
            "school_profile.phoneno as phoneno",
            "school_profile.student_count as student_count",
            "school_profile.billing_id as billing_id",
            DB::raw(
                "DATE_FORMAT(school_profile.created_at, '%e %b %Y %h:%i %p')as join_date"
            ),
            "school_profile.status as status",
            "school_profile.image as pimage",
            "school_profile.subscribe_status as subscribe_status",
            "school_profile.approval_status as approval_status",
            "subscription_plan.plan_name as plan_name",
            "school_profile.tenant_id as tenant",
            "domains.domain as domain"
        )
            ->leftJoin(
                "subscription_plan",
                "subscription_plan.id",
                "=",
                "school_profile.plan_id"
            )
            ->leftJoin(
                "domains",
                "domains.tenant_id",
                "=",
                "school_profile.tenant_id"
            )
            ->orderBy("school_profile.created_at", "desc");

        $status = $request->get("actvstatus");
        $subscstatus = $request->get("subscstatus");
        $apprstatus = $request->get("approval_status");
        $fromdate = $request->get("fromdate");
        $todate = $request->get("todate");

        if ($status != "null" && $status != null) {
            $data = $data->where("school_profile.status", $status);
        }

        $datatables = Datatables::of($data)
            ->addColumn("pimage", function ($data) {
                if ($data->pimage != null) {
                    $url = asset($data->pimage);
                    return '<img src="' .
                        $url .
                        '" border="0" width="40"  class="img-rounded" align="center" />';
                } else {
                    $url = asset("assets/images/default.jpg");
                    return '<img src="' .
                        $url .
                        '" border="0" width="40" class="img-rounded" align="center" />';
                }
            })
            ->addColumn("subscribe_status", function ($data) {
                $status = strtolower($data->subscribe_status);
                switch ($status) {
                    case "0":
                        return '<span class="text-danger"> Not Approved </span>';
                    case "1":
                        return '<span class="text-success">Subscribed</span>';
                    case "2":
                        return '<span class="text-warning"><b>Soon Expired</b></span>';
                    case "3":
                        return '<span class="text-danger">Privilege/Expired</span>';
                    default:
                        return '<span class="text-danger">Unknown</span>';
                }
            })
            ->addColumn("billing_id", function ($data) {
                $status = strtolower($data->billing_id);
                switch ($status) {
                    case "1":
                        return '<span class=""> Term </span>';
                    case "2":
                        return '<span class=""> Session </span>';
                    case "3":
                        return '<span class="bg-white"> Nil </span>';
                }
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "id" => $data->id,
                    "route" => "schoolmanagement",
                    "showEdit" => true,
                    "showDelete" => true,
                    "showView" => true,
                    "editRoute" => "schoolmanagement.edit",
                    "deleteRoute" => "schoolmanagement.destroy",
                    "viewRoute" => "schoolmanagement.show",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns([
                "pimage",
                "status",
                "billing_id",
                "subscribe_status",
                "action",
            ])
            ->make(true);
    }
    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        //CGate::authorize("edit-students");
        if ($request->ajax()) {
            $data = SchoolProfile::find($request->id);
            if ($data) {
                $data->update([
                    "status" => $request->status,
                ]);
            }

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

    function filterModuleList(Request $request)
    {
        $planId = $request->input("plan_id");
        $filterList = PlanPriceModel::where("plan_id", $planId)
            ->get("modules")
            ->first();

        $moduleList = ModuleModel::where("status", 1)
            ->get(["id", "module_name"])
            ->map(function ($module) {
                return [
                    "id" => $module->id,
                    "module_name" => $module->module_name,
                ];
            })
            ->toArray();
        $filteredModuleList = $filterList->modules ?? [];
        return json_encode([
            "moduleList" => $moduleList,
            "filteredModuleList" => $filteredModuleList,
        ]);
    }

    // method calling via axios request on clicking confirm/deny button
    public function onboardApproval(Request $request)
    {
        $school_id = $request->input("schoolId");
        $action = $request->input("action");
        $superAdminId = User::getUser()->id; // The currently logged-in super admin

        // Find the approval record for this super admin
        $approval = SchoolApproval::where("school_id", $school_id)
            ->where("user_id", $superAdminId)
            ->firstOrFail();

        // Update the approval status (approve or deny)
        $approval->status =
            $request->input("action") == "approve" ? "approved" : "denied";
        $approval->save();

        // Check if all super admins have responded
        $pendingApprovals = SchoolApproval::where("school_id", $school_id)
            ->whereIn("status", ["pending", "denied"])
            ->count();

        if ($pendingApprovals == 0) {
            // If no pending approvals, finalize the onboarding status
            $this->finalizeApproval($school_id);
            $schoolData = SchoolProfile::find($school_id);
            $schoolName = $schoolData->school_name;
            $schoolEmail = $schoolData->email;

            $mailRequest = new MailController();
            $mailRequest->sendOnboardNotificationEmail(
                $schoolName,
                $schoolEmail
            );
            Session::flash(
                "success",
                "School onboarding completed successfully. Email notification sent to the school."
            );
            return response()->json([
                "success" => true,
                "message" => "Approval Updated",
                "redirect" => route("schoolmanagement.index"),
            ]);
        } else {
            Session::flash("success", "Status Updated");
            return response()->json([
                "success" => true,
                "message" => "Approval Updated",
                "redirect" => route("schoolmanagement.index"),
            ]);
        }
    }

    public function finalizeApproval($school_id)
    {
        $approvedCount = SchoolApproval::where("school_id", $school_id)
            ->where("status", "approved")
            ->count();
        $superAdmins = UserModel::where("approval_process", "1")->count();

        // Check if all approval access admin users approved
        if ($approvedCount == $superAdmins) {
            // Mark the school as approved
            $school = SchoolProfile::find($school_id);
            $school->approval_status = "1";
            $school->save();

            // Notify the school of successful onboarding
        } else {
            // If not all approved, mark the process as denied
            $school = SchoolProfile::find($school_id);
            $school->approval_status = "0";
            $school->save();

            // Notify the school of denial
        }
    }

    public function approveSchool(Request $request)
    {
        $school_id = $request->input("schoolId");

        $school = SchoolProfile::find($school_id);

        if (!$school) {
            return response()->json(
                ["success" => false, "message" => "School not found"],
                404
            );
        }

        //currently logged in user
        $loggedUserid = User::getUser()->id;

        // users only having approval access using get method
        $approved_access_users_get = UserModel::where(
            "approval_process",
            1
        )->get();

        // check whether admin user got new approval access to approve school
        foreach ($approved_access_users_get as $admin) {
            $userid = $admin->id;
            $existingApproval = SchoolApproval::where("school_id", $school->id)
                ->where("user_id", $userid)
                ->first();

            // create records for the new admin user
            if (!$existingApproval) {
                SchoolApproval::create([
                    "school_id" => $school->id,
                    "user_id" => $userid,
                ]);
            }
        }

        $loggedUser = UserModel::where("id", $loggedUserid)->first();

        $approved_access_users_get = $approved_access_users_get->where(
            "id",
            "!=",
            $loggedUserid
        );

        $approved_access_users_get->prepend($loggedUser);

        \Log::debug("school_information:", [
            "school_information" => $school,
        ]);
        $view = view("schoolmanagement::admin.components.approvel", [
            "school_information" => $school,
            "approved_access_users_get" => $approved_access_users_get,
            "loggedUserid" => $loggedUserid,
        ])->render();

        return response()->json(["view" => $view, "success" => true]);
    }

    function paymentCalculation(Request $request)
    {
        $billingID = $request->input("billing_id");
        $planID = $request->input("plan_id");
        $student_count = (int) $request->input("student_count");
        $discount = (int) $request->input("discount");
        $column = $billingID == "1" ? "term_amount" : "session_amount";

        $termValues = PlanPriceModel::where("plan_id", $planID)
            ->get(["id", $column])
            ->toArray();
        $termAmount = !empty($termValues) ? $termValues[0][$column] : 0;

        // calculate the subtotal using term amount multiply with no. of students
        $subTotal = $student_count * $termAmount;

        // calculate the  due amount by subtracting the discount from total
        $totalDue = $subTotal - ($subTotal * $discount) / 100;

        \Log::debug("Term Value and Total Due:", [
            "termAmount" => $termAmount,
            "totalDue" => $totalDue,
        ]);
        \Log::debug("Sub Total:", [
            "subTotal" => Configurations::CurrencyFormat($subTotal),
        ]);

        return response()->json([
            "subTotal" => Configurations::CurrencyFormat($subTotal),
            "totalDue" => Configurations::CurrencyFormat($totalDue),
            "formatsubTotal" => $subTotal,
            "formattotalDue" => $totalDue,
        ]);
    }

    function generateRegisterNumber()
    {
        $lastSchool = SchoolProfile::orderBy("reg_no", "desc")->first();
        if ($lastSchool) {
            $lastNumber = (int) substr($lastSchool->reg_no, 2);
        } else {
            $lastNumber = 0;
        }

        $newNumber = $lastNumber + 1;
        $formattedNumber = "SM" . str_pad($newNumber, 5, "0", STR_PAD_LEFT);

        return $formattedNumber;
    }
}
