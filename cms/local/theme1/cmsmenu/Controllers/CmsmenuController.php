<?php

namespace cms\cmsmenu\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\cmsmenu\Models\CmsmenuModel;
use cms\core\configurations\Traits\FileUploadTrait;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;

class CmsmenuController extends Controller
{
    use FileUploadTrait;
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
        $homepage_datalists = [];
        $homepage_record = [];
        $homepage_datalists = CmsmenuModel::where("type", "1")
            ->get()
            ->toArray();
        if (!empty($homepage_datalists)) {
            foreach ($homepage_datalists as $homepage_data) {
                //dd($homepage_data['value']);
                $homepage_record[$homepage_data["key"]] =
                    $homepage_data["value"];
            }
        }
        // $footer_data = [];
        // $footer_keys[] = ['home_sec7_link1','home_sec7_link2','home_sec7_link3','home_sec2_image4'];
        // if(array_key_exists($label, $homepage_record)){
        //     $footer_data[$label] = $homepage_record[$label];
        // }

        return view("cmsmenu::admin.home", [
            "homepage_record" => $homepage_record,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("cmsmenu::admin.edit", ["layout" => "create"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $uploadPath = "cmsmenu/home/";
        $requestInput = $request->all();
        $home_keys = $requestInput["home_key"];
        $home_values = $requestInput["home_val"];
        $rules = ["home_val.*" => "required"];
        $files = [
            "home_sec1_image1",
            "home_sec2_image1",
            "home_sec2_image2",
            "home_sec2_image3",
            "home_sec2_image4",
            "home_sec3_image1",
            "home_sec3_image2",
            "home_sec3_image3",
            "home_sec4_image1",
            "home_sec4_image2",
            "home_sec4_image3",
            "home_sec5_image1",
            "home_sec5_image2",
            "home_sec5_image3",
            "home_sec3_image4",
            "home_sec5_image4",
        ];
        $messages = [];
        foreach ($files as $file) {
            $rules[$file] = "max:4000|mimes:jpg,jpeg,png";
        }
        //custom error messages
        $messages = [
            "home_val.*.required" =>
                "All fields marked with * are required and must be filled.",

            "home_sec1_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec1_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec2_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec2_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec2_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec2_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec2_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec2_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec2_image4.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec2_image4.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec3_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec3_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec3_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec3_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec3_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec3_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec3_image4.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec3_image4.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec4_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec4_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec4_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec4_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec4_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec4_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec5_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec5_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec5_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec5_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec5_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec5_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "home_sec5_image4.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "home_sec5_image4.mimes" =>
                "Supported file format: jpg, jpeg, png.",
        ];

        // $this->validate($request, $rules, $messages );

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $home_sec1_imgkey = $file;
                $home_sec1_imgvalue = $this->CoverImage(
                    $request->$file,
                    $uploadPath
                );

                // Append the image key and value to the database array
                $home_keys[] = $home_sec1_imgkey;
                $home_values[] = $home_sec1_imgvalue;
            }
        }

        // Iterate through keys and values and save them
        // for ($i = 0; $i < count($home_keys); $i++) {
        //     CmsmenuModel::updateOrCreate(
        //             ['key' => $home_keys[$i]],
        //             ['value' => $home_values[$i],
        //             'type' => "1"]
        //     );
        // }
        for ($i = 0; $i < count($home_keys); $i++) {
            if ($home_values[$i] != null) {
                CmsmenuModel::updateOrCreate(
                    ["key" => $home_keys[$i]],
                    ["value" => $home_values[$i], "type" => "1"]
                );
            }
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("cmsmenu.index");
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
        $data = CmsmenuModel::find($id);
        return view("cmsmenu::admin.edit", [
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
                (new CmsmenuModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);
        $obj = CmsmenuModel::find($id);
        $obj->name = $request->name;
        $obj->desc = $request->desc;
        $obj->status = $request->status;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("cmsmenu.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_cmsmenu)) {
            $delObj = new CmsmenuModel();
            foreach ($request->selected_cmsmenu as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("cmsmenu.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-cmsmenu");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = CmsmenuModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new CmsmenuModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new CmsmenuModel())->getTable() .
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
                    route("cmsmenu.edit", $data->id) .
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
        CGate::authorize("edit-cmsmenu");

        if (!empty($request->selected_cmsmenu)) {
            $obj = new CmsmenuModel();
            foreach ($request->selected_cmsmenu as $k => $v) {
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
