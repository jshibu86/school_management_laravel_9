<?php

namespace cms\cmsmenu\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use cms\cmsmenu\Models\CmsmenuModel;
use cms\core\configurations\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;

class AcademicsController extends Controller
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
        $acadpage_datalists = [];
        $acad_data = [];
        $acadpage_datalists = CmsmenuModel::where("type", "3")
            ->get()
            ->toArray();
        if (!empty($acadpage_datalists)) {
            foreach ($acadpage_datalists as $acadpage_data) {
                //dd($acadpage_data['value']);
                $acad_data[$acadpage_data["key"]] = $acadpage_data["value"];
            }
        }
        return view("cmsmenu::admin.academics.academics", [
            "acad_data" => $acad_data,
        ]);
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
        $messages = [];
        $uploadPath = "cmsmenu/home/";
        $requestInput = $request->all();
        $acad_keys = $requestInput["acad_key"];
        $acad_values = $requestInput["acad_val"];
        $rules = ["acad_val.*" => "required"];
        $files = [
            "acad_sec1_image1",
            "acad_sec2_image1",
            "acad_sec2_image2",
            "acad_sec2_image3",
            "acad_sec4_image1",
            "acad_sec4_image2",
            "acad_sec4_image3",
            "acad_sec4_image4",
            "acad_sec2_image4",
        ];

        foreach ($files as $file) {
            $rules[$file] = "max:4000|mimes:jpg,jpeg,png";
        }

        //custom error messages
        $messages = [
            "acad_val.*.required" =>
                "All fields marked with * are required and must be filled.",
            "acad_sec1_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec1_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec2_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec2_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec2_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec2_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec2_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec2_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec2_image4.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec2_image4.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec3_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec3_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec3_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec3_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec3_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec3_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec4_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec4_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec4_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec4_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec4_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec4_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec5_image1.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec5_image1.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec5_image2.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec5_image2.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec5_image3.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec5_image3.mimes" =>
                "Supported file format: jpg, jpeg, png.",
            "acad_sec2_image4.max" =>
                "Image size should not be greater than 4000 kilobytes.",
            "acad_sec2_image4.mimes" =>
                "Supported file format: jpg, jpeg, png.",
        ];

        $this->validate($request, $rules, $messages);

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $acad_sec1_imgkey = $file;
                $acad_sec1_imgvalue = $this->CoverImage(
                    $request->$file,
                    $uploadPath
                );

                // Append the image key and value to the database array
                $acad_keys[] = $acad_sec1_imgkey;
                $acad_values[] = $acad_sec1_imgvalue;
            }
        }

        // Iterate through keys and values and save them
        for ($i = 0; $i < count($acad_keys); $i++) {
            CmsmenuModel::updateOrCreate(
                ["key" => $acad_keys[$i]],
                ["value" => $acad_values[$i], "type" => "3"]
            );
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("academicsmenu.index");
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
        //
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
}
