<?php

namespace cms\fees\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\fees\Models\FeesModel;
use Mail;
use App\Mail\UnpaidEmail;
use App\Notifications\FirebasePushNotification;
use Yajra\DataTables\Facades\DataTables;
use cms\fees\Models\FeeTypeModel;
use Configurations;
use cms\lclass\Models\LclassModel;
use cms\students\Models\StudentsModel;
use cms\fees\Models\SchoolTypeModel;
use cms\section\Models\SectionModel;
use cms\department\Models\DepartmentModel;
use cms\fees\Models\FeeSetupListModel;
use cms\academicyear\Models\AcademicyearModel;
use Carbon\CarbonPeriod;
use Carbon\Carbon;
use cms\core\configurations\Models\ConfigurationModel;
use Session;
use DB;
use CGate;
use PDF;
use User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Http;
use cms\dormitory\Models\DormitoryStudentModel;
use cms\fees\Models\FeeReminderMappingModel;
use cms\fees\Models\FeeReminderModel;
use cms\exam\Models\ExamTermModel;
use cms\fees\Mail\FeeCollectionMail;
use cms\fees\Models\AcademicFeeModel;
use cms\fees\Models\FeeSetupModel;
use cms\transport\Models\TransportStudents;
use cms\core\configurations\Models\HistoryModel;
use cms\core\user\Models\UserModel;
use Doctrine\DBAL\Configuration;
use Facade\FlareClient\Http\Response;
use Notification;
use Swift_TransportException;

class FeesController extends Controller
{
    public $student;
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
            $academic_year = $request->query->get("academic_year", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section_id", 0);
            $student_id = $request->query->get("student_id", 0);

            $term = ExamTermModel::find(
                Configurations::getCurrentAcademicterm()
            )->exam_term_name;

            $student_info = StudentsModel::with(
                "class",
                "section",
                "department"
            )->find($student_id);
            $department_id = SectionModel::where(
                "id",
                $student_info->section_id
            )
                ->pluck("department_id")
                ->first();
            $student_info->department_name = $department_id
                ? DepartmentModel::where("id", $department_id)
                    ->pluck("dept_name")
                    ->first()
                : "None";

            $feesetup = FeeSetupModel::with("feelists")
                ->where([
                    "class_id" => $class_id,
                    "academic_year" => $academic_year,
                ])
                ->first();
            $scholarship = StudentsModel::find($student_id)->scholarship;
            if (!$feesetup) {
                return response()->json([
                    "error" => "There is No FeeSetup Available",
                ]);
            }

            $feedata = json_decode(
                @ConfigurationModel::where("name", "=", "feestructure")->first()
                    ->parm
            );

            if (!$feedata) {
                return response()->json([
                    "error" =>
                        "There is No FeeStructure Available Kindly Setup in Configurations Settings",
                ]);
            }

            $academic_fee_info_sum = AcademicFeeModel::where(
                "student_id",
                $student_id
            )->sum("due_amount");

            $academic_fee_info = AcademicFeeModel::where(
                "student_id",
                $student_id
            )->get();

            $term_due = [];
            $month_due = [];
            $full_due = [];

            $total_amount = 0;

            if ($scholarship) {
                $schamount =
                    ($scholarship / 100) * $feesetup->total_amount +
                    $academic_fee_info_sum;

                $total_amount =
                    $feesetup->total_amount + $academic_fee_info_sum;
                $grand_total = $total_amount - $schamount;
            } else {
                $grand_total = $feesetup->total_amount + $academic_fee_info_sum;
            }

            $fee_split = Configurations::FEESPLIT;
            // dd($grand_total);
            if ($feedata->payment_type == 1) {
                // termwise pay method
                foreach ($feedata->dueinfo as $termid => $due) {
                    if (!isset($term_due[$termid])) {
                        $term_due[$termid] = new \stdClass();
                        $term_due[$termid]->terminfo = ExamTermModel::find(
                            $termid
                        )->exam_term_name;
                        $term_due[$termid]->duedate = $due->date;
                        $term_due[$termid]->per = $due->per;
                        $term_due[$termid]->amount =
                            ($due->per / 100) * $grand_total;
                        $term_due[$termid]->ispaid = FeesModel::where([
                            "academic_year" => $academic_year,
                            "student_id" => $student_id,
                            "pay_term_id" => $termid,
                        ])->first();
                    }
                }
            } elseif ($feedata->payment_type == 0) {
                $monthsPay = Configurations::GetMonthsOfAcademicYear(
                    $academic_year,
                    null
                );

                $monthlyPayment = $grand_total / sizeof($monthsPay);

                for ($i = 0; $i < sizeof($monthsPay); $i++) {
                    $paymentAmount = $monthlyPayment;

                    // If it's the last month, adjust the amount to cover the remaining balance
                    if ($i == sizeof($monthsPay)) {
                        $paymentAmount =
                            $grand_total -
                            $monthlyPayment * sizeof($monthsPay) -
                            1;
                    }

                    if (!isset($month_due[$i])) {
                        $month_due[$i] = new \stdClass();
                        $month_due[$i]->year = $monthsPay[$i]["year"];
                        $month_due[$i]->month = $monthsPay[$i]["month"];

                        $month_due[$i]->amount = $paymentAmount;
                        $month_due[$i]->ispaid = FeesModel::where([
                            "academic_year" => $academic_year,
                            "student_id" => $student_id,
                            "pay_month" => $monthsPay[$i]["month"],
                            "pay_month_year" => $monthsPay[$i]["year"],
                        ])->first();
                    }

                    //echo "Month: $paymentMonth, Amount: $paymentAmount" . PHP_EOL;
                }
            } else {
                $full_due[0] = new \stdClass();
                $full_due[0]->duedate = $feedata->dueinfo;
                $full_due[0]->ispaid = FeesModel::where([
                    "academic_year" => $academic_year,
                    "student_id" => $student_id,
                    "pay_type" => 2,
                ])->first();
                $full_due[0]->amount = $grand_total;
            }

            //return $full_due;

            $view = view("fees::admin.includes.feeinfo", [
                "data" => $feesetup,
                "term_due" => $term_due,
                "grand_total" => $grand_total,
                "feedata" => $feedata,
                "academic_fee_info" => $academic_fee_info,
                "month_due" => $month_due,
                "full_due" => $full_due,

                "scholarship" => $scholarship,
                "total_amount" => $total_amount,
                "student_info" => $student_info,
                "term" => $term,
            ])->render();

            $feeview = view("fees::admin.includes.feelist", [
                "data" => $feesetup,
                "term_due" => $term_due,
                "grand_total" => $grand_total,
                "feedata" => $feedata,
                "academic_fee_info" => $academic_fee_info,

                "scholarship" => $scholarship,
                "total_amount" => $total_amount,
            ])->render();

            return response()->json(["view" => $view, "feeview" => $feeview]);
        }
        $academicyears = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $current_pay_type = Configurations::getCurrentFeePaymentType();
        $current_month_year = now()
            ->startOfMonth()
            ->format("F Y");
        [$month, $year] = explode(" ", $current_month_year);

        $term = ExamTermModel::where("id", $current_academic_term)->first();
        if ($term->from_date !== null && $term->to_date !== null) {
            $formDate = Carbon::createFromFormat("m/d/Y", $term->from_date);
            $endDate = Carbon::createFromFormat("m/d/Y", $term->to_date);
        } else {
            $formDate = null;
            $endDate = null;
        }

        $school_type_info = SchoolTypeModel::where("status", 1)
            ->pluck("school_type", "id")
            ->toArray();

        $school_type_infos = Configurations::getSchooltypes();

        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();
        $section_lists = [];
        $subjects = [];

        $examterms = Configurations::getCurentAcademicTerms();
        $payment_types = [];
        $payment = Configurations::FEEPAYMENTTYPES;
        $type = Configurations::getCurrentFeePaymentType();
        $payment_types[] = ["id" => $type, "text" => $payment[$type]];
        $payment_types = collect($payment_types)->pluck("text", "id");
        // dd($payment_types);
        $timezone = Configurations::getConfig("site")->time_zone;
        if (Session::get("ACTIVE_GROUP") == "Student") {
            $user_id = User::getUser()->id;
            $student_id = StudentsModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            if ($type == 0) {
                $nowmonth = Carbon::now($timezone)->format("F");
                $nowyear = Carbon::now($timezone)->format("Y");
                $paid = FeesModel::where([
                    "student_id" => $student_id,
                    "payment_month" => $nowmonth,
                    "payment_year" => $nowyear,
                ])->first();
            } elseif ($type == 1) {
                $term = ExamTermModel::where(
                    "id",
                    $current_academic_term
                )->first();
                $temp_form = Carbon::createFromFormat(
                    "Y/m/d",
                    $term->from_date
                );
                $trmp_end = Carbon::createFromFormat("Y/m/d", $term->to_date);
                $paid = FeesModel::where("student_id", $student_id)
                    ->whereDateBetween("payment_date", [$temp_form, $temp_end])
                    ->first();
            } else {
                $temp_year = AcademicyearModel::where(
                    "id",
                    $current_academic_year
                )->first();
                $temp_form = Carbon::createFromFormat(
                    "Y/m/d",
                    $temp_year->start_date
                );
                $trmp_end = Carbon::createFromFormat(
                    "Y/m/d",
                    $temp_year->end_date
                );
                $paid = FeesModel::where("student_id", $student_id)
                    ->whereDateBetween("payment_date", [$temp_form, $temp_end])
                    ->first();
            }
            $btn_exist = $paid ? 1 : 0;
        } else {
            if ($type == 0) {
                // dd("type 0");
                $nowmonth = Carbon::now($timezone)->format("F");
                $nowyear = Carbon::now($timezone)->format("Y");
                $paid = FeesModel::where([
                    "payment_month" => $nowmonth,
                    "payment_year" => $nowyear,
                ])->first();
                // dd($nowmonth, $nowyear, $paid);
            } elseif ($type == 1) {
                // dd("type 1");
                $term = ExamTermModel::where(
                    "id",
                    $current_academic_term
                )->first();
                // dd($term);

                $temp_form = Carbon::parse($term->from_date)->format("Y/m/d");
                $temp_end = Carbon::parse($term->to_date)->format("Y/m/d");
                // dd($temp_form, $temp_end);
                $paid = FeesModel::whereBetween("payment_date", [
                    $temp_form,
                    $temp_end,
                ])->first();
            } else {
                // dd("type 2");
                $temp_year = AcademicyearModel::where(
                    "id",
                    $current_academic_year
                )->first();
                $temp_form = Carbon::createFromFormat(
                    "Y/m/d",
                    $temp_year->start_date
                );
                $trmp_end = Carbon::createFromFormat(
                    "Y/m/d",
                    $temp_year->end_date
                );
                $paid = FeesModel::whereDateBetween("payment_date", [
                    $temp_form,
                    $temp_end,
                ])->first();
            }
            $btn_exist = $paid ? 1 : 0;
        }
        // dd($paid, $btn_exist);
        return view("fees::admin.index", [
            "academicyears" => $academicyears,
            "current_academic_year" => $current_academic_year,
            "current_academic_term" => $current_academic_term,
            "current_month_year" => $current_month_year,
            "formDate" => $formDate,
            "endDate" => $endDate,
            "school_type_info" => $school_type_info,
            "school_type_infos" => $school_type_infos,
            "class_lists" => $class_lists,
            "section_lists" => $section_lists,
            "subjects" => $subjects,
            "examterms" => $examterms,
            "payment_types" => $payment_types,
            "type" => $type,
            "btn_exist" => $btn_exist,
        ]);
    }

    public function payfeepayment(Request $request)
    {
        if ($request->ajax()) {
            $type = $request->query->get("type", 0);
            $academic_year = $request->academic_year;
            $class_id = $request->class_id;
            $section_id = $request->section_id;
            $student_id = $request->student_id;
            $paid_amount = $request->paid_amount;

            $term = ExamTermModel::find(
                Configurations::getCurrentAcademicterm()
            )->exam_term_name;

            $student_info = StudentsModel::with(
                "class",
                "section",
                "department"
            )->find($student_id);

            $department = $student_info->section->department_id
                ? DepartmentModel::where(
                    "id",
                    $student_info->section->department_id
                )
                    ->pluck("dept_name")
                    ->first()
                : "NA";

            $feesetup = FeeSetupModel::with("feelists")
                ->where([
                    "class_id" => $class_id,
                    "academic_year" => $academic_year,
                ])
                ->first();

            //return $student_info;

            if ($type == "term") {
                $per = $request->per;
                $selected_term_date = $request->selected_term_date;
                $selected_term = $request->selected_term;
                $view = view(
                    "fees::admin.includes.payfee",
                    compact(
                        "student_info",
                        "term",
                        "paid_amount",
                        "per",
                        "type",
                        "class_id",
                        "section_id",
                        "student_id",
                        "academic_year",
                        "feesetup",
                        "selected_term_date",
                        "selected_term",
                        "department"
                    )
                )->render();

                return response()->json(["viewfile" => $view]);
            } elseif ($type == "month") {
                $selected_month = $request->selected_month;
                $selected_year = $request->selected_year;
                $view = view(
                    "fees::admin.includes.payfee",
                    compact(
                        "student_info",
                        "term",
                        "paid_amount",

                        "type",
                        "class_id",
                        "section_id",
                        "student_id",
                        "academic_year",
                        "feesetup",
                        "selected_month",
                        "selected_year",
                        "department"
                    )
                )->render();
                return response()->json(["viewfile" => $view]);
            } else {
                $selected_term_date = $request->selected_term_date;
                $view = view(
                    "fees::admin.includes.payfee",
                    compact(
                        "student_info",
                        "term",
                        "paid_amount",

                        "type",
                        "class_id",
                        "section_id",
                        "student_id",
                        "academic_year",
                        "feesetup",
                        "selected_term_date",
                        "department"
                    )
                )->render();
                return response()->json(["viewfile" => $view]);
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd($this->student);
        $student_info = [];
        if (Session::get("ACTIVE_GROUP") == "Student") {
            $student_info = StudentsModel::where(
                "user_id",
                User::getUser()->id
            )->first();
        }

        $info = Configurations::getAcademicandTermsInfo();

        $academicyears = Configurations::getAcademicyears();
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)
            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $feetypes = FeeTypeModel::where("status", 1)
            ->pluck("type_name", "id")
            ->toArray();
        // dd($info);
        $departments = DepartmentModel::where("status", 1)
            ->pluck("dept_name", "id")
            ->toArray();

        // for ajax data

        $class_id = 2;
        $academic_year = 3;
        $section_id = 2;
        $student_id = 25;

        //dd($term_due);

        $paymode = Configurations::getConfig("feestructure")->payment_type;
        $current_academic_year = Configurations::getCurrentAcademicyear();

        return view("fees::admin.edit", [
            "layout" => "create",
            "class_lists" => $class_lists,
            "sections" => [],
            "students" => [],
            "academicyears" => $academicyears,
            "info" => $info,
            "feetypes" => $feetypes,
            "departments" => $departments,
            "paymode" => $paymode,
            "student_info" => $student_info,
            "is_student" =>
                Session::get("ACTIVE_GROUP") == "Student" ? true : false,
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
        //dd(Configurations::convertRupeesToWords($request->paid_amount));
        $this->validate($request, [
            "academic_year" => "required",
            "class_id" => "required",
            "section_id" => "required",
        ]);
        $student_info = StudentsModel::select(
            "students.id as id",
            "students.reg_no as reg_no",

            DB::raw("CONCAT(first_name, last_name) AS full_name"),
            "parent.father_name as parentname",
            "parent.guardian_name as guardianname",
            "lclass.name as classname",
            "section.name as sectionname"
        )
            ->leftJoin("parent", "parent.id", "=", "students.parent_id")
            ->leftJoin("lclass", "lclass.id", "=", "students.class_id")
            ->leftJoin("section", "section.id", "=", "students.section_id")
            ->find($request->student_id);
        $config = Configurations::getConfig("site");
        $image = parse_url($config->imagec, PHP_URL_PATH);
        // dd($image);
        if ($request->type == "term") {
            $term_name = ExamTermModel::find($request->selected_term)
                ->exam_term_name;
        } else {
            $term_name = ExamTermModel::find(
                Configurations::getCurrentAcademicterm()
            )->exam_term_name;
        }

        $academic = AcademicyearModel::find($request->academic_year)->year;

        // return $pdf->stream();

        //return new FeeCollectionMail();
        //dd($request->all());

        DB::beginTransaction();

        $feedata = json_decode(
            @ConfigurationModel::where("name", "=", "feestructure")->first()
                ->parm
        );

        $fee_info = FeesModel::withTrashed()
            ->latest("id")
            ->first();

        $bill_no = Configurations::GenerateUsername(
            $fee_info != null ? $fee_info->bill_no : null,
            "F"
        );
        $host = request()->getHttpHost();
        try {
            $obj = new FeesModel();
            $obj->academic_year = $request->academic_year;
            $obj->bill_no = $bill_no;
            $obj->class_id = $request->class_id;
            $obj->section_id = $request->section_id;
            $obj->student_id = $request->student_id;
            $obj->fee_setup_id = $request->fee_setup_id;
            $obj->paid_amount = $request->paid_amount;
            $obj->payment_method = $request->payment_method;
            $obj->payment_date = date("Y-m-d");
            $obj->payment_year = Carbon::now()->year;
            $obj->payment_month = Carbon::now()->monthName;
            $obj->remark = $request->remark;
            $obj->pay_type = $feedata->payment_type;
            $obj->pay_term_id = $request->selected_term;
            $obj->due_date = $request->selected_term_date;
            $obj->pay_month = $request->selected_month;
            $obj->pay_month_year = $request->selected_year;
            if ($obj->save()) {
                $pdf = PDF::loadView("fees::pdf.receiptpdf", [
                    "student_info" => $student_info,
                    "fee_info" => $fee_info,
                    "config" => $config,
                    "term_name" => $term_name,
                    "academic" => $academic,
                    "paid_amount" => $request->paid_amount,
                    "current_url" => $host,
                    "pay_month" => $request->selected_month,
                    "pay_year" => $request->selected_year,
                    "image" => $image,
                    "paymethod" =>
                        Configurations::FEEPAYMENTMETHOD[
                            $request->payment_method
                        ],
                    "words" => Configurations::convertRupeesToWords(
                        $request->paid_amount
                    ),
                ]);

                $to_email = "sgwebfreelancer@gmail.com";
                $make_name = hexdec(uniqid()) . "." . "pdf";
                //$pdf->save("feesreceipt", $make_name);
                $pdfPath = public_path("school/feesreceipt/" . $make_name);
                file_put_contents($pdfPath, $pdf->output());

                $path = "/school/feesreceipt/" . $make_name;
                FeesModel::find($obj->id)->update(["receipt_url" => $path]);

                // try {
                //     Mail::to("example@gmail.com")->send(
                //         new FeeCollectionMail($pdf, $student_info)
                //     );
                // } catch (Swift_TransportException $e) {
                //     dd("Email sending failed: " . $e->getMessage());
                // }
                //Mail::to($to_email)->send(new FeeCollectionMail($pdf));
            }

            DB::commit();

            return response()->json(
                ["success" => "Fee Payment Success", "path" => asset($path)],
                200
            );
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            // dd($e);
            return response()->json(["error" => $message], 400);
            // return redirect()
            //     ->back()
            //     ->withInput()
            //     ->with("exception_error", $message);
        }

        // Session::flash("success", "saved successfully");
        return redirect()
            ->route("fees.create")
            ->with("invoice", "Payment Done Successfully")
            ->with("invoicelink", $path);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
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
        $data = FeesModel::find($id);
        return view("fees::admin.edit", ["layout" => "edit", "data" => $data]);
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
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new FeesModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);

        try {
            $obj = FeesModel::find($id);
            $obj->name = $request->name;
            $obj->desc = $request->desc;
            $obj->status = $request->status;
            $obj->save();
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
        return redirect()->route("fees.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_fees)) {
            $delObj = new FeesModel();
            foreach ($request->selected_fees as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new FeesModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("fees.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-fees");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_pay_type = Configurations::getCurrentFeePaymentType();
        if ($current_pay_type == 0) {
            $current_month_year = now()
                ->startOfMonth()
                ->format("F Y");
            [$month, $year] = explode(" ", $current_month_year);
            //  $month = $current_month_year[0];
            //  $year = $current_month_year[1];
            $current_data = FeesModel::select(
                DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                "fee_collection.id as id",
                "fee_collection.student_id as student_id",
                "fee_collection.academic_year as academic_year",
                "academicyear.year as year",
                "paid_amount",
                "payment_date",
                "receipt_url",
                "students.reg_no as reg_no",
                DB::raw(
                    "CONCAT(students.first_name, students.last_name) AS full_name"
                ),
                DB::raw(
                    "(CASE WHEN " .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "0" THEN "Disabled"
                WHEN ' .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "-1" THEN "Trashed"
                ELSE "Enabled" END) AS status'
                )
            )
                ->leftjoin(
                    "students",
                    "students.id",
                    "=",
                    "fee_collection.student_id"
                )
                ->leftjoin(
                    "academicyear",
                    "academicyear.id",
                    "=",
                    "fee_collection.academic_year"
                )
                ->where("fee_collection.status", "!=", -1)
                ->where([
                    "payment_month" => $month,
                    "payment_year" => $year,
                ]);
        } elseif ($current_pay_type == 1) {
            $current_academic_term = Configurations::getCurrentAcademicterm();
            $term = ExamTermModel::where("id", $current_academic_term)->first();
            $formDate = Carbon::createFromFormat("m/d/Y", $term->from_date);

            $endDate = Carbon::createFromFormat("m/d/Y", $term->to_date);

            // Format the dates to Y/m/d format
            $start_date = $formDate->format("Y/m/d");
            $end_date = $endDate->format("Y/m/d");

            $current_data = FeesModel::select(
                DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                "fee_collection.id as id",
                "fee_collection.student_id as student_id",
                "fee_collection.academic_year as academic_year",
                "academicyear.year as year",
                "paid_amount",
                "payment_date",
                "receipt_url",
                "students.reg_no as reg_no",
                DB::raw(
                    "CONCAT(students.first_name, students.last_name) AS full_name"
                ),
                DB::raw(
                    "(CASE WHEN " .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "0" THEN "Disabled"
                WHEN ' .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "-1" THEN "Trashed"
                ELSE "Enabled" END) AS status'
                )
            )
                ->leftjoin(
                    "students",
                    "students.id",
                    "=",
                    "fee_collection.student_id"
                )
                ->leftjoin(
                    "academicyear",
                    "academicyear.id",
                    "=",
                    "fee_collection.academic_year"
                )
                ->where("fee_collection.status", "!=", -1)
                ->where("fee_collection.academic_year", $current_academic_year)
                ->whereBetween("payment_date", [$start_date, $end_date]);
        } else {
            $current_data = FeesModel::select(
                DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                "fee_collection.id as id",
                "fee_collection.student_id as student_id",
                "fee_collection.academic_year as academic_year",
                "academicyear.year as year",
                "paid_amount",
                "payment_date",
                "receipt_url",
                "students.reg_no as reg_no",
                DB::raw(
                    "CONCAT(students.first_name, students.last_name) AS full_name"
                ),
                DB::raw(
                    "(CASE WHEN " .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "0" THEN "Disabled"
                WHEN ' .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "-1" THEN "Trashed"
                ELSE "Enabled" END) AS status'
                )
            )
                ->leftjoin(
                    "students",
                    "students.id",
                    "=",
                    "fee_collection.student_id"
                )
                ->leftjoin(
                    "academicyear",
                    "academicyear.id",
                    "=",
                    "fee_collection.academic_year"
                )
                ->where("fee_collection.status", "!=", -1)
                ->where("fee_collection.academic_year", $current_academic_year);
        }
        // dd($current_data);
        $data = $current_data;
        // $data = FeesModel::select(
        //     DB::raw("@rownum  := @rownum  + 1 AS rownum"),
        //     "fee_collection.id as id",
        //     "fee_collection.academic_year as academic_year",
        //     "academicyear.year as year",
        //     "paid_amount",
        //     "payment_date",
        //     "receipt_url",
        //     "students.reg_no as reg_no",
        //     DB::raw(
        //         "CONCAT(students.first_name, students.last_name) AS full_name"
        //     ),
        //     DB::raw(
        //         "(CASE WHEN " .
        //             DB::getTablePrefix() .
        //             (new FeesModel())->getTable() .
        //             '.status = "0" THEN "Disabled"
        //     WHEN ' .
        //             DB::getTablePrefix() .
        //             (new FeesModel())->getTable() .
        //             '.status = "-1" THEN "Trashed"
        //     ELSE "Enabled" END) AS status'
        //     )
        // )
        //     ->leftjoin(
        //         "students",
        //         "students.id",
        //         "=",
        //         "fee_collection.student_id"
        //     )
        //     ->leftjoin(
        //         "academicyear",
        //         "academicyear.id",
        //         "=",
        //         "fee_collection.academic_year"
        //     )
        //     ->where("fee_collection.status", "!=", -1);

        if (Session::get("ACTIVE_GROUP") == "Student") {
            $student = StudentsModel::where(
                "user_id",
                User::getUser()->id
            )->first();

            $data = $data->where("student_id", $student->id);
        }

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
                return '<a target="_blank" class=" btn btn-info print_url" data-student="' .
                    $data->student_id .
                    '" data-paid-amount="' .
                    $data->paid_amount .
                    '" data="' .
                    $data->id .
                    '" href="' .
                    asset($data->receipt_url) .
                    '" >Receipt</a>';
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->make(true);
    }
    public function getUnpaidData(Request $request)
    {
        CGate::authorize("view-fees");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_pay_type = Configurations::getCurrentFeePaymentType();
        $fees_setup = FeeSetupModel::where(
            "academic_year",
            $current_academic_year
        )->get();
        $fees_setup_id = $fees_setup->pluck("id");
        $fees_setup_class_id = $fees_setup->pluck("class_id");
        $class_students = StudentsModel::whereIn(
            "class_id",
            $fees_setup_class_id
        )->get();
        if ($current_pay_type == 0) {
            $current_month_year = now()
                ->startOfMonth()
                ->format("F Y");
            [$month, $year] = explode(" ", $current_month_year);
            //  $month = $current_month_year[0];
            //  $year = $current_month_year[1];
            // $dateString = $month;
            // $dateParts = explode(" ", $dateString);

            // $monthName = $dateParts[0]; // March
            // $year = $dateParts[1]; // 2024

            $current_data = FeesModel::select(
                DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                "fee_collection.id as id",
                "fee_collection.academic_year as academic_year",
                "academicyear.year as year",
                "paid_amount",
                "payment_date",
                "receipt_url",
                "students.reg_no as reg_no",
                DB::raw(
                    "CONCAT(students.first_name, students.last_name) AS full_name"
                ),
                DB::raw(
                    "(CASE WHEN " .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "0" THEN "Disabled"
                WHEN ' .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "-1" THEN "Trashed"
                ELSE "Enabled" END) AS status'
                )
            )
                ->leftjoin(
                    "students",
                    "students.id",
                    "=",
                    "fee_collection.student_id"
                )
                ->leftjoin(
                    "academicyear",
                    "academicyear.id",
                    "=",
                    "fee_collection.academic_year"
                )
                ->where("fee_collection.status", "!=", -1)
                ->where([
                    "payment_month" => $month,
                    "payment_year" => $year,
                    "fee_collection.academic_year" => $current_academic_year,
                ]);

            $data1 = [];
            $paid_ids = [];
            foreach ($class_students as $student) {
                $exist = $current_data
                    ->where("student_id", $student->id)
                    ->first();
                if ($exist) {
                    $sumPaidAmount = $exist->sum("paid_amount");
                    $total_amount = $fees_setup
                        ->where("class_id", $student->class_id)
                        ->pluck("total_amount")
                        ->first();
                    if ($sumPaidAmount < $total_amount) {
                        $data1[] = $exist;
                    } else {
                        $paid_ids[] = $student->id; // Use $student->id instead of $student_id
                    }
                }
            }
            $academic_term = Configurations::getCurrentAcademicterm();
            $history_info = HistoryModel::where(
                "academic_year",
                $current_academic_year
            )->first();
            if ($history_info) {
                $fees_pay_info = json_decode(
                    $history_info->academic_year_history
                );
                $fees_info = $fees_pay_info->due_term_dates;
                $fees = $fees_info->$academic_term;
                $fees_per = $fees->per;
            } else {
                $fees_per = 0;
            }

            // dd( $fees);

            $fees_percentage = $fees_per;
        } elseif ($current_pay_type == 1) {
            $data = json_decode(
                @ConfigurationModel::where("name", "=", "feestructure")->first()
                    ->parm
            );

            $feedata = $data->dueinfo;

            if ($feedata !== null) {
                $fees = $feedata;
                $academic_term = Configurations::getCurrentAcademicterm();
                $fees_percent = $fees->$academic_term;
                $fees_per = $fees_percent->per;
            } else {
                $history_info = HistoryModel::where(
                    "academic_year",
                    $academic_year
                )->first();
                if ($history_info) {
                    $fees_pay_info = json_decode(
                        $history_info->academic_year_history
                    );
                    $fees_info = $fees_pay_info->due_term_dates;
                    $fees = $fees_info->$academic_term;
                    $fees_per = $fees->per;
                } else {
                    $fees_per = 0;
                }

                // dd( $fees);
            }

            $fees_percentage = $fees_per;
            $current_academic_term = Configurations::getCurrentAcademicterm();
            $term = ExamTermModel::where("id", $current_academic_term)->first();
            $formDate = Carbon::createFromFormat("m/d/Y", $term->from_date);

            $endDate = Carbon::createFromFormat("m/d/Y", $term->to_date);

            // Format the dates to Y/m/d format
            $start_date = $formDate->format("Y/m/d");
            $end_date = $endDate->format("Y/m/d");

            $current_data = FeesModel::select(
                DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                "fee_collection.id as id",
                "fee_collection.academic_year as academic_year",
                "academicyear.year as year",
                "paid_amount",
                "payment_date",
                "receipt_url",
                "students.reg_no as reg_no",
                DB::raw(
                    "CONCAT(students.first_name, students.last_name) AS full_name"
                ),
                DB::raw(
                    "(CASE WHEN " .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "0" THEN "Disabled"
                WHEN ' .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "-1" THEN "Trashed"
                ELSE "Enabled" END) AS status'
                )
            )
                ->leftjoin(
                    "students",
                    "students.id",
                    "=",
                    "fee_collection.student_id"
                )
                ->leftjoin(
                    "academicyear",
                    "academicyear.id",
                    "=",
                    "fee_collection.academic_year"
                )
                ->where("fee_collection.status", "!=", -1)
                ->where("fee_collection.academic_year", $current_academic_year)
                ->whereBetween("payment_date", [$start_date, $end_date]);

            $data1 = [];
            $paid_ids = [];
            foreach ($class_students as $student) {
                $exist = $current_data
                    ->where("student_id", $student->id)
                    ->first();
                if ($exist) {
                    $sumPaidAmount = $exist->sum("paid_amount");
                    $total_amount = $fees_setup
                        ->where("class_id", $student->class_id)
                        ->pluck("total_amount")
                        ->first();
                    if ($sumPaidAmount < $total_amount) {
                        $data1[] = $exist;
                    } else {
                        $paid_ids[] = $student->id; // Use $student->id instead of $student_id
                    }
                }
            }
        } else {
            $current_data = FeesModel::select(
                DB::raw("@rownum  := @rownum  + 1 AS rownum"),
                "fee_collection.id as id",
                "fee_collection.academic_year as academic_year",
                "academicyear.year as year",
                "paid_amount",
                "payment_date",
                "receipt_url",
                "students.reg_no as reg_no",
                DB::raw(
                    "CONCAT(students.first_name, students.last_name) AS full_name"
                ),
                DB::raw(
                    "(CASE WHEN " .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "0" THEN "Disabled"
                WHEN ' .
                        DB::getTablePrefix() .
                        (new FeesModel())->getTable() .
                        '.status = "-1" THEN "Trashed"
                ELSE "Enabled" END) AS status'
                )
            )
                ->leftjoin(
                    "students",
                    "students.id",
                    "=",
                    "fee_collection.student_id"
                )
                ->leftjoin(
                    "academicyear",
                    "academicyear.id",
                    "=",
                    "fee_collection.academic_year"
                )
                ->where("fee_collection.status", "!=", -1)
                ->where("fee_collection.academic_year", $current_academic_year);

            $data1 = [];
            $paid_ids = [];
            foreach ($class_students as $student) {
                $exist = $current_data
                    ->where("student_id", $student->id)
                    ->first();
                if ($exist) {
                    $sumPaidAmount = $exist->sum("paid_amount");
                    $total_amount = $fees_setup
                        ->where("class_id", $student->class_id)
                        ->pluck("total_amount")
                        ->first();
                    if ($sumPaidAmount < $total_amount) {
                        $data1[] = $exist;
                    } else {
                        $paid_ids[] = $student->id; // Use $student->id instead of $student_id
                    }
                }
            }
        }
        $data = collect($class_students)->whereNotIn("id", $paid_ids);
        $unpaid_data = $data1;
        $fees_type = "unpaid";

        $payment_type = $current_pay_type;

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("unpaid_amount", function ($data) use (
                $unpaid_data,
                $payment_type,
                $current_academic_year,
                $fees_setup,
                $fees_percentage
            ) {
                $monthsPay = Configurations::GetMonthsOfAcademicYear(
                    $current_academic_year,
                    null
                );
                $academic_fee_info_sum = DB::table("academic_fees")
                    ->where("student_id", $data->id)
                    ->sum("due_amount");
                $total_amount = $fees_setup
                    ->where("class_id", $data->class_id)
                    ->pluck("total_amount")
                    ->first();
                if ($data->scholarship !== null) {
                    $schamount =
                        ($data->scholarship / 100) * $total_amount +
                        $academic_fee_info_sum;

                    $total = $total_amount + $academic_fee_info_sum;

                    $grand_total = $total - $schamount;
                } else {
                    $grand_total = $total_amount + $academic_fee_info_sum;
                }
                if ($payment_type == 0) {
                    $monthlyPayment = $grand_total / sizeof($monthsPay);
                }
                if ($payment_type == 1) {
                    $termsPay = $grand_total * ($fees_percentage / 100);
                }
                if ($payment_type == 2) {
                    $onePay = $grand_total;
                }
                $exist = collect($unpaid_data)
                    ->where("student_id", $data->id)
                    ->first();

                if ($exist) {
                    $paid_amount = $exist->sum("paid_amount");

                    if ($payment_type == 0) {
                        $tot_amount = $monthlyPayment - $paid_amount;
                    }
                    if ($payment_type == 1) {
                        $tot_amount = $termsPay - $paid_amount;
                    }
                    if ($payment_type == 2) {
                        $tot_amount = $onePay - $paid_amount;
                    }
                    $amount = $tot_amount;
                } else {
                    if ($payment_type == 0) {
                        $tot_amount = $monthlyPayment;
                    }
                    if ($payment_type == 1) {
                        $tot_amount = $termsPay;
                    }
                    if ($payment_type == 2) {
                        $tot_amount = $onePay;
                    }
                    $amount = $tot_amount;
                }
                return round($amount);
            })
            ->addColumn("status", function ($data) {
                return "<span class='text-danger'>Unpaid</span>";
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
            ->addColumn("action", function ($data) use (
                $fees_setup,
                $payment_type
            ) {
                $total_amount = $fees_setup
                    ->where("class_id", $data->class_id)
                    ->pluck("total_amount")
                    ->first();
                return " <button type='button' class='btn btn-default view_fees_unpaid' data-toggle='modal' data-student-id='$data->id' data-unpaid-id = '1' data-payment-type = '$payment_type' data-total-amount='$total_amount'><i class='fa fa-eye'></i></button>";
            })
            ->with([
                "unpaid_data" => $unpaid_data,
                "payment_type" => $payment_type,
                "current_academic_year" => $current_academic_year,
                "fees_setup" => $fees_setup,
                "fees_percentage" => $fees_percentage,
            ]);

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->rawColumns(["status", "action"])->make(true);
    }
    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-fees");
        if ($request->ajax()) {
            FeesModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_fees)) {
            $obj = new FeesModel();
            foreach ($request->selected_fees as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function FeesPayment(Request $request)
    {
        if ($request->ajax()) {
            $academic_year = $request->query->get("academic_year", 0);
            $academic_term = $request->query->get("academic_term", 0);
            $class_id = $request->query->get("class_id", 0);
            $section_id = $request->query->get("section", 0);
            $school_type = $request->query->get("school_type", 0);
            $month = $request->query->get("month", 0);
            $payment_type = $request->query->get("payment_type", 0);
            $type = $request->query->get("type", 0);
            $reminder = $request->query("reminder", 0);
            $fees_percentage = 0;
            $reminder_text = Configurations::getFeesReminderText();
            $students = StudentsModel::where([
                "academic_year" => $academic_year,
                "class_id" => $class_id,
                "section_id" => $section_id,
            ])->get();
            if ($type == 3) {
                $academic_terms = DB::table("exam_term")
                    ->where("academic_year", $academic_year)
                    ->select("id", "exam_term_name as text")
                    ->get();
                $history = DB::table("history")
                    ->where("academic_year", $academic_year)
                    ->pluck("academic_year_history")
                    ->first();
                $current_academic_year = Configurations::getCurrentAcademicyear();
                if ($academic_year == $current_academic_year) {
                    $current_config = ConfigurationModel::where("name", "site")
                        ->select("parm")
                        ->first();
                    $data_config = json_decode($current_config);
                    $parm = json_decode($data_config->parm);
                    $payment_type = $parm->payment_type;
                    return response()->json([
                        "academic_terms" => $academic_terms,
                        "payment_type" => $payment_type,
                        "data" => $data_config,
                    ]);
                }
                if ($history) {
                    $data = json_decode($history);
                    $payment_type = $data->payment_type;
                    return response()->json([
                        "academic_terms" => $academic_terms,
                        "payment_type" => $payment_type,
                    ]);
                }

                $type = Configurations::getCurrentFeePaymentType();
                $payment_types = array_combine(
                    array_keys(Configurations::FEEPAYMENTTYPES),
                    Configurations::FEEPAYMENTTYPES
                );
                return response()->json([
                    "academic_terms" => $academic_terms,
                    "payment_types" => $payment_types,
                    "type" => $type,
                ]);
            }

            if ($type == 2) {
                $unpaidAmounts = $request->input("unpaid_amount");
                $total_amount = FeeSetupModel::where([
                    "academic_year" => $academic_year,
                    "class_id" => $class_id,
                    "school_type" => $school_type,
                ])
                    ->pluck("total_amount")
                    ->first();

                if ($payment_type == 0) {
                    $dateString = $month;
                    $dateParts = explode(" ", $dateString);

                    $monthName = $dateParts[0]; // March
                    $year = $dateParts[1]; // 2024

                    $data = FeesModel::with("academicyear", "student")
                        ->where([
                            "academic_year" => $academic_year,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                            "payment_year" => $year,
                            "pay_type" => $payment_type,
                        ])
                        ->get();

                    $data1 = [];
                    $paid_ids = [];
                    foreach ($students as $student) {
                        $exist = $data
                            ->where("student_id", $student->id)
                            ->first();
                        if ($exist) {
                            $sumPaidAmount = $exist->sum("paid_amount");
                            if ($sumPaidAmount < $total_amount) {
                                $data1[] = $exist;
                            } else {
                                $paid_ids[] = $student->id; // Use $student->id instead of $student_id
                            }
                        }
                    }
                } elseif ($payment_type == 1) {
                    $data = json_decode(
                        @ConfigurationModel::where(
                            "name",
                            "=",
                            "feestructure"
                        )->first()->parm
                    );
                    $feedata = json_decode($data->dueinfo);
                    if ($feedata !== null) {
                        $fees = $feedata;
                        $fees_per = $fees->where("id", $academic_term);
                    } else {
                        $history_info = HistoryModel::where(
                            "academic_year",
                            $academic_year
                        )->first();
                        $fees_pay_info = json_decode(
                            $history_info->academic_year_history
                        );
                        $fees_info = $fees_pay_info->due_term_dates;
                        $fees = $fees_info->$academic_term;
                        $fees_per = $fees->per;
                        // dd( $fees);
                    }

                    $fees_percentage = $fees_per;

                    // dd($fees_percentage);

                    $term = ExamTermModel::where("id", $academic_term)->first();
                    // Convert the form_date and end_date to Carbon instances
                    // dd($term);
                    // dd( $term->from_date,$term->to_date);
                    $formDate = Carbon::createFromFormat(
                        "m/d/Y",
                        $term->from_date
                    );

                    $endDate = Carbon::createFromFormat(
                        "m/d/Y",
                        $term->to_date
                    );

                    // Format the dates to Y/m/d format
                    $start_date = $formDate->format("Y/m/d");
                    $end_date = $endDate->format("Y/m/d");

                    $data = FeesModel::with("academicyear", "student")
                        ->where([
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                            "pay_type" => $payment_type,
                        ])
                        ->whereBetween("payment_date", [$start_date, $end_date])
                        ->where("paid_amount", "!=", $total_amount)
                        ->get();

                    $data1 = [];
                    $paid_ids = [];
                    foreach ($students as $student) {
                        $exist = $data
                            ->where("student_id", $student->id)
                            ->first();
                        if ($exist) {
                            $sumPaidAmount = $exist->sum("paid_amount");
                            if ($sumPaidAmount < $total_amount) {
                                $data1[] = $exist;
                            } else {
                                $paid_ids[] = $student->id; // Use $student->id instead of $student_id
                            }
                        }
                    }
                } else {
                    $data = FeesModel::with("academicyear", "student")
                        ->where([
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                            "pay_type" => $payment_type,
                        ])
                        ->where("paid_amount", "!=", $total_amount)
                        ->get();
                    $data1 = [];
                    $paid_ids = [];
                    foreach ($students as $student) {
                        $exist = $data
                            ->where("student_id", $student->id)
                            ->first();
                        if ($exist) {
                            $sumPaidAmount = $exist->sum("paid_amount");
                            if ($sumPaidAmount < $total_amount) {
                                $data1[] = $exist;
                            } else {
                                $paid_ids[] = $student->id; // Use $student->id instead of $student_id
                            }
                        }
                    }
                }
                $fees_type = "unpaid";

                $unpaid_students = collect($students)->whereNotIn(
                    "id",
                    $paid_ids
                );
                // dd($unpaidAmounts);
                if ($reminder == 1) {
                    $view = view(
                        "fees::admin.reminder",
                        compact(
                            "unpaid_students",
                            "data1",
                            "total_amount",
                            "fees_type",
                            "academic_year",
                            "class_id",
                            "section_id",
                            "school_type",
                            "reminder_text",
                            "unpaidAmounts"
                        )
                    )->render();
                } else {
                    $view = view(
                        "fees::admin.fees_payment_append",
                        compact(
                            "unpaid_students",
                            "data1",
                            "total_amount",
                            "fees_type",
                            "fees_percentage",
                            "academic_year",
                            "payment_type"
                        )
                    )->render();
                }

                return response()->json([
                    "data" => $data,
                    "view" => $view,
                    "paid" => $paid_ids,
                    "academic_year" => $academic_year,
                    "class_id" => $class_id,
                    "section_id" => $section_id,
                    "pay_type" => $payment_type,
                    "total_amt" => $total_amount,
                    "unpaidAmounts" => $unpaidAmounts,
                ]);
            }

            if ($type == 1) {
                if ($payment_type == 0) {
                    $dateString = $month;
                    $dateParts = explode(" ", $dateString);

                    $monthName = $dateParts[0]; // March
                    $year = $dateParts[1]; // 2024

                    // Convert month name to month number
                    // $monthNumber = date('m', strtotime($monthName));

                    // $start_date = date('m/d/Y', mktime(0, 0, 0, $monthNumber, 1, $year));
                    // $end_date = date('m/d/Y', mktime(0, 0, 0, $monthNumber + 1, 0, $year));

                    $data = FeesModel::with("academicyear", "student")
                        ->where([
                            "academic_year" => $academic_year,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                            "payment_month" => $monthName,
                            "payment_year" => $year,
                            "pay_type" => $payment_type,
                        ])
                        ->get();
                } elseif ($payment_type == 1) {
                    $term = ExamTermModel::where("id", $academic_term)->first();
                    // Convert the form_date and end_date to Carbon instances
                    $formDate = Carbon::createFromFormat(
                        "m/d/Y",
                        $term->form_date
                    );
                    $endDate = Carbon::createFromFormat(
                        "m/d/Y",
                        $term->end_date
                    );

                    // Format the dates to Y/m/d format
                    $start_date = $formDate->format("Y/m/d");
                    $end_date = $endDate->format("Y/m/d");

                    $data = FeesModel::with("academicyear", "student")
                        ->where([
                            "academic_year" => $academic_year,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                            "pay_type" => $payment_type,
                        ])
                        ->whereBetween("payment_date", [$start_date, $end_date])
                        ->get();
                } else {
                    $data = FeesModel::with("academicyear", "student")
                        ->where([
                            "academic_year" => $academic_year,
                            "class_id" => $class_id,
                            "section_id" => $section_id,
                            "pay_type" => $payment_type,
                        ])
                        ->get();
                }
                $fees_type = "paid";
                $view = view(
                    "fees::admin.fees_payment_append",
                    compact("students", "data", "fees_type")
                )->render();
                if (count($data) > 0) {
                    $btn_exist = 1;
                } else {
                    $btn_exist = 0;
                }

                return response()->json([
                    "data" => $data,
                    "view" => $view,
                    "btn_exist" => $btn_exist,
                ]);
            }
        }
    }

    public function FeesReminder(Request $request)
    {
        // dd($request->all());
        $type = $request->query("type", 0);
        $reminder_text = configurations::GetFeesReminderText();
        if ($type == 1) {
            $unpaid_start = $request->query("unpaid_start", 0);
            if ($unpaid_start == 1) {
                $view_index = view("fees::admin.unpaid_student_view", [
                    "name" => $request->name,
                    "reg_no" => $request->reg_no,
                    "unpaid_amount" => $request->unpaid_amount,
                    "total_amount" => $request->total_amount,
                    "payment_type" => $request->payment_type,
                    "unpaid_start" => $unpaid_start,
                ])->render();
            } else {
                $view_index = view("fees::admin.unpaid_student_view", [
                    "name" => $request->name,
                    "reg_no" => $request->reg_no,
                    "unpaid_amount" => $request->unpaid_amount,
                    "total_amount" => $request->total_amount,
                    "scholarship" => $request->scholarship,
                    "monthly_amount" => $request->monthly_amount,
                    "term_amount" => $request->term_amount,
                    "one_pay_amount" => $request->one_pay_amount,
                    "payment_type" => $request->payment_type,
                    "unpaid_start" => $unpaid_start,
                ])->render();
            }
            $view = $view_index;
        } else {
            $view = view("fees::admin.reminder", [
                "layout" => "create",
            ])->render();
        }

        return response()->json(["view" => $view]);
    }

    public function ConfirmFeesReminder(Request $request)
    {
        if ($request->submit == "shedule") {
            $this->validate($request, [
                "schedule_date" => "required",
                "schedule_time" => "required",
                function ($attribute, $value, $fail) {
                    if ($value === "00:00") {
                        $fail("The schedule time cannot be 00:00.");
                    }
                },
                "shedule_reminder_text" => "required",
            ]);
        }

        try {
            DB::beginTransaction();
            $fees_reminder = new FeeReminderModel();
            $fees_reminder->school_type = $request->school_type;
            $fees_reminder->class_id = $request->class_id;
            $fees_reminder->section_id = $request->section_id;
            $fees_reminder->reminder_type = $request->submit;
            if ($request->submit == "instant") {
                $dateTime = Carbon::now();
                $date = $dateTime->toDateString();
                $time = $dateTime->format("H:i");
                $reminder_text = $request->reminder_text;
                $fees_reminder->reminder_text = $reminder_text;
            } else {
                $date = $request->schedule_date;
                $time = $request->schedule_time;
                $reminder_text = $request->shedule_reminder_text;
                $fees_reminder->reminder_text = $reminder_text;
            }

            $fees_reminder->date = $date;
            $fees_reminder->time = $time;
            $fees_reminder->save();
            if ($fees_reminder->save()) {
                $reminder_id = FeeReminderModel::where([
                    "school_type" => $request->school_type,
                    "class_id" => $request->class_id,
                    "section_id" => $request->section_id,
                    "reminder_type" => $request->submit,
                    "date" => $date,
                    "time" => $time,
                ])
                    ->pluck("id")
                    ->first();
                $reminder_txt = FeeReminderModel::where([
                    "school_type" => $request->school_type,
                    "class_id" => $request->class_id,
                    "section_id" => $request->section_id,
                    "reminder_type" => $request->submit,
                    "date" => $date,
                    "time" => $time,
                ])
                    ->pluck("reminder_text")
                    ->first();
                foreach ($request->student as $key => $data) {
                    $student_data = StudentsModel::where("id", $data)->first();
                    $school_name = Configurations::getConfig("site")
                        ->school_name
                        ? Configurations::getConfig("site")->school_name
                        : "S-Management";
                    $logoUrl = Configurations::getConfig("site")->imagec;
                    // dd($logoUrl);
                    $unpaid_data = [
                        "name" => $student_data->first_name,
                        "unpaid_amount" => $request->unpaid_amount[$key],
                        "gender" => $student_data->gender,
                        "school" => $school_name,
                        "logoUrl" => $logoUrl,
                    ];
                    //   dd($unpaid_data['name'],$unpaid_data['unpaid_amount'],$unpaid_data['gender']);
                    $env = config("app.env");
                    // dd($env);
                    if ($env == "local") {
                        \CmsMail::setMailTrapConfig();
                    } else {
                        \CmsMail::setMailConfig();
                    }
                    // \CmsMail::setMailTrapConfig();
                    $mail = Mail::to($student_data->email)->send(
                        new UnpaidEmail(
                            $unpaid_data["name"],
                            $unpaid_data["unpaid_amount"],
                            $unpaid_data["gender"],
                            $reminder_txt,
                            $unpaid_data["school"],
                            $unpaid_data["logoUrl"]
                        )
                    );
                    if ($mail) {
                        $fee_reminder_mapping = new FeeReminderMappingModel();
                        $fee_reminder_mapping->fee_reminder_id = $reminder_id;
                        $fee_reminder_mapping->student_id = $data;
                        $fee_reminder_mapping->unpaid_amount =
                            $request->unpaid_amount[$key];
                        $fee_reminder_mapping->save();
                    }

                    $messaging = app("firebase.messaging");
                    $user = UserModel::where(
                        "id",
                        $student_data->user_id
                    )->first();

                    if ($user && $user->device_token) {
                        $result = $messaging->validateRegistrationTokens(
                            $user->device_token
                        );

                        if ($result && sizeof($result["valid"])) {
                            $success = Notification::sendNow(
                                $user,
                                new FirebasePushNotification(
                                    "Fee unpaid Notification",
                                    "Please pay Your Upcomming Month fees",
                                    [
                                        "route" => "notification",
                                        "type" => "global",

                                        "student_name" => $user->username,
                                    ],
                                    null
                                )
                            );
                        }
                    }
                }
            }
            DB::commit();
            Session::flash("success", "Reminder created Successfully!!");
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            // dd($e);
            Session::flash("error", $e->getMessage());
            return redirect()->back();
        }
    }

    public function BulkPrint(Request $request)
    {
        // dd($request->all());
        $student_info = StudentsModel::select(
            "students.id as id",
            "students.reg_no as reg_no",
            DB::raw("CONCAT(first_name, last_name) AS full_name"),
            "parent.father_name as parentname",
            "parent.guardian_name as guardianname",
            "lclass.name as classname",
            "section.name as sectionname"
        )
            ->leftJoin("parent", "parent.id", "=", "students.parent_id")
            ->leftJoin("lclass", "lclass.id", "=", "students.class_id")
            ->leftJoin("section", "section.id", "=", "students.section_id")
            ->whereIn("students.id", $request->student_id)
            ->get();
        // dd( $request->all());
        $config = Configurations::getConfig("site");

        if ($request->type == "term") {
            $term_name = ExamTermModel::find($request->selected_term)
                ->exam_term_name;
        } else {
            $term_name = ExamTermModel::find(
                Configurations::getCurrentAcademicterm()
            )->exam_term_name;
        }

        $academic = AcademicyearModel::find($request->academic_year)->year;

        DB::beginTransaction();

        $feedata = json_decode(
            @ConfigurationModel::where("name", "=", "feestructure")->first()
                ->parm
        );

        $fee_info = FeesModel::withTrashed()
            ->latest("id")
            ->first();

        $bill_no = Configurations::GenerateUsername(
            $fee_info != null ? $fee_info->bill_no : null,
            "F"
        );
        $host = request()->getHttpHost();
        $words = [];
        foreach ($request->paid_amount as $amount) {
            $words[] = Configurations::convertRupeesToWords($amount);
        }

        return view("fees::pdf.receiptpdfbulk", [
            "student_details" => $student_info,
            "fee_info" => $fee_info,
            "config" => $config,
            "term_name" => $term_name,
            "academic" => $academic,
            "paid_amount" => $request->paid_amount,
            "current_url" => $host,
            "words" => $words,
        ]);
    }
}
