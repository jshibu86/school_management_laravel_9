<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use cms\attendance\Traits\AttendanceTrait;
use cms\event\Models\EventModel;
use cms\students\Models\StudentsModel;
use Illuminate\Http\Request;
use Configurations;
use DB;
use File;
use Carbon\Carbon;
use cms\academicyear\Models\AcademicyearModel;
use cms\classtimetable\Models\ClasstimetableModel;
use cms\core\configurations\Models\ConfigurationModel;
use cms\dormitory\Models\DormitoryStudentModel;
use cms\exam\Models\ExamTermModel;
use cms\fees\Models\AcademicFeeModel;
use cms\fees\Models\FeeSetupModel;
use cms\productcategory\Models\ProductcategoryModel;
use cms\shop\Models\OrderItemsModel;
use cms\shop\Models\OrderModel;
use cms\shop\Models\ProductModel;
use cms\wallet\Models\WalletModel;
use cms\exam\Models\ExamModel;
use cms\core\user\Models\UserModel;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\teacher\Models\TeacherModel;
use cms\fees\Models\FeesModel;
use Dompdf\Dompdf;
use Dompdf\Options;
use PDF;

class StudentController extends StudentBaseController
{
    use ApiResponse, AttendanceTrait, FileUploadTrait;
    //

    public function StudentLandingPage(Request $request)
    {
        // get All Events
        [
            $date,
            $time,
            $currentMonth,
            $currentYear,
            $current_day,
            $dayOfWeek,
        ] = Configurations::getcurrentDateTime();

        // Validate date and time
        try {
            $dateObj = new \DateTime($date);
            $timeObj = new \DateTime($time);
        } catch (\Exception $e) {
            return $this->error("Invalid date or time format.", 500);
        }

        $current_academic = Configurations::getAcademicandTermsInfo();

        try {
            $events = EventModel::where("status", 1)->get();

            $weekends = Configurations::getConfig("site")->week_end;

            $currentacademic_term = Configurations::getCurrentAcademicterm();

            $student_id = $this->GetStudent($request->user()->id);
            // dd($student_id);
            $wallet = WalletModel::where(
                "parent_id",
                $student_id->parent_id
            )->first();
            $wallet_amount = $wallet ? $wallet->wallet_amount : 0;
            $cal = $this->AttendanceWeekCalculation($weekends, $student_id->id);
            if (!empty($cal)) {
                [$weeks, $datesInMonth, $lastFormat, $total_percentage] = $cal;
            }
            $dayofWeek = $dayOfWeek == 0 ? 7 : $dayOfWeek; //0 for sunday

            $timetable = ClasstimetableModel::select(
                "classtimetable.id",
                "classtimetable.class_id",
                "section_id",
                "teacher_id",
                "subject_id",
                "period_id",
                "period.from",
                "period.to",
                "teacher.teacher_name",
                "subject.name as subjectname"
            )
                ->where([
                    "classtimetable.academic_year" =>
                        $current_academic["current_academic_year"],
                    "day" => $dayOfWeek,
                    "classtimetable.class_id" => $this->GetStudent(
                        $request->user()->id
                    )->class_id,
                    "section_id" => $this->GetStudent($request->user()->id)
                        ->section_id,
                ])
                ->where(function ($query) use ($time) {
                    $query->where("period.from", ">", $time);
                })
                ->join(
                    "period_class_mapping as period",
                    "period.id",
                    "=",
                    "classtimetable.period_id"
                )
                ->join(
                    "teacher",
                    "teacher.id",
                    "=",
                    "classtimetable.teacher_id"
                )
                ->join(
                    "subject",
                    "subject.id",
                    "=",
                    "classtimetable.subject_id"
                )
                ->get();

            $timezone = Configurations::getConfig("site")->time_zone;
            $currentDateTime = Carbon::now($timezone)->format("m/d/Y g:i a");

            $upcoming_assignments = ExamModel::with(
                "academyyear",
                "class",
                "section",
                "subject",
                "notification"
            )
                ->where([
                    "type_of_exam" => "homework",
                    "exam_term" => $currentacademic_term,
                    "class_id" => $student_id->class_id,
                    "section_id" => $student_id->section_id,
                ])
                ->whereRaw(
                    "STR_TO_DATE(CONCAT(exam_date, ' ', exam_time), '%m/%d/%Y %l:%i %p') >= STR_TO_DATE(?, '%m/%d/%Y %l:%i %p')",
                    [$currentDateTime]
                )
                ->first();

            // dd($upcoming_assignments);
            $data = [
                "events" => $events,
                "upcommingclasses" => $timetable,
                "upcoming_assignments" => $upcoming_assignments,
                "total_percentage" => $total_percentage ?? null,
                "weekattendance" => $lastFormat ?? null,
                "walletamount" => Configurations::CurrencyFormat(
                    $wallet_amount
                ),
            ];

            return $this->success($data, "Successfully Data Fetched", 200);
        } catch (\Exception $e) {
            // dd($e);
            return $this->error($e, 500);
        }
    }

    public function TuckShopCategories(Request $request)
    {
        try {
            // tuckshop categories

            $categories = ProductcategoryModel::select(
                "id",
                "category_name",
                "category_image"
            )
                ->where("status", 1)
                ->where("category_type", 1)
                ->get();

            $data = [
                "categories" => $categories,
            ];
            return $this->success($data, "Successfully Data Fetched", 200);

            // products
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
    // public function studentsList(Request $request){
    //     $class_id = $request->class_id;
    //     $section_id = $request->section_id;
    //     $type = $request->type;
    //     if($type == "add")
    //     {
    //        return "student added Successfully";
    //     }
    //     if($type == "view"){
    //         $id = $request->id;

    //         $student = StudentsModel::where('id',$id)->get();

    //         return $student;
    //     }
    //     $students = StudentsModel::where([
    //         "class_id" => $class_id,
    //         "section_id" => $section_id,
    //     ])
    //         ->where("status", 1)
    //         ->select([
    //             "students.id as id",
    //             DB::raw(
    //                 "CONCAT(students.username, ' - ', students.email) as text"
    //             ),
    //         ])
    //         ->get();

    //     return $students;
    // }
    public function Products(Request $request)
    {
        $category_id = $request->query->get("category_id", 0) ?? null;
        $is_recommend = $request->query->get("is_recommend", 0) ?? null;
        $is_popular = $request->query->get("is_popular", 0) ?? null;

        // popular products
        if (
            $category_id != null &&
            $is_recommend != null &&
            $is_popular != null
        ) {
            $products = ProductModel::with("category:id,category_name")
                ->select(
                    "products.id",
                    "products.product_name",
                    "product_thambnail",
                    "products.product_code",
                    "selling_price",

                    "category_id",
                    "product_qty as stock",
                    DB::raw("COALESCE(SUM(order_items.qty), 0) as total_sales")
                )
                ->leftJoin(
                    "order_items",
                    "products.id",
                    "=",
                    "order_items.product_id"
                )
                ->when($category_id, function ($query) use ($category_id) {
                    return $query->where("category_id", $category_id);
                })
                ->when($is_recommend, function ($query) use ($is_recommend) {
                    return $query->where("is_recommended", $is_recommend);
                })
                ->when(
                    $is_popular,
                    function ($query) {
                        return $query
                            ->groupBy("products.id")
                            ->orderByDesc("total_sales");
                    },
                    function ($query) {
                        return $query->orderBy("products.id"); // Order by product ID if not popular
                    }
                )

                ->where("products.status", "!=", -1)
                ->groupBy("products.id")
                ->where("product_type", Configurations::SHOPPRODUCT)

                ->paginate(12);
        } else {
            $products = ProductModel::with("category:id,category_name")
                ->select(
                    "products.id",
                    "products.product_name",
                    "product_thambnail",
                    "products.product_code",
                    "selling_price",

                    "category_id",
                    "product_qty as stock",
                    DB::raw("COALESCE(SUM(order_items.qty), 0) as total_sales")
                )
                ->leftJoin(
                    "order_items",
                    "products.id",
                    "=",
                    "order_items.product_id"
                )

                ->where("products.status", "!=", -1)
                ->groupBy("products.id")
                ->where("product_type", Configurations::SHOPPRODUCT)

                ->get();
        }

        $data = [
            "products" => $products,
        ];

        return $this->success($data, "Successfully Data Fetched", 200);
    }

    public function Checkout(Request $request)
    {
        $this->validate(
            $request,
            [
                "payment_type" => "required",
            ],
            ["payment_type" => "Please Select a Payment Type"]
        );
        DB::beginTransaction();
        try {
            $student_id = $this->GetStudent($request->user()->id);

            $order_number =
                "SM/" . date("y") . "/" . mt_rand(10000000, 99999999);

            $obj = new OrderModel();

            $obj->student_id = $student_id->id;
            $obj->user_id = $student_id->user_id;

            $obj->order_number = $order_number;
            $obj->order_amount = $request->total_amount;
            $obj->payment_type =
                $request->payment_type == 1 ? "wallet" : "fluterwave";
            $obj->currency = "NGN";
            $obj->order_date = date("Y-m-d");
            $obj->order_month = Carbon::now()->format("F");
            $obj->order_year = Carbon::now()->format("Y");
            $obj->processing_date = Carbon::now()->format("d F Y");

            if ($obj->save()) {
                // saveign order products

                foreach ($request->carts as $cart) {
                    $product = ProductModel::where(
                        "id",
                        $cart["product_id"]
                    )->first();

                    if ($product) {
                        $ord_item = new OrderItemsModel();
                        $ord_item->order_id = $obj->id;
                        $ord_item->product_id = $cart["product_id"];
                        $ord_item->product_name = $product->product_name;
                        $ord_item->product_code = $product->product_code;
                        $ord_item->product_price = $product->selling_price;
                        $ord_item->qty = $cart["qty"];
                        $ord_item->total_price =
                            $product->selling_price * $cart["qty"];
                        $ord_item->product_image = $product->product_thambnail;

                        $ord_item->save();
                    }
                }

                // amount deduction from wallet

                if ($request->payment_type == Configurations::WALLETPAYMENT) {
                    $wallet = WalletModel::where(
                        "parent_id",
                        $student_id->parent_id
                    )->first();

                    $amount = $wallet->wallet_amount - $request->total_amount;

                    $wallet->update(["wallet_amount" => $amount]);

                    $order = OrderModel::find($obj->id)->update([
                        "payment_status" => 1,
                        "order_status" => 3,
                    ]);
                }
                $ordered_items = OrderItemsModel::where(
                    "order_id",
                    $obj->id
                )->get();
                foreach ($ordered_items as $item) {
                    // find product

                    $product = ProductModel::find($item->product_id);

                    if ($product) {
                        $product->update([
                            "product_qty" => $product->product_qty - $item->qty,
                        ]);
                    }
                }

                // sending oreder mail
            } else {
                DB::rollback();
                return $this->error("Whoops Something Went Wrong", 500);
            }

            DB::commit();
            return $this->success(
                ["succes" => true],
                "Checkout Completed",
                200
            );
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            return $this->error($e, 500);
        }
    }

    public function GetAllEvents(Request $request)
    {
        // singleevent

        $event_id = $request->query->get("event_id", 0);

        if ($event_id) {
            $events = EventModel::find($event_id);
            return $events;
        }
        $events = EventModel::where("status", 1)->get();

        return $events;
    }

    public function GetHostelInformation(Request $request)
    {
        $info = DormitoryStudentModel::with("room", "dormitory")
            ->where("academic_year", Configurations::getCurrentAcademicyear())
            ->where("student_id", $this->GetStudent($request->user()->id)->id)
            ->first();

        if (!$info) {
            return $this->error("You Are Not Subscribed Hostel", 200);
        }
        return $this->success($info, "Successfully Data Fetched", 200);
    }

    public function StudentAttendanceHistory(Request $request)
    {
        $student_id = $this->GetStudent($request->user()->id)->id;

        $current_academic_year_info = AcademicyearModel::find(
            Configurations::getCurrentAcademicyear()
        );
        $weekends = Configurations::getConfig("site")->week_end;

        $start_date_month = Carbon::parse(
            $current_academic_year_info->start_date
        )

            ->startOfMonth()
            ->copy()
            ->format("Y-m-d");
        // dd($current_academic_year_info);
        $calender = $this->createAttendanceCalenderTwoDates(
            $start_date_month,
            $current_academic_year_info->end_date,
            true,
            $weekends,
            $student_id,
            $current_academic_year_info->id
        );

        return response()->json(["calender" => $calender]);
    }

    // student fees information

    public function MakeFeePayment(Request $request)
    {
        try {
            $student_id = $request->user()->student->id;
            // dd($student_id);
            $academic_year = Configurations::getCurrentAcademicyear();
            $term = ExamTermModel::find(
                Configurations::getCurrentAcademicterm()
            )->exam_term_name;

            $student_info = StudentsModel::with(
                "class",
                "section",
                "department"
            )->find($student_id);

            $feesetup = FeeSetupModel::with("feelists")
                ->where([
                    "class_id" => $student_info->class_id,
                    "academic_year" => Configurations::getCurrentAcademicyear(),
                ])
                ->first();
            // dd($feesetup);
            $scholarship = StudentsModel::find($student_id)->scholarship;
            if (!$feesetup) {
                return response()->json(
                    [
                        "error" => "There is No FeeSetup Available",
                    ],
                    500
                );
            }

            $feedata = json_decode(
                @ConfigurationModel::where("name", "=", "feestructure")->first()
                    ->parm
            );
            // dd($feedata);
            if (!$feedata) {
                return response()->json(
                    [
                        "error" =>
                            "There is No FeeStructure Available Kindly Setup in Configurations Settings",
                    ],
                    500
                );
            }

            $fees_array = [];

            // dd($feesetup->feelists);
            foreach (@$feesetup->feelists as $list) {
                $fees_array[] = [
                    "fee_id" => $list->id,
                    "fee_name" => $list->fee_name,
                    "fee_amount" => $list->fee_amount,
                    "is_compulsury" => $list->is_compulsory,
                ];
            }

            $academic_fee_info_sum = AcademicFeeModel::where(
                "student_id",
                $student_id
            )->sum("due_amount");

            $academic_fee_info = AcademicFeeModel::where(
                "student_id",
                $student_id
            )->get();

            // dd($academic_fee_info);
            if (@$academic_fee_info) {
                foreach (@$academic_fee_info as $info) {
                    $fees_array[] = [
                        "fee_id" => $info->id,
                        "fee_name" => @$info->fee_name,
                        "fee_amount" => $info->due_amount,
                        "is_compulsury" => 0,
                    ];
                }
            }
            $total_amount = 0;
            $term_due = [];
            $month_due = [];
            $full_due = [];
            $fee_compulsory_total = $feesetup->feelists
                ->where("is_compulsory", 1)
                ->sum("fee_amount");
            if ($scholarship) {
                $schamount =
                    ($scholarship / 100) * $fee_compulsory_total +
                    $academic_fee_info_sum;

                $total_amount = $fee_compulsory_total + $academic_fee_info_sum;
                $grand_total = $total_amount - $schamount;
            } else {
                $grand_total = $fee_compulsory_total + $academic_fee_info_sum;
            }
            if ($scholarship) {
                $schamount = ($scholarship / 100) * $feesetup->total_amount;

                $total_amount = $feesetup->total_amount;
                $comp_grand_total = $total_amount - $schamount;
            } else {
                $comp_grand_total = $feesetup->total_amount;
            }
            // $feedata->payment_type = 2;
            $fee_split = Configurations::FEESPLIT;
            if ($feedata->payment_type == 1) {
                // termwise pay method
                // dd($feedata->dueinfo);
                if (isset($feedata->dueinfo)) {
                    foreach ($feedata->dueinfo as $termid => $due) {
                        if (!isset($term_due[$termid])) {
                            $term_due[$termid] = new \stdClass();
                            $term_due[$termid]->terminfo = ExamTermModel::find(
                                $termid
                            )->exam_term_name;
                            $term_due[$termid]->duedate = $due->date;
                            $term_due[$termid]->per = $due->per;
                            $term_due[$termid]->amount =
                                ($due->per / 100) * $comp_grand_total;
                            $is_paid = FeesModel::where([
                                "academic_year" => $academic_year,
                                "student_id" => $student_id,
                                "pay_term_id" => $termid,
                            ])->first();
                            $term_due[$termid]->ispaid = $is_paid
                                ? $is_paid
                                : null;
                        }
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
                            $comp_grand_total -
                            $monthlyPayment * sizeof($monthsPay) -
                            1;
                    }

                    if (!isset($month_due[$i])) {
                        $month_due[$i] = new \stdClass();
                        $month_due[$i]->year = $monthsPay[$i]["year"];
                        $month_due[$i]->month = $monthsPay[$i]["month"];

                        $month_due[$i]->amount = $paymentAmount;
                        $is_paid = FeesModel::where([
                            "academic_year" => $academic_year,
                            "student_id" => $student_id,
                            "pay_month" => $monthsPay[$i]["month"],
                            "pay_month_year" => $monthsPay[$i]["year"],
                        ])->first();
                        $month_due[$i]->ispaid = $is_paid ? $is_paid : null;
                    }

                    //echo "Month: $paymentMonth, Amount: $paymentAmount" . PHP_EOL;
                }
            } else {
                $full_due[0] = new \stdClass();
                $full_due[0]->duedate = $feedata->dueinfo;
                $is_paid = FeesModel::where([
                    "academic_year" => $academic_year,
                    "student_id" => $student_id,
                    "pay_type" => 2,
                ])->first();
                $full_due[0]->ispaid = $is_paid ? $is_paid : null;
                $full_due[0]->amount = $grand_total;
            }
            // dd($month_due);
            $payable_total = "";
            if ($feedata->payment_type == 1) {
                $currrent_academic_term = Configurations::getCurrentAcademicterm();
                $due_term = $request->term_id
                    ? $term_due[$request->term_id]
                    : $term_due[$currrent_academic_term];
                $payable_total = !empty($term_due) ? $due_term : null;
            } elseif ($feedata->payment_type == 0) {
                $current_month = $request->month
                    ? $request->month
                    : Carbon::now()->format("F");
                $current_year = $request->year
                    ? $request->year
                    : Carbon::now()->format("Y");
                // dd($current_month, $current_year);
                $current_month_due = collect($month_due)->first(function (
                    $item
                ) use ($current_month, $current_year) {
                    return $item->year == $current_year &&
                        $item->month == $current_month;
                });
                $payable_total = $current_month_due;
            } else {
                $payable_total = $full_due;
            }
            $fees_payment_type = [
                "id" => $feedata->payment_type,
                "text" =>
                    Configurations::FEEPAYMENTTYPES[$feedata->payment_type],
            ];
            // dd($term_due, $month_due, $full_due);
            $pay_methods = Configurations::FEEPAYMENTMETHOD;
            $methods = [];
            foreach ($pay_methods as $id => $method) {
                $methods[] = ["id" => $id, "text" => $method];
            }
            return [
                "fees_details" => $fees_array,
                "grand_total" => $grand_total,
                "scholarship" => $scholarship,
                "total_amount" => $total_amount,
                "fees_payment_type" => $fees_payment_type,
                "payable_total" => $payable_total,
                "feesetup_id" => $feesetup->id,
                "fees_pay_methods" => $methods,
            ];
            return $request->user()->student;
        } catch (\Exception $e) {
            // dd($e);
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function OrderItems(Request $request)
    {
        $user_id = $request->user()->id;
        $orders = OrderModel::with("orderitems")
            ->where(["user_id" => $user_id, "status" => 1])
            ->whereNull("deleted_at")
            ->orderBy("order_date", "desc")
            ->get();
        $orders = $orders->transform(function ($order) {
            if ($order->order_status == -2) {
                $order->order_status = "Return";
            } elseif ($order->order_status == -1) {
                $order->order_status = "Cancel";
            } elseif ($order->order_status == 0) {
                $order->order_status = "New";
            } elseif ($order->order_status == 1) {
                $order->order_status = "Processing";
            } elseif ($order->order_status == 2) {
                $order->order_status = "Shipped";
            } else {
                $order->order_status = "Deliver";
            }
            return $order;
        });
        if (isset($orders)) {
            return $this->success($orders, "Data Fetched Successfully", 200);
        } else {
            return $this->error("No Orders found", 500);
        }
    }

    public function UpdateProfile(Request $request)
    {
        $user_id = $request->user()->id;
        $student = $request->user()->student;
        // dd($student);
        $user = UserModel::find($user_id);
        if ($request->imagec) {
            $user->images = $this->uploadImage($request->imagec, "image");
            $user->save();
        }
        if ($student) {
            $student = StudentsModel::find($student->id);
            if ($request->imagec) {
                $student->image = $user->images;
            }
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

            $student->save();
        } else {
            $teacher = TeacherModel::where("user_id", $user_id)->first();
            if ($request->imagec) {
                $teacher->image = $user->images;
            }
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

            $teacher->save();
        }
        return $this->success("", "Profile Updated Successfully", 200);
    }

    public function ConformFeePay(Request $request)
    {
        //dd(Configurations::convertRupeesToWords($request->paid_amount));
        // $this->validate($request, [
        //     "academic_year" => "required",
        //     "class_id" => "required",
        //     "section_id" => "required",
        // ]);
        $user_id = $request->user()->id;
        $student_id = $request->user()->student->id;
        $acyear = Configurations::getCurrentacademicyear();
        $acterm = Configurations::getCurrentAcademicterm();
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
            ->find($student_id);
        $config = Configurations::getConfig("site");
        $image = parse_url($config->imagec, PHP_URL_PATH);
        // dd($image);

        if ($request->payment_type == 1) {
            $term_name = ExamTermModel::find($acterm)->exam_term_name;
        } else {
            $term_name = ExamTermModel::find(
                Configurations::getCurrentAcademicterm()
            )->exam_term_name;
        }

        $academic = AcademicyearModel::find($acyear)->year;

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
            $obj->academic_year = $acyear;
            $obj->bill_no = $bill_no;
            $obj->class_id = $student_info->class_id;
            $obj->section_id = $student_info->section_id;
            $obj->student_id = $student_id;
            $obj->fee_setup_id = $request->fee_setup_id;
            $obj->paid_amount = $request->paid_amount;
            $obj->payment_method = $request->payment_method;
            $obj->payment_date = date("Y-m-d");
            $obj->payment_year = Carbon::now()->year;
            $obj->payment_month = Carbon::now()->monthName;
            $obj->remark = $request->remark;
            $obj->pay_type = $feedata->payment_type;
            $obj->pay_term_id = $request->selected_term
                ? $request->selected_term
                : null;
            $obj->due_date = $request->selected_term_date
                ? Carbon::parse($request->selected_term_date)->format("Y-m-d")
                : null;
            $obj->pay_month = $request->selected_month
                ? Carbon::parse($request->selected_month)->format("F")
                : Carbon::now()->monthName;
            $obj->pay_month_year = $request->selected_year
                ? $request->selected_year
                : Carbon::now()->year;
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
                $pdf_url = asset($path);
            }

            DB::commit();

            return $this->success($pdf_url, "Fees Paid successfully", 200);
        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
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
    }

    public function AcademicYearList(Request $request)
    {
        $list = AcademicyearModel::where("status", 1)
            ->select("id", "year as text")
            ->get();

        return $this->success($list, "Data Fetched Successfully", 200);
    }
    public function AcademicTermList(Request $request, $id)
    {
        $list = ExamTermModel::where(["status" => 1, "academic_year" => $id])
            ->select("id", "exam_term_name as text")
            ->get();
        return $this->success($list, "Data Fetched Successfully", 200);
    }
}
