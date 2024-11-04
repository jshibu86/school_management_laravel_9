<?php

namespace cms\mark\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\mark\Models\MarkDistributionModel;
use cms\mark\Models\SchoolTypeModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use Configurations;
use Carbon\Carbon;
class DistributeMarkController extends Controller
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $school_id = $request->school_id;
        // dd($school_id);
        DB::beginTransaction();
        try {
            $mark_data = [];

            // Assuming distribution_name_0 and distribution_mark_0 will always be together
            foreach (
                $request->input("distribution_name")
                as $index => $distributionName
            ) {
                $distributionMark = $request->input("distribution_mark")[
                    $index
                ];
                $status = $request->input("status")[$index] ?? 0; // Assuming default status is 0 if not provided

                $obj = new MarkDistributionModel();
                $obj->distribution_name = $distributionName;
                $obj->mark = $distributionMark;
                $obj->school_type_id = $school_id;
                $obj->save();
            }
            DB::commit();

            return redirect()
                ->route("markdistribution.index")
                ->with("success", "Distribution Added Successfully");
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
        //
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
        $school_id = $id;

        DB::beginTransaction();
        try {
            foreach (
                $request->input("distribution_name")
                as $index => $distributionName
            ) {
                $distributionMark = $request->input("distribution_mark")[
                    $index
                ];
                $status = $request->input("status")[$index] ?? 0; // Default status is 0 if not provided
                $distribution_id = isset(
                    $request->input("distribution_id")[$index]
                )
                    ? $request->input("distribution_id")[$index]
                    : 0;
                // Retrieve and update each record based on school_id
                $is_exists = MarkDistributionModel::where(
                    "id",
                    $distribution_id
                )->exists();
                if ($distribution_id !== 0) {
                    $obj = MarkDistributionModel::where([
                        "school_type_id" => $id,
                        "id" => $distribution_id,
                    ])->first();
                    // dd($id);
                    $obj->distribution_name = $distributionName;
                    $obj->mark = $distributionMark;
                    $obj->status = $status;
                    $obj->save();
                } else {
                    $obj = new MarkDistributionModel();
                    $obj->distribution_name = $distributionName;
                    $obj->mark = $distributionMark;
                    $obj->school_type_id = $school_id;
                    $obj->save();
                }
            }

            DB::commit();

            return redirect()
                ->route("markdistribution.index")
                ->with("success", "Distribution Updated Successfully");
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
    public function destroy($id)
    {
        //
    }
    public function add_mark_distribution(Request $request)
    {
        //    dd($request->all());

        $view = view("mark::markdistribution.mark_distribute_append")
            ->with([
                "distribution" => "yes",
            ])
            ->render();
        return response()->json(["view" => $view]);
    }
    public function statusChange(Request $request)
    {
        $id = $request->id;
        $status = $request->status ? 1 : 0;
        $distribution = MarkDistributionModel::find($id);
        $distribution->status = $status;
        if ($distribution->save()) {
            return response()->json(["success" => true]);
        }
    }
}
