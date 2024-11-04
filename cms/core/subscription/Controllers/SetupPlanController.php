<?php

namespace cms\core\subscription\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\subscription\Models\SubscriptionModel;
use cms\core\subscription\Models\PlanPriceModel;
use cms\core\subscription\Models\ModuleModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;

class SetupPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("subscription::admin.plansetup.index");
    }
    // to display the plan creation page
    public function newPlanCreate()
    {
        return view("subscription::admin.plansetup.newplan", [
            "layout" => "create",
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("subscription::admin.plansetup.edit", [
            "layout" => "create",
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
        info("Info Store Method: " . $request); // logs to log file
        // $this->validate($request,[
        //     'name' => 'required|min:3|max:50|unique:'.(new SubscriptionModel())->getTable().',name',
        //     'desc' => 'required|min:3|max:190',
        //     'status' => 'required',
        // ]);
        $subscription_plan_table = new SubscriptionModel();
        $subscription_plan_table->plan_name = $request->subscription_plan_name;
        $subscription_plan_table->plan_description =
            $request->subscription_plan_desc;
        $subscription_plan_table->save();

        Session::flash("success", "Plan created successfully");
        return redirect()->route("setupplan.index");
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
        $data = SubscriptionModel::find($id);
        return view("subscription::admin.plansetup.newplan", [
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
        $subscription_plan_table = SubscriptionModel::find($id);
        $subscription_plan_table->plan_name = $request->subscription_plan_name;
        $subscription_plan_table->plan_description =
            $request->subscription_plan_desc;
        $subscription_plan_table->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("setupplan.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // if(!empty($request->selected_subscription))
        // {
        //     $delObj = new SubscriptionModel;
        //     foreach ($request->selected_subscription as $k => $v) {

        //         if($delItem = $delObj->find($v))
        //         {
        //             $delItem->delete();
        //         }
        //     }
        // }
        if ($id) {
            // delete the foreign key exists in the plan_price table
            PlanPriceModel::where("plan_id", $id)->delete();

            // delete the primary key exist in the subscription table
            SubscriptionModel::where("id", $id)->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("setupplan.index");
    }
    /*
     * get plan list data
     */
    public function getData(Request $request)
    {
        // CGate::authorize('view-subscription');
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = SubscriptionModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "plan_name",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new SubscriptionModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new SubscriptionModel())->getTable() .
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
                return view("layout::datatable.action", [
                    "id" => $data->id,
                    "data" => $data,
                    "route" => "setupplan",
                    "showEdit" => true,
                    "showDelete" => true,
                    "showView" => false,
                    "editRoute" => "setupplan.edit",
                    "deleteRoute" => "setupplan.destroy",
                    "viewRoute" => "setupplan.show",
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
