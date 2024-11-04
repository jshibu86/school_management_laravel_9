<?php

namespace cms\gallery\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\gallery\Models\GalleryModel;

use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Session;
use DateTime;
use DB;
use CGate;
use cms\core\configurations\Traits\FileUploadTrait;

class GalleryController extends Controller
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
        return view("gallery::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("gallery::admin.edit", ["layout" => "create"]);
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
        $this->validate(
            $request,
            [
                "title" => "required",
                "imagec" => "image|mimes:jpeg,png,jpg|max:4048",
            ],
            [
                "title.required" => "Gallery Title was Required",
                "imagec.image" => "The file must be an image",
                "imagec.mimes" =>
                    "The image must be a file of type: jpeg, png, jpg, gif",
                "imagec.max" => "The image may not be greater than 2MB",
            ]
        );
        DB::beginTransaction();
        try {
            $obj = new GalleryModel();
            $obj->title = $request->title;
            $obj->created_date = Carbon::now();
            $obj->description = $request->description;
            if ($request->imagec) {
                $obj->image = $this->uploadAttachment(
                    $request->imagec,
                    "image",
                    "gallery/"
                );
            }

            $obj->save();

            DB::commit();
            Session::flash("success", "saved successfully");
            return redirect()->route("gallery.index");
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
        $data = GalleryModel::find($id);
        return view("gallery::admin.edit", [
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
        $this->validate(
            $request,
            [
                "title" => "required",
                "imagec" => "image|mimes:jpeg,png,jpg|max:4048",
            ],
            [
                "title.required" => "Gallery Title was Required",
                "imagec.image" => "The file must be an image",
                "imagec.mimes" =>
                    "The image must be a file of type: jpeg, png, jpg, gif",
                "imagec.max" => "The image may not be greater than 2MB",
            ]
        );

        try {
            $obj = GalleryModel::find($id);

            $obj->title = $request->title;
            $obj->description = $request->description;
            if ($request->imagec) {
                $this->deleteImage("/", $obj->image);
                $obj->image = $this->uploadAttachment(
                    $request->imagec,
                    "image",
                    "gallery/"
                );
            }

            $obj->save();
            DB::commit();
            Session::flash("success", "Updated successfully");
            return redirect()->route("gallery.index");
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
        if (!empty($request->selected_event)) {
            $delObj = new GalleryModel();
            foreach ($request->selected_event as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new GalleryModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("gallery.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-gallery");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = GalleryModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "title",
            "created_date",
            "image",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new GalleryModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new GalleryModel())->getTable() .
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
            ->addColumn("created_date", function ($data) {
                $dateString = $data->created_date;
                $dateTime = new DateTime($dateString);

                $formattedDate = $dateTime->format("F j, Y");
                return $formattedDate;
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
                    "route" => "gallery",
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
        CGate::authorize("edit-gallery");

        if ($request->ajax()) {
            GalleryModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_event)) {
            $obj = new GalleryModel();
            foreach ($request->selected_event as $k => $v) {
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
