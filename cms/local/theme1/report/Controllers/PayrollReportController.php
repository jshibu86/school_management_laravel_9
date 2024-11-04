<?php
namespace cms\report\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\leave\Models\LeaveModel;
use cms\payrool\Controllers\PayroolController;
use cms\payrool\Models\PayrollModel;
use cms\payrool\Models\SaleryPayrollPayment;
use Configurations;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use CGate;
class PayrollReportController extends Controller
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

        // total leave applied

        $leave = LeaveModel::where("group_id", Configurations::TEACHER)->get();

        [
            $date,
            $time,
            $currentMonth,
            $currentYear,
            $current_day,
            $dayOfWeek,
        ] = Configurations::getcurrentDateTime();

        $total_amount = SaleryPayrollPayment::where([
            "academic_year" => Configurations::getCurrentAcademicyear(),
            "month" => $currentMonth,
            "year" => $currentYear,
        ])->sum("net_salery");

        $total_count = SaleryPayrollPayment::where([
            "academic_year" => Configurations::getCurrentAcademicyear(),
            "month" => $currentMonth,
            "year" => $currentYear,
        ])->count();

        $assigned_user_group = PayrollModel::groupBy("group_id")
            ->pluck("group_id")
            ->toArray();

        $user_group = UserGroupModel::where("status", 1)
            ->whereIn("id", $assigned_user_group)
            ->pluck("group", "id")
            ->toArray();

        //dd($leave);

        if ($request->ajax()) {
            $academic_year = $request->query->get(
                "acyear",
                Configurations::getCurrentAcademicyear()
            );
            $initial = $request->query->get("initial", 0);
            $user_group = $request->query->get("user_group", 0);
            $member_id = $request->query->get("member_id", 0);
            $month = $request->query->get("month", 0);

            if ($initial) {
                $data = SaleryPayrollPayment::query();

                $datatables = $data
                    ->with("academicyear")
                    ->select(
                        DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                        "salery_payroll_payment.id as id",
                        "salery_payroll_payment.academic_year",

                        "users.name as name",
                        "user_groups.group as group",
                        "salery_payroll_payment.month as month",
                        "salery_payroll_payment.year as year",
                        "salery_payroll_payment.net_salery",

                        "salery_payroll_payment.group_id as group_id"
                    )

                    ->leftjoin(
                        "users",
                        "users.id",
                        "=",
                        "salery_payroll_payment.user_id"
                    )
                    ->join(
                        "user_groups",
                        "user_groups.id",
                        "=",
                        "salery_payroll_payment.group_id"
                    );

                $datatables = Datatables::of($data)->addIndexColumn();

                //return $status_;
                // if ($status_) {
                //     return $status_;
                // }
                if (count((array) $data) == 0) {
                    return [];
                }

                return $datatables
                    ->rawColumns(["applicantname", "status"])
                    ->make(true);
            }

            // gettign studentsfrom userid
            // return $students;

            $data = SaleryPayrollPayment::query();

            $datatables = $data
                ->with("academicyear")
                ->when($user_group, function ($q) use ($user_group) {
                    $q->where("group_id", $user_group);
                })
                ->when($member_id, function ($q) use ($member_id) {
                    $q->where("user_id", $member_id);
                })

                ->when($month, function ($q) use ($month) {
                    $q->where("month", explode(" ", $month)[0])->where(
                        "year",
                        explode(" ", $month)[1]
                    );
                })
                ->select(
                    DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                    "salery_payroll_payment.id as id",
                    "salery_payroll_payment.academic_year",

                    "users.name as name",
                    "user_groups.group as group",
                    "salery_payroll_payment.month as month",
                    "salery_payroll_payment.year as year",
                    "salery_payroll_payment.net_salery",

                    "salery_payroll_payment.group_id as group_id"
                )

                ->leftjoin(
                    "users",
                    "users.id",
                    "=",
                    "salery_payroll_payment.user_id"
                )
                ->join(
                    "user_groups",
                    "user_groups.id",
                    "=",
                    "salery_payroll_payment.group_id"
                );

            $datatables = Datatables::of($data)
                ->addIndexColumn()
                ->addColumn("applicantname", function ($data) {
                    return "<div class='d-flex appname'><span>" .
                        $data->name .
                        "</span><span class='badge bg-primary'>" .
                        $data->group .
                        "</span></div>";
                })
                ->addColumn("fromto", function ($data) {
                    return $data->from . "-" . $data->to;
                })
                ->addColumn("status", function ($data) {
                    if ($data->leavestatus == -1) {
                        return "<span class='badge bg-danger'>Rejected</span>";
                    } elseif ($data->leavestatus == 1) {
                        return "<span class='badge bg-success'>Approved</span>";
                    } else {
                        return "<span class='badge bg-warning'>Pending</span>";
                    }
                });

            //return $status_;
            // if ($status_) {
            //     return $status_;
            // }
            if (count((array) $data) == 0) {
                return [];
            }

            return $datatables
                ->rawColumns(["applicantname", "status"])
                ->make(true);
        }

        return view(
            "report::admin.report.payroll.payrollreport",
            compact(
                "academicyears",
                "class_lists",
                "sections",
                "school_type_info",
                "leave",
                "currentMonth",
                "currentYear",
                "total_amount",
                "user_group",
                "total_count"
            )
        );
        dd("leave");
    }

    public function payrolltotalamount(Request $request)
    {
        $academic_year = $request->query->get(
            "acyear",
            Configurations::getCurrentAcademicyear()
        );
        $initial = $request->query->get("initial", 0);
        $user_group = $request->query->get("user_group", 0);
        $month = $request->query->get("month", 0);

        $total_amount = SaleryPayrollPayment::where([
            "academic_year" => Configurations::getCurrentAcademicyear(),
            "month" => explode(" ", $month)[0],
            "year" => explode(" ", $month)[1],
        ])->sum("net_salery");

        $total_count = SaleryPayrollPayment::where([
            "academic_year" => Configurations::getCurrentAcademicyear(),
            "month" => explode(" ", $month)[0],
            "year" => explode(" ", $month)[1],
        ])->count();

        return response()->json([
            "totalamount" => Configurations::CurrencyFormat($total_amount),
            "month" => explode(" ", $month)[0],
            "total_count" => $total_count,
        ]);
    }

    public function payrollbulkprint(Request $request)
    {
        //dd($request->all());
        if ($request->isMethod("post")) {
            $this->validate($request, [
                "group_id" => "required",
                "month" => "required",
            ]);

            $group_id = $request->group_id;
            $selectedMemberIds = $request->input("member_id");
            // dd($selectedMemberIds[0]);
            $month = explode(" ", $request->month)[0];
            $year = explode(" ", $request->month)[1];

            $user_ids = UserGroupMapModel::where(
                "group_id",
                $request->group_id
            )->pluck("user_id");

            $users = [];

            $users_data = UserModel::with([
                "salerypayrollpayment" => function ($q) use ($month, $year) {
                    $q->where(["month" => $month, "year" => $year]);
                },
            ])
                ->where("status", 1)
                ->whereNull("deleted_at")
                ->whereIn("id", $user_ids)

                ->get();

            $config = Configurations::getConfig("site");
            $host = request()->getHttpHost();

            if ($group_id && $selectedMemberIds[0] != 0 && $month) {
                $payment_added = SaleryPayrollPayment::where("month", $month)
                    ->where("year", $year)
                    ->whereIn("user_id", $selectedMemberIds)
                    ->count();

                if ($payment_added) {
                    return view("payrool::admin.includes.payslipbulkprint", [
                        "config" => $config,
                        "users_data" => $users_data,
                        "view" => true,
                        "grade" => [],
                        "current_url" => $host,
                        "data" => $selectedMemberIds,
                    ]);
                }
            } else {
                if ($selectedMemberIds[0] == 0) {
                    $payment_added = SaleryPayrollPayment::where([
                        "month" => $month,
                        "year" => $year,
                    ])
                        ->whereIn("user_id", $user_ids)
                        ->count();

                    if ($payment_added) {
                        return view(
                            "payrool::admin.includes.payslipbulkprint",
                            [
                                "config" => $config,
                                "users_data" => $users_data,
                                "view" => true,
                                "grade" => [],

                                "current_url" => $host,
                                "data" => [],
                            ]
                        );
                    }
                }
            }

            if (!$payment_added) {
                return redirect()
                    ->back()
                    ->with("error", "No Payroll Payment Added");
            }
        }

        $payroll = new PayroolController();
        return $payroll->PaymentHistory($request, "report");
    }
}
