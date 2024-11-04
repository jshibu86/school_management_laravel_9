<?php

namespace cms\shop\Controllers;

use DB;
use CGate;
use Carbon\Carbon;

use Session;
use Configurations;
use PDF;
use Mail;

use Illuminate\Http\Request;
use cms\shop\Models\OrderModel;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Http\Controllers\Controller;
use cms\core\user\Models\UserModel;
use cms\shop\Models\OrderItemsModel;
use cms\shop\Models\ProductModel;
use cms\shop\Mail\OrderMail;
use cms\students\Models\StudentsModel;
use cms\wallet\Models\WalletModel;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
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
        //dd("done");
        return view("shop::admin.order.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $carts = Cart::content();
        $carttotal = Cart::subtotal();
        //getting student parent id

        $active_student = Configurations::Activestudent();

        $address_communication = json_decode(
            $active_student->address_communication
        );

        $parsed = Configurations::carttotal($carts);

        // dd($parsed);

        $wallet = WalletModel::where(
            "parent_id",
            $active_student->parent_id
        )->first();
        if ($request->ajax()) {
            $view = view("shop::admin.checkout.includes.cartpage", [
                "carts" => $carts,
                "carttotal" => $carttotal,

                "walletamount" => $wallet ? $wallet->wallet_amount : 0,
            ])->render();

            return response()->json(["viewfile" => $view]);
        }

        return view("shop::admin.checkout.checkout", [
            "layout" => "create",
            "carts" => $carts,
            "carttotal" => $carttotal,

            "walletamount" => $wallet ? $wallet->wallet_amount : 0,
            "active_student" => $active_student,
            "address_communication" => $address_communication,
            "parsed" => $parsed,
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
                "payment_type" => "required",
            ],
            ["payment_type" => "Please Select a Payment Type"]
        );
        DB::beginTransaction();
        try {
            $carts = Cart::content();

            $cartqty = Cart::count();
            $carttotal = Cart::subtotal();

            if (empty($carts)) {
                return redirect()
                    ->back()
                    ->with(
                        "exception_error",
                        "Please Add Some Products In Your Cart"
                    );
            }
            $order_number =
                "SM/" . date("y") . "/" . mt_rand(10000000, 99999999);

            $obj = new OrderModel();

            if (Session::get("ACTIVE_GROUP") == "Student") {
                $obj->student_id = Configurations::Activestudent()->id;
                $obj->user_id = Configurations::Activestudent()->user_id;
            }
            if (Session::get("ACTIVE_GROUP") == "Parent") {
                $obj->parent_id = Configurations::Activeparent()->id;
                $obj->user_id = Configurations::Activeparent()->user_id;
            }
            $obj->order_number = $order_number;
            $obj->order_amount = $carttotal;
            $obj->payment_type = $request->payment_type;
            $obj->currency = "NGN";
            $obj->order_date = date("Y-m-d");
            $obj->order_month = Carbon::now()->format("F");
            $obj->order_year = Carbon::now()->format("Y");
            $obj->processing_date = Carbon::now()->format("d F Y");

            if ($obj->save()) {
                // saveign order products

                foreach ($carts as $cart) {
                    $product = ProductModel::where("id", $cart->id)->first();
                    $ord_item = new OrderItemsModel();
                    $ord_item->order_id = $obj->id;
                    $ord_item->product_id = $cart->id;
                    $ord_item->product_name = $cart->name;
                    $ord_item->product_code = $product->product_code;
                    $ord_item->product_price = $cart->price;
                    $ord_item->qty = $cart->qty;
                    $ord_item->total_price = $cart->options->total;
                    $ord_item->product_image = $cart->options->image;

                    $ord_item->save();
                }

                // amount deduction from wallet

                if ($request->payment_type == "wallet") {
                    if (Session::get("ACTIVE_GROUP") == "Student") {
                        $active_student = Configurations::Activestudent();
                        $wallet = WalletModel::where(
                            "parent_id",
                            $active_student->parent_id
                        )->first();

                        $amount = $wallet->wallet_amount - $carttotal;

                        $wallet->update(["wallet_amount" => $amount]);
                    }
                    if (Session::get("ACTIVE_GROUP") == "Parent") {
                        $activeparent = Configurations::Activeparent();

                        $wallet = WalletModel::where(
                            "parent_id",
                            $activeparent->id
                        )->first();

                        $amount = $wallet->wallet_amount - $carttotal;

                        $wallet->update(["wallet_amount" => $amount]);
                    }

                    $order = OrderModel::find($obj->id)->update([
                        "payment_status" => 1,
                        "order_status" => 3,
                    ]);

                    $ordered_items = OrderItemsModel::where(
                        "order_id",
                        $obj->id
                    )->get();

                    foreach ($ordered_items as $item) {
                        // find product

                        $product = ProductModel::find($item->product_id);

                        if ($product) {
                            $product->update([
                                "product_qty" =>
                                    $product->product_qty - $item->qty,
                            ]);
                        }
                    }
                }

                // sending oreder mail

                try {
                    ini_set("max_execution_time", 120);
                    $order_data = OrderModel::find($obj->id);

                    $user_data = UserModel::where(
                        "id",
                        $order_data->user_id
                    )->first();

                    $pdf = PDF::loadView(
                        "shop::admin.checkout.mail.orderpdf",
                        compact("carts", "carttotal", "order_data", "user_data")
                    );

                    // return $pdf->download("invoice.pdf");

                    // dd("done");

                    if (Session::get("ACTIVE_GROUP") == "Student") {
                        $activestudent_email = Configurations::Activestudent()
                            ->email;
                        if (env("APP_ENV") == "production") {
                            \CmsMail::setMailConfig();
                            Mail::to($activestudent_email)->send(
                                new OrderMail($order_data, $pdf, $user_data)
                            );
                        }

                        Cart::destroy();
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
            } else {
                DB::rollback();
                return redirect()
                    ->back()
                    ->with("exception_error", "Oops !! Something Wrong");
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

        //Session::flash("success", "Order Placed successfully");
        $order_msg =
            "Your order Placed Successfully Order No :" .
            $order_data->order_number;
        return redirect()
            ->route("order.index")
            ->with("success_default", $order_msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = OrderModel::with("orderitems")->find($id);

        $user_data = UserModel::where("id", $data->user_id)->first();

        return view("shop::admin.order.show", [
            "data" => $data,
            "user_data" => $user_data,
        ]);

        //dd($order_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = OrderModel::find($id);
        return view("1::admin.edit", ["layout" => "edit", "data" => $data]);
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
            "status" => "required",
        ]);
        DB::beginTransaction();
        try {
            $obj = OrderModel::find($id);

            $obj->order_status = $request->status;
            $obj->save();

            if ($request->status == 3) {
                // decrease product stock

                $ordered_items = OrderItemsModel::where("order_id", $id)->get();

                foreach ($ordered_items as $item) {
                    // find product

                    $product = ProductModel::find($item->product_id);

                    if ($product) {
                        $product->update([
                            "product_qty" => $product->product_qty - $item->qty,
                        ]);
                    }
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

        Session::flash("success", "Order Status Changed successfully");
        return redirect()->route("order.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_1)) {
            $delObj = new OrderModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new OrderModel();
            $delItem = $delObj->find($id);

            // find order items
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("order.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-shop");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = OrderModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "orders.id as id",
            "order_number",
            "payment_type",
            "order_amount",
            "payment_status",
            "users.name as customername",
            "order_status",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new OrderModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new OrderModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("orders.status", "!=", -1)
            ->join("users", "users.id", "=", "orders.user_id")
            ->orderBy("orders.id", "desc");

        if (Session::get("ACTIVE_GROUP") == "Student") {
            $active_student = Configurations::Activestudent()->id;
            $data = $data->where("orders.student_id", $active_student);
        }

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("orderstatus", function ($data) {
                if ($data->order_status == "1") {
                    return " <span class='text-danger'>Processing</span>";
                } elseif ($data->order_status == "3") {
                    return " <span class='text-success'>Completed</span>";
                } elseif ($data->order_status == "-1") {
                    return " <span class='text-danger'>Cancel</span>";
                } elseif ($data->order_status == "-2") {
                    return " <span class='text-danger'>Return</span>";
                }
            })

            ->addColumn("paymentstatus", function ($data) {
                if ($data->payment_status != "1") {
                    return " <span class='text-danger'>Pending</span>";
                } else {
                    return " <span class='text-success'>Success</span>";
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
                    "route" => "order",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables
            ->rawColumns(["paymentstatus", "action", "orderstatus"])
            ->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-1");
        if ($request->ajax()) {
            OrderModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new OrderModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }
}
