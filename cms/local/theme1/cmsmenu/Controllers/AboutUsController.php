<?php

namespace cms\cmsmenu\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\cmsmenu\Models\CmsmenuModel;
use cms\cmsmenu\Controllers\WebsiteController;
use cms\core\configurations\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;

class AboutUsController extends Controller
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
        $aboutus_datalists = [];
        $aboutus_data = [];
        $aboutus_datalists = CmsmenuModel::where("type", "2")
            ->get()
            ->toArray();

        if (!empty($aboutus_datalists)) {
            foreach ($aboutus_datalists as $aboutus_record) {
                $aboutus_data[$aboutus_record["key"]] =
                    $aboutus_record["value"];
            }
        }
        //dd($aboutus_data);
        return view("cmsmenu::admin.Aboutus.home", [
            "aboutus_data" => $aboutus_data,
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
        $about_keys = $requestInput["about_key"];
        $about_values = $requestInput["about_val"];
        $rules = ["about_val.*" => "required"];
        $files = [
            "about_sec1_img",
            "about_sec2_image1",
            "about_sec2_image2",
            "about_sec3_image1",
            "about_sec3_image2",
            "about_sec3_image3",
            "about_sec4_image1",
            "about_sec4_image2",
            "about_sec4_image3",
            "about_sec4_image4",
        ];

        foreach ($files as $file) {
            $rules[$file] = "max:4000|mimes:jpg,jpeg,png";
        }

        //custom error messages
        $messages = [
            "about_val.*.required" =>
                "All fields marked with * are required and must be filled.",
            "about_sec1_img.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec1_img.mimes" => "Supported file format: jpg, jpeg, png.",
            "about_sec2_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec2_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "about_sec2_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec2_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "about_sec3_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec3_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "about_sec3_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec3_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "about_sec3_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec3_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "about_sec4_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec4_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "about_sec4_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec4_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "about_sec4_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec4_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "about_sec4_image4.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "about_sec4_image4.mimes" =>
                "Supported file format: jpg, jpeg, png.",
        ];

        // $this->validate($request, $rules, $messages);

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $about_imgkey = $file;
                $about_imgvalue = $this->CoverImage(
                    $request->$file,
                    $uploadPath
                );

                // Append the image key and value to the database array
                $about_keys[] = $about_imgkey;
                $about_values[] = $about_imgvalue;
            }
        }

        // Iterate through keys and values and save them
        // for ($i = 0; $i < count($about_keys); $i++) {
        //     CmsmenuModel::updateOrCreate(
        //             ['key' => $about_keys[$i]],
        //             ['value' => $about_values[$i],
        //             'type' => "2"]
        //     );
        // }

        for ($i = 0; $i < count($about_keys); $i++) {
            if ($about_values[$i] != null) {
                CmsmenuModel::updateOrCreate(
                    ["key" => $about_keys[$i]],
                    ["value" => $about_values[$i], "type" => "2"]
                );
            }
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("aboutus.index");
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
