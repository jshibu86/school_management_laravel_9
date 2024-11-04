<?php

namespace cms\core\subscription\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\subscription\Models\SubscriptionModel;
use cms\core\subscription\Models\SubscriptionSettingModel;
use cms\core\subscription\Models\SubscriptionSettingSchoolModel;
use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;

class SubscriptionSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("subscription::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("subscription::admin.edit", ["layout" => "create"]);
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

        $schoolIds = $request->input("school_id");
        $privilegeDays = $request->input("privilege_days");

        if (
            !empty($schoolIds) &&
            !empty($privilegeDays) &&
            count($schoolIds) === count($privilegeDays)
        ) {
            foreach ($schoolIds as $index => $schoolId) {
                SubscriptionSettingSchoolModel::updateOrCreate(
                    ["school_info" => $schoolId],
                    ["privilege_days" => $privilegeDays[$index]]
                );
            }
        }
        // save to subscription setting table
        $notify_days = $request->notify_days;
        $pay_info = $request->pay_info;
        $paymentSettingJson = json_encode($pay_info);
        $reminderSettings = [
            "soon_expired" => $request->input("reminder_setting1"),
            "expired_privilege" => $request->input("reminder_setting2"),
            "expired" => $request->input("reminder_setting3"),
        ];
        $reminderSettingsJson = json_encode($reminderSettings);

        // to find an record exists, then update else create a new record
        $firstRecord = SubscriptionSettingModel::first();
        //dd($firstRecord);
        if ($firstRecord) {
            $firstRecord->notify_days = $notify_days;
            $firstRecord->payment_info = $paymentSettingJson;
            $firstRecord->reminder_info = $reminderSettingsJson;
            $firstRecord->save();
        } else {
            SubscriptionSettingModel::create([
                "notify_days" => $notify_days,
                "payment_info" => $paymentSettingJson,
                "reminder_info" => $reminderSettingsJson,
            ]);
        }

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
        return view("subscription::admin.edit", [
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
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new SubscriptionModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);
        $obj = SubscriptionModel::find($id);
        $obj->name = $request->name;
        $obj->desc = $request->desc;
        $obj->status = $request->status;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("subscription.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_subscription)) {
            $delObj = new SubscriptionModel();
            foreach ($request->selected_subscription as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("subscription.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        // Fetch data from both models
        $subscriptionSetting = DB::table("subscription_setting")->first();
        $subscriptionSchoolSetting = DB::table("subscription_setting_school")
            ->select("school_info", "privilege_days")
            ->get();

        // Combine data
        $data = [
            "subscription_setting" => $subscriptionSetting,
            "subscription_school_setting" => $subscriptionSchoolSetting,
        ];

        if ($data) {
            return $data;
        } else {
            return [];
        }
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
