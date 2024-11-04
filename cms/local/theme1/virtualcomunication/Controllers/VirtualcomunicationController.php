<?php

namespace cms\virtualcomunication\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\user\Models\UserModel;
use Yajra\DataTables\Facades\DataTables;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\virtualcomunication\Models\VirtualcomunicationModel;
use cms\virtualcomunication\Models\VirtualCommunicationMappingModel;
use cms\lclass\Models\LclassModel;
use cms\section\Models\SectionModel;
use cms\students\Models\StudentsModel;
use Carbon\Carbon;
use Session;
use DB;
use CGate;
use DateTime;
use Configurations;
use Illuminate\Support\Facades\URL;
use cms\students\Models\ParentModel;

class VirtualcomunicationController extends Controller
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
        $user_id = \Auth::user()->id;
        $user_group_id = UserGroupMapModel::where("user_id", $user_id)
            ->pluck("group_id")
            ->first();
        $allowed_group = Configurations::ALLOWEDGROUPS;
        if (array_key_exists($user_group_id, $allowed_group)) {
            $is_allowed = true;
        } else {
            $is_allowed = false;
        }

        return view("virtualcomunication::admin.index", [
            "is_allowed" => $is_allowed,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = [];
        $users = UserModel::select(
            DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text"),
            "id"
        )
            ->whereNull("deleted_at")
            ->pluck("text", "id");
        $users->prepend("All", "all");
        $meeting_types = collect(Configurations::MEETINGTYPES)->pluck(
            "text",
            "id"
        );
        $class = LclassModel::where("status", "=", 1)->pluck(
            "name as text",
            "id"
        );
        // dd( $users);
        return view("virtualcomunication::admin.edit", [
            "layout" => "create",
            "users" => $users,
            "groups" => $groups,
            "class" => $class,
            "meeting_types" => $meeting_types,
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
        $this->validate(
            $request,
            [
                "meeting_title" => "required",
                "meeting_date" => "required",
                "meet_time" => "required",
                "participants" => "required|array",
            ],
            [
                "meeting_title.required" => "The meeting title is required.",
                "meeting_date.required" => "The meeting date is required.",
                "meet_time.required" => "The meeting time is required.",
                "participants.required" => "Participants are required.",
                "participants.array" => "Participants must be an array.",
            ]
        );
        try {
            $user_id = \Auth::user()->id;

            $obj = new VirtualcomunicationModel();
            $obj->title = $request->meeting_title;
            $obj->moderator = $user_id;
            $obj->meeting_token = $request->meeting_token;
            $obj->meeting_date = $request->meeting_date;
            $obj->time = $request->meet_time;
            $obj->description = $request->meeting_description;
            $obj->meeting_type = $request->meeting_type;
            if ($obj->save()) {
                // dd($request->all());
                if (in_array("all", (array) $request->participants)) {
                    // dd("all");
                    $group_id = $request->participants_group;
                    if ($group_id == "all") {
                        if ($request->meeting_type == 1) {
                            $pta_groups = Configurations::PTAMEETINGGROUPS;
                            $group_ids = UserGroupModel::whereIn(
                                "group",
                                $pta_groups
                            )
                                ->where("status", 1)
                                ->pluck("id");
                            $user_ids = UserGroupMapModel::whereIn(
                                "group_id",
                                $group_ids
                            )->pluck("user_id");
                        } else {
                            $user_ids = UserGroupMapModel::where(
                                "group_id",
                                "!=",
                                5
                            )->pluck("user_id");
                        }
                    } elseif ($group_id == "4") {
                        $user_ids = StudentsModel::where(
                            "class_id",
                            $request->class_id
                        )
                            ->where("section_id", $request->section)
                            ->pluck("user_id");
                    } elseif ($group_id == "5") {
                        // dd(5);
                        $parent_ids = StudentsModel::where(
                            "class_id",
                            $request->class_id
                        )
                            ->where("section_id", $request->section)
                            ->where("status", 1)
                            ->whereNull("deleted_at")
                            ->pluck("parent_id");

                        $user_ids = ParentModel::whereIn(
                            "id",
                            $parent_ids
                        )->pluck("user_id");
                        $up_obj = VirtualcomunicationModel::find($obj->id);
                        $up_obj->class = $request->class_id;
                        $up_obj->section = $request->section;
                        $up_obj->save();
                    } else {
                        $user_ids = UserGroupMapModel::where(
                            "group_id",
                            $group_id
                        )->pluck("user_id");
                    }
                    // dd($user_ids, $user_id);
                    if (!in_array($user_id, (array) $user_ids)) {
                        $map = new VirtualCommunicationMappingModel();
                        $map->virtual_comunication_list_id = $obj->id;
                        $map->participants = $user_id;
                        $map->save();
                    }
                    foreach ($user_ids as $key => $participant) {
                        $map = new VirtualCommunicationMappingModel();
                        $map->virtual_comunication_list_id = $obj->id;
                        $map->participants = $participant;
                        $map->save();
                    }
                } else {
                    // dd("none");
                    if (!in_array($user_id, $request->participants)) {
                        $map = new VirtualCommunicationMappingModel();
                        $map->virtual_comunication_list_id = $obj->id;
                        $map->participants = $user_id;
                        $map->save();
                    }
                    if ($request->meeting_type == 1) {
                        $up_obj = VirtualcomunicationModel::find($obj->id);
                        $up_obj->class = $request->class_id;
                        $up_obj->section = $request->section;
                        $up_obj->save();
                    }

                    foreach ($request->participants as $key => $participant) {
                        $map = new VirtualCommunicationMappingModel();
                        $map->virtual_comunication_list_id = $obj->id;
                        $map->participants = $participant;
                        $map->save();
                    }
                }
            }
            DB::commit();
            Session::flash("success", "Virutal Meeting created successfully");
            return redirect()->route("virtualcomunication.index");
        } catch (\Exception $e) {
            DB::rollBack();
            // dd($e);
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
        $data = VirtualcomunicationModel::find($id);
        return view("virtualcomunication::admin.edit", [
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
                (new VirtualcomunicationModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);
        $obj = VirtualcomunicationModel::find($id);
        $obj->name = $request->name;
        $obj->desc = $request->desc;
        $obj->status = $request->status;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("virtualcomunication.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_virtualcomunication)) {
            $delObj = new VirtualcomunicationModel();
            foreach ($request->selected_virtualcomunication as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("virtualcomunication.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-virtualcomunication");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        $user_id = \Auth::user()->id;
        DB::statement(DB::raw("set @rownum=" . (int) $sTart));
        $moderator_ids = VirtualcomunicationModel::where(
            "moderator",
            $user_id
        )->pluck("id");
        $member_ids = VirtualCommunicationMappingModel::where(
            "participants",
            $user_id
        )->pluck("virtual_comunication_list_id");
        $merged_ids = $moderator_ids->merge($member_ids)->unique();
        $ids = $merged_ids->toArray();
        $data = VirtualcomunicationModel::select(
            DB::raw("@rownum := @rownum + 1 AS rownum"),
            "virtual_comunication_list.id as id",
            "virtual_comunication_list.title as title",
            "users.name as moderator_name",
            "virtual_comunication_list.meeting_date as date",
            "virtual_comunication_list.time as time", // Assuming time is stored as a string
            "virtual_comunication_list.created_at",
            DB::raw('(CASE 
                WHEN virtual_comunication_list.status = "0" THEN "Disabled"
                WHEN virtual_comunication_list.status = "-1" THEN "Trashed"
                ELSE "Enabled" 
            END) AS status')
        )
            ->join(
                "users",
                "virtual_comunication_list.moderator",
                "=",
                "users.id"
            )
            ->with("user")
            ->whereIn("virtual_comunication_list.id", $ids) // Assuming there's a relation 'user' in VirtualcomunicationModel
            ->get();

        foreach ($data as $item) {
            // Parse the time string and format it to 12-hour with AM/PM
            $timeObj = DateTime::createFromFormat("H:i", $item->time);
            $formattedTime = $timeObj ? $timeObj->format("h:i A") : $item->time; // Use original string if parsing fails
            $item->formatted_time = $formattedTime;
        }

        $datatables = Datatables::of($data)
            ->addIndexColumn() // Automatically adds the DT_RowIndex column
            ->addColumn("check", function ($data) {
                return $data->rownum;
            })
            ->addColumn("date_created", function ($data) {
                return $data->created_at
                    ? Carbon::parse($data->created_at)->format("m/d/Y")
                    : "NA";
            })
            ->addColumn("participant", function ($data) {
                $participants = VirtualCommunicationMappingModel::where(
                    "virtual_comunication_list_id",
                    $data->id
                )->count();
                return $participants . " Participants";
            })
            ->addColumn("actdeact", function ($data) {
                $statusbtnvalue =
                    $data->status == "Enabled"
                        ? "<i class='glyphicon glyphicon-remove'></i>&nbsp;&nbsp;Disable"
                        : "<i class='glyphicon glyphicon-ok'></i>&nbsp;&nbsp;Enable";
                return '<a class="statusbutton btn btn-default" data-toggle="modal" data="' .
                    $data->id .
                    '" href="#">' .
                    $statusbtnvalue .
                    "</a>";
            })
            ->addColumn("action", function ($data) {
                // return view("layout::datatable.action", [
                //     "data" => $data,
                //     "route" => "homework",
                // ])->render();

                $subdate = Carbon::parse($data->date)->format("Y-m-d");
                $subtime = Carbon::parse($data->time)->format("H:i:s");
                $datetime = $subdate . " " . $subtime;

                // $datetime = "2023-05-03 13:25:00";

                $expiration = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    $datetime
                );

                // Convert to Carbon instance
                $examStart = Carbon::createFromFormat("Y-m-d H:i:s", $datetime);

                // Add 10 minutes
                if ($data->date !== null && $data->time !== null) {
                    $examEnd = $examStart->copy()->addHours(24);
                } else {
                    $examEnd = $examStart->copy()->addHours(24);
                }

                // Get current time
                $now = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    now(Configurations::getConfig("site")->time_zone)
                );

                if ($examEnd < $now) {
                    return view("layout::datatable.virtualcommunication", [
                        "data" => $data,
                        "type" => "expired",
                    ])->render();
                } else {
                    if ($now->between($examStart, $examEnd)) {
                        return view("layout::datatable.virtualcommunication", [
                            "data" => $data,
                            "type" => "join",
                        ])->render();
                    } else {
                        if ($expiration->isFuture()) {
                            $displayStart = $examStart->format(
                                'F j, Y \a\t g:i A'
                            );
                            return view(
                                "layout::datatable.virtualcommunication",
                                [
                                    "data" => $data,
                                    "type" => "pending",
                                ]
                            )->render();
                        }
                    }
                }
            })
            ->addColumn("formatted_time", function ($data) {
                return $data->formatted_time;
            });

        return $datatables->make(true);
    }

    public function getPTAData(Request $request)
    {
        CGate::authorize("view-virtualcomunication");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;
        $user_id = \Auth::user()->id;
        $student = StudentsModel::where("user_id", $user_id)->first();
        DB::statement(DB::raw("set @rownum=" . (int) $sTart));
        if ($student) {
            $parent_id = ParentModel::where("id", $student->parent_id)
                ->pluck("user_id")
                ->first();
            $meeting_ids = VirtualCommunicationMappingModel::where(
                "participants",
                $parent_id
            )->pluck("virtual_comunication_list_id");
            $pta_ids = VirtualcomunicationModel::whereIn("id", $meeting_ids)
                ->where([
                    "meeting_type" => 1,
                    "class" => $student->class_id,
                    "section" => $student->section_id,
                ])
                ->pluck("id");
        } else {
            $moderator_ids = VirtualcomunicationModel::where(
                "moderator",
                $user_id
            )->pluck("id");
            $member_ids = VirtualCommunicationMappingModel::where(
                "participants",
                $user_id
            )->pluck("virtual_comunication_list_id");
            $merged_ids = $moderator_ids->merge($member_ids)->unique();
            $ids = $merged_ids->toArray();
            $pta_ids = VirtualcomunicationModel::whereIn("id", $ids)
                ->where([
                    "meeting_type" => 1,
                ])
                ->pluck("id");
        }

        // $merged_ids = $moderator_ids->merge($member_ids)->unique();
        // $ids = $merged_ids->toArray();
        $data = VirtualcomunicationModel::select(
            DB::raw("@rownum := @rownum + 1 AS rownum"),
            "virtual_comunication_list.id as id",
            "virtual_comunication_list.title as title",
            "users.name as moderator_name",
            "virtual_comunication_list.meeting_date as date",
            "virtual_comunication_list.time as time", // Assuming time is stored as a string
            "virtual_comunication_list.created_at",
            DB::raw('(CASE 
                WHEN virtual_comunication_list.status = "0" THEN "Disabled"
                WHEN virtual_comunication_list.status = "-1" THEN "Trashed"
                ELSE "Enabled" 
            END) AS status')
        )
            ->join(
                "users",
                "virtual_comunication_list.moderator",
                "=",
                "users.id"
            )
            ->with("user")
            ->whereIn("virtual_comunication_list.id", $pta_ids) // Assuming there's a relation 'user' in VirtualcomunicationModel
            ->get();

        foreach ($data as $item) {
            // Parse the time string and format it to 12-hour with AM/PM
            $timeObj = DateTime::createFromFormat("H:i", $item->time);
            $formattedTime = $timeObj ? $timeObj->format("h:i A") : $item->time; // Use original string if parsing fails
            $item->formatted_time = $formattedTime;
        }

        $datatables = Datatables::of($data)
            ->addIndexColumn() // Automatically adds the DT_RowIndex column
            ->addColumn("check", function ($data) {
                return $data->rownum;
            })
            ->addColumn("date_created", function ($data) {
                return $data->created_at
                    ? Carbon::parse($data->created_at)->format("m/d/Y")
                    : "NA";
            })
            ->addColumn("participant", function ($data) {
                $participants = VirtualCommunicationMappingModel::where(
                    "virtual_comunication_list_id",
                    $data->id
                )->count();
                return $participants . " Participants";
            })
            ->addColumn("actdeact", function ($data) {
                $statusbtnvalue =
                    $data->status == "Enabled"
                        ? "<i class='glyphicon glyphicon-remove'></i>&nbsp;&nbsp;Disable"
                        : "<i class='glyphicon glyphicon-ok'></i>&nbsp;&nbsp;Enable";
                return '<a class="statusbutton btn btn-default" data-toggle="modal" data="' .
                    $data->id .
                    '" href="#">' .
                    $statusbtnvalue .
                    "</a>";
            })
            ->addColumn("action", function ($data) {
                // return view("layout::datatable.action", [
                //     "data" => $data,
                //     "route" => "homework",
                // ])->render();

                $subdate = Carbon::parse($data->date)->format("Y-m-d");
                $subtime = Carbon::parse($data->time)->format("H:i:s");
                $datetime = $subdate . " " . $subtime;

                // $datetime = "2023-05-03 13:25:00";

                $expiration = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    $datetime
                );

                // Convert to Carbon instance
                $examStart = Carbon::createFromFormat("Y-m-d H:i:s", $datetime);

                // Add 10 minutes
                if ($data->date !== null && $data->time !== null) {
                    $examEnd = $examStart->copy()->addHours(24);
                } else {
                    $examEnd = $examStart->copy()->addHours(24);
                }

                // Get current time
                $now = Carbon::createFromFormat(
                    "Y-m-d H:i:s",
                    now(Configurations::getConfig("site")->time_zone)
                );

                if ($examEnd < $now) {
                    return view("layout::datatable.virtualcommunication", [
                        "data" => $data,
                        "type" => "expired",
                    ])->render();
                } else {
                    if ($now->between($examStart, $examEnd)) {
                        return view("layout::datatable.virtualcommunication", [
                            "data" => $data,
                            "type" => "join",
                        ])->render();
                    } else {
                        if ($expiration->isFuture()) {
                            $displayStart = $examStart->format(
                                'F j, Y \a\t g:i A'
                            );
                            return view(
                                "layout::datatable.virtualcommunication",
                                [
                                    "data" => $data,
                                    "type" => "pending",
                                ]
                            )->render();
                        }
                    }
                }
            })
            ->addColumn("formatted_time", function ($data) {
                return $data->formatted_time;
            });

        return $datatables->make(true);
    }

    /*
     * country bulk action
     * eg : trash,enabled,disabled
     * delete is destroy function
     */
    function statusChange(Request $request)
    {
        CGate::authorize("edit-virtualcomunication");

        if (!empty($request->selected_virtualcomunication)) {
            $obj = new VirtualcomunicationModel();
            foreach ($request->selected_virtualcomunication as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function JoinMeeting(Request $request, $id)
    {
        // dd(\Auth::user()->student);
        $user_id = \Auth::user()->id;
        $user_grp = UserGroupMapModel::where("user_id", $user_id)
            ->pluck("group_id")
            ->first();
        $user_info = UserModel::where("id", $user_id)->first();
        $data = VirtualcomunicationModel::where("id", $id)->first();
        $meeting_map = VirtualCommunicationMappingModel::where(
            "virtual_comunication_list_id",
            $id
        )->pluck("participants");
        if ($data->meeting_type == 1) {
            $pta_groups = Configurations::PTAMEETINGGROUPS;
            $group_ids = $groups = UserGroupModel::whereIn("group", $pta_groups)
                ->where("status", 1)
                ->pluck("id");
            $groups = UserGroupModel::whereIn("group", $pta_groups)
                ->where("status", 1)
                ->pluck("group as text", "id");
            $user_ids = UserGroupMapModel::whereIn("group_id", $group_ids)
                ->whereNotIn("user_id", $meeting_map)
                ->pluck("user_id");
        } else {
            if ($user_grp == 4) {
                $groups = UserGroupModel::where("status", 1)
                    ->where("id", "=", 4)
                    ->pluck("group as text", "id");
            } else {
                $groups = UserGroupModel::where("status", 1)
                    ->where("id", "!=", 5)
                    ->pluck("group as text", "id");
                $groups->prepend("All", "all");
            }

            $user_ids = UserGroupMapModel::whereNotIn(
                "user_id",
                $meeting_map
            )->pluck("user_id");
        }

        $users = UserModel::select(
            DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text"),
            "id"
        )
            ->whereIn("id", $user_ids)
            ->pluck("text", "id");

        // dd($pta_groups, $meeting_map, $user_ids, $users);
        $participants = VirtualCommunicationMappingModel::with("user")
            ->where("virtual_comunication_list_id", $id)
            ->where("participants", "!=", $user_id)
            ->get();
        $class = LclassModel::where("status", "=", 1)->pluck(
            "name as text",
            "id"
        );

        // dd($user_info);
        return view("virtualcomunication::admin.join_meeting", [
            "groups" => $groups,
            "users" => $users,
            "data" => $data,
            "participants" => $participants,
            "user_info" => $user_info,
            "class" => $class,
        ]);
    }

    public function GetParticipants(Request $request)
    {
        $group_id = $request->query("group_id", 0);
        $section = $request->query("section", 0);
        $class_id = $request->query("class_id", 0);
        $meeting_type = $request->query("meeting_type", 0);
        $id = $request->query("meeting_id", 0);
        $type = $request->query("type");
        if ($type == "student") {
            $user_ids = StudentsModel::where("class_id", $class_id)
                ->where("status", 1)
                ->whereNull("deleted_at")
                ->where("section_id", $section)
                ->pluck("user_id");
            $users = UserModel::whereIn("id", $user_ids)
                ->select(
                    DB::raw(
                        "CONCAT(username, ' - ', name, ' - ', email) as text"
                    ),
                    "id"
                )
                ->whereNull("deleted_at")
                ->get();
            $allOption = ["id" => "all", "text" => "All"];
            $users->prepend($allOption);
            // dd($users,$$user_ids,group_id,$type);
            // dd($users);
            return response()->json([
                "users" => $users,
                "user_ids" => $user_ids,
                "group_id" => $group_id,
                "type" => $type,
                "student" => "yes",
            ]);
        } elseif ($type == "parent") {
            $parent_ids = StudentsModel::where("class_id", $class_id)
                ->where("section_id", $section)
                ->where("status", 1)
                ->whereNull("deleted_at")
                ->pluck("parent_id");
            $user_ids = ParentModel::whereIn("id", $parent_ids)->pluck(
                "user_id"
            );
            $users = UserModel::whereIn("id", $user_ids)
                ->select(
                    DB::raw(
                        "CONCAT(username, ' - ', name, ' - ', email) as text"
                    ),
                    "id"
                )
                ->whereNull("deleted_at")
                ->get();
            $allOption = ["id" => "all", "text" => "All"];
            $users->prepend($allOption);
            // dd($users,$$user_ids,group_id,$type);
            // dd($users);
            return response()->json([
                "users" => $users,
                "user_ids" => $user_ids,
                "group_id" => $group_id,
                "type" => $type,
                "student" => "yes",
            ]);
        } else {
            if ($group_id == "all") {
                if ($meeting_type == 1) {
                    $pta_groups = Configurations::PTAMEETINGGROUPS;
                    $group_ids = UserGroupModel::whereIn("group", $pta_groups)
                        ->where("status", 1)
                        ->pluck("id");
                    // dd($group_ids);
                    $user_ids = UserGroupMapModel::whereIn(
                        "group_id",
                        $group_ids
                    )->pluck("user_id");
                    // dd($group_ids, $user_ids);
                    $users = UserModel::whereIn("id", $user_ids)
                        ->select(
                            DB::raw(
                                "CONCAT(username, ' - ', name, ' - ', email) as text"
                            ),
                            "id"
                        )
                        ->whereNull("deleted_at")
                        ->get();
                } else {
                    $user_ids = UserGroupMapModel::pluck("user_id");
                    $users = UserModel::select(
                        DB::raw(
                            "CONCAT(username, ' - ', name, ' - ', email) as text"
                        ),
                        "id"
                    )
                        ->whereNull("deleted_at")
                        ->get();
                }
            } else {
                $meeting = VirtualcomunicationModel::where("id", $id)->first();
                if ($meeting) {
                    $meeting_map = VirtualCommunicationMappingModel::where(
                        "virtual_comunication_list_id",
                        $id
                    )->pluck("participants");
                    if ($group_id == 5) {
                        $parent_ids = StudentsModel::where(
                            "class_id",
                            $meeting->class
                        )
                            ->where("section_id", $meeting->section)
                            ->pluck("parent_id");
                        // dd($meeting, $parent_ids);
                        $user_ids = ParentModel::whereIn("id", $parent_ids)
                            ->whereNotIn("user_id", $meeting_map)
                            ->pluck("user_id");
                    } else {
                        $user_ids = UserGroupMapModel::where(
                            "group_id",
                            $group_id
                        )
                            ->whereNotIn("user_id", $meeting_map)
                            ->pluck("user_id");
                    }
                } else {
                    if ($group_id == 5) {
                        $parent_ids = StudentsModel::where(
                            "class_id",
                            $class_id
                        )
                            ->where("section_id", $section)
                            ->where("status", 1)
                            ->whereNull("deleted_at")
                            ->pluck("parent_id");
                        $user_ids = ParentModel::whereIn(
                            "id",
                            $parent_ids
                        )->pluck("user_id");
                    } else {
                        $user_ids = UserGroupMapModel::where(
                            "group_id",
                            $group_id
                        )->pluck("user_id");
                    }
                }

                $users = UserModel::whereIn("id", $user_ids)
                    ->select(
                        DB::raw(
                            "CONCAT(username, ' - ', name, ' - ', email) as text"
                        ),
                        "id"
                    )
                    ->whereNull("deleted_at")
                    ->get();
            }

            if (!$users->isEmpty()) {
                $allOption = ["id" => "all", "text" => "All"];
                $users->prepend($allOption);
            }

            return response()->json([
                "users" => $users,
                "user_ids" => $user_ids,
                "group_id" => $group_id,
                "type" => $type,
                "student" => "no",
            ]);
        }
    }

    public function GetParticipantGroups(Request $request)
    {
        $meeting_type = $request->query("meeting_type", 0);
        if ($meeting_type == 1) {
            $pta_groups = Configurations::PTAMEETINGGROUPS;
            $groups = UserGroupModel::whereIn("group", $pta_groups)
                ->where("status", 1)
                ->select("id", "group as text")
                ->get();
        } else {
            $groups = UserGroupModel::where("status", 1)
                ->where("id", "!=", 5)
                ->select("id", "group as text")
                ->get();
            $groups->prepend(["id" => "all", "text" => "All"]);
        }

        return response()->json(["groups" => $groups]);
    }

    public function AddParticipants(Request $request)
    {
        $user_id = \Auth::user()->id;
        $id = $request->query("id", 0);
        $group_id = $request->query("group_id", 0);
        $participants = $request->query("participants", 0);
        $meeting = VirtualcomunicationModel::where("id", $id)->first();
        $meeting_map = VirtualCommunicationMappingModel::where(
            "virtual_comunication_list_id",
            $id
        )->pluck("participants");
        if (in_array("all", (array) $participants)) {
            if ($group_id == "all") {
                if ($meeting_type == 1) {
                    $pta_groups = Configurations::PTAMEETINGGROUPS;
                    $group_ids = UserGroupModel::whereIn("group", $pta_groups)
                        ->where("status", 1)
                        ->pluck("id");
                    // dd($group_ids);
                    $user_ids = UserGroupMapModel::whereIn(
                        "group_id",
                        $group_ids
                    )->pluck("user_id");
                    // dd($group_ids, $user_ids);
                    $users = UserModel::whereIn("id", $user_ids)
                        ->select(
                            DB::raw(
                                "CONCAT(username, ' - ', name, ' - ', email) as text"
                            ),
                            "id"
                        )
                        ->get();
                } else {
                    $user_ids = UserGroupMapModel::whereNotIn(
                        "user_id",
                        $meeting_map
                    )
                        ->where("group_id", "!=", 5)
                        ->pluck("user_id");
                }
            } else {
                if ($group_id == "5") {
                    $parent_ids = StudentsModel::where(
                        "class_id",
                        $meeting->class
                    )
                        ->where("section_id", $meeting->section)
                        ->pluck("parent_id");
                    $user_ids = ParentModel::whereIn("id", $parent_ids)
                        ->whereNotIn("user_id", $meeting_map)
                        ->pluck("user_id");
                } else {
                    $user_ids = UserGroupMapModel::where("group_id", $group_id)
                        ->whereNotIn("user_id", $meeting_map)
                        ->pluck("user_id");
                }
            }

            foreach ($user_ids as $participant) {
                $add = new VirtualCommunicationMappingModel();
                $add->virtual_comunication_list_id = $id;
                $add->participants = $participant;
                $add->save();
            }
        } else {
            foreach ($participants as $participant) {
                $add = new VirtualCommunicationMappingModel();
                $add->virtual_comunication_list_id = $id;
                $add->participants = $participant;
                $add->save();
            }
        }
        $participants_map = VirtualCommunicationMappingModel::with("user")
            ->where("virtual_comunication_list_id", $id)
            ->whereIn("participants", $participants)
            ->get();
        $view = view("virtualcomunication::admin.participantslist", [
            "participants" => $participants_map,
        ])->render();
        return response()->json([
            "id" => $id,
            "participants" => $participants_map,
            "view" => $view,
        ]);
    }

    public function Sections(Request $request)
    {
        $class_id = $request->query("class_id", 0);

        $sections = SectionModel::where("class_id", $class_id)
            ->where("status", "=", 1)
            ->select("name as text", "id")
            ->get();
        return response()->json(["sections" => $sections]);
    }
}
