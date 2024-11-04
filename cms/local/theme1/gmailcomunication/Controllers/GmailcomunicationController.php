<?php

namespace cms\gmailcomunication\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\gmailcomunication\Models\GmailcomunicationModel;
use cms\core\configurations\Traits\FileUploadTrait;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Pagination\LengthAwarePaginator;
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\gmailcomunication\Models\GmailGroupModel;
use cms\gmailcomunication\Models\GmailGroupReceptiantsModel;
use cms\gmailcomunication\Models\GmailGroupMessages;
use cms\gmailcomunication\Models\GmailIndividualMessages;
use cms\gmailcomunication\Models\GmailIndividualMessageMappingModel;
use cms\gmailcomunication\Models\StarredGmailMessagesModel;
use cms\gmailcomunication\Models\GmaliIndividualMessageReceiversModel;
use cms\gmailcomunication\Models\DeletedMessagesModel;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use cms\core\layout\helpers\CmsMail;
use Mail;
use App\Mail\ExternalMail;
use Carbon\Carbon;
use Session;
use DB;
use CGate;
use DateTime;
use Configurations;
class GmailcomunicationController extends Controller
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
    public function index(Request $request)
    {
        $user_id = \Auth::user()->id;

        $users_groups = GmailGroupReceptiantsModel::where(
            "user_id",
            $user_id
        )->pluck("gmail_group_id");
        $groups = GmailGroupModel::whereIn("id", $users_groups)->get();

        $group_ids = $groups->pluck("id");
        $group_receptiants = GmailGroupReceptiantsModel::with("username")
            ->whereIn("gmail_group_id", $group_ids)
            ->get();
        $gmail_group_messages = GmailGroupMessages::with("username")
            ->select("*")
            ->get();
        // dd($gmail_group_messages);

        $users = UserModel::select("id", "name", "images", "email")->get();

        $eligible = Configurations::GetGmailGroupEligibleRoles();

        $current_user_role = UserGroupMapModel::where("user_id", $user_id)
            ->pluck("group_id")
            ->first();

        $eligible_role = in_array($current_user_role, $eligible) ? "yes" : "no";
        // dd($eligible_role);

        //user's eligible message receptiants
        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_config_ids = [];
        foreach ($group_config as $key => $data) {
            $group_config_ids[] = $data->id;
        }
        $eligibele_receptiants_ids = UserGroupMapModel::whereIn(
            "group_id",
            $group_config_ids
        )->pluck("user_id");

        $eligibele_receptiants_group = UserGroupModel::whereIn(
            "id",
            $group_config_ids
        )->pluck("group as text", "id");

        $eligibele_receptiants_group->prepend("All", "all");
        // dd($user_id);
        $filtered_ids = $eligibele_receptiants_ids->diff($user_id)->all();
        // dd($filtered_ids);
        $eligibele_receptiants = UserModel::whereIn("id", $filtered_ids)
            ->select(
                DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text"),
                "id"
            )
            ->pluck("text", "id");

        //user's inbox messages
        $user_inbox = GmaliIndividualMessageReceiversModel::where(
            "user_id",
            $user_id
        )->pluck("message_id");
        $deleted_ids = DeletedMessagesModel::where("user_id", $user_id)->pluck(
            "message_id"
        );
        $perPage = 6;
        $inbox_messages = GmailIndividualMessages::with("senter")
            ->where("external_message", "!=", "1")
            ->whereIn("id", $user_inbox)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->paginate($perPage);

        $inbox_messages_view = GmailIndividualMessages::with("senter")
            ->whereIn("id", $user_inbox)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->get();
        $inbox_messages_count = $inbox_messages->count();
        $inbox_ids = $inbox_messages_view->pluck("id");
        $senter_ids = $inbox_messages_view->pluck("from_id");
        //dd($senter_ids);
        $senter_roles = UserGroupMapModel::with("usergroup")
            ->whereIn("user_id", $senter_ids)
            ->get();
        // $inbox_messages_maping = GmailIndividualMessageMappingModel::with("senter","reciver")->whereIn('message_id',$inbox_ids)->where('reciever',$user_id)->get();
        $inbox_messages_maping = GmailIndividualMessageMappingModel::with(
            "senter_info",
            "reciver"
        )
            ->whereIn("message_id", $inbox_ids)
            ->get();
        //dd($inbox_messages_maping);
        if ($inbox_messages_maping !== null) {
            $inbox_messages_maping->each(function ($message_map) {
                $date = $message_map->created_at;
                $dateTime = Carbon::parse($date);
                $year = $dateTime->year;
                $currentYear = Carbon::now()->year;
                $isCurrentDay = $dateTime->isToday();

                if ($isCurrentDay) {
                    $formattedDate = $message_map->time; // Assuming $message->time is a formatted time string
                } elseif ($year == $currentYear) {
                    $formattedDate = $dateTime->format("F d");
                } else {
                    $formattedDate = $dateTime->format("F d, Y");
                }

                $message_map->formatted_date = $formattedDate;
            });
        }

        $InboxPaginated = null;
        // if(!empty($inbox_messages)){
        //     $inbox_messages_array = $inbox_messages->toArray();
        //     $perPage = 6;
        //     $page = request()->input('page', 1);
        //     $currentPageItems = array_slice( $inbox_messages_array, ($page - 1) * $perPage, $perPage);
        //     $InboxPaginated = new LengthAwarePaginator(
        //         $currentPageItems,
        //         count( $inbox_messages_array),
        //         $perPage,
        //         $page,
        //         [
        //             'path' => request()->url(),
        //             'query' => request()->query(),
        //         ]
        //     );
        // }

        //sentmessages list

        $sent_messages = GmailIndividualMessages::with("reciver", "senter")
            ->where("external_message", "!=", "1")
            ->where("from_id", $user_id)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->paginate($perPage);
        $sent_messages_list = GmailIndividualMessages::with("reciver", "senter")
            ->where("external_message", "!=", "1")
            ->where("from_id", $user_id)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->get();
        $sent_ids = $sent_messages->pluck("id");
        $sent_messages_count = $sent_messages->count();
        $reciver_det = GmaliIndividualMessageReceiversModel::whereIn(
            "message_id",
            $sent_ids
        )->get();
        $reciver_ids = GmaliIndividualMessageReceiversModel::whereIn(
            "message_id",
            $sent_ids
        )->pluck("user_id");
        //dd($reciver_ids);
        $reciver_roles = UserGroupMapModel::with("usergroup")
            ->whereIn("user_id", $reciver_ids)
            ->get();
        //dd( $reciver_roles);
        $sent_messages_maping = GmailIndividualMessageMappingModel::with(
            "senter_info",
            "reciver"
        )
            ->whereIn("message_id", $sent_ids)
            ->groupBy("senter", "message_id", "created_at")
            ->get();
        // dd( $inbox_messages_maping);
        if ($sent_messages_maping !== null) {
            $sent_messages_maping->each(function ($message_map) {
                $date = $message_map->created_at;
                $dateTime = Carbon::parse($date);
                $year = $dateTime->year;
                $currentYear = Carbon::now()->year;
                $isCurrentDay = $dateTime->isToday();

                if ($isCurrentDay) {
                    $formattedDate = $message_map->time; // Assuming $message->time is a formatted time string
                } elseif ($year == $currentYear) {
                    $formattedDate = $dateTime->format("F d");
                } else {
                    $formattedDate = $dateTime->format("F d, Y");
                }

                $message_map->formatted_date = $formattedDate;
            });
        }

        $sentPaginated = null;

        //starred messages
        $starred_ids = StarredGmailMessagesModel::where(
            "user_id",
            $user_id
        )->pluck("message_id");
        $starred_messages = GmailIndividualMessages::with("reciver", "senter")
            ->where("external_message", "!=", "1")
            ->whereIn("id", $starred_ids)
            ->whereNotIn("id", $deleted_ids)
            ->where("is_draft", "!=", "1")
            ->orderBy("id", "desc")
            ->paginate($perPage);
        //   dd($user_id,$starred_ids,$starred_messages_list);
        $starred_senter_ids = $starred_messages->pluck("from_id");
        //dd($senter_ids);
        $starred_senter_roles = UserGroupMapModel::with("usergroup")
            ->whereIn("user_id", $starred_senter_ids)
            ->get();
        $starred_messages_count = $starred_messages->count();
        $starredPaginated = null;

        //draft messages
        $draft_messages = GmailIndividualMessages::with("reciver", "senter")
            ->where("external_message", "!=", "1")
            ->where("from_id", $user_id)
            ->where("is_draft", "=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->paginate($perPage);

        $draft_messages_list = GmailIndividualMessages::with(
            "reciver",
            "senter"
        )
            ->where("external_message", "!=", "1")
            ->where("from_id", $user_id)
            ->where("is_draft", "=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->get();

        $draft_ids = $draft_messages->pluck("id");
        $draft_messages_count = $draft_messages->count();
        $draft_reciver_details = GmaliIndividualMessageReceiversModel::whereIn(
            "message_id",
            $draft_ids
        )->get();
        $draft_reciver_ids = $draft_reciver_details->pluck("user_id");
        //dd($reciver_ids);
        $draft_reciver_roles = UserGroupMapModel::with("usergroup")
            ->whereIn("user_id", $draft_reciver_ids)
            ->get();
        //dd( $reciver_roles);
        $draft_messages_maping = GmailIndividualMessageMappingModel::with(
            "senter",
            "reciver"
        )
            ->whereIn("message_id", $draft_ids)
            ->get();
        // dd( $senter_ids);
        $draftPaginated = null;
        if (!empty($draft_messages)) {
            $draft_messages_array = $draft_messages->toArray();
            $perPage = 6;
            $page = request()->input("page", 1);
            $currentPageItems = array_slice(
                $draft_messages_array,
                ($page - 1) * $perPage,
                $perPage
            );
            $draftPaginated = new LengthAwarePaginator(
                $currentPageItems,
                count($draft_messages_array),
                $perPage,
                $page,
                [
                    "path" => request()->url(),
                    "query" => request()->query(),
                ]
            );
        }

        $bin_messages = GmailIndividualMessages::with("senter", "reciver")
            ->where("external_message", "!=", "1")
            ->where("is_draft", "!=", "1")
            ->whereIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->paginate($perPage);
        $bin_messages_count = $bin_messages->count();
        $bin_ids = $bin_messages->pluck("id");
        $senter_ids = $bin_messages->pluck("from_id");
        //dd($senter_ids);
        $bin_messages_maping = GmailIndividualMessageMappingModel::with(
            "senter",
            "reciver"
        )
            ->whereIn("message_id", $inbox_ids)
            ->where("reciever", $user_id)
            ->get();
        // dd($senter_roles);
        $binPaginated = null;

        //   dd($sent_messages_maping);

        //external_sent messages
        $external_sent_messages = GmailIndividualMessages::with(
            "reciver",
            "senter"
        )
            ->where("external_message", "=", "1")
            ->where("from_id", $user_id)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->paginate(6);
        // dd( $external_sent_messages);
        $external_sent_ids = $external_sent_messages->pluck("id");
        $external_sent_messages_count = $external_sent_messages->count();
        $external_reciver_det = GmaliIndividualMessageReceiversModel::whereIn(
            "message_id",
            $external_sent_ids
        )->get();
        $external_reciver_ids = GmaliIndividualMessageReceiversModel::whereIn(
            "message_id",
            $external_sent_ids
        )->pluck("user_id");
        //dd($reciver_ids);
        $external_reciver_roles = UserGroupMapModel::with("usergroup")
            ->whereIn("user_id", $external_reciver_ids)
            ->get();
        //dd( $reciver_roles);
        // dd( $senter_ids);

        if (request()->ajax()) {
            $type = request()->input("type");
            if ($type == "sent") {
                return view(
                    "gmailcomunication::admin.messages.sent_messages",
                    compact("sent_messages")
                )->render();
            } elseif ($type == "inbox") {
                return view(
                    "gmailcomunication::admin.messages.inbox_messages",
                    compact("inbox_messages", "senter_roles", "starred_ids")
                )->render();
            } elseif ($type == "starred") {
                return view(
                    "gmailcomunication::admin.messages.starred_messages",
                    compact("starred_messages", "starred_senter_roles")
                )->render();
            } elseif ($type == "draft") {
                return view(
                    "gmailcomunication::admin.messages.draft_messages",
                    compact("draft_messages")
                )->render();
            } elseif ($type == "bin") {
                return view(
                    "gmailcomunication::admin.messages.bin_messages",
                    compact("bin_messages")
                )->render();
            } else {
                return view(
                    "gmailcomunication::admin.messages.external_sent_messages",
                    compact("external_sent_messages")
                )->render();
            }
        }
        return view("gmailcomunication::admin.index", [
            "groups" => $groups,
            "group_receptiants" => $group_receptiants,
            "gmail_group_messages" => $gmail_group_messages,
            "eligibele_receptiants_group" => $eligibele_receptiants_group,
            "user_id" => $user_id,
            "users" => $users,
            "eligible_role" => $eligible_role,
            "eligibele_receptiants" => $eligibele_receptiants,
            "inbox_messages" => $inbox_messages,
            "senter_roles" => $senter_roles,
            "sent_messages" => $sent_messages,
            "sent_messages_list" => $sent_messages_list,
            "reciver_roles" => $reciver_roles,
            "sent_messages_count" => $sent_messages_count,
            "reciver_ids" => $reciver_ids,
            "reciver_details" => $reciver_det,
            "inbox_messages_count" => $inbox_messages_count,
            "inbox_messages_maping" => $inbox_messages_maping,
            "InboxPaginated" => $InboxPaginated,
            "sentPaginated" => $sentPaginated,
            "starred_messages" => $starred_messages,
            "starred_senter_roles" => $starred_senter_roles,
            "starredPaginated" => $starredPaginated,
            "starred_messages_count" => $starred_messages_count,
            "starred_ids" => $starred_ids,
            "sent_messages_maping" => $sent_messages_maping,
            "draft_messages" => $draft_messages,
            "draft_messages_list" => $draft_messages_list,
            "draft_reciver_roles" => $draft_reciver_roles,
            "draft_messages_count" => $draft_messages_count,
            "draft_reciver_ids" => $draft_reciver_ids,
            "draft_reciver_details" => $draft_reciver_details,
            "draft_messages_maping" => $draft_messages_maping,
            "draftPaginated" => $draftPaginated,
            "bin_messages" => $bin_messages,
            "binPaginated" => $binPaginated,
            "bin_messages_count" => $bin_messages_count,
            "bin_messages_maping" => $bin_messages_maping,
            "inbox_messages_view" => $inbox_messages_view,
            "external_sent_messages" => $external_sent_messages,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        return view("gmailcomunication::admin.edit", ["layout" => "create"]);
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
            "name" =>
                "required|min:3|max:50|unique:" .
                (new GmailcomunicationModel())->getTable() .
                ",name",
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);
        $obj = new GmailcomunicationModel();
        $obj->name = $request->name;
        $obj->desc = $request->desc;
        $obj->status = $request->status;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("gmailcomunication.index");
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
        $data = GmailcomunicationModel::find($id);
        return view("gmailcomunication::admin.edit", [
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
                (new GmailcomunicationModel())->getTable() .
                ",name," .
                $id,
            "desc" => "required|min:3|max:190",
            "status" => "required",
        ]);
        $obj = GmailcomunicationModel::find($id);
        $obj->name = $request->name;
        $obj->desc = $request->desc;
        $obj->status = $request->status;
        $obj->save();

        Session::flash("success", "saved successfully");
        return redirect()->route("gmailcomunication.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_gmailcomunication)) {
            $delObj = new GmailcomunicationModel();
            foreach ($request->selected_gmailcomunication as $k => $v) {
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        Session::flash("success", "data Deleted Successfully!!");
        return redirect()->route("gmailcomunication.index");
    }
    /*
     * get data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-gmailcomunication");
        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . (int) $sTart));

        $data = GmailcomunicationModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "id",
            "name",
            "desc",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new GmailcomunicationModel())->getTable() .
                    '.status = "0" THEN "Disabled"
            WHEN ' .
                    DB::getTablePrefix() .
                    (new GmailcomunicationModel())->getTable() .
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
                    route("gmailcomunication.edit", $data->id) .
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
        CGate::authorize("edit-gmailcomunication");

        if (!empty($request->selected_gmailcomunication)) {
            $obj = new GmailcomunicationModel();
            foreach ($request->selected_gmailcomunication as $k => $v) {
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "Status changed Successfully!!");
        return redirect()->back();
    }

    public function CreateGroupModel(Request $request)
    {
        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_types = $group_config->pluck("group", "id");
        $group_ids = $group_config->pluck("id");

        $user_id = UserGroupMapModel::whereIn("group_id", $group_ids)->pluck(
            "user_id"
        );

        $group_recipients = UserModel::whereIn("id", $user_id)->pluck(
            "name",
            "id"
        );
        $group_types->prepend("All", "all");
        $group_recipients->prepend("All", "all");
        $view = view("gmailcomunication::admin.creategroupmodel", [
            "layout" => "create",
            "group_types" => $group_types,
            "group_recipients" => $group_recipients,
        ])->render();

        return response()->json(["view" => $view]);
    }

    public function CreateGroup(Request $request)
    {
        // dd($request->all());
        $this->validate(
            $request,
            [
                "group_image" => "required",
                "group_image" => "mimes:jpg,jpeg,png|max:2048",
                "group_title" => "required|unique:gmail_group,title",
                "recipient" => "required|array|min:1",
                "group_type" => "required",
            ],
            [
                "recipient.required" =>
                    "You must select at least one recipient.",
                "recipient.min" => "You must select at least one recipient.",
                "group_type.required" =>
                    "You must select at least one group type.",
                "group_type.min" => "You must select at least one group type.",
                "group_image.required" => "You must select a group image.",
                "group_image.mimes" =>
                    "The group image must be a file of type: jpg, jpeg, png.",
                "group_image.max" =>
                    "The group image may not be greater than 4000 kilobytes in size.",
                "group_title.unique" =>
                    "This Group title was already selected.Please enter unique one.",
            ]
        );

        try {
            DB::beginTransaction();
            $user_id = $request->user()->id;
            $type = $request->group_type == "all" ? 0 : $request->group_type;
            $data = new GmailGroupModel();

            $data->title = $request->group_title;
            $data->descripition = $request->group_description;
            if ($request->group_image) {
                $group_image = $this->GmailGroupImage(
                    $request->group_image,
                    "image"
                );
                $data->image = $group_image;
            }

            $data->type = $type;
            $data->creater = $user_id;
            $data->save();

            if ($data->save()) {
                // dd($request->recipient);
                if (!in_array($user_id, $request->recipient)) {
                    $groupreceptiants = new GmailGroupReceptiantsModel();
                    $groupreceptiants->gmail_group_id = $data->id;
                    $groupreceptiants->user_id = $user_id;
                    $groupreceptiants->save();
                }
                if (in_array("all", $request->recipient)) {
                    $group_type = $request->group_type;

                    if ($group_type == "all") {
                        $user_ids = UserGroupMapModel::pluck("user_id");
                    } else {
                        $user_ids = UserGroupMapModel::whereIn(
                            "group_id",
                            (array) $group_type
                        )->pluck("user_id");
                    }

                    if ($user_ids) {
                        foreach ($user_ids as $key => $id) {
                            $groupreceptiants = new GmailGroupReceptiantsModel();
                            $groupreceptiants->gmail_group_id = $data->id;
                            $groupreceptiants->user_id = $id;
                            $groupreceptiants->save();
                        }

                        //    dd("its end");
                    }
                } else {
                    foreach ($request->recipient as $key => $recipient) {
                        $groupreceptiants = new GmailGroupReceptiantsModel();
                        $groupreceptiants->gmail_group_id = $data->id;
                        $groupreceptiants->user_id = $recipient;
                        $groupreceptiants->save();
                    }
                }

                $msg = "Group Created successfully";
                $class_name = "success";
            } else {
                $msg = "Something went wrong !! ";
                $class_name = "error";
            }
            DB::commit();
            Session::flash($class_name, $msg);
            return redirect(route("gmailcomunication.index"));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function GetReceptiants(Request $request)
    {
        $group_type = $request->query("group_type", 0);
        if ($group_type == "all") {
            $user_id = UserGroupMapModel::pluck("user_id");
        } else {
            $user_id = UserGroupMapModel::where("group_id", $group_type)->pluck(
                "user_id"
            );
        }

        $group_recipients = UserModel::whereIn("id", $user_id)
            ->select("id", "name as text")
            ->get();
        $group_recipients->prepend(["id" => "all", "text" => "All"]);
        return response()->json(["receptiants" => $group_recipients]);
    }

    public function EditGroupModel(Request $request)
    {
        $group_id = $request->query("group_id", 0);
        $group = GmailGroupModel::where("id", $group_id)->first();
        $receptiants = GmailGroupReceptiantsModel::where(
            "gmail_group_id",
            $group_id
        )->pluck("user_id");

        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_types = $group_config->pluck("group", "id");
        $group_ids = $group_config->pluck("id");

        if ($group->type == 0) {
            $user_id = UserGroupMapModel::whereIn(
                "group_id",
                $group_ids
            )->pluck("user_id");
        } else {
            $user_id = UserGroupMapModel::where(
                "group_id",
                $group->type
            )->pluck("user_id");
        }

        $group_recipients = UserModel::whereIn("id", $user_id)->pluck(
            "name",
            "id"
        );
        $group_types->prepend("All", "all");
        $group_recipients->prepend("All", "all");
        $view = view("gmailcomunication::admin.creategroupmodel", [
            "group_types" => $group_types,
            "group_recipients" => $group_recipients,
            "group" => $group,
            "receptiants" => $receptiants,
            "layout" => "edit",
        ])->render();

        return response()->json(["view" => $view]);
    }

    public function UpdateGroup(Request $request, $id)
    {
        // dd($request->all());
        $this->validate(
            $request,
            [
                "group_image" => "mimes:jpg,jpeg,png|max:2048",
                "group_title" => "required",
                "recipient" => "required",
            ],
            [
                "recipient.required" =>
                    "You must select at least one recipient.",
                "recipient.min" => "You must select at least one recipient.",

                "group_image.required" => "You must select a group image.",
                "group_image.mimes" =>
                    "The group image must be a file of type: jpg, jpeg, png.",
                "group_image.max" =>
                    "The group image may not be greater than 4000 kilobytes in size.",
            ]
        );

        try {
            DB::beginTransaction();
            $user_id = $request->user()->id;
            $type = $request->group_type == "all" ? 0 : $request->group_type;
            $data = GmailGroupModel::find($id);

            $data->title = $request->group_title;
            $data->descripition = $request->group_description;
            if ($request->group_image) {
                $group_image = $this->GmailGroupImage(
                    $request->group_image,
                    "image"
                );
                $data->image = $group_image;
            }

            $data->type = $type;
            $data->creater = $user_id;
            $data->save();

            if ($data->save()) {
                // dd($request->recipient);
                $delete = GmailGroupReceptiantsModel::where(
                    "gmail_group_id",
                    $id
                )->delete();
                if (in_array("all", $request->recipient) && $delete) {
                    if (!in_array($user_id, $request->recipient)) {
                        $groupreceptiants = new GmailGroupReceptiantsModel();
                        $groupreceptiants->gmail_group_id = $data->id;
                        $groupreceptiants->user_id = $user_id;
                        $groupreceptiants->save();
                    }
                    $group_type = $request->group_type;

                    if ($group_type == "all") {
                        $user_ids = UserGroupMapModel::pluck("user_id");
                    } else {
                        $user_ids = UserGroupMapModel::whereIn(
                            "group_id",
                            (array) $group_type
                        )->pluck("user_id");
                    }

                    if ($user_ids) {
                        foreach ($user_ids as $key => $id) {
                            $groupreceptiants = new GmailGroupReceptiantsModel();
                            $groupreceptiants->gmail_group_id = $data->id;
                            $groupreceptiants->user_id = $id;
                            $groupreceptiants->save();
                        }
                        //    dd("its end");
                    }
                } else {
                    if (!in_array($user_id, $request->recipient)) {
                        $groupreceptiants = new GmailGroupReceptiantsModel();
                        $groupreceptiants->gmail_group_id = $data->id;
                        $groupreceptiants->user_id = $user_id;
                        $groupreceptiants->save();
                    }
                    foreach ($request->recipient as $key => $recipient) {
                        $groupreceptiants = new GmailGroupReceptiantsModel();
                        $groupreceptiants->gmail_group_id = $data->id;
                        $groupreceptiants->user_id = $recipient;
                        $groupreceptiants->save();
                    }
                }

                $msg = "Group Updated successfully";
                $class_name = "success";
            } else {
                $msg = "Something went wrong !! ";
                $class_name = "error";
            }
            DB::commit();
            Session::flash($class_name, $msg);
            return redirect(route("gmailcomunication.index"));
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function DeleteGroup(Request $request)
    {
        // $group_id = $request->query('group_id',0);
        $group_id = $request->query("group_id");

        // dd($group_id);
        $group = GmailGroupModel::find($group_id);
        $group->delete();
        if ($group->delete()) {
            $group_receptaints_delete = GmailGroupReceptiantsModel::where(
                "gmail_group_id",
                $group_id
            )->delete();
            if ($group_receptaints_delete) {
                $success = "deleted";
            }
        }
        return response()->json(["success" => "success"]);
    }

    public function GroupMessage(Request $request)
    {
        $user_id = $request->user()->id;
        $group_id = $request->input("group_id");
        $message = $request->input("message");
        $files = $request->file("files");
        // dd($request->input("message"));
        // dd($files);
        $file_paths = null;
        $group_message = new GmailGroupMessages();
        if ($files) {
            $group_image = $this->GmailGroupMessageFiles($files, "images");
            $file_paths = $group_image;
            // dd($file_paths);
            $group_message->files = json_encode($file_paths);
        }

        $time = Configurations::getcurrentDateTime();
        // dd($time);
        $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
        //  dd($current_time);
        $msg_time = $current_time->format("h:i a");

        $group_message->gmail_group_id = $group_id;
        $group_message->userid = $user_id;
        $group_message->message = $message;
        $group_message->time = $msg_time;

        $group_message->save();

        if ($group_message->save()) {
            $user = UserModel::where("id", $user_id)
                ->select("name", "images")
                ->first();
            $view = view("gmailcomunication::admin.gmailgroupmessage", [
                "user_id" => $user_id,
                "group_id" => $group_id,
                "message" => $message,
                "user" => $user,
                "time" => $msg_time,
                "file_paths" => $file_paths,
            ])->render();

            return response()->json(["view" => $view]);
        }
    }

    public function IndividualMessage(Request $request)
    {
        // dd($request->all());

        $from_id = \Auth::user()->id;

        $time = Configurations::getcurrentDateTime();
        // dd($time);
        $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
        $msg_time = $current_time->format("h:i a");
        if ($request->ajax()) {
            $starred_id = $request->query("starred_id");
            if ($starred_id) {
                $star = $request->query("star");
                if ($star == "1") {
                    $starred_msg = new StarredGmailMessagesModel();
                    $starred_msg->message_id = $starred_id;
                    $starred_msg->user_id = $from_id;
                    $starred_msg->time = $msg_time;
                    $starred_msg->save();
                    if ($starred_msg->save()) {
                        return response()->json(["star_status" => "added"]);
                    }
                } else {
                    $starred_msg = StarredGmailMessagesModel::where([
                        "message_id" => $starred_id,
                        "user_id" => $from_id,
                    ])->delete();
                    if ($starred_msg) {
                        return response()->json(["star_status" => "deleted"]);
                    }
                }
            } else {
                $message_id = $request->input("message_id");
                $message_type = $request->input("message_type");
                $reciever_id = $request->input("senter_id");
                $message = $request->input("message");
                $replay_files = $request->file("files");
                $paths = null;
                // dd($reciever_id);
                if ($replay_files) {
                    $replay_image = $this->GmailIndividualMessageFiles(
                        $replay_files,
                        "images"
                    );
                    $paths = $replay_image;
                    // dd($file_paths);
                }
                $receivers = explode(",", $reciever_id);
                foreach ($receivers as $receiver) {
                    $message_map = new GmailIndividualMessageMappingModel();
                    $message_map->message_id = $message_id;
                    $message_map->senter = $from_id;
                    $message_map->reciever = $receiver;
                    $message_map->message = $message;
                    if ($paths) {
                        $message_map->files = json_encode($paths);
                    }
                    $message_map->message_type = $message_type;
                    $message_map->time = $msg_time;
                    $message_map->save();
                }

                if ($message_map->save()) {
                    $user = UserModel::where("id", $from_id)
                        ->select("name", "images")
                        ->first();
                    $view = view("gmailcomunication::admin.replaymessage", [
                        "user_id" => $from_id,
                        "message_id" => $message_id,
                        "message" => $message,
                        "user" => $user,
                        "time" => $msg_time,
                        "file_paths" => $paths,
                    ])->render();

                    return response()->json(["view" => $view]);
                }
            }
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    "to_users" => "required|array|min:1",
                    "compose_message_img" => "sometimes|array|max:3",
                    "compose_message_img.*" =>
                        "sometimes|file|mimes:jpg,png,jpeg,pdf|max:2048",
                ],
                [
                    "to_users.required" =>
                        "You must select at least one recipient.",
                    "to_users.min" => "You must select at least one recipient.",
                    "to_users.array" => "Invalid selection format.",
                    "compose_message_img.max" =>
                        "You may not upload more than 3 files.",
                    "compose_message_img.*.mimes" =>
                        "Each file must be a file of type: jpg, png, jpeg, pdf.",
                    "compose_message_img.*.max" =>
                        "Each file must not exceed 2MB in size.",
                ]
            );

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with("active_tab", "compose");
            }

            $files = $request->compose_message_img;
            if ($files) {
                $group_image = $this->GmailIndividualMessageFiles(
                    $files,
                    "images"
                );
                $file_paths = $group_image;
                // dd($file_paths);
            }
            try {
                DB::beginTransaction();
                if ($request->submit == "draft_send") {
                    $draft_id = $request->draft_id;
                    $message = GmailIndividualMessages::find($draft_id);
                    $message->from_id = $from_id;
                    $message->subject = $request->gmail_subject;
                    $message->message = $request->gmail_description;
                    if (!empty($file_paths)) {
                        if ($request->old_paths) {
                            $paths = array_merge(
                                $request->old_paths,
                                $file_paths
                            );
                        } else {
                            $paths = $file_paths;
                        }

                        // dd($paths);
                        $message->files = json_encode($paths);
                    }
                    $message->is_draft = 0;
                    $message->time = $msg_time;
                    $message->save();
                    if ($message->save()) {
                        foreach ($request->to_users as $key => $user) {
                            $message_receivers = new GmaliIndividualMessageReceiversModel();
                            $message_receivers->message_id = $message->id;
                            $message_receivers->user_id = $user;
                            $message_receivers->time = $msg_time;
                            $message_receivers->save();
                        }
                    }
                } else {
                    $message = new GmailIndividualMessages();
                    $message->from_id = $from_id;
                    $message->subject = $request->gmail_subject;
                    $message->message = $request->gmail_description;
                    if ($files) {
                        $message->files = json_encode($file_paths);
                    }
                    $message->is_draft = $request->submit == "draft" ? 1 : 0;
                    $message->time = $msg_time;
                    $message->save();
                    if ($message->save()) {
                        foreach ($request->to_users as $key => $user) {
                            $message_receivers = new GmaliIndividualMessageReceiversModel();
                            $message_receivers->message_id = $message->id;
                            $message_receivers->user_id = $user;
                            $message_receivers->time = $msg_time;
                            $message_receivers->save();
                        }
                    }
                }

                DB::commit();
                if ($request->submit == "draft") {
                    Session::flash(
                        "success",
                        "Message Saved to Draft Successfully"
                    );
                } else {
                    Session::flash("success", "Message Sent Successfully");
                }

                return redirect()->route("gmailcomunication.index");
            } catch (\Exception $e) {
                DB::rollback();
                $message = str_replace(
                    ["\r", "\n", "'", "`"],
                    " ",
                    $e->getMessage()
                );
                // dd($message);
                return redirect()
                    ->back()
                    ->withInput()
                    ->with("exception_error", $message);
            }
        }
    }

    public function DeleteMessages(Request $request)
    {
        $user_id = \Auth::user()->id;

        $time = Configurations::getcurrentDateTime();
        $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
        $msg_time = $current_time->format("h:i a");

        $type = $request->query("msg_type", 0);
        $check_ids_string = $request->query("check_ids", 0);
        $check_ids = explode(",", $check_ids_string);
        if ($type == "bin") {
            $delete = GmailIndividualMessages::whereIn(
                "id",
                $check_ids
            )->delete();
            if ($delete) {
                $delete_map = GmailIndividualMessageMappingModel::whereIn(
                    "message_id",
                    $check_ids
                )->delete();
                $delete_receivers = GmaliIndividualMessageReceiversModel::whereIn(
                    "message_id",
                    $check_ids
                )->delete();
            }
        } elseif ($type == "restore") {
            foreach ($check_ids as $id) {
                $delete = DeletedMessagesModel::where([
                    "message_id" => $id,
                    "user_id" => $user_id,
                ]);
                $delete->delete();
            }
        } else {
            // dd($check_ids);
            foreach ($check_ids as $id) {
                $delete = new DeletedMessagesModel();
                $delete->message_id = $id;
                $delete->user_id = $user_id;
                $delete->time = $msg_time;
                $delete->save();
            }
        }

        return response()->json(["deleted" => "1"]);
    }

    public function ExternalMessage(Request $request)
    {
        // dd($request->all());
        $from_id = \Auth::user()->id;

        $time = Configurations::getcurrentDateTime();
        // dd($time);
        $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
        $msg_time = $current_time->format("h:i a");

        $validator = Validator::make(
            $request->all(),
            [
                "to_users" => "required|array|min:1",
                "message_img" => "sometimes|array|max:3",
                "message_img.*" =>
                    "sometimes|file|mimes:jpg,png,jpeg,pdf|max:2048",
            ],
            [
                "to_users.required" =>
                    "You must select at least one recipient.",
                "to_users.min" => "You must select at least one recipient.",
                "message_img.max" => "You may not upload more than 3 files.",
                "message_img.*.mimes" =>
                    "Each file must be a file of type: jpg, png, jpeg, pdf.",
                "message_img.*.max" => "Each file must not exceed 2MB in size.",
            ]
        );

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput()
                ->with("active_tab", "external_compose");
        }

        $files = $request->message_img;
        $file_paths = null;
        if ($files) {
            $group_image = $this->GmailIndividualMessageFiles($files, "images");
            $file_paths = $group_image;
            // dd($file_paths);
        }
        try {
            DB::beginTransaction();
            if ($request->submit == "draft_send") {
                $draft_id = $request->draft_id;
                $message = GmailIndividualMessages::find($draft_id);
                $message->from_id = $from_id;
                $message->subject = $request->gmail_subject;
                $message->message = $request->gmail_description;
                if (!empty($file_paths)) {
                    $paths = array_merge($request->old_paths, $file_paths);
                    dd($paths);
                    $message->files = json_encode($paths);
                }
                $message->is_draft = 0;
                $message->time = $msg_time;

                $message->save();
                if ($message->save()) {
                    foreach ($request->to_users as $key => $user) {
                        $message_receivers = new GmaliIndividualMessageReceiversModel();
                        $message_receivers->message_id = $message->id;
                        $message_receivers->user_id = $user;
                        $message_receivers->time = $msg_time;
                        $message_receivers->save();
                    }
                }
            } else {
                $message = new GmailIndividualMessages();
                $message->from_id = $from_id;
                $message->subject = $request->gmail_subject;
                $message->message = $request->gmail_message;
                if ($files) {
                    $message->files = json_encode($file_paths);
                }
                $message->is_draft = $request->submit == "draft" ? 1 : 0;
                $message->external_message = "1";
                $message->time = $msg_time;
                $message->save();
                if ($message->save()) {
                    foreach ($request->to_users as $key => $user) {
                        $message_receivers = new GmaliIndividualMessageReceiversModel();
                        $message_receivers->message_id = $message->id;
                        $message_receivers->user_id = $user;
                        $message_receivers->time = $msg_time;
                        $message_receivers->save();
                    }
                }

                if ($message_receivers->save()) {
                    $from_user = UserModel::where("id", $from_id)->first();
                    $to_users = UserModel::whereIn(
                        "id",
                        $request->to_users
                    )->get();
                    foreach ($to_users as $to_user) {
                        $senter_mail = $from_user->email;
                        $senter_name = $from_user->name;
                        // dd($senter_mail,$senter_name);
                        // dd($request->gmail_message);
                        $message_info = GmailIndividualMessages::where(
                            "id",
                            $message->id
                        )
                            ->pluck("message")
                            ->first();
                        $message_det = (string) $message_info;
                        $school_name = Configurations::getConfig("site")
                            ->school_name
                            ? Configurations::getConfig("site")->school_name
                            : "S-Management";
                        $data = [
                            "name" => $to_user->name,
                            "content" => $message_info,
                            "files" => $file_paths,
                            "subject" => $request->subject,
                            "fromEmail" => $from_user->email,
                            "fromName" => $from_user->name,
                            "logoUrl" =>
                                "https://schoolmanagement.webbazaardevelopment.com/school/profiles/1748204780466456.png",
                            "school" => $school_name,
                        ];
                        // dd($senter_mail,$senter_name);
                        $env = config("app.env");
                        if ($env == "local") {
                            \CmsMail::setMailTrapConfig();
                        } else {
                            \CmsMail::setMailConfig();
                        }
                        $mail = Mail::to($to_user->email)->send(
                            new ExternalMail(
                                $data["name"],
                                $data["content"],
                                $data["files"],
                                $data["subject"],
                                $data["fromEmail"],
                                $data["fromName"],
                                $data["logoUrl"],
                                $data["school"]
                            )
                        );
                    }
                }
            }

            DB::commit();
            if ($request->submit == "draft") {
                Session::flash(
                    "success",
                    "Message Saved to Draft Successfully"
                );
            } else {
                Session::flash("success", "Message Sent Successfully");
            }

            return redirect()->route("gmailcomunication.index");
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            // dd($message);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }
    }

    public function Receptiants(Request $request)
    {
        $user_id = \Auth::user()->id;
        $id = $request->query("id", 0);
        if ($id == "all") {
            $group_config = Configurations::GetGmailRoleTypes($request);
            $group_config_ids = [];
            foreach ($group_config as $key => $data) {
                $group_config_ids[] = $data->id;
            }
            $eligibele_receptiants_ids = UserGroupMapModel::whereIn(
                "group_id",
                $group_config_ids
            )->pluck("user_id");

            $filtered_ids = $eligibele_receptiants_ids->diff($user_id)->all();
            // dd($filtered_ids);
            $eligibele_receptiants = UserModel::whereIn("id", $filtered_ids)
                ->select(
                    "id",
                    DB::raw(
                        "CONCAT(username, ' - ', name, ' - ', email) as text"
                    )
                )
                ->get();
        } else {
            $eligibele_receptiants_ids = UserGroupMapModel::where(
                "group_id",
                $id
            )->pluck("user_id");

            $filtered_ids = $eligibele_receptiants_ids->diff($user_id)->all();
            // dd($filtered_ids);
            $eligibele_receptiants = UserModel::whereIn("id", $filtered_ids)
                ->select(
                    "id",
                    DB::raw(
                        "CONCAT(username, ' - ', name, ' - ', email) as text"
                    )
                )
                ->get();
        }

        return response()->json(["receptiants" => $eligibele_receptiants]);
    }
}
