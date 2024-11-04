<?php namespace cms\payrool\Controllers;

use App\Exports\PayrollSheduleExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\payrool\Models\PayrollModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use Configurations;
use cms\teacher\Models\TeacherModel;
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\payrool\Models\SaleryPayrollPayment;
use cms\payrool\Models\SaleryTemplateModel;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use cms\payrool\Mail\PayrollPaySlip;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;

class PayroolController extends Controller
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
    public function index()
    {
        return view("payrool::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $assigned_user_group = PayrollModel::groupBy("group_id")
            ->pluck("group_id")
            ->toArray();

        array_push($assigned_user_group, 1, 2, 4, 5);

        //dd($assigned_user_group);
        $user_group = UserGroupModel::where("status", 1)
            ->whereNotIn("id", $assigned_user_group)
            ->pluck("group", "id")
            ->toArray();

        $salery_grades = SaleryTemplateModel::where("status", 1)
            ->pluck("grade_name", "id")
            ->toArray();

        if ($request->ajax()) {
            $type = $request->query->get("type");
            $user_group = $request->query->get("group_id");
            $user_ids = UserGroupMapModel::where(
                "group_id",
                $request->query->get("group_id")
            )->pluck("user_id");

            $users = [];

            $users_data = UserModel::where("status", 1)
                ->whereNull("deleted_at")
                ->whereIn("id", $user_ids)
                ->get();
            // dd( $users_data);
            // if ($type == 2) {
            //     if ($user_group == 3) {
            //         $users_group_data = TeacherModel::where("status", "!=", -1)
            //             ->select("id", "teacher_name as text")
            //             ->get();
            //         return $users_group_data;
            //     }

            //     // if ($user_group == 4) {
            //     //     $users_name = AccountModel::where("status", "!=", -1)
            //     //         ->select("id", "teacher_name as text")
            //     //         ->get();
            //     //     return $users_name;
            //     // }
            // }

            if ($type == "payment") {
                $user_ids = UserGroupMapModel::where(
                    "group_id",
                    $request->query->get("group_id")
                )->pluck("user_id");

                $users = [];

                $users_data = UserModel::with("salerypayroll.grade")
                    ->where("status", 1)
                    ->whereNull("deleted_at")
                    ->whereIn("id", $user_ids)

                    ->get();

                $view = view("payrool::admin.includes.makepaymentuser", [
                    "users_data" => $users_data,
                    "salery_grades" => $salery_grades,
                    "layout" => "create",
                ])->render();
                return response()->json(["view" => $view]);
            } else {
                $view = view("payrool::admin.includes.salerytemplateuser", [
                    "users_data" => $users_data,
                    "salery_grades" => $salery_grades,
                    "layout" => "create",
                ])->render();
            }

            return response()->json(["view" => $view]);
        }

        //dd($users_data);

        //dd($salery_grades);
        return view("payrool::admin.edit", [
            "layout" => "create",
            "user_group" => $user_group,
            "students" => [],
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
        $this->validate($request, [
            "group" => "required",
        ]);
        DB::beginTransaction();
        try {
            [
                $date,
                $time,
                $month,
                $year,
            ] = Configurations::getcurrentDateTime();

            foreach ($request->users as $user_id => $grade_id) {
                # code...

                $grade_basic = SaleryTemplateModel::find($grade_id)
                    ->basic_salery;

                $obj = new PayrollModel();
                $obj->group_id = $request->group;
                $obj->user_id = $user_id;
                $obj->grade_id = $grade_id;
                $obj->basic_salery = $grade_basic;
                $obj->month = $month;
                $obj->year = $year;
                $obj->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            dd($e);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("payrool.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("payroll.index");
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
        $data = PayrollModel::find($id);

        $user_data = PayrollModel::with("userinfo")
            ->where("group_id", $data->group_id)
            ->get();

        $user_group = UserGroupModel::where("status", 1)
            ->whereNotIn("id", [1, 2, 4, 5])
            ->pluck("group", "id")
            ->toArray();

        $salery_grades = SaleryTemplateModel::where("status", 1)
            ->pluck("grade_name", "id")
            ->toArray();

        //dd($user_data);
        return view("payrool::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "user_data" => $user_data,
            "user_group" => $user_group,
            "salery_grades" => $salery_grades,
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
        $this->validate($request, [
            "group" => "required",
        ]);
        DB::beginTransaction();
        try {
            [
                $date,
                $time,
                $month,
                $year,
            ] = Configurations::getcurrentDateTime();

            foreach ($request->users as $user_id => $grade_id) {
                # code...

                $is_exists = PayrollModel::where([
                    "group_id" => $request->group,
                    "user_id" => $user_id,
                    "grade_id" => $grade_id,
                ])->first();

                if (!$is_exists) {
                    $grade_basic = SaleryTemplateModel::find($grade_id)
                        ->basic_salery;

                    $obj = new PayrollModel();
                    $obj->group_id = $request->group;
                    $obj->user_id = $user_id;
                    $obj->grade_id = $grade_id;
                    $obj->basic_salery = $grade_basic;
                    $obj->month = $month;
                    $obj->year = $year;
                    $obj->save();
                }
            }
            DB::commit();
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

        Session::flash("success", "saved successfully");
        return redirect()->route("payroll.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_payrool)) {
            $delObj = new PayrollModel();
            foreach ($request->selected_payrool as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new PayrollModel();
            $delItem = $delObj->find($id);

            //dd($delItem);

            PayrollModel::where("group_id", $delItem->group_id)->delete();
            //$delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("payroll.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-payrool");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = PayrollModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "salery_payroll.id as id",
            "salery_payroll.group_id",
            "user_groups.group as group",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new PayrollModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new PayrollModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("salery_payroll.status", "!=", -1)
            ->join(
                "user_groups",
                "user_groups.id",
                "=",
                "salery_payroll.group_id"
            )
            ->groupBy("salery_payroll.group_id");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })

            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "payroll",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-payrool");
        if ($request->ajax()) {
            PayrollModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_payrool)) {
            $obj = new PayrollModel();
            foreach ($request->selected_payrool as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function PayrollSchedule(Request $request, $id = null)
    {
        try {
            $assigned_user_group = PayrollModel::groupBy("group_id")
                ->pluck("group_id")
                ->toArray();
            $user_group = UserGroupModel::where("status", 1)
                ->whereIn("id", $assigned_user_group)
                ->pluck("group", "id")
                ->toArray();

            //dd($users_data);

            if ($request->isMethod("post") || $request->ajax()) {
                //dd($request->all());
                if ($request->ajax()) {
                    $group = $request->query->get("group_id");
                } else {
                    $group = $request->group;
                }

                //$group=$request->query()->get("group_id",$request->group);
                $user_ids = UserGroupMapModel::where("group_id", $group)->pluck(
                    "user_id"
                );

                $users_data = UserModel::with("salerypayroll.grade")
                    ->where("status", 1)
                    ->whereNull("deleted_at")
                    ->whereIn("id", $user_ids)

                    ->get();
                // dd("here");
                $schedule_data = [];

                foreach ($users_data as $key => $user) {
                    if (!isset($schedule_data[$user->id])) {
                        $schedule_data[$user->id] = new \stdClass();
                    }

                    $schedule_data[$user->id]->name = $user->name;
                    $schedule_data[$user->id]->working_days = 30;
                    $schedule_data[$user->id]->absent_days = 0;
                    //  //
                    $basic_salery = $user->salerypayroll
                        ? $user->salerypayroll->grade->basic_salery
                        : null;
                    $annual_basic_salery = $user->salerypayroll
                        ? $user->salerypayroll->grade->basic_salery * 12
                        : null;

                    $cra_basic = 200000;

                    $annual_income_twenty_per = $annual_basic_salery * 0.2;

                    // ////
                    $schedule_data[$user->id]->basic_salery = $basic_salery;
                    $schedule_data[$user->id]->due_salery = $basic_salery / 30;
                    $schedule_data[
                        $user->id
                    ]->annual_basic_salery = $annual_basic_salery;
                    $schedule_data[$user->id]->cra_basic = $cra_basic;
                    $schedule_data[
                        $user->id
                    ]->annual_income_twenty_per = $annual_income_twenty_per;

                    $gps_employee_eight_per = $basic_salery * 0.08 * 12;
                    $gps_employee_ten_per = $basic_salery * 0.1 * 12;
                    $schedule_data[
                        $user->id
                    ]->gps_employee_eight_per = $gps_employee_eight_per;
                    $schedule_data[
                        $user->id
                    ]->gps_employee_ten_per = $gps_employee_ten_per;
                    $schedule_data[$user->id]->gps_total =
                        $gps_employee_eight_per + $gps_employee_ten_per;
                    $schedule_data[$user->id]->hmo = 0;

                    $total_cra =
                        $cra_basic +
                        $annual_income_twenty_per +
                        $gps_employee_eight_per;
                    $schedule_data[$user->id]->total_cra = $total_cra;

                    $chargable_income = $annual_basic_salery - $total_cra;

                    $schedule_data[
                        $user->id
                    ]->chargable_income = $chargable_income;

                    // calculate for first 300 etc

                    $first_three_hundred =
                        $chargable_income > 300000 ? 300000 : $chargable_income;

                    $seven_per = $first_three_hundred * 0.07;

                    //

                    $second_three_hundred = 0;

                    if ($chargable_income - $first_three_hundred > 300000) {
                        $second_three_hundred = 300000;
                    } else {
                        $second_three_hundred =
                            $chargable_income - $first_three_hundred;
                    }

                    $eleven_per = $second_three_hundred * 0.11;

                    //

                    $next_five_hundred = 0;

                    if (
                        $chargable_income -
                            $first_three_hundred -
                            $second_three_hundred >
                        500000
                    ) {
                        $next_five_hundred = 500000;
                    } else {
                        $next_five_hundred =
                            $chargable_income -
                            $first_three_hundred -
                            $second_three_hundred;
                    }

                    $fifteen_per = $next_five_hundred * 0.15;

                    $schedule_data[
                        $user->id
                    ]->first_three_hundred = $first_three_hundred;

                    $schedule_data[$user->id]->seven_per = $seven_per;

                    $schedule_data[
                        $user->id
                    ]->second_three_hundred = $second_three_hundred;

                    $schedule_data[$user->id]->eleven_per = $eleven_per;

                    $schedule_data[
                        $user->id
                    ]->next_five_hundred = $next_five_hundred;

                    $payee = ($seven_per + $eleven_per + $fifteen_per) / 12;

                    $schedule_data[$user->id]->fifteen_per = $fifteen_per;
                    $schedule_data[$user->id]->payee = $payee;
                }

                if ($id) {
                    if (isset($schedule_data[$id])) {
                        return $schedule_data[$id]->payee;
                    }
                }

                //dd($schedule_data);

                if ($request->ajax()) {
                    $view = view("payrool::schedule.export", [
                        "schedule_data" => $schedule_data,
                    ])->render();
                    return response()->json(["view" => $view]);
                } else {
                    return Excel::download(
                        new PayrollSheduleExport($schedule_data),
                        "filename.xlsx"
                    );
                }
            }

            return view("payrool::schedule.index", [
                "user_group" => $user_group,
                "layout" => "create",
            ]);
        } catch (\Exception $e) {
            dd($e);
        }

        dd($request->all());
    }

    public function PayrollMakePayment(Request $request)
    {
        $assigned_user_group = PayrollModel::groupBy("group_id")
            ->pluck("group_id")
            ->toArray();

        $user_group = UserGroupModel::where("status", 1)
            ->whereIn("id", $assigned_user_group)
            ->pluck("group", "id")
            ->toArray();

        // dd($users_data);

        $salery_grades = SaleryTemplateModel::where("status", 1)
            ->pluck("grade_name", "id")
            ->toArray();

        if ($request->isMethod("post")) {
            $this->validate($request, [
                "group" => "required",
                "month" => "required",
            ]);
            [
                $date,
                $time,
                $month,
                $year,
            ] = Configurations::getcurrentDateTime();

            try {
                DB::beginTransaction();

                //dd($request->all());
                //dd(explode(" ", $request->month)[0]);

                //dd($request->all());

                //return view("payrool::admin.includes.payslip");

                for ($i = 0; $i < sizeof($request->users); $i++) {
                    # code...

                    $bill_no = SaleryPayrollPayment::where("status", 1)
                        ->latest("id")
                        ->first();

                    $slip_no = Configurations::GenerateBillnumber(
                        $bill_no != null ? $bill_no->payslip_no : 1,
                        "F"
                    );

                    // grade
                    $is_exists = SaleryPayrollPayment::where([
                        "month" => explode(" ", $request->month)[0],
                        "year" => explode(" ", $request->month)[1],
                        "user_id" => $request->users[$i],
                    ])->first();

                    if (!$is_exists) {
                        $grade = SaleryTemplateModel::find($request->grade[$i]);
                        if ($grade) {
                            $obj = new SaleryPayrollPayment();
                            $obj->user_id = $request->users[$i];
                            $obj->group_id = $request->group;
                            $obj->payment_date = $date;
                            $obj->academic_year = Configurations::getCurrentAcademicyear();
                            $obj->basic_salery = $request->basic_salery[$i];

                            $obj->payslip_no = $slip_no;

                            $obj->deduction = $this->CalculateDeduction(
                                $request,
                                $request->users[$i]
                            );
                            $obj->particulars = $grade->particulars;
                            $obj->month = explode(" ", $request->month)[0];
                            $obj->year = explode(" ", $request->month)[1];

                            $user = UserModel::find($request->users[$i]);

                            //dd($grade);
                            if ($obj->save()) {
                                // calculate newsalery

                                $netsalery = $this->CalculateNetSalery($obj);
                                SaleryPayrollPayment::find($obj->id)->update([
                                    "net_salery" => $netsalery,
                                ]);
                            }

                            // if ($obj->save()) {
                            //     $pdf = Pdf::loadView(
                            //         "payrool::admin.includes.payslip",
                            //         ["user" => $user, "grade" => $grade]
                            //     );
                            //     \CmsMail::setMailConfig();
                            //     Mail::to($user->email)->send(
                            //         new PayrollPaySlip($pdf, $request->month)
                            //     );
                            // }
                        }
                    }
                }

                DB::commit();
                $data = ["data"];

                return redirect()
                    ->route("PayrollMakePayment")
                    ->with(
                        "success",
                        "Payment Slip Generated Successfully and Send users Email Address"
                    );
            } catch (\Exception $e) {
                DB::rollback();
                dd($e);
                return redirect()
                    ->back()
                    ->with("error", $e->getMessage());
            }
        }

        return view("payrool::admin.edit", [
            "layout" => "create",
            "user_group" => $user_group,
            "students" => [],
            "type" => "payment",

            "salery_grades" => $salery_grades,
        ]);
    }

    public function CalculateDeduction(Request $request, $iduser)
    {
        $tax = Configurations::getConfig("site")->payroll_tax;
        $employer_pension = Configurations::getConfig("site")->employer_pension;
        $employee_pension = Configurations::getConfig("site")->employee_pension;
        return [
            "tax_per" => $tax ? $tax : 0,
            "tax_amount" => $this->PayrollSchedule($request, $iduser),
            "employer_pension_per" => $employer_pension
                ? $employer_pension
                : 10,
            "employee_pension_per" => $employee_pension ? $employee_pension : 8,
        ];
    }

    public function PaymentHistory(Request $request, $type = null)
    {
        //dd($request->all());
        $user_group = UserGroupModel::where("status", 1)
            ->whereNotIn("id", [1, 2, 4, 5])
            ->pluck("group", "id")
            ->toArray();

        if ($request->isMethod("post")) {
            $this->validate($request, [
                "group_id" => "required",
                "month" => "required",
            ]);

            $month = explode(" ", $request->month)[0];
            $year = explode(" ", $request->month)[1];
            if (
                !empty($request->member_id) ||
                in_array(0, $request->member_id)
            ) {
                $users_data = UserModel::with("salerypayrollpayment.grade")
                    ->where("status", 1)
                    ->whereNull("deleted_at")
                    ->whereIn("id", $request->member_id)

                    ->get();
            } else {
                $user_ids = UserGroupMapModel::where(
                    "group_id",
                    $request->group_id
                )->pluck("user_id");
                $users_data = UserModel::with("salerypayrollpayment.grade")
                    ->where("status", 1)
                    ->whereNull("deleted_at")
                    ->whereIn("id", $user_ids)

                    ->get();
            }

            $users = [];

            // dd($users_data);
            return view("payrool::paymenthistory.index", [
                "layout" => "create",
                "user_group" => $user_group,
                "users_data" => $users_data,
                "monthyear" => $request->month,
                "month" => $month,
                "year" => $year,
                "group_id" => $request->group_id,
            ]);
            // dd("here");
        }

        //dd($users_data);

        $monthyear = "August 2023";

        $month = explode(" ", $monthyear)[0];
        $year = explode(" ", $monthyear)[1];
        return view("payrool::paymenthistory.index", [
            "layout" => "create",
            "user_group" => $user_group,
            "users_data" => [],
            "type" => $type,
        ]);
        // dd("here");
    }

    public function ViewPayslip(Request $request, $id)
    {
        // dd($id);

        $salery_payment = SaleryPayrollPayment::find($id);
        $config = Configurations::getConfig("site");

        $teacher = TeacherModel::where(
            "user_id",
            $salery_payment->user_id
        )->get();

        $salery_payment_details = SaleryPayrollPayment::where(
            "user_id",
            $salery_payment->user_id
        )->get();

        $host = request()->getHttpHost();

        $user = UserModel::find($salery_payment->user_id);
        //dd($user->username);
        // $grade = SaleryTemplateModel::find($request->grade[$i]);

        // return view("payrool::admin.includes.payslip", [
        //     "user" => $user,
        //     "view" => true,
        //     "grade" => [],
        //     "salery_payment" => $salery_payment,
        // ]);
        $config = Configurations::getConfig("site");
        $image = parse_url($config->imagec, PHP_URL_PATH);
        $pdf = Pdf::loadView("payrool::admin.includes.payslip", [
            "user" => $user,
            "config" => $config,
            "teacher" => $teacher,
            "view" => true,
            "grade" => [],
            "salery_payment" => $salery_payment,
            "salery_payment_details" => $salery_payment_details,
            "current_url" => $host,
            "image" => $image,
        ]);

        // $fontPath = "theme/vendors/font-poppins/fonts/poppins-regular.ttf";
        // $pdf->setOption(
        //     "user-style-sheet",
        //     "body { font-family: 'poppins',sans-serif; }"
        // );

        // $pdf->setOptions([
        //     "defaultFont" => "poppins",
        //     "defaultPaperSize" => "a4",
        // ]);
        // $pdf->setOption("font-path", $fontPath);
        // $pdf->render();

        return $pdf->stream();

        return view("payrool::admin.includes.payslip", [
            "user" => $user,
            "view" => true,
            "grade" => [],
            "salery_payment" => $salery_payment,
        ]);
    }

    public function CalculateNetSalery($obj)
    {
        $viewDeduction = 0;
        $basic_per = 100;
        foreach (@$obj->particulars as $particular) {
            $viewDeduction += $particular["deduction_amount"];
            $basic_per -= $particular["deduction_per"];
        }

        $basic = $obj->basic_salery - $viewDeduction;
        $tax =
            $obj->deduction["tax_per"] == 0
                ? $obj->deduction["tax_amount"]
                : ($basic * $obj->deduction["tax_per"]) / 100;

        $employer_pension =
            ($basic * $obj->deduction["employer_pension_per"]) / 100;
        $employee_pension =
            ($basic * $obj->deduction["employee_pension_per"]) / 100;

        $net = $basic - ($tax + $employee_pension);

        return $net;
    }
}
