<?php

namespace App\Http\Controllers;

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

class VirtualComunicationAPIController extends Controller
{
    public function VirtualMeeting($type = null)
    {
        $user_id = \Auth::user()->id;
        $user_group_id = UserGroupMapModel::where("user_id", $user_id)
            ->pluck("group_id")
            ->first();
        $allowed_group = Configurations::ALLOWEDGROUPS;
        if (array_key_exists($user_group_id, $allowed_group)) {
            $is_allowed = "yes";
        } else {
            $is_allowed = "no";
        }

        $moderator_ids = VirtualcomunicationModel::where(
            "moderator",
            $user_id
        )->pluck("id");
        $member_ids = VirtualCommunicationMappingModel::where(
            "participants",
            $user_id
        )->pluck("virtual_comunication_list_id");
        $merged_ids = $moderator_ids->merge($member_ids)->unique();
        if ($type == "pta") {
            $student = StudentsModel::where("user_id", $user_id)->first();
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
                // dd($parent_id, $meeting_ids);
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
            $meeting_list = VirtualcomunicationModel::with("user")
                ->whereIn("id", $pta_ids)
                ->where("meeting_type", 1)
                ->get();
        } else {
            $meeting_list = VirtualcomunicationModel::with("user")
                ->whereIn("id", $merged_ids)
                ->where("meeting_type", 0)
                ->get();
        }

        $meetings = [];
        // dd($merged_ids);
        foreach ($meeting_list as $list) {
            $participants_list = VirtualCommunicationMappingModel::with("user")
                ->where("participants", "!=", $user_id)
                ->where("virtual_comunication_list_id", $list->id)
                ->get();
            $participants = [];
            // dd($participants_list);
            foreach ($participants_list as $plist) {
                if ($plist->user) {
                    $participants[] = [
                        "id" => $plist->participants,
                        "name" => $plist->user->name,
                        "image" => $plist->user->images,
                    ];
                }
            }
            // if ($participants_list) {
            //     foreach ($participants_list as $plist) {
            //         if ($plist->user) {
            //             $participants[] = [
            //                 "id" => $plist->participants,
            //                 "name" => $plist->user->name,
            //                 "image" => $plist->user->images,
            //             ];
            //         } else {
            //             $participants[] = [
            //                 "id" => $plist->participants,
            //                 "name" => "NA",
            //                 "image" => "NA",
            //             ];
            //         }
            //     }
            // }
            $timeObj = DateTime::createFromFormat("H:i", $list->time);
            $formattedTime = $timeObj ? $timeObj->format("h:i A") : $list->time;

            $formattedDate = Carbon::parse($list->meeting_date)->format(
                "d/m/Y"
            );
            $meetings[] = [
                "meeting_id" => $list->id,
                "meeting_title" => $list->title,
                "meeting_token" => $list->meeting_token,
                "meeting_description" => $list->description,
                "moderator" => $list->moderator,
                "moderator_name" => $list->user->name,
                "moderator_image" => $list->user->images,
                "meeting_date" => $formattedDate,
                "time" => $formattedTime,
                "participants" => $participants,
            ];
        }

        return response()->json([
            "meeting_list" => $meetings,
            "is_allowed" => $is_allowed,
        ]);
    }

    public function Create($type = null)
    {
        $user_id = \Auth::user()->id;
        if ($type == "pta") {
            $pta_groups = Configurations::PTAMEETINGGROUPS;
            $user_ids = UserGroupModel::whereIn("group", $pta_groups)
                ->where("status", 1)
                ->pluck("id");
            $groups = UserGroupModel::whereIn("group", $pta_groups)
                ->where("status", 1)
                ->select("id", "group as text")
                ->get();
            // $allOption = ["id" => "all", "text" => "All"];
            // $groups->prepend($allOption);
            $users = UserModel::whereIn("id", $user_ids)
                ->select(
                    "id",
                    DB::raw(
                        "CONCAT(username, ' - ', name, ' - ', email) as text"
                    )
                )
                ->whereNull("deleted_at")
                ->get();
            // $users->prepend($allOption);
        } else {
            $groups = UserGroupModel::select("id", "group as text")->get();
            $allOption = ["id" => "all", "text" => "All"];
            $groups->prepend($allOption);
            $users = UserModel::select(
                "id",
                DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text")
            )
                ->whereNull("deleted_at")
                ->get();
            $users->prepend($allOption);
        }
        $class = LclassModel::where("status", "=", 1)
            ->select("id", "name as text")
            ->get();
        // $meeting_types = Configurations::MEETINGTYPES;
        return response()->json([
            "users" => $users,
            "groups" => $groups,
            "class" => $class,
            // "meeting_types" => $meeting_types,
        ]);
    }

    public function Store(Request $request)
    {
        try {
            $user_id = \Auth::user()->id;

            $obj = new VirtualcomunicationModel();
            $obj->title = $request->meeting_title;
            $obj->moderator = $user_id;
            $obj->meeting_token = $request->meeting_token;
            $obj->meeting_date = $request->meeting_date;
            $obj->time = $request->meet_time;
            $obj->description = $request->meeting_description;
            $obj->meeting_type = $request->meeting_type ?? 0;

            if ($obj->save()) {
                if (in_array("all", $request->participants)) {
                    // dd("all");
                    $group_id = $request->participants_group;
                    if ($group_id == "all") {
                        $meeting_type = $request->meeting_type ?? 0;
                        if ($meeting_type == 1) {
                            $pta_groups = Configurations::PTAMEETINGGROUPS;
                            $group_ids = UserGroupModel::whereIn(
                                "group",
                                $pta_groups
                            )
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
                            $users = UserModel::select(
                                DB::raw(
                                    "CONCAT(username, ' - ', name, ' - ', email) as text"
                                ),
                                "id"
                            )->get();
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
                        $parent_ids = StudentsModel::where(
                            "class_id",
                            $request->class_id
                        )
                            ->where("section_id", $request->section)
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
                    // dd($user_ids);
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
                    if (!in_array($user_id, (array) $request->participants)) {
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

            return response()->json([
                "status" => "Virutal Meeting created successfully",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function Join(Request $request)
    {
        $id = $request->meeting_id;
        $user_id = \Auth::user()->id;
        $user_info = UserModel::where("id", $user_id)->first();

        // Fetch the meeting list
        $meeting_list = VirtualcomunicationModel::with("user")
            ->where("id", $id)
            ->first();
        if (!$meeting_list) {
            return response()->json(["error" => "Meeting not found"], 404);
        }

        // Fetch participants list
        $participants_list = VirtualCommunicationMappingModel::with("user")
            ->where("virtual_comunication_list_id", $id)
            ->where("participants", "!=", $user_id)
            ->get();

        // Format meeting information
        $timeObj = DateTime::createFromFormat("H:i", $meeting_list->time);
        $formattedTime = $timeObj
            ? $timeObj->format("h:i A")
            : $meeting_list->time;

        $dateObject = DateTime::createFromFormat(
            "d/m/Y",
            $meeting_list->meeting_date
        );
        $formattedDate = $dateObject
            ? $dateObject->format("m/d/Y")
            : $meeting_list->meeting_date;

        $meetings = [
            "meeting_id" => $meeting_list->id,
            "meeting_title" => $meeting_list->title,
            "meeting_token" => $meeting_list->meeting_token,
            "moderator" => $meeting_list->moderator,
            "moderator_name" => $meeting_list->user->name,
            "moderator_image" => $meeting_list->user->images,
            "meeting_date" => $formattedDate,
            "time" => $formattedTime,
        ];

        // Format participants information
        $participants = [];
        foreach ($participants_list as $plist) {
            if ($plist->user) {
                $participants[] = [
                    "id" => $plist->participants,
                    "name" => $plist->user->name,
                    "image" => $plist->user->images,
                ];
            }
        }

        return response()->json([
            "meeting_info" => $meetings,
            "participants" => $participants,
            "user_info" => $user_info,
        ]);
    }

    public function AddParticipantsModel(Request $request, $meeting_type = null)
    {
        if ($meeting_type == 1) {
            $pta_groups = Configurations::PTAMEETINGGROUPS;
            $group_ids = UserGroupModel::whereIn("group", $pta_groups)
                ->where("status", 1)
                ->pluck("id");
            $groups = UserGroupModel::whereIn("group", $pta_groups)
                ->select("id", "group as text")
                ->get();
            $user_ids = UserGroupMapModel::whereIn(
                "group_id",
                $group_ids
            )->pluck("user_id");

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
            $groups = UserGroupModel::where("id", "!=", 5)
                ->select("id", "group as text")
                ->get();
            $users = UserModel::select(
                "id",
                DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text")
            )
                ->whereNull("deleted_at")
                ->get();
        }
        $allOption = ["id" => "all", "text" => "All"];
        if (!$meeting_type) {
            $groups->prepend($allOption);
        }
        $users->prepend($allOption);
        return response()->json(["users" => $users, "groups" => $groups]);
    }

    public function StoreParticipants(Request $request)
    {
        $user_id = \Auth::user()->id;
        $meeting_id = $request->meeting_id;
        $group_id = $request->group_id;
        $class_id = $request->class_id;
        $section_id = $request->section_id;
        $participants = $request->participants;
        $meeting_type = $request->meeting_type ?? 0;
        $meeting = VirtualcomunicationModel::where("id", $meeting_id)->first();
        $meeting_map = VirtualCommunicationMappingModel::where(
            "virtual_comunication_list_id",
            $meeting_id
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
            } elseif ($class_id && $section_id) {
                if ($group_id == "5") {
                    $parent_ids = StudentsModel::where("class_id", $class_id)
                        ->where("section_id", $section_id)
                        ->where("status", 1)
                        ->whereNull("deleted_at")
                        ->pluck("parent_id");

                    $user_ids = ParentModel::whereIn("id", $parent_ids)->pluck(
                        "user_id"
                    );
                    // $up_obj = VirtualcomunicationModel::find($obj->id);
                    // $up_obj->class = $class_id;
                    // $up_obj->section = $section_id;
                    // $up_obj->save();
                } else {
                    $user_ids = StudentsModel::where("class_id", $class_id)
                        ->where("section_id", $section_id)
                        ->whereNotIn("user_id", $meeting_map)
                        ->pluck("user_id");
                }
            } else {
                $user_ids = UserGroupMapModel::whereNotIn(
                    "user_id",
                    $meeting_map
                )
                    ->where("group_id", "=", $group_id)
                    ->pluck("user_id");
            }

            foreach ($user_ids as $participant) {
                $add = new VirtualCommunicationMappingModel();
                $add->virtual_comunication_list_id = $meeting_id;
                $add->participants = $participant;
                $add->save();
            }
        } else {
            foreach ($participants as $participant) {
                $add = new VirtualCommunicationMappingModel();
                $add->virtual_comunication_list_id = $meeting_id;
                $add->participants = $participant;
                $add->save();
            }
        }

        // $participants_map = VirtualCommunicationMappingModel::with("user")
        //     ->where("virtual_comunication_list_id", $id)
        //     ->whereIn("participants", $participants)
        //     ->get();

        return response()->json([
            "status" => "Participants added Successfully",
        ]);
    }

    public function GetParticipants(Request $request)
    {
        $group_id = $request->group_id;
        $class_id = $request->class_id;
        $section_id = $request->section_id;
        $meeting_type = $request->meeting_type ?? 0;
        if ($group_id == "all") {
            if ($meeting_type == 1) {
                $pta_groups = Configurations::PTAMEETINGGROUPS;
                $group_ids = UserGroupModel::whereIn("group", $pta_groups)
                    ->where("status", 1)
                    ->pluck("id");
                $user_ids = UserGroupMapModel::whereIn(
                    "group_id",
                    $group_ids
                )->pluck("user_id");
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
                $user_ids = UserGroupMapModel::where(
                    "group_id",
                    "!=",
                    5
                )->pluck("user_id");
            }
        } elseif ($class_id && $section_id) {
            if ($meeting_type == 1) {
                $parent_ids = StudentsModel::where("class_id", $class_id)
                    ->where("section_id", $section_id)
                    ->where("status", 1)
                    ->whereNull("deleted_at")
                    ->pluck("parent_id");
                $user_ids = ParentModel::whereIn("id", $parent_ids)->pluck(
                    "user_id"
                );
            } else {
                $user_ids = StudentsModel::where("class_id", $class_id)
                    ->where("section_id", $section_id)
                    ->where("status", 1)
                    ->whereNull("deleted_at")
                    ->pluck("user_id");
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
        } else {
            $user_ids = UserGroupMapModel::where("group_id", $group_id)->pluck(
                "user_id"
            );
        }

        $users = UserModel::select(
            "id",
            DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text")
        )
            ->whereIn("id", $user_ids)
            ->whereNull("deleted_at")
            ->get();
        $allOption = ["id" => "all", "text" => "All"];
        $users->prepend($allOption);
        return response()->json(["participants" => $users]);
    }

    public function Section(Request $request)
    {
        $class_id = $request->class_id;

        $sections = SectionModel::where("class_id", $class_id)
            ->select("id", "name")
            ->get();

        return response()->json(["sections" => $sections]);
    }
}
