<?php

namespace cms\payrool\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\payrool\Models\SaleryTemplateModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\payrool\Models\SaleryParticularsModel;

class SaleryTemplateController extends Controller
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
        return view("payrool::template.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $particulars = SaleryParticularsModel::where("status", 1)->get();
        return view("payrool::template.edit", [
            "layout" => "create",
            "particulars" => $particulars,
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
            "grade_name" =>
                "required|unique:" .
                (new SaleryTemplateModel())->getTable() .
                ",grade_name",
            "basic_salery" => "required",
        ]);
        DB::beginTransaction();
        try {
            $particulars = [];
            if (sizeof($request->particular)) {
                for ($i = 0; $i < sizeof($request->particular); $i++) {
                    $particulars[] = [
                        "particular_id" => $request->particular[$i],
                        "deduction_per" => $request->deduction[$i],
                        "deduction_amount" => $request->amount[$i],
                    ];
                }
            }
            $obj = new SaleryTemplateModel();
            $obj->grade_name = $request->grade_name;
            $obj->basic_salery = $request->basic_salery;
            $obj->salery_with_particulars = $request->salery_with_particulars;
            $obj->total_deduction = $request->total_deduction;
            $obj->particulars = sizeof($particulars) ? $particulars : null;
            $obj->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            dd($e);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("salerytemplate.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("salerytemplate.index");
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
        $data = SaleryTemplateModel::find($id);
        $particulars = SaleryParticularsModel::where("status", 1)->get();

        //dd($data);
        return view("payrool::template.edit", [
            "layout" => "edit",
            "data" => $data,
            "particulars" => $particulars,
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
        abort(500);
        $this->validate($request, [
            "name" =>
                "required|min:3|max:50|unique:" .
                (new SaleryTemplateModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);

        try {
            $obj = SaleryTemplateModel::find($id);
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
        return redirect()->route("salerytemplate.index");
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
            $delObj = new SaleryTemplateModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new SaleryTemplateModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("salerytemplate.index");
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

        $data = SaleryTemplateModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "grade_name",
            "basic_salery",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new SaleryTemplateModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new SaleryTemplateModel())->getTable() .
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
                    "route" => "salerytemplate",
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
            SaleryTemplateModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new SaleryTemplateModel();
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
