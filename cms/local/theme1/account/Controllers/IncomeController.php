<?php

namespace cms\account\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\account\Models\IncomeExpenseModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use cms\academicyear\Models\AcademicyearModel;
use cms\account\Models\IncomeExpenseCategoryModel;
use Configurations;
class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("account::income.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $category = IncomeExpenseCategoryModel::where("type", "income")->pluck(
            "category_name",
            "id"
        );

        return view("account::income.edit", [
            "layout" => "create",
            "category" => $category,
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
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

        DB::beginTransaction();
        try {
            [$month, $year] = Configurations::DatepickerToMonth(
                $request->entry_date
            );
            $obj = new IncomeExpenseModel();
            $obj->title = $request->title;
            $obj->academic_year = $request->academic_year;
            $obj->category_id = $request->category_id;
            $obj->type = $request->type;
            $obj->entry_date = $request->entry_date;
            $obj->description = $request->description;
            $obj->amount = $request->amount;
            $obj->month = $month;
            $obj->year = $year;
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
                ->route("income.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("expense.index");
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
        $category = IncomeExpenseCategoryModel::where("type", "income")->pluck(
            "category_name",
            "id"
        );
        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $data = IncomeExpenseModel::find($id);
        return view("account::income.edit", [
            "layout" => "edit",
            "data" => $data,
            "category" => $category,
            "academic_years" => $academic_years,
            "current_academic_year" => $current_academic_year,
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
        try {
            [$month, $year] = Configurations::DatepickerToMonth(
                $request->entry_date
            );
            $obj = IncomeExpenseModel::find($id);
            $obj->title = $request->title;
            $obj->academic_year = $request->academic_year;
            $obj->category_id = $request->category_id;
            $obj->type = $request->type;
            $obj->entry_date = $request->entry_date;
            $obj->description = $request->description;
            $obj->amount = $request->amount;
            $obj->month = $month;
            $obj->year = $year;
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
        return redirect()->route("income.index");
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
            $delObj = new IncomeModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new IncomeExpenseModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("income.index");
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

        $data = IncomeExpenseModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "academic_year",
            "title",
            "entry_date",
            "amount",
            "category_id",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new IncomeExpenseModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new IncomeExpenseModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("status", "!=", -1)
            ->where("type", "income");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("check", function ($data) {
                if ($data->id != "1") {
                    return $data->rownum;
                } else {
                    return "";
                }
            })
            ->addColumn("acyear", function ($data) {
                return AcademicyearModel::where(
                    "id",
                    $data->academic_year
                )->first()->year;
            })
            ->addColumn("category", function ($data) {
                return IncomeExpenseCategoryModel::where(
                    "id",
                    $data->category_id
                )->first()->category_name;
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
                    "route" => "income",
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
            IncomeExpenseModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new IncomeModel();
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
