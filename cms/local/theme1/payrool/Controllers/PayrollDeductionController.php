<?php

namespace cms\payrool\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\payrool\Models\PayrollDeductionModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;

class PayrollDeductionController extends Controller
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
            $id = $request->query->get("id");
            PayrollDeductionModel::find($id)->delete();

            return true;
        }
        $deductions = PayrollDeductionModel::get();

        // dd($deductions);
        return view("payrool::payrolldeduction.index", [
            "layout" => "create",
            "deductions" => $deductions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("1::admin.edit", ["layout" => "create"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());

        DB::beginTransaction();
        try {
            if (
                $request->has("deduction_id") &&
                sizeof($request->deduction_id)
            ) {
                // update

                if (sizeof($request->deduction_name)) {
                    for ($i = 0; $i < sizeof($request->deduction_name); $i++) {
                        if (isset($request->deduction_id[$i])) {
                            // update exists
                            $exists = PayrollDeductionModel::find(
                                $request->deduction_id[$i]
                            );
                            $exists->deduction_name =
                                $request->deduction_name[$i];
                            $exists->percentage = $request->percentage[$i];
                            $exists->active = $request->hidden_value[$i];
                            $exists->update();
                        } else {
                            $obj = new PayrollDeductionModel();
                            $obj->deduction_name = $request->deduction_name[$i];
                            $obj->percentage = $request->percentage[$i];
                            $obj->active = $request->hidden_value[$i];
                            $obj->save();
                        }
                    }
                } else {
                    DB::rollback();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with("exception_error", "Please Add any Dedutions");
                }
            } else {
                // create fresh
                if (sizeof($request->deduction_name)) {
                    for ($i = 0; $i < sizeof($request->deduction_name); $i++) {
                        $obj = new PayrollDeductionModel();
                        $obj->deduction_name = $request->deduction_name[$i];
                        $obj->percentage = $request->percentage[$i];
                        $obj->active = $request->activate[$i];
                        $obj->save();
                    }
                } else {
                    DB::rollback();
                    return redirect()
                        ->back()
                        ->withInput()
                        ->with("exception_error", "Please Add any Dedutions");
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
                ->with(
                    "exception_error",
                    "Something Went Wrong Maybe You Missed Out Fill Some Data"
                );
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("1.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("payrolldeduction.index");
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
        $data = PayrollDeductionModel::find($id);
        return view("1::admin.edit", ["layout" => "edit", "data" => $data]);
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
        dd($request->all());
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new PayrollDeductionModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);

        try {
            $obj = PayrollDeductionModel::find($id);
            $obj->name = $request->name;
            $obj->desc = $request->desc;
            $obj->status = $request->status;
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
        return redirect()->route("1.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_1)) {
            $delObj = new PayrollDeductionModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new PayrollDeductionModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("1.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-1");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = PayrollDeductionModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new PayrollDeductionModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new PayrollDeductionModel())->getTable() .
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
                    "route" => "1",
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
        CGate::authorize("edit-1");
        if ($request->ajax()) {
            PayrollDeductionModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new PayrollDeductionModel();
            foreach ($request->selected_1 as $k => $v) {
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
