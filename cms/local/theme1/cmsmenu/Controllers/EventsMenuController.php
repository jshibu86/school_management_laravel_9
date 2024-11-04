<?php

namespace cms\cmsmenu\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\cmsmenu\Models\CmsmenuModel;
use App\Models\EventsMenuModel;
use Yajra\DataTables\Facades\DataTables;
use cms\core\configurations\Traits\FileUploadTrait;

use Session;
use DB;
use CGate;

class EventsMenuController extends Controller
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
        $events_menu = EventsMenuModel::get();
        $menu = $events_menu->pluck("value", "key")->toArray();
        return view("cmsmenu::admin.events.eventsmenu", ["data" => $menu]);
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
        $this->validate(
            $request,
            [
                "banner_title" => "required",
                "banner_image" => "image|mimes:jpeg,png,jpg|max:4096",
            ],
            [
                "banner_title.required" => "Banner title was required",
                "banner_image.image" => "The file must be an image",
                "banner_image.mimes" =>
                    "The image must be a file of type: jpeg, png, jpg, gif",
                "banner_image.max" => "The image may not be greater than 4MB",
            ]
        );

        $eventsmenu = EventsMenuModel::all();
        foreach ($eventsmenu as $menu) {
            try {
                if (
                    $menu->key == "banner_title" &&
                    isset($request->banner_title)
                ) {
                    $menu->value = $request->banner_title;
                }
                if (
                    $menu->key == "banner_description" &&
                    isset($request->banner_description)
                ) {
                    $menu->value = $request->banner_description;
                }
                if (
                    $menu->key == "banner_image" &&
                    isset($request->banner_image)
                ) {
                    $menu->value = $this->uploadCMSMenuImage(
                        $request->banner_image,
                        "cmsmenu/events/"
                    );
                }
                $menu->update();
            } catch (\Exception $e) {
                error_log("Error updating menu item: " . $e->getMessage());
            }
        }

        Session::flash("success", "saved successfully");
        return redirect()->route("eventsmenu_index");
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
