<?php

namespace cms\shop\Controllers;

use DB;
use CGate;
use Carbon\Carbon;
use Session;

use Illuminate\Http\Request;
use cms\shop\Models\ProductModel;
use App\Http\Controllers\Controller;
use cms\productbrand\Models\ProductbrandModel;
use cms\shop\Models\PurchaseOrderModel;
use Yajra\DataTables\Facades\DataTables;
use cms\productcategory\Models\ProductcategoryModel;
use cms\shop\Models\OrderModel;
use cms\shop\Models\SupplierModel;
use Illuminate\Validation\Rule;
class PurchaseOrderController extends Controller
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
        if ($request->ajax()) {
            //getfull product

            $cat_id = $request->query->get("cat");
            $brand_id = $request->query->get("brand");
            $type = $request->query->get("type");

            $products = ProductModel::select("id", "product_name as text")
                ->where("status", 1)
                ->where("category_id", $cat_id);

            if ($type == 1) {
                $products = $products->where("brand_id", $brand_id)->get();
            } else {
                $products = $products->get();
            }

            return $products;
        }
        return view("shop::admin.purchase.index", ["type" => 1]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $products = ProductModel::active()
            ->where("product_type", $request->query->get("type", 0))
            ->pluck("product_name", "id")
            ->toArray();

        $categories = ProductcategoryModel::where("status", 1)
            ->where("category_type", $request->query->get("type", 0))
            ->pluck("category_name", "id")
            ->toArray();

        $vendors = SupplierModel::where("status", 1)
            ->pluck("supplier_name", "id")
            ->toArray();

        // dd($products);
        return view("shop::admin.purchase.edit", [
            "layout" => "create",
            "products" => [],
            "categories" => $categories,
            "brands" => [],
            "vendors" => $vendors,
            "type" => $request->query->get("type", 0),
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
                "purchase_no" => [
                    "required",

                    Rule::unique("purchase_order")->where(function (
                        $query
                    ) use ($request) {
                        return $query->where(
                            "purchase_type",
                            $request->purchase_type
                        );
                    }),
                ],

                "bill_no" => [
                    "required",

                    Rule::unique("purchase_order")->where(function (
                        $query
                    ) use ($request) {
                        return $query->where(
                            "purchase_type",
                            $request->purchase_type
                        );
                    }),
                ],

                "quantity" => "required",
            ],
            [
                "purchase_no.unique" =>
                    "This Purchase Number Already Registered",
                "bill_no.unique" => "This Bill Number Already Registered",
            ]
        );
        DB::beginTransaction();
        try {
            $obj = new PurchaseOrderModel();
            $obj->purchase_date = $request->purchase_date;
            $obj->purchase_no = $request->purchase_no;
            $obj->bill_no = $request->bill_no;
            $obj->product_id = $request->product_id;
            $obj->vendor_id = $request->vendor_id;
            $obj->quantity = $request->quantity;
            $obj->purchase_price = $request->purchase_price;
            $obj->selling_price = $request->selling_price;
            $obj->purchase_type = $request->purchase_type;
            $obj->purchase_month = Carbon::now()->format("F");
            $obj->purchase_year = Carbon::now()->format("Y");
            if ($obj->save()) {
                // update product information

                $product = ProductModel::find($request->product_id);

                $updatedstock =
                    $product->product_qty + (int) $request->quantity;

                $product->update([
                    "product_qty" => $updatedstock,
                    "selling_price" => $request->selling_price,
                ]);
            } else {
                DB::rollback();
                $message = "Something Went Wrong";
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", $message);
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

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("purchase.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "Order saved successfully");

        $route =
            $request->purchase_type == 1
                ? "purchase.index"
                : "InventoryPurchase";
        return redirect()->route($route);
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
        $data = PurchaseOrderModel::find($id);

        $product = ProductModel::where(["id" => $data->product_id])->first();

        $categories = ProductcategoryModel::where("status", 1)

            ->where("id", $product->category_id)

            ->pluck("category_name", "id")
            ->toArray();

        $brands = ProductbrandModel::where("status", 1)
            ->where("id", $product->brand_id)

            ->pluck("brand_name", "id")
            ->toArray();

        // /dd($data);

        $products = ProductModel::active()
            ->pluck("product_name", "id")
            ->toArray();

        $vendors = SupplierModel::where("status", 1)
            ->pluck("supplier_name", "id")
            ->toArray();

        return view("shop::admin.purchase.edit", [
            "layout" => "edit",
            "data" => $data,
            "products" => $products,
            "categories" => $categories,
            "brands" => $brands,
            "product" => $product,
            "vendors" => $vendors,
            "type" => $data->purchase_type,
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
        $this->validate(
            $request,
            [
                "purchase_no" => [
                    "required",

                    Rule::unique("purchase_order")->where(function (
                        $query
                    ) use ($request, $id) {
                        return $query
                            ->where("purchase_type", $request->purchase_type)
                            ->where("id", "!=", $id);
                    }),
                ],

                "bill_no" => [
                    "required",

                    Rule::unique("purchase_order")->where(function (
                        $query
                    ) use ($request, $id) {
                        return $query
                            ->where("purchase_type", $request->purchase_type)
                            ->where("id", "!=", $id);
                    }),
                ],

                "quantity" => "required",
            ],
            [
                "purchase_no.unique" =>
                    "This Purchase Number Already Registered",
                "bill_no.unique" => "This Bill Number Already Registered",
            ]
        );

        try {
            $obj = PurchaseOrderModel::find($id);

            // checking quantity

            $old_order_quantity = $obj->quantity;

            $product = ProductModel::find($obj->product_id);

            // dd($updatedstock);

            if ($product->product_qty == 0) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "Whoops !! This product Already In OutofStock ,Can't Edit this Order"
                    );
            }

            // if ($product->product_qty < $obj->quantity) {
            //     return redirect()
            //         ->back()
            //         ->withInput()
            //         ->with(
            //             "exception_error",
            //             "Whoops !! This product Lessthan Quantity in your old Older Quantity ,So can't Increase or Decrease Quantity"
            //         );
            // }

            $obj->purchase_date = $request->purchase_date;
            $obj->purchase_no = $request->purchase_no;
            $obj->bill_no = $request->bill_no;
            $obj->product_id = $request->product_id;
            $obj->vendor_id = $request->vendor_id;
            $obj->quantity = $request->quantity;
            $obj->purchase_price = $request->purchase_price;
            $obj->selling_price = $request->selling_price;

            if ($obj->save()) {
                // $updatedstock =
                //     $product->product_qty -
                //     $old_order_quantity +
                //     (int) $request->quantity;

                $product->update([
                    // "product_qty" => $updatedstock,
                    "selling_price" => $request->selling_price,
                ]);
            }
            // $obj->save();
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

        $route =
            $request->purchase_type == 1
                ? "purchase.index"
                : "InventoryPurchase";
        return redirect()->route($route);
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
            $delObj = new PurchaseOrderModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            // dd("here");
            $delObj = new PurchaseOrderModel();

            $delItem = $delObj->find($id);

            //dd($delItem);

            $type = $delItem->purchase_type;

            $old_order_quantity = $delItem->quantity;

            $product = ProductModel::find($delItem->product_id);

            // dd($updatedstock);
            $route = $type == 1 ? "purchase.index" : "InventoryPurchase";

            if ($product->product_qty == 0) {
                $delItem->delete();

                Session::flash("success", "data Deleted Successfully!!");
                return redirect()->route($route);
            }

            if ($product->product_qty < $delItem->quantity) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "Whoops !! This product Lessthan Quantity in your old Older Quantity ,So can't Decrease Quantity and Delete"
                    );
            } else {
                $updatedstock = $product->product_qty - $old_order_quantity;

                $product->update([
                    "product_qty" => $updatedstock,
                ]);
                $delItem->delete();
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route($route);
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-1");
        $type = $request->query->get("type");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = PurchaseOrderModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "purchase_order.id as id",
            "purchase_order.product_id as product_id",
            "purchase_date",
            "purchase_no",
            "bill_no",
            "quantity",
            "purchase_price",
            "products.product_name as product_name",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new PurchaseOrderModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new PurchaseOrderModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("purchase_order.status", "!=", -1)
            ->where("purchase_order.purchase_type", $type)
            ->join("products", "products.id", "=", "purchase_order.product_id");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("pprice", function ($data) {
                return number_format($data->purchase_price, 2);
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
                    "route" => "purchase",
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
        CGate::authorize("edit-1");
        if ($request->ajax()) {
            PurchaseOrderModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new PurchaseOrderModel();
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

    // purchase reports

    public function purchaseReport()
    {
        return view("shop::admin.purchase.purchasereport");
    }

    public function getreportdata(
        Request $request,
        $is_report = false,
        $is_sales_report = false
    ) {
        //dd($request->all());

        if (!$is_report) {
            if ($request->report_type == "weekly") {
                if (
                    $request->start_date == null ||
                    $request->end_date == null
                ) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with("type", "weekly")

                        ->with(
                            "exception_error",
                            "Please Select Start and End Dates"
                        );
                }
            }

            if ($request->report_type == "monthly") {
                if ($request->month == null) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with("type", "monthly")

                        ->with("exception_error", "Please Select Month");
                }
            }

            if ($request->report_type == "daily") {
                if ($request->day == null) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with("type", "daily")

                        ->with("exception_error", "Please Select Day");
                }
            }

            if ($request->report_type == "yearly") {
                if ($request->year == null) {
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with("type", "yearly")
                        ->with("exception_error", "Please Select Year");
                }
            }
        }

        //dd($request->all());
        $original_date = $request->day ? $request->day : null; //11/30/2022
        $convert_date = null; //2022-11-30
        $convert_startdate = null; //2022-11-30
        $convert_enddate = null; //2022-11-30
        $month = null;
        $year = null;

        // main_arrays

        $orders = [];
        $purchase_data = [];
        $final_data = [];
        $products = ProductModel::where("status", 1)->get();

        // date convert

        if ($request->day) {
            $convert_date = date("Y-m-d", strtotime($request->day));
        }

        if ($request->start_date && $request->end_date) {
            $convert_startdate = date("Y-m-d", strtotime($request->start_date));
            $convert_enddate = date("Y-m-d", strtotime($request->end_date));
        }
        //dd("not");

        $orders = OrderModel::query();

        $orders = $orders
            ->with("orderitems", "user:id,name")
            ->where("status", 1)
            ->where("order_status", 3);

        if ($request->start_date) {
            $orders = $orders->whereBetween("order_date", [
                $convert_startdate,
                $convert_enddate,
            ]);
        }

        if ($request->day) {
            $orders = $orders->whereDate("order_date", "=", $convert_date);
        }

        if ($request->month) {
            $month_convert = explode(" ", $request->month);
            $month = $month_convert[0];
            $year = $month_convert[1];

            $orders = $orders->where([
                "order_month" => $month,
                "order_year" => $year,
            ]);
        }

        if ($request->year) {
            $orders = $orders->where("order_year", "=", $request->year);
        }

        if ($request->start_date && $request->end_date) {
        }

        if ($request->payment_status) {
            $orders = $orders->where(
                "payment_status",
                "=",
                $request->payment_status
            );
        }

        if ($request->payment_type) {
            $type = $request->payment_type == 1 ? "wallet" : "fluterwave";
            $orders = $orders->where("payment_type", "=", $type);
        }

        $orders = $orders->get();

        if ($is_sales_report) {
            return $orders;
        }

        //dd($orders);

        // purchase data

        $purchase_data = PurchaseOrderModel::query();

        $purchase_data = $purchase_data
            ->with("product", "vendor")
            ->where("status", 1);

        if ($request->start_date) {
            $purchase_data = $purchase_data->whereBetween("purchase_date", [
                $request->start_date,
                $request->end_date,
            ]);
        }

        if ($request->day) {
            $purchase_data = $purchase_data->where(
                "purchase_date",
                "=",
                $original_date
            );
        }

        if ($request->month) {
            $month_convert = explode(" ", $request->month);
            $month = $month_convert[0];
            $year = $month_convert[1];

            $purchase_data = $purchase_data->where([
                "purchase_month" => $month,
                "purchase_year" => $year,
            ]);
        }

        if ($request->year) {
            $purchase_data = $purchase_data->where(
                "purchase_year",
                "=",
                $request->year
            );
        }

        //dd($original_date);

        $purchase_data = $purchase_data->get();

        if ($is_report) {
            return $purchase_data;
        }
        //dd($purchase_data);

        foreach ($products as $product) {
            $pcount = 0;

            $psale = 0;
            if (!isset($final_data[$product->id])) {
                $final_data[$product->id] = new \stdClass();

                $final_data[$product->id]->product_name =
                    $product->product_name;
            }

            if (!isset($final_data[$product->id]->totalorderqty)) {
                //$final_data[$product->id]->totalorderqty = new \stdClass();
                $count = 0;

                $ordersale = 0;
                foreach ($orders as $order) {
                    foreach ($order->orderitems as $item) {
                        if ($item->product_id == $product->id) {
                            $count = $count + $item->qty;

                            $ordersale = $ordersale + $item->total_price;
                            $final_data[$product->id]->totalorderqty = $count;
                            $final_data[
                                $product->id
                            ]->totalordersale = $ordersale;
                        }
                    }
                }
            }

            if (!isset($final_data[$product->id]->totalpurchaseqty)) {
                //$final_data[$product->id]->totalorderqty = new \stdClass();
                $pcount = 0;

                $psale = 0;
                foreach ($purchase_data as $item) {
                    if ($item->product_id == $product->id) {
                        $pcount = $pcount + $item->quantity;

                        $psale = $psale + $item->purchase_price;
                        $final_data[$product->id]->totalpurchaseqty = $pcount;
                        $final_data[$product->id]->totalpurchasesale = $psale;
                    }
                }
            }
        }

        return view("shop::admin.purchase.purchasereport", [
            "final_data" => $final_data,
            "type" => $request->report_type,
            "day" => $request->day,
            "month" => $request->month,
            "year" => $request->year,
            "start_date" => $request->start_date,
            "end_date" => $request->end_date,
        ]);

        dd($final_data);
    }
}
