<?php

namespace cms\core\subscription\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\subscription\Models\SubscriptionModel;
use cms\core\subscription\Models\ModuleModel;
use Illuminate\Support\Str;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("subscription::admin.module.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("subscription::admin.module.edit", ["layout" => "create"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $this->validate($request,[
        //     'name' => 'required|min:3|max:50|unique:'.(new ModuleModel())->getTable().',name',
        //     'desc' => 'required|min:3|max:190',
        //     'status' => 'required',
        // ]);
        $module_model = new ModuleModel();
        $module_model->module_name = $request->module_name;
        $module_model->module_description = $request->module_desc;
        $module_model->module_slug = Str::slug($request->module_name, "-");
        $module_model->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("module.index");
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
        $data = ModuleModel::find($id);
        return view("subscription::admin.module.edit", [
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
        // $this->validate($request,[
        //     'name' => 'required|min:3|max:50|unique:'.(new SubscriptionModel())->getTable().',name,'.$id,
        //     'desc' => 'required|min:3|max:190',
        //     'status' => 'required',
        // ]);
        //dd($request);
        $module_model = ModuleModel::find($id);
        $module_model->module_name = $request->module_name;
        $module_model->module_description = $request->module_desc;
        $module_model->module_slug = Str::slug($request->module_name, "-");
        $module_model->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("module.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($id) {
            // delete the primary key exist in the subscription table
            ModuleModel::where("id", $id)->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("module.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        // CGate::authorize('view-module');
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = ModuleModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "module_name",
            "module_description",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ModuleModel())->getTable() .
                    '.status = "0" THEN "Disabled"
    WHEN ' .
                    DB::getTablePrefix() .
                    (new ModuleModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
    ELSE "Enabled" END) AS status'
            )
        );

        $datatables = Datatables::of($data)
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
                return [];
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
        CGate::authorize("edit-subscription");

        if (!empty($request->selected_subscription)) {
            $obj = new SubscriptionModel();
            foreach ($request->selected_subscription as $k => $v) {
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
