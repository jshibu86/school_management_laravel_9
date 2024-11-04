<?php

namespace cms\exam\Controllers;
use Illuminate\Http\Request;
use cms\exam\Models\ExamTypeModel;
use App\Http\Controllers\Controller;
use cms\exam\Models\ExamTermModel;
use Yajra\DataTables\Facades\DataTables;
use cms\academicyear\Models\AcademicyearModel;
use cms\lclass\Models\LclassModel;
use Configurations;
use DB;
use CGate;
use Session;
use Carbon\Carbon;

class ExamTermController extends Controller
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
        //dd("here");
        if ($request->ajax()) {
            $acyear_id = $request->query->get("academic_year", 0);

            // return $acyear;
            $obj2 = new AcademicyearModel();

            $obj2->year = AcademicyearModel::where("id", $acyear_id)
                ->select("start_date", "end_date")
                ->first();

            $terms = ExamTermModel::select("id", "exam_term_name as text")
                ->where("academic_year", $acyear_id)
                ->where("status", 1)

                ->get();

            // return $obj2->year;
            return $terms;
        }
        return view("exam::admin.examterm.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $academic_years = Configurations::getAcademicyears();
        $academic_years_new = [];
        $datavalue = [];
        $data = [];

        foreach ($academic_years as $key => $value) {
            $is_exists = ExamTermModel::where("academic_year", $key)->first();

            if (!$is_exists) {
                $academic_years_new[$key] = $value;
            }
        }

        return view("exam::admin.examterm.edit", [
            "layout" => "create",
            "academic_years" => $academic_years_new,
            "datavalue" => $datavalue,
            "data" => $data,
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
        //dd($request->all());
        // dd(
        //     $request->termname,
        //     $request->term_start_date,
        //     $request->term_end_date
        // );
        $this->validate($request, [
            "termname" => "required",
            "academic_year" => "required",
        ]);

        DB::beginTransaction();
        try {
            $obj = new ExamTermModel();
            $obj->exam_term_name = mb_convert_case(
                $request->exam_term_name,
                MB_CASE_TITLE,
                "UTF-8"
            );

            $year = $request->academic_year;
            // dd($year);

            $is_exists = ExamTermModel::where("academic_year", $year)->first();

            if ($is_exists) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "This AcademicYear $year Already Added"
                    );
            }

            if (count($request->termname)) {
                $termnames = $request->termname;
                $termStartDates = $request->term_start_date;
                $termEndDates = $request->term_end_date;

                // Assuming that all arrays have the same length
                $count = count($termnames);

                for ($i = 0; $i < $count; $i++) {
                    $obj1 = new ExamTermModel();
                    $obj1->academic_year = $year;

                    // $obj2 = new AcademicyearModel();

                    // $obj2->year = AcademicyearModel::where(
                    //     "year",
                    //     $year
                    // )->first()->id;

                    // $obj1->academic_year = $obj2->year;

                    $obj1->exam_term_name = $termnames[$i];
                    $obj1->from_date = $termStartDates[$i];
                    $obj1->to_date = $termEndDates[$i];
                    $term_order = array_search(
                        $termnames[$i],
                        Configurations::TERMNAMES
                    );

                    $obj1->order = $term_order;
                    $obj1->save();
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
                ->with("exception_error", $message);
        }

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("examterm.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("examterm.index");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ExamTermModel::where("id", $id)->first();

        $academic_year = DB::table("academicyear")
            ->where("id", $data->academic_year)
            ->pluck("year")
            ->first();

        return view("exam::admin.examterm.view", [
            "data" => $data,
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
        $academic_years = Configurations::getAcademicyears();

        $academic_years_new = [];

        foreach ($academic_years as $key => $value) {
            $is_exists = ExamTermModel::where("academic_year", $key)->first();

            if ($is_exists) {
                $academic_years_new[$key] = $value;
            }
        }
        $academic_id = ExamTermModel::where("id", $id)
            ->pluck("academic_year")
            ->first();
        $aca_id = AcademicyearModel::where("id", $academic_id)
            ->select("year")
            ->first();

        $datavalue = ExamTermModel::where("id", $id)
            ->where("status", 1)
            ->get();
        if (
            count($datavalue) == 0 ||
            $datavalue === null ||
            $datavalue->isEmpty()
        ) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    "exception_error",
                    "This AcademicYear $aca_id->year doesn't create academic terms"
                );
        }
        //dd(count($datavalue));
        // dd($id);
        $data = (object) ["id" => $id, "academicyear" => $academic_id];
        // echo $key->id; // Accessing the property 'id' of the object '$key'
        // dd($data->academicyear);

        return view("exam::admin.examterm.edit", [
            "layout" => "edit",
            "data" => $data,
            "datavalue" => $datavalue,
            "academic_years" => $academic_years_new,
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
            "academic_year" => "required",
        ]);

        DB::beginTransaction();
        try {
            $obj = ExamTermModel::find($id);

            $obj->exam_term_name = $request->existtermname;
            $obj->from_date = $request->existterm_start_date;
            $obj->to_date = $request->existterm_end_date;
            $obj->save();

            $year = $request->academic_year;

            if (isset($request->termname)) {
                foreach ($request->termname as $key => $termname) {
                    $obj = new ExamTermModel();

                    $obj->exam_term_name = $termname;
                    $obj->from_date = $request->term_start_date[$key];
                    $obj->to_date = $request->term_end_date[$key];
                    $obj->academic_year = $year;
                    $obj->save();
                }
            }

            DB::commit();
            Session::flash("success", "Updated successfully");
            return redirect()->route("examterm.index");
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
            $delObj = new ExamTermModel();
            foreach ($request->selected_1 as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new ExamTermModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("examterm.index");
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

        //dd($data->academic_year);

        $data = ExamTermModel::select(
            DB::raw("@rownum := @rownum + 1 AS rownum"),
            "id",
            "exam_term_name",
            "academic_year",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new ExamTermModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new ExamTermModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            )
        )
            ->where("status", "!=", -1)
            ->orderBy("academic_year", "asc");

        $datatables = Datatables::of($data)
            ->addIndexColumn()
            ->addColumn("academic_year", function ($data) {
                $data = AcademicyearModel::where(
                    "id",
                    $data->academic_year
                )->first();
                if ($data) {
                    return $data->year;
                } else {
                    return "N/A";
                }
            })

            // ->addColumn("academic_year", function ($data) {
            //     return AcademicyearModel::where("status", 1)->first()->year;
            // })

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
                    "route" => "examterm",
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
            ExamTermModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_1)) {
            $obj = new ExamTermModel();
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
