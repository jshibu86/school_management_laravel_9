<?php

namespace cms\shop\Controllers;

use DB;
use CGate;

use Session;
use Configurations;

use Illuminate\Http\Request;
use cms\shop\Models\ProductModel;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Arr;

class CartController extends Controller
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
        return view("1::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("1::admin.edit", ["layout" => "create"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new ProductModel())->getTable() .
                ",name",
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);
        DB::beginTransaction();
        try {
            $obj = new ProductModel();
            $obj->name = $request->name;
            $obj->desc = $request->desc;
            $obj->status = $request->status;
            $obj->save();

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
        return redirect()->route("1.index");
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
        $data = ProductModel::find($id);
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
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new ProductModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);

        try {
            $obj = ProductModel::find($id);
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
        return redirect()->route("1.index");
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
            $delObj = new ProductModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new ProductModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("1.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-1");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ProductModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ProductModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ProductModel())->getTable() .
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
                    "route" => "1",
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
            ProductModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new ProductModel();
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

    public function addtocart(Request $request)
    {
        $id = $request->id;
        $product = ProductModel::findOrFail($id);
        //getting all carts
        $carts = Cart::content();
        $cartqty = Cart::count();
        //check product already in cart

        //return $carts;

        if ($cartqty > 0) {
            $productExists = [];
            foreach ($carts as $cart) {
                if ($cart->id == $id) {
                    $productExists[] = $cart;
                }
            }
            if (!empty($productExists)) {
                $previous_qty =
                    (int) $productExists[0]->qty + $request->quantity;

                $total =
                    (float) $previous_qty * (float) $product->selling_price;
                Cart::update($productExists[0]->rowId, $previous_qty);

                Cart::update($productExists[0]->rowId, [
                    "options" => [
                        "total" => $total,
                        "image" => $productExists[0]->options->image,
                    ],
                ]);
                return response()->json([
                    "success" => "Successfully Added on Your Cart",
                ]);
            } else {
                Cart::add([
                    "id" => $id,
                    "name" => $product->product_name,
                    "qty" => (int) $request->quantity,
                    "price" => (float) $product->selling_price,
                    "weight" => 1,
                    "weight1" => 1,

                    "options" => [
                        "image" => $product->product_thambnail,
                        "color" => $request->color,
                        "size" => $request->size,
                        "default_qty" => $product->product_qty,
                        "total" =>
                            (float) $request->quantity *
                            (float) $product->selling_price,
                    ],
                ]);
                return response()->json([
                    "success" => "Successfully Added on Your Cart",
                ]);
            }
        } else {
            Cart::add([
                "id" => $id,
                "name" => $product->product_name,
                "qty" => (int) $request->quantity,
                "price" => (float) $product->selling_price,
                "weight" => 1,
                "weight1" => 1,

                "options" => [
                    "image" => $product->product_thambnail,
                    "color" => $request->color,
                    "size" => $request->size,
                    "default_qty" => $product->product_qty,
                    "total" =>
                        (float) $request->quantity *
                        (float) $product->selling_price,
                ],
            ]);
        }

        return response()->json([
            "success" => "Successfully Added on Your Cart",
        ]);

        return true;
    }

    public function minicart()
    {
        $carts = Cart::content();
        $cartqty = Cart::count();
        $carttotal = Cart::subtotal();

        $view = view("shop::admin.cart.minicart", [
            "carts" => $carts,
        ])->render();

        return response()->json([
            "carts" => $carts,
            "viewfile" => $view,
            "cartqty" => $cartqty,
            "carttotal" => $carttotal,
        ]);
    }

    public function Productremove(Request $request)
    {
        $rowId = $request->query->get("rowid");

        Cart::remove($rowId);
        $cartqty = Cart::count();
        $carts = Cart::content();
        $parsed = Configurations::carttotal($carts);
        return response()->json([
            "success" => "Product Remove from Cart",
            "cartcount" => $cartqty,
            "parsed" => $parsed,
        ]);
    }

    public function updatecart(Request $request)
    {
        $rowId = $request->query->get("id");
        $qty = $request->query->get("qty");

        $product = Cart::get($rowId);
        $product_model = ProductModel::findOrFail($product->id);

        //return $product->id;

        //$previous_qty = (int) $product->qty + $qty;

        $total = (float) $qty * (float) $product_model->selling_price;
        Cart::update($product->rowId, $qty);

        Cart::update($product->rowId, [
            "options" => [
                "total" => $total,
                "default_qty" => $product_model->product_qty,
                "image" => $product->options->image,
            ],
        ]);

        $carts = Cart::content();
        $parsed = Configurations::carttotal($carts);

        // Cart::update($rowId, $qty); // Will update the quantity

        return response()->json([
            "success" => "cart update",
            "parsed" => $parsed,
        ]);
    }
}
