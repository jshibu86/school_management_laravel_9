<?php

namespace cms\productcategory\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\productcategory\Models\ProductcategoryModel;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;
use Session;
use DB;
use CGate;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\productbrand\Models\ProductbrandModel;

class ProductcategoryController extends Controller
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
    public function index()
    {
        return view("productcategory::admin.index", ["type" => 1]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view("productcategory::admin.edit", [
            "layout" => "create",
            "type" => $request->query->get("type"),
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
        $this->validate(
            $request,
            [
                "category_name" => "required|max:50",
            ],
            ["category_name.required" => "Please Provide Category Name"]
        );

        $category_slug = strtolower(
            str_replace(" ", "", $request->category_name)
        );

        $is_exists = ProductcategoryModel::where(
            "category_slug",
            $category_slug
        )
            ->where("category_type", $request->category_type)
            ->first();

        if ($is_exists) {
            throw ValidationException::withMessages([
                "category_name" => "This category Name Already Added",
            ]);
        }

        DB::beginTransaction();
        try {
            $obj = new ProductcategoryModel();
            $obj->category_name = $request->category_name;
            $obj->category_slug = $category_slug;
            $obj->category_desc = $request->category_desc;
            $obj->category_type = $request->category_type;
            if ($request->imagec) {
                $obj->category_image = $this->ProductImage(
                    $request->imagec,
                    "school/products/"
                );
            }
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
                ->route("productcategory.create", [
                    "type" => $request->category_type,
                ])
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");

        $route =
            $request->category_type == 1
                ? "productcategory.index"
                : "InventoryCategory";
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
        $data = ProductcategoryModel::find($id);
        return view("productcategory::admin.edit", [
            "layout" => "edit",
            "data" => $data,
            "type" => $data->category_type,
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
                "category_name" => "required|max:50",
            ],
            ["category_name.required" => "Please Provide Category Name"]
        );

        $category_slug = strtolower(
            str_replace(" ", "", $request->category_name)
        );

        $is_exists = ProductcategoryModel::where(
            "category_slug",
            $category_slug
        )
            ->where("id", "!=", $id)
            ->where("category_type", $request->category_type)
            ->first();

        if ($is_exists) {
            throw ValidationException::withMessages([
                "category_name" => "This category Name Already Added",
            ]);
        }

        try {
            $obj = ProductcategoryModel::find($id);
            $obj->category_name = $request->category_name;
            $obj->category_slug = $category_slug;
            $obj->category_desc = $request->category_desc;
            $obj->category_type = $request->category_type;
            if ($request->imagec) {
                $this->deleteImage(
                    null,
                    $obj->category_image ? $obj->category_image : null
                );
                $obj->category_image = $this->ProductImage(
                    $request->imagec,
                    "school/products/"
                );
            }
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

        $route =
            $request->category_type == 1
                ? "productcategory.index"
                : "InventoryCategory";
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
        if (!empty($request->selected_productcategory)) {
            $delObj = new ProductcategoryModel();
            foreach ($request->selected_productcategory as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new ProductcategoryModel();

            // delete brands also

            ProductbrandModel::where("category_id", $id)->forceDelete();
            $delItem = $delObj->find($id);
            $type = $delItem->category_type;
            $delItem->forceDelete();
        }

        Session::flash("success", "data Deleted Successfully!!");

        $route = $type == 1 ? "productcategory.index" : "InventoryCategory";
        return redirect()->route($route);
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-productcategory");
        $type = $request->query->get("type");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ProductcategoryModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "category_name",
            "category_desc",

            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ProductcategoryModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ProductcategoryModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("status", "!=", -1)
            ->where("category_type", $type);

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
                    "route" => "productcategory",
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
        CGate::authorize("edit-productcategory");
        if ($request->ajax()) {
            ProductcategoryModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_productcategory)) {
            $obj = new ProductcategoryModel();
            foreach ($request->selected_productcategory as $k => $v) {
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
