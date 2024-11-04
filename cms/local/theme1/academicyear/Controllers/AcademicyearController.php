<?php

namespace cms\academicyear\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\academicyear\Models\AcademicyearModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use Configurations;
use Carbon\Carbon;
use cms\exam\Models\ExamTermModel;

class AcademicyearController extends Controller
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
        return view("academicyear::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("academicyear::admin.edit", ["layout" => "create"]);
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
            "name" => "required|regex:/^[a-zA-Z\s]*$/",
            "year_from" => "required",
            "year_to" => "required",
            "start_date" => "required|before:end_date",
            "end_date" => "required|after:start_date",
        ]);

        $year = $request->year_from . "-" . $request->year_to;

        $is_exists = AcademicyearModel::where("year", $year)->first();

        if ($is_exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "exception_error",
                    "This AcademicYear $year Already Added"
                );
        }

        if ($request->year_from > $request->year_to) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "exception_error",
                    "This is Not a Valid AcademicYear $year Year From Lessthan Year To"
                );
        }

        $obj = new AcademicyearModel();

        $obj->title = $request->name ? $request->name : $year;
        $obj->start_date = $request->start_date;
        $obj->end_date = $request->end_date;
        $obj->year = $year;

        $obj->save();

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("academicyear.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "Academic Year saved successfully");
        return redirect()->route("academicyear.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $academic_year = AcademicyearModel::find($id);

        $academic_terms = ExamTermModel::where(
            "academic_year",
            $academic_year->id
        )->get();
        //dd( $academic_term);
        return view("academicyear::admin.show", [
            "academic_terms" => $academic_terms,
            "academic_year" => $academic_year,
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
        $data = AcademicyearModel::find($id);

        $year_split = explode("-", $data->year);
        $data["year_from"] = $year_split[0];
        $data["year_to"] = $year_split[1];

        return view("academicyear::admin.edit", [
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
                "required|regex:/^[a-zA-Z\s]*$/|unique:" .
                (new AcademicyearModel())->getTable() .
                ",year," .
                $id,
            "year_from" => "required",
            "year_to" => "required",
            "start_date" => "required|before:end_date",
            "end_date" => "required|after:start_date",
        ]);

        // dd($request->all());
        $obj = AcademicyearModel::find($id);
        $year = $request->year_from . "-" . $request->year_to;

        $is_exists = AcademicyearModel::where("year", $year)->first();

        if ($is_exists && $is_exists->id != $id) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "exception_error",
                    "This AcademicYear $year Already Added"
                );
        }

        if ($request->year_from > $request->year_to) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "exception_error",
                    "This is Not a Valid AcademicYear $year Year From Lessthan Year To"
                );
        }
        $obj->title = $request->name ? $request->name : $year;

        $obj->start_date = $request->start_date;
        $obj->end_date = $request->end_date;
        $obj->year = $year;

        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("academicyear.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_academicyear)) {
            if (
                ($key = array_search(
                    Configurations::getCurrentAcademicyear(),
                    $request->selected_academicyear
                )) !== false
            ) {
                $request->selected_academicyear = array_except(
                    $request->selected_academicyear,
                    [$key]
                );
            }
            $delObj = new AcademicyearModel();
            foreach ($request->selected_academicyear as $k => $v) {
                //echo $v;
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new AcademicyearModel();
            $delItem = $delObj->find($id);
            $delItem->forceDelete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("academicyear.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-academicyear");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = AcademicyearModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "title",
            "year",
            "start_date",
            "end_date",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new AcademicyearModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new AcademicyearModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
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
                $statusbtnvalue =
                    $data->status == "Enabled"
                        ? "<i class='glyphicon glyphicon-ok'></i>&nbsp;&nbsp;Enabled"
                        : "<i class='glyphicon glyphicon-remove'></i>&nbsp;&nbsp;Disable";

                // return '<input id="toggle-demo" type="checkbox" checked data-toggle="toggle" data-on="Ready" data-off="Not Ready" data-onstyle="success" data-offstyle="danger">';
                return '<a class="statusbutton btn btn-default" data-toggle="modal" data="' .
                    $data->id .
                    '" href="">' .
                    $statusbtnvalue .
                    '"</a>"';
            })
            ->addColumn("action", function ($data) {
                return view("layout::datatable.action", [
                    "data" => $data,
                    "route" => "academicyear",
                    //  "route1" => "examterm",
                ])->render();

                //return $data->id;
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->rawColumns(["actdeact", "action"])->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-academicyear");

        if ($request->ajax()) {
            AcademicyearModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_academicyear)) {
            if (
                ($key = array_search(
                    Configurations::getCurrentAcademicyear(),
                    $request->selected_academicyear
                )) !== false
            ) {
                $request->selected_academicyear = array_except(
                    $request->selected_academicyear,
                    [$key]
                );
            }
            $obj = new AcademicyearModel();
            foreach ($request->selected_academicyear as $k => $v) {
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
