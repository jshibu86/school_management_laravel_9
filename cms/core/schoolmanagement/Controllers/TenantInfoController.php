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

class TenantInfoController extends Controller
{
    use FileUploadTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected MailController $mailController;

    public function index()
    {
        return view("schoolmanagement::admin.tenant_info.index");
    }

    public function create()
    {
        return view("tenants.create");
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
        ]);

        TenantInformation::create($request->all());
        return redirect()
            ->route("tenants.index")
            ->with("success", "Tenant created successfully.");
    }

    public function show($id)
    {
        $school_profile = SchoolProfile::with("plan_payment")->find($id);
        // dd($school_profile);
        $school_profile->plan_payment->bill_amount = Configurations::CurrencyFormat(
            $school_profile->plan_payment->bill_amount
        );
        $school_profile->plan_payment->due_amount = Configurations::CurrencyFormat(
            $school_profile->plan_payment->due_amount
        );
        $planinfo = SubscriptionModel::with("plan_price_info")
            ->where("id", $school_profile->plan_id)
            ->first();
        $planinfo->plan_price_info->term_amount = Configurations::CurrencyFormat(
            $planinfo->plan_price_info->term_amount
        );
        $planinfo->plan_price_info->session_amount = Configurations::CurrencyFormat(
            $planinfo->plan_price_info->session_amount
        );
        $planinfo->plan_type =
            Configurations::SUBSCRIPTIONPLANTYPES[$school_profile->billing_id];

        $moduleIds = json_decode($planinfo->plan_price_info->modules, true);
        $moduleList = ModuleModel::where("status", 1)
            ->pluck("module_name", "id")
            ->toArray();

        return view(
            "schoolmanagement::admin.tenant_info.plan_info",
            compact("moduleList", "planinfo", "school_profile", "moduleIds")
        );
    }

    public function edit($id)
    {
        $tenant = TenantInformation::find($id);
        return view("tenants.edit", compact("tenant"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required",
        ]);

        $tenant = TenantInformation::find($id);
        $tenant->update($request->all());

        return redirect()
            ->route("tenants.index")
            ->with("success", "Tenant updated successfully.");
    }

    public function destroy($id)
    {
        $tenant = TenantInformation::find($id);
        $tenant->delete();

        return redirect()
            ->route("tenants.index")
            ->with("success", "Tenant deleted successfully.");
    }

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
            "domains.domain as domain",
            "tenants.data as database_data"
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
            ->leftJoin(
                "tenants",
                "tenants.id",
                "=",
                "school_profile.tenant_id"
            );

        $status = $request->get("actvstatus");
        $subscstatus = $request->get("subscstatus");
        $apprstatus = $request->get("approval_status");
        $fromdate = $request->get("fromdate");
        $todate = $request->get("todate");

        if ($status != "null" && $status != null) {
            $data = $data->where("school_profile.status", $status);
        }

        $datatables = Datatables::of($data)
            ->addColumn("database_name", function ($data) {
                $db_data = json_decode($data->database_data);
                $name = $db_data->tenancy_db_name
                    ? $db_data->tenancy_db_name
                    : "NA";
                return $name;
            })
            ->addColumn("domain", function ($data) {
                $env = config("app.env");
                if ($env == "local") {
                    $url = "http://" . $data->domain . ":8000/";
                } else {
                    $url = "https://" . $data->domain;
                }
                return '<a href="' .
                    $url .
                    '" target="_blank">' .
                    $data->domain .
                    "</a>";
            })

            ->addColumn("action", function ($data) {
                return '<a href="' .
                    route("tenant_info.show", $data->id) .
                    '" class="btn btn-primary text-center" id="plan_info_btn">Plan Info</a>';
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->addIndexColumn()
            ->rawColumns(["status", "action", "domain"])
            ->make(true);
    }
}
