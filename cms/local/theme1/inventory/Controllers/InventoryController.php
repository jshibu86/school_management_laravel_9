<?php

namespace cms\inventory\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\inventory\Models\InventoryModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\inventory\Models\InventoryDistributionModel;
use cms\inventory\Models\InventoryDistributionUserModel;
use cms\lclass\Models\LclassModel;
use cms\productcategory\Models\ProductcategoryModel;
use cms\section\Models\SectionModel;
use cms\shop\Models\ProductModel;
use cms\students\Models\StudentsModel;

class InventoryController extends Controller
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
            $product_id = $request->query->get("id", 0);

            return ProductModel::find($product_id);
        }
        return view("inventory::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)

            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $user_group = UserGroupModel::where("id", "!=", 1)
            ->pluck("group", "id")
            ->toArray();

        $categories = ProductcategoryModel::where("category_type", 2)
            ->pluck("category_name", "id")
            ->toArray();
        return view("inventory::admin.edit", [
            "layout" => "create",
            "class_lists" => $class_lists,
            "section_lists" => [],
            "users" => [],
            "user_group" => $user_group,
            "categories" => $categories,
            "products" => [],
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
        // dd($request->all());
        $this->validate($request, [
            "product_id" => "required",
        ]);
        DB::beginTransaction();
        try {
            // checking quantify

            $product_quantity = ProductModel::find($request->product_id)
                ->product_qty;
            if (
                $product_quantity == 0 ||
                $request->quantity > $product_quantity
            ) {
                DB::rollback();
                $message =
                    "Product Stock Not Available Kindly Add Stock/Purchase";
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", $message);
            }

            $selected_users = [];
            $all = 0;

            if ($request->member_id && is_array($request->member_id)) {
                if (in_array(0, $request->member_id)) {
                    // find all members ids
                    $all = 1;
                    $selected_users = $this->FindUserIds($request->user_group);
                } else {
                    $selected_users = $request->member_id;
                }
            }

            if ($request->student_id && is_array($request->student_id)) {
                if (in_array(0, $request->student_id)) {
                    // find all members ids
                    $all = 1;
                    $selected_users = $this->FindUserIds($request->user_group);
                } else {
                    $selected_users = $request->student_id;
                }
            }

            //  dd($selected_users);

            $product = ProductModel::find($request->product_id);

            //dd($product);

            $obj = new InventoryDistributionModel();
            $obj->user_group_id = $request->user_group;
            $obj->category_id = $request->category_id;
            $obj->product_id = $request->product_id;
            $obj->distribution_date = $request->distribution_date;
            $obj->quantity = $request->quantity;
            $obj->total_price = $request->quantity * $product->selling_price;
            $obj->all_checked = $all;

            if ($obj->save()) {
                // save users
                foreach ($selected_users as $key => $user) {
                    # code...
                    $duser = new InventoryDistributionUserModel();
                    $duser->distribution_id = $obj->id;
                    $duser->class_id = $request->class_id;
                    $duser->section_id = $request->section_id;
                    if ($request->student_id) {
                        $duser->student_id = $user;
                    }

                    $duser->user_id = $user;

                    $duser->save();
                }

                $product->decrement("product_qty", $request->quantity);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            // dd($e);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("inventory.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("inventory.index");
    }

    public function FindUserIds($user_group)
    {
        $user_ids = UserGroupMapModel::where("group_id", $user_group)->pluck(
            "user_id"
        );
        return $user_ids;
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
        $class_lists = LclassModel::whereNull("deleted_at")
            ->where("status", "!=", -1)

            ->orderBy("id", "asc")
            ->pluck("name", "id")
            ->toArray();

        $user_group = UserGroupModel::where("id", "!=", 1)
            ->pluck("group", "id")
            ->toArray();

        $categories = ProductcategoryModel::where("category_type", 2)
            ->pluck("category_name", "id")
            ->toArray();
        $data = InventoryDistributionModel::find($id);

        // gettign group users

        $users = UserModel::where("status", 1)
            ->whereNull("deleted_at")
            ->whereIn("id", $this->FindUserIds($data->user_group_id))
            ->select([
                "users.id as id",
                DB::raw(
                    "CONCAT(users.username, ' - ', users.name,'-',users.email) as text"
                ),
            ])
            ->pluck("text", "id")
            ->prepend("selected All Users", 0);

        $selected_users = [];
        $selected_students = [];
        $is_student = false;

        if ($data->all_checked == 0) {
            $selected_users = InventoryDistributionUserModel::where(
                "distribution_id",
                $data->id
            )
                ->pluck("user_id")
                ->toArray();

            $selected_students = InventoryDistributionUserModel::where(
                "distribution_id",
                $data->id
            )
                ->pluck("student_id")
                ->toArray();

            if (sizeof($selected_students)) {
                $is_student = true;
            }
        } else {
            $selected_users = [0];
            $selected_students = [0];
        }

        //dd($selected_students);

        $student_ids = InventoryDistributionUserModel::where(
            "distribution_id",
            $data->id
        )->pluck("student_id");

        $students = StudentsModel::where("status", 1)
            ->select([
                "students.id as id",
                DB::raw(
                    "CONCAT(students.username, ' - ', students.email) as text"
                ),
            ])
            ->pluck("text", "id")
            ->prepend("selected All Students", 0);

        $products = ProductModel::where("category_id", $data->category_id)
            ->pluck("product_name", "id")
            ->toArray();

        $product = ProductModel::find($data->product_id);

        $user_info = InventoryDistributionUserModel::where(
            "distribution_id",
            $data->id
        )->first();
        $section_lists = SectionModel::where(
            "class_id",
            $user_info->class_id
        )->pluck("name", "id");
        // dd($is_student);

        return view("inventory::admin.edit", [
            "layout" => "edit",
            "class_lists" => $class_lists,
            "user_group" => $user_group,
            "categories" => $categories,
            "section_lists" => $section_lists,
            "users" => $users,
            "data" => $data,
            "products" => $products,
            "product" => $product,
            "selected_users" => $selected_users,
            "students" => $students,
            "selected_students" => $selected_students,
            "is_student" => $is_student,
            "user_info" => $user_info,
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

        try {
            DB::beginTransaction();
            $obj = InventoryDistributionModel::find($id);
            $product_quantity = ProductModel::find($obj->product_id)
                ->product_qty;

            $product = ProductModel::find($obj->product_id);
            if (
                $product_quantity == 0 ||
                $request->quantity > $product_quantity
            ) {
                DB::rollback();
                $message =
                    "Product Stock Not Available Kindly Add Stock/Purchase";
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", $message);
            }

            $decrease_qty = $request->quantity - (int) $obj->quantity;

            if ($decrease_qty < 0) {
                $product->increment("product_qty", abs($decrease_qty));
            } else {
                $product->decrement("product_qty", $decrease_qty);
            }
            $obj->update(["quantity" => $request->quantity]);

            $obj->update([
                "total_price" => $request->quantity * $product->selling_price,
            ]);

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
        return redirect()->route("inventory.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_inventory)) {
            $delObj = new InventoryModel();
            foreach ($request->selected_inventory as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new InventoryDistributionModel();
            $delItem = $delObj->find($id);

            InventoryDistributionUserModel::where(
                "distribution_id",
                $id
            )->delete();

            $qty = $delItem->quantity;
            $productid = $delItem->product_id;

            $delItem->delete();

            ProductModel::where("id", $productid)->increment(
                "product_qty",
                $qty
            );
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("inventory.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-inventory");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = InventoryDistributionModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "inventory_distribution.id as id",
            "user_group_id",
            "product_id",
            "user_groups.group as group",
            "products.product_name as product_name",
            "inventory_distribution.quantity as qty",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new InventoryDistributionModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new InventoryDistributionModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )

            ->join(
                "user_groups",
                "user_groups.id",
                "=",
                "inventory_distribution.user_group_id"
            )
            ->join(
                "products",
                "products.id",
                "=",
                "inventory_distribution.product_id"
            )

            ->where("inventory_distribution.status", "!=", -1);

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
                    "route" => "inventory",
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
        CGate::authorize("edit-inventory");
        if ($request->ajax()) {
            InventoryDistributionModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_inventory)) {
            $obj = new InventoryDistributionModel();
            foreach ($request->selected_inventory as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function InventoryCategory(Request $request)
    {
        return view("productcategory::admin.index", ["type" => 2]);
    }

    public function InventoryProduct(Request $request)
    {
        return view("shop::admin.index", ["products" => [], "type" => 2]);
    }

    public function InventoryPurchase(Request $request)
    {
        return view("shop::admin.purchase.index", ["type" => 2]);
    }
}
