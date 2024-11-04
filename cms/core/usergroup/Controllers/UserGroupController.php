<?php

namespace cms\core\usergroup\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Yajra\DataTables\Facades\DataTables;

//helpers
use DB;
use User;
use Session;
use CGate;

//models
use cms\core\usergroup\Models\UserGroupModel;

class UserGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            CGate::resouce("usergroup");
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("usergroup::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("usergroup::admin.edit", ["layout" => "create"]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "group" =>
                "required|unique:" .
                (new UserGroupModel())->getTable() .
                ",group|max:191",
        ]);

        $data = new UserGroupModel();
        $data->group = mb_convert_case($request->group, MB_CASE_TITLE, "UTF-8");
        $data->status = 1;

        if ($data->save()) {
            $msg = "Group save successfully";
            $class_name = "success";
        } else {
            $msg = "Something went wrong !! Please try again later !!";
            $class_name = "error";
        }
        if ($request->has("submit_cat_continue")) {
            return redirect()
                ->route("usergroup.create")
                ->with("success", "Saved Successfully");
        }
        Session::flash($class_name, $msg);
        return redirect(route("usergroup.index"));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo "hai";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = UserGroupModel::find($id);
        return view("usergroup::admin.edit", [
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
            "group" =>
                "required|unique:" .
                (new UserGroupModel())->getTable() .
                ",group," .
                $id,
        ]);

        $data = UserGroupModel::find($id);
        $data->group = mb_convert_case($request->group, MB_CASE_TITLE, "UTF-8");
        $data->status = 1;

        if ($data->save()) {
            $msg = "Group Update successfully";
            $class_name = "success";
        } else {
            $msg = "Something went wrong !! Please try again later !!";
            $class_name = "error";
        }

        Session::flash($class_name, $msg);
        return redirect(route("usergroup.index"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        //print_r($request->selected_groups);exit;

        if (!empty($request->selected_groups)) {
            if (($key = array_search(1, $request->selected_groups)) !== false) {
                $request->selected_groups = array_except(
                    $request->selected_groups,
                    [$key]
                );
            }

            $delObj = new UserGroupModel();
            foreach ($request->selected_groups as $k => $v) {
                //echo $v;
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }
        if ($id) {
            $delObj = new UserGroupModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "User Group Deleted Successfully!!");
        return redirect()->route("usergroup.index");
    }

    /*
     * *********************additional methods*************************
     */

    /*
     * get user data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-usergroup");

        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        //$sTart = 0;
        DB::statement(DB::raw("set @rownum=" . $sTart));

        $data = UserGroupModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "group",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new UserGroupModel())->getTable() .
                    '.status = "0" THEN "Disabled"
             WHEN ' .
                    DB::getTablePrefix() .
                    (new UserGroupModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
             ELSE "Enabled" END) AS status'
            )
        )->orderBy("id", "asc");

        $datatables = Datatables::of($data)
            //->addColumn('check', '{!! Form::checkbox(\'selected_users[]\', $id, false, array(\'id\'=> $rownum, \'class\' => \'catclass\')); !!}{!! Html::decode(Form::label($rownum,\'<span></span>\')) !!}')
            ->addIndexColumn()
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
                if ($data->id != "1") {
                    return view("layout::datatable.action", [
                        "data" => $data,
                        "route" => "usergroup",
                    ])->render();
                } else {
                    return "";
                }

                //return $data->id;
            });

        // return $data;
        if (count((array) $data) == 0) {
            return [];
        }

        return $datatables->make(true);
    }

    /*
     * user bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-usergroup");

        if ($request->ajax()) {
            UserGroupModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_groups)) {
            if (($key = array_search(1, $request->selected_groups)) !== false) {
                $request->selected_groups = array_except(
                    $request->selected_groups,
                    [$key]
                );
            }

            $obj = new UserGroupModel();
            foreach ($request->selected_groups as $k => $v) {
                //echo $v;
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "User Group Status Changed Successfully!!");
        return redirect()->back();
    }
}
