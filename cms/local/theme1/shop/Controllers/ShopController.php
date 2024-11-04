<?php

namespace cms\shop\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\shop\Models\ShopModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\productbrand\Models\ProductbrandModel;
use cms\productcategory\Models\ProductcategoryModel;
use cms\shop\Models\ProductModel;
use Illuminate\Support\Str;
use cms\shop\Models\SupplierModel;

class ShopController extends Controller
{
    use FileUploadTrait;
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

            $product_id = $request->query->get("product_id");
            $supplier_id = $request->query->get("supplier_id");

            if ($product_id) {
                $product = ProductModel::find($product_id);
                $view = view("shop::admin.includes.productmodel", [
                    "product" => $product,
                ])->render();

                return response()->json(["viewfile" => $view]);
            }

            if ($supplier_id) {
                $supplier_info = SupplierModel::find($supplier_id);
                return response()->json(["supplier_info" => $supplier_info]);
            }

            //search for products
            $item = $request->search;
            $products = ProductModel::where("status", 1)
                ->where("product_qty", ">", 0)
                ->whereNull("deleted_at")
                ->where("product_name", "LIKE", "%$item%")
                ->paginate(12);
            return view("shop::admin.includes.searchresults", [
                "products" => $products,
            ])->render();
        }
        $products = ProductModel::where("status", 1)
            ->where("product_qty", ">", 0)
            ->paginate(12);

        return view("shop::admin.index", [
            "products" => $products,
            "type" => 1,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $categories = ProductcategoryModel::where("status", 1)
            ->where("category_type", $request->query->get("type", 0))
            ->pluck("category_name", "id")

            ->toArray();
        $suppliers = SupplierModel::where("status", 1)->pluck(
            "supplier_name as text",
            "id"
        );

        $suppliers->prepend("New Supplier", "new");

        return view("shop::admin.edit", [
            "layout" => "create",
            "categories" => $categories,
            "brands" => [],
            "suppliers" => $suppliers,
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
        // dd($request->all());

        $rules = [
            "product_name" => "required|min:3|max:50",
            "selling_price" => "required",
            "supplier_name" => "required",
            "supplier_email" => "required",
            "supplier_mobile" => "required",
            "category_id" => "required",
            "supplier_id" => "required",
            "product_qty" => "required",
        ];

        $message = [
            "product_name.required" => "Please Enter Product Name",
            "supplier_name .required" => "Please Enter Supplier Name",
            "supplier_email .required" => "Please Enter Supplier Email",
            "supplier_mobile .required" => "Please Enter Supplier Mobile",
            "category_id.required" => "Please select any Category",
            "supplier_id.required" => "Select the Supplier",
            "product_qty.required" => "Enter the Product Quatity",
        ];

        if ($request->product_type == 1) {
            $rules["brand_id"] = "required";
            $message["brand_id.required"] = "Please select any Brands ";
        }
        $this->validate($request, $rules, $message);
        DB::beginTransaction();
        try {
            $code = rand(99999, 1000000);
            $product_code = "#" . $code . date("Y");
            $obj = new ProductModel();
            $obj->product_name = $request->product_name;
            $obj->category_id = $request->category_id;
            $obj->brand_id = $request->brand_id;
            $obj->product_sku = $request->product_sku;
            $obj->product_slug = Str::slug($request->product_name, "-");
            $obj->product_code = $product_code;
            $obj->product_qty = $request->product_qty;
            $obj->selling_price = $request->selling_price;
            $obj->short_descp = $request->short_descp;
            $obj->product_type = $request->product_type;
            $obj->is_recommended = $request->is_recommended ? 1 : 0;

            if ($request->imagec) {
                $obj->product_thambnail = $this->ProductImage(
                    $request->imagec,
                    "school/products/"
                );
            }

            $obj->save();

            if ($obj->save()) {
                $suplier = SupplierModel::find($request->supplier_id);
                if ($suplier) {
                    $suplier->supplier_name = $request->supplier_name;
                    $suplier->supplier_email = $request->supplier_email;
                    $suplier->supplier_mobile = $request->supplier_mobile;
                    $suplier->supplier_address = $request->supplier_address;
                    $suplier->save();
                    if ($suplier->save()) {
                        $obj->supplier_id = $suplier->id;
                        $obj->update();
                    }
                } else {
                    $suplier = new SupplierModel();
                    $suplier->supplier_name = $request->supplier_name;
                    $suplier->supplier_email = $request->supplier_email;
                    $suplier->supplier_mobile = $request->supplier_mobile;
                    $suplier->supplier_address = $request->supplier_address;
                    $suplier->save();
                    if ($suplier->save()) {
                        $obj->supplier_id = $suplier->id;
                        $obj->update();
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

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("shop.create", ["type" => $request->product_type])
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");

        $route =
            $request->product_type == 1 ? "shop.index" : "InventoryProduct";
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
        $data = ProductModel::with("supplier")->find($id);

        $categories = ProductcategoryModel::where("status", 1)
            ->where("category_type", $data->product_type)
            ->pluck("category_name", "id")
            ->toArray();
        $brands = ProductbrandModel::where("status", 1)
            ->where("category_id", $data->category_id)
            ->pluck("brand_name", "id")
            ->toArray();
        $suppliers = SupplierModel::where("status", 1)->pluck(
            "supplier_name as text",
            "id"
        );

        $suppliers->prepend("New Supplier", "new");

        return view("shop::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "categories" => $categories,
            "brands" => $brands,
            "type" => $data->product_type,
            "suppliers" => $suppliers,
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
        // dd($request->all());
        $rules = [
            "product_name" => "required|min:3|max:50",
            "selling_price" => "required",
            "supplier_name" => "required",
            "supplier_email" => "required",
            "supplier_mobile" => "required",
            "category_id" => "required",
            "supplier_id" => "required",
            "product_qty" => "required",
        ];

        $message = [
            "product_name.required" => "Please Enter Product Name",
            "supplier_name .required" => "Please Enter Supplier Name",
            "supplier_email .required" => "Please Enter Supplier Email",
            "supplier_mobile .required" => "Please Enter Supplier Mobile",
            "category_id.required" => "Please select any Category",
            "supplier_id.required" => "Select the Supplier",
            "product_qty.required" => "Enter the Product Quantity",
        ];

        if ($request->product_type == 1) {
            $rules["brand_id"] = "required";
            $message["brand_id.required"] = "Please select any Brands ";
        }
        $this->validate($request, $rules, $message);

        try {
            $obj = ProductModel::find($id);
            $obj->product_name = $request->product_name;
            $obj->category_id = $request->category_id;
            $obj->brand_id = $request->brand_id;
            $obj->product_sku = $request->product_sku;
            $obj->product_slug = Str::slug($request->product_name, "-");
            $obj->selling_price = $request->selling_price;
            $obj->product_qty = $request->product_qty;
            $obj->short_descp = $request->short_descp;
            $obj->is_recommended = $request->is_recommended ? 1 : 0;

            if ($request->imagec) {
                $this->deleteImage(
                    null,
                    $obj->product_thambnail ? $obj->product_thambnail : null
                );
                $obj->product_thambnail = $this->ProductImage(
                    $request->imagec,
                    "school/products/"
                );
            }
            $obj->save();
            if ($obj->save()) {
                $suplier = SupplierModel::find($request->supplier_id);
                if ($suplier) {
                    $suplier->supplier_name = $request->supplier_name;
                    $suplier->supplier_email = $request->supplier_email;
                    $suplier->supplier_mobile = $request->supplier_mobile;
                    $suplier->supplier_address = $request->supplier_address;
                    $suplier->save();
                    if ($suplier->save()) {
                        $obj->supplier_id = $suplier->id;
                        $obj->update();
                    }
                } else {
                    $suplier = new SupplierModel();
                    $suplier->supplier_name = $request->supplier_name;
                    $suplier->supplier_email = $request->supplier_email;
                    $suplier->supplier_mobile = $request->supplier_mobile;
                    $suplier->supplier_address = $request->supplier_address;
                    $suplier->save();
                    if ($suplier->save()) {
                        $obj->supplier_id = $suplier->id;
                        $obj->update();
                    }
                }
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

        Session::flash("success", "saved successfully");
        $route =
            $request->product_type == 1 ? "shop.index" : "InventoryProduct";
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
        if (!empty($request->selected_shop)) {
            $delObj = new ShopModel();
            foreach ($request->selected_shop as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            DB::statement("SET FOREIGN_KEY_CHECKS=0;");
            $delObj = new ProductModel();
            $delItem = $delObj->find($id);

            $type = $delItem->product_type;

            $this->deleteImage(
                null,
                $delItem->product_thambnail ? $delItem->product_thambnail : null
            );
            $delItem->forceDelete();
            DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        }

        Session::flash("success", "data Deleted Successfully!!");

        $route = $type == 1 ? "shop.index" : "InventoryProduct";
        return redirect()->route($route);
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-shop");
        $type = $request->query->get("type");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ProductModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "product_name",
            "product_thambnail",
            "product_code",
            "selling_price",

            "product_qty",

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
        )
            ->where("status", "!=", -1)
            ->where("product_type", $type);

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("image", function ($data) {
                return "<img src=" .
                    $data->product_thambnail .
                    " width=30 alt='image'/>";
            })
            ->addColumn("qty", function ($data) {
                if ($data->product_qty == 0) {
                    return "<span class='text-danger'>Out of Stock</span>";
                } else {
                    return "<span class='text-success'>Available</span>";
                }
            })
            ->addColumn("price", function ($data) {
                return $data->selling_price;
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
                    "route" => "shop",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->rawColumns(["image", "qty", "action"])->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-shop");
        if ($request->ajax()) {
            ProductModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_shop)) {
            $obj = new ProductModel();
            foreach ($request->selected_shop as $k => $v) {
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
