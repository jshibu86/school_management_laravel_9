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
use cms\core\configurations\helpers\Configurations;
class SetupPlanPriceController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $selectedModuleList = [];
        $already_add_ids = PlanPriceModel::pluck("plan_id");
        $planList = SubscriptionModel::where("status", 1)
            ->whereNotIn("id", $already_add_ids)
            ->pluck("plan_name", "id")
            ->toArray();

        $moduleList = ModuleModel::where("status", 1)
            ->pluck("module_name", "id")
            ->toArray();

        $defaultmodules = Configurations::DEFAULTMODULES;
        // dd($moduleList);
        return view("subscription::admin.plansetup.newplanprice", [
            "layout" => "create",
            "planList" => $planList,
            "moduleList" => $moduleList,
            "selectedModuleList" => $selectedModuleList,
            "defaultmodules" => $defaultmodules,
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
        // $this->validate($request,[
        //     'name' => 'required|min:3|max:50|unique:'.(new SubscriptionModel())->getTable().',name',
        //     'desc' => 'required|min:3|max:190',
        //     'status' => 'required',
        // ]);
        //dd($request->);
        $plan_price_table = new PlanPriceModel();
        $plan_price_table->plan_id = $request->subscription_plan_price_name;
        $plan_price_table->term_amount = $request->amount_term_per_student;
        $plan_price_table->session_amount =
            $request->amount_session_per_student;
        $plan_price_table->visible_status = $request->plan_visible;
        $selectedModules = $request->moduleList;
        $plan_price_table->modules = json_encode($selectedModules);
        $plan_price_table->save();

        Session::flash("success", "saved successfully");
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
        $moduleList = [];
        $planList = SubscriptionModel::where("status", 1)
            ->pluck("plan_name", "id")
            ->toArray();
        $moduleList = ModuleModel::where("status", 1)
            ->pluck("module_name", "id")
            ->toArray();
        $data = PlanPriceModel::find($id);
        //  dd($data);
        if ($data) {
            $selectedModuleList = json_decode($data->modules, true);
        }
        return view("subscription::admin.subscription.show", [
            "moduleList" => $moduleList,
            "selectedModuleList" => $selectedModuleList,
            "planList" => $planList,
            "data" => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd("here");
        $moduleList = [];
        $planList = SubscriptionModel::where("status", 1)
            ->pluck("plan_name", "id")
            ->toArray();
        $moduleList = ModuleModel::where("status", 1)
            ->pluck("module_name", "id")
            ->toArray();
        $data = PlanPriceModel::find($id);
        //  dd($data);
        if ($data) {
            $selectedModuleList = json_decode($data->modules, true);
        }
        return view("subscription::admin.plansetup.newplanprice", [
            "layout" => "edit",
            "moduleList" => $moduleList,
            "selectedModuleList" => $selectedModuleList,
            "planList" => $planList,
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
        //     'name' => 'required|min:3|max:50|unique:'.(new PlanPriceModel())->getTable().',name,'.$id,
        //     'desc' => 'required|min:3|max:190',
        //     'status' => 'required',
        // ]);

        $plan_price_table = PlanPriceModel::find($id);
        $plan_price_table->plan_id = $request->subscription_plan_price_name;
        $plan_price_table->term_amount = $request->amount_term_per_student;
        $plan_price_table->session_amount =
            $request->amount_session_per_student;
        $plan_price_table->visible_status = $request->plan_visible;
        $selectedModules = $request->moduleList;
        $plan_price_table->modules = json_encode($selectedModules);
        $plan_price_table->save();

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
        //     $delObj = new PlanPriceModel;
        //     foreach ($request->selected_subscription as $k => $v) {

        //         if($delItem = $delObj->find($v))
        //         {
        //             $delItem->delete();

        //         }

        //     }

        // }

        if ($id) {
            // delete the primary key exist in the subscription table
            PlanPriceModel::where("id", $id)->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("setupplan.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        // CGate::authorize('view-subscription');
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = PlanPriceModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "plan_price.id as id",
            "plan_price.term_amount as term_amount",
            "plan_price.session_amount as session_amount",
            DB::raw(
                "CASE WHEN plan_price.visible_status = 1 THEN 'Yes' ELSE 'No' END as visible_status"
            ),
            DB::raw(
                "CONCAT(JSON_LENGTH(plan_price.modules), ' Modules') as module_count"
            ),
            "subscription_plan.plan_name as plan_name"
        )->leftJoin(
            "subscription_plan",
            "subscription_plan.id",
            "=",
            "plan_price.plan_id"
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
                if ($data->id != "1") {
                    return view("layout::datatable.action", [
                        "data" => $data,
                        "id" => $data->id,
                        "route" => "setupplanprice",
                        "showEdit" => true,
                        "showDelete" => true,
                        "showView" => true,
                        "editRoute" => "setupplanprice.edit",
                        "deleteRoute" => "setupplanprice.destroy",
                        "viewRoute" => "setupplanprice.show",
                    ])->render();
                } else {
                    return "";
                }
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
            $obj = new PlanPriceModel();
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
