<?php

namespace cms\shop\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use DB;
use CGate;
use cms\shop\Models\SupplierModel;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
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
        return view("shop::admin.supplier.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("shop::admin.supplier.edit", ["layout" => "create"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            "supplier_name" => "required",
            "supplier_email" => "required",
            "supplier_mobile" => "required",
        ];

        $message = [
            "supplier_name .required" => "Please Enter Supplier Name",
            "supplier_email .required" => "Please Enter Supplier Email",
            "supplier_mobile .required" => "Please Enter Supplier Mobile",
        ];
        $this->validate($request, $rules, $message);

        $input = $request->all();

        SupplierModel::create([
            "supplier_name" => $request->supplier_name,
            "supplier_email" => $request->supplier_email,
            "supplier_mobile" => $request->supplier_mobile,
            "supplier_address" => $request->supplier_address,
        ]);

        Session::flash("success", "saved successfully");

        return redirect()->route("supplier.index");
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
        $data = SupplierModel::find($id);

        return view("shop::admin.supplier.edit", [
            "layout" => "edit",
            "data" => $data,
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
        $rules = [
            "supplier_name" => "required",
            "supplier_email" => "required",
            "supplier_mobile" => "required",
        ];

        $message = [
            "supplier_name .required" => "Please Enter Supplier Name",
            "supplier_email .required" => "Please Enter Supplier Email",
            "supplier_mobile .required" => "Please Enter Supplier Mobile",
        ];
        $this->validate($request, $rules, $message);

        $input = $request->all();

        SupplierModel::find($id)->update([
            "supplier_name" => $request->supplier_name,
            "supplier_email" => $request->supplier_email,
            "supplier_mobile" => $request->supplier_mobile,
            "supplier_address" => $request->supplier_address,
        ]);

        Session::flash("success", "updated successfully");

        return redirect()->route("supplier.index");
    }

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
            $delObj = new SupplierModel();
            $delItem = $delObj->find($id);

            $delItem->delete();
            DB::statement("SET FOREIGN_KEY_CHECKS=1;");
        }

        Session::flash("success", "data Deleted Successfully!!");

        return redirect()->route("supplier.index");
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

        $data = SupplierModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",

            "supplier_name",
            "supplier_email",
            "supplier_mobile",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new SupplierModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new SupplierModel())->getTable() .
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
                    "route" => "supplier",
                ])->render();
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->rawColumns(["action"])->make(true);
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
