<?php

namespace cms\productbrand\Controllers;

use DB;
use CGate;
use Session;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use cms\productbrand\Models\ProductbrandModel;
use Illuminate\Validation\ValidationException;
use cms\productcategory\Models\ProductcategoryModel;

class ProductbrandController extends Controller
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
            $cat_id = $request->query->get("cat", 0);

            $brands = ProductbrandModel::select("id", "brand_name as text")
                ->where("status", 1)
                ->where("category_id", $cat_id)
                ->get();

            return $brands;
        }
        return view("productbrand::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = ProductcategoryModel::where("status", 1)
            ->pluck("category_name", "id")
            ->toArray();
        return view("productbrand::admin.edit", [
            "layout" => "create",
            "categories" => $categories,
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
        $this->validate(
            $request,
            [
                "brand_name" => "required|max:50",
                "category_id" => "required",
            ],
            ["brand_name.required" => "Please Provide brand Name"]
        );

        $brand_slug = strtolower(str_replace(" ", "", $request->brand_name));

        $is_exists = ProductbrandModel::where("brand_slug", $brand_slug)
            ->where("category_id", $request->category_id)
            ->first();

        if ($is_exists) {
            throw ValidationException::withMessages([
                "brand_name" => "This Brand Name Already Added",
            ]);
        }

        DB::beginTransaction();
        try {
            $obj = new ProductbrandModel();
            $obj->brand_name = $request->brand_name;
            $obj->category_id = $request->category_id;
            $obj->brand_slug = $brand_slug;
            $obj->brand_desc = $request->brand_desc;
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

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("productbrand.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("productbrand.index");
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
        $categories = ProductcategoryModel::where("status", 1)
            ->pluck("category_name", "id")
            ->toArray();
        $data = ProductbrandModel::find($id);
        return view("productbrand::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "categories" => $categories,
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
        $this->validate(
            $request,
            [
                "brand_name" => "required|max:50",
            ],
            ["brand_name.required" => "Please Provide brand Name"]
        );

        $brand_slug = strtolower(str_replace(" ", "", $request->brand_name));

        $is_exists = ProductbrandModel::where("brand_slug", $brand_slug)
            ->where("id", "!=", $id)
            ->where("category_id", $request->category_id)
            ->first();

        if ($is_exists) {
            throw ValidationException::withMessages([
                "brand_name" => "This Brand Name Already Added",
            ]);
        }

        try {
            $obj = ProductbrandModel::find($id);
            $obj->category_id = $request->category_id;
            $obj->brand_name = $request->brand_name;
            $obj->brand_slug = strtolower(
                str_replace(" ", "", $request->brand_name)
            );
            $obj->brand_desc = $request->brand_desc;
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
        return redirect()->route("productbrand.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_productbrand)) {
            $delObj = new ProductbrandModel();
            foreach ($request->selected_productbrand as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new ProductbrandModel();
            $delItem = $delObj->find($id);
            $delItem->forceDelete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("productbrand.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-productbrand");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ProductbrandModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "productbrand.id as id",
            "brand_name",
            "brand_desc",
            "productcategory.category_name as category_name",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ProductbrandModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ProductbrandModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("productbrand.status", "!=", -1)
            ->join(
                "productcategory",
                "productcategory.id",
                "=",
                "productbrand.category_id"
            );

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
                    "route" => "productbrand",
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
        CGate::authorize("edit-productbrand");
        if ($request->ajax()) {
            ProductbrandModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_productbrand)) {
            $obj = new ProductbrandModel();
            foreach ($request->selected_productbrand as $k => $v) {
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
