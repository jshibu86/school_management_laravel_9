<?php
namespace cms\academicyear\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\academicyear\Models\AcademicyearModel;
use cms\exam\Models\ExamTermModel;
use Session;
use DB;
use Configurations;
use Carbon\Carbon;

class AcademicyearPopupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        return view("academicyear::admin.academicyearpopup.edit", [
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
        //dd($request->all());
        $this->validate($request, [
            "year_from" => "required",
            "year_to" => "required",
            "start_date" => "required|before:end_date",
            "end_date" => "required|after:start_date",

            // "termname" => "required",
            // "term_start_date" => "required|before:term_end_date",
            // "term_end_date" => "required|after:term_start_date",
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
        $obj->year = $year;
        $obj->title = $request->name ? $request->name : $year;
        $obj->start_date = $request->start_date;
        $obj->end_date = $request->end_date;
        $obj->save();

        if (count($request->termname)) {
            $termnames = $request->termname;
            $termStartDates = $request->term_start_date;
            $termEndDates = $request->term_end_date;

            // Assuming that all arrays have the same length
            $count = count($termnames);

            for ($i = 0; $i < $count; $i++) {
                $obj1 = new ExamTermModel();
                // $obj1->academic_year = $year;

                $obj2 = new AcademicyearModel();

                $obj2->year = AcademicyearModel::where(
                    "year",
                    $year
                )->first()->id;

                $obj1->academic_year = $obj2->year;

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

        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("academicyear.create")
                ->with("success", "Saved Successfully");
        }

        Session::flash("success", "Academic Year saved successfully");
        return redirect()->route("academicyear.index");
    }
}
