<?php

namespace cms\account\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\account\Models\AccountModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\account\Models\IncomeExpenseModel;
use cms\fees\Models\FeesModel;
use Configurations;
use Carbon\Carbon;
use cms\academicyear\Models\AcademicyearModel;
use cms\fees\Models\SchoolTypeModel;
use cms\lclass\Models\LclassModel;
use cms\payrool\Models\SaleryPayrollPayment;
use cms\section\Models\SectionModel;
use cms\shop\Models\OrderModel;

class AccountController extends Controller
{
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
        // income expense calculation
        $type = $request->query->get("type", "year");
        $day = $request->query->get("day", null);
        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $academic_year = $request->query->get(
            "academic_year",
            Configurations::getCurrentAcademicyear()
        );
        $incomeexpense = [];
        $names = ["income", "expense", "profit", "balance"];

        [
            $months_half,
            $months_full,
            $yearsMonths,
        ] = Configurations::GetMonthsOfAcademicYear($academic_year, null, true);

        $income_data = [];
        $expense_data = [];
        $profit_data = [];

        foreach ($yearsMonths as $monthfull) {
            # code...
            $income_expense = IncomeExpenseModel::where("status", 1)
                ->where("academic_year", $academic_year)
                ->where("month", $monthfull["month"])
                ->where("year", $monthfull["year"]);
            $fees = FeesModel::where("academic_year", $academic_year)
                ->where("payment_month", $monthfull["month"])
                ->where("payment_year", $monthfull["year"])
                ->sum("paid_amount");
            $income = clone $income_expense;
            $income = $income->where("type", "income")->sum("amount");
            $expense = clone $income_expense;
            $expense = $expense->where("type", "expense")->sum("amount");

            $total_income_data = $income + $fees;
            $income_data[] = $total_income_data;
            $expense_data[] = $expense;
            $profit_data[] = $total_income_data - $expense;
        }

        if ($request->ajax()) {
            if ($request->query->get("type") == "day") {
                // current daay
                $parsedate = Carbon::parse($day)->format("m/d/Y");

                $income_expense_today = IncomeExpenseModel::where(
                    "status",
                    1
                )->where("entry_date", $parsedate);

                $fees = FeesModel::where("payment_date", $day)->sum(
                    "paid_amount"
                );

                $income = clone $income_expense_today;
                $income = $income->where("type", "income")->sum("amount");
                $expense = clone $income_expense_today;
                $expense = $expense->where("type", "expense")->sum("amount");

                $total_income_data = $income + $fees;
                $income_data = [$total_income_data];
                $expense_data = [$expense];
                $profit_data = [$total_income_data - $expense];

                return response()->json([
                    "income_data" => $income_data,
                    "expense_data" => $expense_data,
                    "profit_data" => $profit_data,
                    "month" => [$day],
                ]);
            } else {
                return response()->json([
                    "income_data" => $income_data,
                    "expense_data" => $expense_data,
                    "profit_data" => $profit_data,
                    "month" => $months_half,
                ]);
            }
        }

        //dd($income_data, $expense_data, $profit_data);

        foreach ($names as $name) {
            if (!isset($incomeexpense[$name])) {
                $incomeexpense[$name] = new \stdClass();

                $incomeexpense[$name]->name = $name;
                $income_expense = IncomeExpenseModel::where("status", 1)->where(
                    "academic_year",
                    $academic_year
                );
                $fees = FeesModel::where("academic_year", $academic_year)->sum(
                    "paid_amount"
                );

                if ($name == "income") {
                    // income
                    $total_income = $income_expense
                        ->where("type", "income")
                        ->sum("amount");
                    $incomeexpense[$name]->income = $total_income + $fees;
                    $incomeexpense[$name]->fees = $fees;
                } elseif ($name == "expense") {
                    // expense
                    $total_expense = $income_expense
                        ->where("type", "expense")
                        ->sum("amount");
                    $incomeexpense[$name]->expense = $total_expense;
                } elseif ($name == "profit") {
                    // profit
                    $incomeexpense[$name]->profit =
                        $total_income + $fees - $total_expense;
                } else {
                    // balance
                    $incomeexpense[$name]->balance = 0;
                }
            }
        }

        //dd($incomeexpense);

        // for category wise data expense
        $income_category = IncomeExpenseModel::select(
            "income_expense.id",
            "income_expense_category.category_name",
            DB::raw("SUM(amount) as total_amount")
        )
            ->where("income_expense.type", "income")
            ->join(
                "income_expense_category",
                "income_expense_category.id",
                "=",
                "income_expense.category_id"
            )
            ->where("academic_year", $academic_year)
            ->groupBy("category_id")
            ->get();

        $category_data = [];
        $category_name = [];
        $category_income = [];

        foreach ($income_category as $categorydata) {
            $category_data[] = $categorydata->total_amount;
            $category_name[] = $categorydata->category_name;
            $category_income[] = [
                "id" => $categorydata->id,
                "category_name" => $categorydata->category_name,
                "total_amount" => $categorydata->total_amount,
            ];
            # code...
        }
        $merged_category_name = array_merge($category_name, ["Fee Collection"]);
        $merged_category_data = array_merge($category_data, [
            (int) $incomeexpense["income"]->fees,
        ]);

        $category_income[] = [
            "id" => 0,
            "category_name" => "Fee Collection",
            "total_amount" => (int) $incomeexpense["income"]->fees,
        ];

        return view("account::admin.index", [
            "incomeexpense" => $incomeexpense,
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
            "months_half" => $months_half,
            "income_data" => $income_data,
            "expense_data" => $expense_data,
            "expense_category" => $category_income,
            "category_data" => $merged_category_data,
            "category_name" => $merged_category_name,
            "selected_academic_year" => $academic_year,
            "profit_data" => $profit_data,
            "total_graph" => $incomeexpense["income"]->income
                ? $incomeexpense["income"]->income
                : 0,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $type = $request->query->get("type", "income");

        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $academic_year = $request->query->get(
            "academic_year",
            Configurations::getCurrentAcademicyear()
        );
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        return view("account::admin.edit", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
            "academic_year" => $academic_year,
            "type" => $type,
            "class_lists" => $class_lists,
            "sections" => [],
        ]);
    }

    public function IncomeExpenseCollectionReport(
        Request $request,
        $type = null
    ) {
        $type = $request->type;
        $academic_year = $request->get(
            "academic_year",
            Configurations::getCurrentAcademicyear()
        );
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $date_from = $request->get("date_from", date("m/d/Y"));
        //dd($date_from);
        $date_to = $request->get("date_to", date("m/d/Y"));
        $school_type = $request->get("school_type", null);

        // calculate Fees

        $incomeexpensecollection = [];

        if ($type == "income") {
            foreach (Configurations::FEETYPES as $key => $fee) {
                # code...

                if (!isset($incomeexpensecollection[$key])) {
                    $incomeexpensecollection[$key] = new \stdClass();

                    $converted_date_from = Carbon::createFromFormat(
                        "m/d/Y",
                        $date_from
                    );
                    $converted_date_to = Carbon::createFromFormat(
                        "m/d/Y",
                        $date_to
                    );

                    $incomeexpensecollection[
                        $key
                    ]->date_from = $converted_date_from->format("Y-m-d");
                    $incomeexpensecollection[
                        $key
                    ]->date_to = $converted_date_to->format("Y-m-d");
                    $incomeexpensecollection[$key]->name = $fee;
                    switch ($key) {
                        case 1:
                            # code...for school fees

                            $fees = FeesModel::where(
                                "academic_year",
                                $academic_year
                            )
                                ->whereBetween("payment_date", [
                                    $converted_date_from->format("Y-m-d"),
                                    $converted_date_to->format("Y-m-d"),
                                ])
                                ->sum("paid_amount");

                            $incomeexpensecollection[
                                $key
                            ]->total_amount = $fees;

                            break;

                        case 2:
                            //manual income
                            $income = IncomeExpenseModel::with(
                                "category",
                                "academicyear"
                            )
                                ->where("type", "income")
                                ->whereBetween("entry_date", [
                                    $date_from,
                                    $date_to,
                                ])
                                ->sum("amount");

                            $incomeexpensecollection[
                                $key
                            ]->total_amount = $income;

                            break;

                        case 3:
                            // tuck shop fees

                            $orders = OrderModel::whereBetween("order_date", [
                                $converted_date_from->format("Y-m-d"),
                                $converted_date_to->format("Y-m-d"),
                            ])->sum("order_amount");
                            $incomeexpensecollection[
                                $key
                            ]->total_amount = $orders;

                            break;

                        case 4:
                            $incomeexpensecollection[$key]->total_amount = 0;
                            break;

                        case 5:
                            $incomeexpensecollection[$key]->total_amount = 0;
                            break;

                        default:
                            # code...
                            break;
                    }
                }
            }
        } else {
            foreach (
                Configurations::EXPENCEFEETYPES
                as $expenseid => $expense
            ) {
                if (!isset($incomeexpensecollection[$expenseid])) {
                    $converted_date_from = Carbon::createFromFormat(
                        "m/d/Y",
                        $date_from
                    );
                    $converted_date_to = Carbon::createFromFormat(
                        "m/d/Y",
                        $date_to
                    );
                    $incomeexpensecollection[$expenseid] = new \stdClass();

                    $incomeexpensecollection[
                        $expenseid
                    ]->date_from = $converted_date_from->format("Y-m-d");
                    $incomeexpensecollection[
                        $expenseid
                    ]->date_to = $converted_date_to->format("Y-m-d");
                    $incomeexpensecollection[$expenseid]->name = $expense;

                    switch ($expenseid) {
                        case 1:
                            # code...payroll payment

                            $SaleryPayrollPayment = SaleryPayrollPayment::where(
                                "academic_year",
                                $academic_year
                            )
                                ->whereBetween("payment_date", [
                                    $converted_date_from->format("Y-m-d"),
                                    $converted_date_to->format("Y-m-d"),
                                ])
                                ->sum("basic_salery");

                            $incomeexpensecollection[
                                $expenseid
                            ]->total_amount = $SaleryPayrollPayment;
                            break;

                        case 2:
                            $expense = IncomeExpenseModel::with(
                                "category",
                                "academicyear"
                            )
                                ->where("type", "expense")
                                ->whereBetween("entry_date", [
                                    $date_from,
                                    $date_to,
                                ])
                                ->sum("amount");

                            $incomeexpensecollection[
                                $expenseid
                            ]->total_amount = $expense;
                            break;

                        default:
                            # code...
                            break;
                    }
                }
            }
            //dd("expense");
        }

        //dd($incomeexpensecollection);

        $academic_info = AcademicyearModel::find($academic_year)->year;
        $academic_years = Configurations::getAcademicyears();

        $school_type_info = SchoolTypeModel::where("status", 1)->pluck(
            "school_type",
            "id"
        );

        $school_type_info->prepend("All", "all");
        return view("account::admin.edit", [
            "incomeexpensecollection" => $incomeexpensecollection,
            "academic_info" => $academic_info,
            "type" => $type,
            "date_from" => $date_from,
            "date_to" => $date_to,
            "layout" => "create",
            "academic_years" => $academic_years,
            "current_academic_year" => $academic_year,
            "school_type" => $school_type_info,
        ]);

        //dd($incomeexpensecollection);
    }

    public function IncomeExpensereportView(Request $request, $type = null)
    {
        $type = $request->type;
        $fee_type = $request->get("fee_type", null);
        $class_id = $request->get("class_id", null);
        $section_id = $request->get("section_id", null);
        $academic_year = $request->get("academic_year", null);
        $date_from = $request->get("date_from", date("m/d/Y"));
        $date_to = $request->get("date_to", date("m/d/Y"));

        // dd($date_from);
        $sections = [];
        $fees = [];
        $manual_income = [];
        $tuck_shop = [];
        $payroll_data = [];
        $manual_expense = [];
        //dd($type, $fee_type);
        // dd($request->all());
        if (array_key_exists($fee_type, Configurations::FEETYPES)) {
            //  dd($type, $fee_type);
            $converted_date_from = Carbon::createFromFormat(
                "m/d/Y",
                $date_from
            );
            $converted_date_to = Carbon::createFromFormat("m/d/Y", $date_to);
            $academic_years = Configurations::getAcademicyears();
            $current_academic_year = Configurations::getCurrentAcademicyear();
            $academic_year = $request->query->get(
                "academic_year",
                Configurations::getCurrentAcademicyear()
            );
            $class_lists = LclassModel::whereNull("deleted_at")
                ->where("status", "!=", -1)
                ->orderBy("id", "asc")
                ->pluck("name", "id");

            $class_lists->prepend("All", 0);

            $school_type = SchoolTypeModel::where("status", 1)->pluck(
                "school_type",
                "id"
            );

            $school_type->prepend("All", "all");

            //$school_type[0] = "additional_value";

            if ($type == "income") {
                switch ($fee_type) {
                    case 1:
                        # code...fee payment
                        $fees = FeesModel::with(
                            "classinfo",
                            "section",
                            "student",
                            "academicyear"
                        );

                        if ($academic_year) {
                            $fees = $fees->where(
                                "academic_year",
                                $academic_year
                            );
                        }
                        if (
                            $class_id &&
                            ($class_id != 0 || $class_id != "All")
                        ) {
                            //dd("yes");
                            $fees = $fees->where("class_id", $class_id);
                        }

                        if (
                            $request->school_type &&
                            $request->school_type != 0
                        ) {
                            $class_ids = LclassModel::where(
                                "school_type_id",
                                $request->school_type
                            )->pluck("id");

                            $fees = $fees->whereIn("class_id", $class_ids);
                        }

                        if ($class_id && $section_id) {
                            $sections = SectionModel::where("status", 1)
                                ->where("class_id", $class_id)
                                ->pluck("name", "id")
                                ->toArray();
                            $fees = $fees
                                ->where("class_id", $class_id)
                                ->where("section_id", $section_id);
                        }
                        if ($date_from && $date_to) {
                            $fees = $fees->whereBetween("payment_date", [
                                $converted_date_from->format("Y-m-d"),
                                $converted_date_to->format("Y-m-d"),
                            ]);
                        }

                        $fees = $fees->get();
                        break;

                    case 2:
                        // manual income
                        $manual_income = IncomeExpenseModel::where(
                            "type",
                            "income"
                        );

                        if ($academic_year) {
                            $manual_income = $manual_income->where(
                                "academic_year",
                                $academic_year
                            );
                        }

                        if ($date_from && $date_to) {
                            $manual_income = $manual_income->whereBetween(
                                "entry_date",
                                [$date_from, $date_to]
                            );
                        }

                        $manual_income = $manual_income->get();
                        break;

                    case 3:
                        // tuckshop income
                        $tuck_shop = OrderModel::with("student");

                        // if ($academic_year) {
                        //     $tuck_shop = $tuck_shop->where(
                        //         "academic_year",
                        //         $academic_year
                        //     );
                        // }
                        if ($date_from && $date_to) {
                            $tuck_shop = $tuck_shop->whereBetween(
                                "order_date",
                                [
                                    $converted_date_from->format("Y-m-d"),
                                    $converted_date_to->format("Y-m-d"),
                                ]
                            );
                        }
                        $tuck_shop = $tuck_shop->get();

                        break;

                    default:
                        # code...
                        break;
                }
            } else {
                // expense data

                switch ($fee_type) {
                    case 1:
                        # code...
                        $payroll_data = SaleryPayrollPayment::with(
                            "user",
                            "academicyear"
                        );

                        if ($academic_year) {
                            $payroll_data = $payroll_data->where(
                                "academic_year",
                                $academic_year
                            );
                        }

                        if ($date_from && $date_to) {
                            $payroll_data = $payroll_data->whereBetween(
                                "payment_date",
                                [
                                    $converted_date_from->format("Y-m-d"),
                                    $converted_date_to->format("Y-m-d"),
                                ]
                            );
                        }

                        $payroll_data = $payroll_data->get();
                        break;

                    case 2:
                        $manual_expense = IncomeExpenseModel::where(
                            "type",
                            "expense"
                        );

                        if ($academic_year) {
                            $manual_expense = $manual_expense->where(
                                "academic_year",
                                $academic_year
                            );
                        }

                        if ($date_from && $date_to) {
                            $manual_expense = $manual_expense->whereBetween(
                                "entry_date",
                                [$date_from, $date_to]
                            );
                        }

                        $manual_expense = $manual_expense->get();
                        break;

                    default:
                        # code...
                        break;
                }
            }
        } else {
            return redirect()
                ->back()
                ->with("error", "Something Went Wrong !! Fee Types Not Found");
        }

        //dd($fees);
        return view("account::admin.show", [
            "layout" => "create",
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
            "academic_year" => $academic_year,
            "type" => $request->type,
            "class_lists" => $class_lists,
            "sections" => $sections,
            "class_id" => $class_id,
            "section_id" => $section_id,
            "date_from" => $date_from,
            "date_to" => $date_to,
            "fees" => $fees,
            "feetype" => $fee_type,
            "manual_income" => $manual_income,
            "tuck_shop" => $tuck_shop,
            "payroll_data" => $payroll_data,
            "manual_expense" => $manual_expense,
            "school_type" => $school_type,
            "school_type_id" => $request->school_type,
        ]);
    }

    public function getData(Request $request)
    {
        CGate::authorize("view-account");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = AccountModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new AccountModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new AccountModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )->where("status", "!=", -1);

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
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "account",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->make(true);
    }
}
