<?php

namespace cms\admissionform\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\admissionform\Models\AdmissionformModel;
use cms\admission\Models\AdmissionModel;
use cms\cmsmenu\Models\CmsmenuModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;

class AdmissionformController extends Controller
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
        $admissionModel = new AdmissionModel();
        $tableColumns = $admissionModel->getTableColumns();
        $hiddenColumns = ["id", "created_at", "updated_at"];
        $tableColumns = array_diff($tableColumns, $hiddenColumns);

        $tableColumns[] = "Note";
        $formattedColumns = [];
        // dd($tableColumns);
        foreach ($tableColumns as $key => $columnName) {
            $formattedColumnName = str_replace("stu", "student", $columnName);
            $formattedColumnName = str_replace(
                "msg",
                "message",
                $formattedColumnName
            );
            $formattedColumnName = str_replace("_", " ", $formattedColumnName);

            $formattedColumns[$key] = ucwords($formattedColumnName);
        }
        // dd($formattedColumns);
        $is_active = AdmissionformModel::where("is_active", 1)
            ->pluck("menu_name")
            ->toArray();
        $dataRecord = CmsmenuModel::where("key", "alert_msg")
            ->pluck("value", "key")
            ->all();

        return view("admissionform::admin.index", [
            "items" => $tableColumns,
            "is_active" => $is_active,
            "dataRecord" => $dataRecord,
            "formattedColumns" => $formattedColumns,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $selectedCheckboxes = $request->all();
        // dd($selectedCheckboxes);
        $labels = $selectedCheckboxes["label"];
        $matchedArray = [];

        $alert_msg = $request->alert_msg;
        // dd($alert_msg);
        if ($alert_msg) {
            CmsmenuModel::updateOrCreate(
                ["key" => "alert_msg"],
                ["value" => $alert_msg]
            );
        }

        //Iterate labels to get the checkbox values
        foreach ($labels as $label) {
            $isActive = isset($selectedCheckboxes[$label]) ? 1 : 0;

            // Update the is active db record with status as 1
            $updateRecord = AdmissionformModel::where(
                "menu_name",
                $label
            )->first();

            if ($updateRecord) {
                $updateRecord->is_active = $isActive;
                $updateRecord->save();
            } else {
                // create a new record if it doesn't exist
                $obj = new AdmissionformModel();
                $obj->menu_name = $label;
                $obj->is_active = $isActive;
                $obj->save();
            }
        }
        Session::flash("success", "Status changed Successfully!!");
        return redirect()->route("admissionform.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = AdmissionformModel::find($id);
        return view("admissionform::admin.edit", [
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
    public function update(Request $request)
    {
        $id = $request->query("itemid", 0);
        $item = AdmissionformModel::find($id);
        $item->is_active = $request->is_active == "false" ? 0 : 1;
        $item->save();

        //     dd($request->all);
        //     $checkboxes = $request->checkboxes;
        //     $labels = $request->label;

        //    foreach ($labels as $key=>$label) {
        //     $obj = new AdmissionformModel;
        //     $obj->menu_name = $label;
        //     $obj->is_active = (isset($checkboxes[$key]) && $checkboxes[$key] == "on") ? 1 : 0;
        //     $obj->save();
        //    }

        //    foreach($checkboxes as $checkboxData){
        //         $obj->menu_name =$checkboxData['label'];
        //         $obj->is_active = isset($checkboxData['checked']);
        //    }

        return response()->json(["id" => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_admissionform)) {
            $delObj = new AdmissionformModel();
            foreach ($request->selected_admissionform as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("admissionform.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-admissionform");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = AdmissionformModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new AdmissionformModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new AdmissionformModel())->getTable() .
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
                return '<a class="editbutton btn btn-default" data-toggle="modal" data="' .
                    $data->id .
                    '" href="' .
                    route("admissionform.edit", $data->id) .
                    '" ><i class="glyphicon glyphicon-edit"></i>&nbsp;Edit</a>';
                //return $data->id;
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
        CGate::authorize("edit-admissionform");

        if (!empty($request->selected_admissionform)) {
            $obj = new AdmissionformModel();
            foreach ($request->selected_admissionform as $k => $v) {
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
