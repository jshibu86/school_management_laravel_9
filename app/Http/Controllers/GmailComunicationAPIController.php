<?php

namespace App\Http\Controllers;

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
use App\Traits\ApiResponse;

class GmailComunicationAPIController extends Controller
{
    use FileUploadTrait, ApiResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        //
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

    public function InboxMessages(Request $request)
    {
        $user_id = \Auth::user()->id;

        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_config_ids = [];
        foreach ($group_config as $key => $data) {
            $group_config_ids[] = $data->id;
        }
        $eligibele_receptiants_ids = UserGroupMapModel::whereIn(
            "group_id",
            $group_config_ids
        )->pluck("user_id");
        // dd($user_id);
        $filtered_ids = $eligibele_receptiants_ids->diff($user_id)->all();
        // dd($filtered_ids);
        $eligibele_receptiants = UserModel::whereIn("id", $filtered_ids)
            ->select(
                "id",
                DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text")
            )
            ->get();

        $user_inbox = GmaliIndividualMessageReceiversModel::where(
            "user_id",
            $user_id
        )->pluck("message_id");
        $deleted_ids = DeletedMessagesModel::where("user_id", $user_id)->pluck(
            "message_id"
        );

        $inbox_messages_list = GmailIndividualMessages::with("senter")
            ->whereIn("id", $user_inbox)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->get();
        $inbox_messages_count = $inbox_messages_list->count();
        $inbox_ids = $inbox_messages_list->pluck("id");
        $senter_ids = $inbox_messages_list->pluck("from_id");
        //dd($senter_ids);
        $senter_roles = UserGroupMapModel::with("usergroup")
            ->whereIn("user_id", $senter_ids)
            ->get();
        $inbox_messages_maping = GmailIndividualMessageMappingModel::with(
            "senter_info",
            "reciver"
        )
            ->whereIn("message_id", $inbox_ids)
            ->get();
        // dd($senter_roles);
        // dd($inbox_ids,$inbox_messages_maping);
        $starred_ids = StarredGmailMessagesModel::where("user_id", $user_id)
            ->pluck("message_id")
            ->toArray();
        $inbox_messages = [];

        foreach ($inbox_messages_list as $message) {
            $post = $senter_roles->where("user_id", $message->from_id)->first();
            // dd( $post);
            if ($post->usergroup && $post->usergroup->group == "Teacher") {
                $background = "#ccf0eb";
                $color = "#32ab13";
            } elseif (
                $post->usergroup &&
                $post->usergroup->group == "Student"
            ) {
                $background = "#f7e2ff";
                $color = "#D456FD";
            } elseif ($post->usergroup && $post->usergroup->group == "Parent") {
                $background = "#ffede0";
                $color = "rgba(var(--bs-warning-rgb), var(--bs-text-opacity));";
            } elseif (
                $post->usergroup &&
                $post->usergroup->group == "Super Admin"
            ) {
                $background = "#e1d5f5";
                $color = "#673ab7";
            } else {
                $background = "";
                $color = "";
            }

            if (in_array($message->id, $starred_ids)) {
                $starred = "1";
            } else {
                $starred = "0";
            }
            $date = $message->created_at;
            $dateTime = new DateTime($date);
            $year = $dateTime->format("Y");
            $currentYear = date("Y");
            $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

            if ($isCurrentDay) {
                $formattedDate = $message->time;
            } elseif ($year == $currentYear) {
                $formattedDate = $dateTime->format("F d");
            } else {
                $formattedDate = $dateTime->format("F d, Y");
            }

            $replys = $inbox_messages_maping->where("message_id", $message->id);
            $reply_message = [];

            foreach ($replys as $reply) {
                $reply_date = $reply->created_at;
                $reply_dateTime = new DateTime($reply_date);
                $year = $reply_dateTime->format("Y");
                $currentYear = date("Y");
                $isCurrentDay =
                    $reply_dateTime->format("Y-m-d") === date("Y-m-d");

                if ($isCurrentDay) {
                    $formatted_reply_Date = $reply->time;
                } elseif ($year == $currentYear) {
                    $formatted_reply_Date = $reply_dateTime->format("F d");
                } else {
                    $formatted_reply_Date = $reply_dateTime->format("F d, Y");
                }

                $file_paths = json_decode($reply->files);
                $reply_message[] = [
                    "sender" => $reply->senter,
                    "sender_name" => $reply->senter_info->name,
                    "message" => $reply->message,
                    "files" => $file_paths,
                    "date" => $formatted_reply_Date,
                ];
            }

            $file_paths = json_decode($message->files);

            $inbox_messages[] = [
                "message_id" => $message->id,
                "sender_id" => $message->senter->id,
                "sender_image" => $message->senter->images,
                "sender" => $message->senter->name,
                "sender_gmail" => $message->senter->email,
                "sender_group" => $post->usergroup->group,
                "group_background" => $background,
                "group_color" => $color,
                "subject" => $message->subject,
                "message" => $message->message,
                "files" => $file_paths,
                "date" => $formattedDate,
                "starred" => $starred,
                "replay_messages" => $reply_message,
            ];
        }
        $page = request()->get("page", 1); // Get the current page or default to 1
        $perPage = 4; // Items per page

        $collection = collect($inbox_messages);
        $currentPageItems = $collection
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $page,
            ["path" => request()->url(), "query" => request()->query()]
        );
        return response()->json([
            "inbox_messages" => $paginator,
            "eligibele_receptiants" => $eligibele_receptiants,
        ]);
    }

    public function EligibleReceptiants(Request $request)
    {
        $user_id = \Auth::user()->id;

        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_config_ids = [];
        foreach ($group_config as $key => $data) {
            $group_config_ids[] = $data->id;
        }
        $eligibele_receptiants_ids = UserGroupMapModel::whereIn(
            "group_id",
            $group_config_ids
        )->pluck("user_id");
        // dd($user_id);
        $filtered_ids = $eligibele_receptiants_ids->diff($user_id)->all();
        // dd($filtered_ids);
        $eligibele_receptiants = UserModel::whereIn("id", $filtered_ids)
            ->select(
                "id",
                DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text")
            )
            ->get();

        return response()->json([
            "eligible_receptiants" => $eligibele_receptiants,
        ]);
    }

    // public function IndividualMessage(Request $request){
    //     $from_id = \Auth::user()->id;
    //     $validator = Validator::make($request->all(), [
    //         'to_users' => 'required|array|min:1',
    //         'compose_message_img' => 'sometimes|array|max:3',
    //         'compose_message_img.*' => 'sometimes|file|mimes:jpg,png,jpeg,pdf|max:2048',
    //      ], [
    //         'to_users.required' => 'You must select at least one recipient.',
    //         'to_users.min' => 'You must select at least one recipient.',
    //         'compose_message_img.max' => 'You may not upload more than 3 files.',
    //         'compose_message_img.*.mimes' => 'Each file must be a file of type: jpg, png, jpeg, pdf.',
    //         'compose_message_img.*.max' => 'Each file must not exceed 2MB in size.',
    //     ]);

    //     $files = $request->compose_message_img;

    //     try{
    //         if ($files) {
    //             $group_image = $this->GmailIndividualMessageFiles($files, "images");
    //             $file_paths = $group_image;
    //             //  dd($group_image);
    //         }
    //         // dd($files);
    //         DB::beginTransaction();
    //         $submit = $request->submit ?? 0;
    //         $time = Configurations::getcurrentDateTime();
    //         // dd($time);
    //         $current_time = \DateTime::createFromFormat('H:i:s', $time[1]);
    //         $msg_time = $current_time->format("h:i a");
    //         if($submit == "draft_send"){

    //             $draft_id = $request->draft_id;
    //             $message = GmailIndividualMessages::find($draft_id);
    //             $message->from_id = $from_id;
    //             $message->subject = $request->gmail_subject;
    //             $message->message = $request->gmail_description;
    //             if(!empty($file_paths)){

    //                 $paths = array_merge($request->old_paths, $file_paths);
    //                 // dd($paths);
    //                 $message->files = json_encode($paths);
    //             }
    //             $message->is_draft = 0;
    //             $message->time = $msg_time;
    //             $message->save();
    //             if( $message->save()){
    //                 foreach ($request->to_users as $key => $user) {
    //                     $message_receivers = new GmaliIndividualMessageReceiversModel();
    //                     $message_receivers->message_id = $message->id;
    //                     $message_receivers->user_id = $user;
    //                     $message_receivers->time = $msg_time;
    //                     $message_receivers->save();
    //                 }
    //             }
    //         }
    //         else{
    //             $message = new GmailIndividualMessages();
    //             $message->from_id = $from_id;
    //             $message->subject = $request->gmail_subject;
    //             $message->message = $request->gmail_description;
    //             if($files){
    //                 $message->files = json_encode($file_paths);
    //             }
    //             $message->is_draft = ($request->submit == "draft") ? 1 :0;
    //             $message->time = $msg_time;
    //             $message->save();
    //             if($message->save()){
    //                 foreach ($request->to_users as $key => $user) {
    //                     $message_receivers = new GmaliIndividualMessageReceiversModel();
    //                     $message_receivers->message_id = $message->id;
    //                     $message_receivers->user_id = $user;
    //                     $message_receivers->time = $msg_time;
    //                     $message_receivers->save();
    //                 }
    //             }
    //         }

    //         DB::commit();
    //         if($submit == "draft"){
    //             return "Message Saved to Draft Successfully";
    //         }
    //         else{
    //            return "Message Sent Successfully";
    //         }

    //     }catch(\Exception $e)
    //     {
    //         DB::rollback();
    //         $message = str_replace(
    //             ["\r", "\n", "'", "`"],
    //             " ",
    //             $e->getMessage()
    //         );
    //         dd($message);
    //         return redirect()
    //             ->back()
    //             ->withInput()
    //             ->with("exception_error", $message);
    //     }
    // }
    public function IndividualMessage(Request $request)
    {
        $from_id = \Auth::user()->id;

        $this->validate(
            $request,
            [
                "to_users" => "required|array|min:1",
                "compose_message_img" => "sometimes|array|max:3",
                // "compose_message_img.*" =>
                //     "sometimes|file|mimes:jpg,png,jpeg,pdf|max:2048",
            ],
            [
                "to_users.required" =>
                    "You must select at least one recipient.",
                "to_users.min" => "You must select at least one recipient.",
                // "compose_message_img.max" =>
                //     "You may not upload more than 3 files.",
                // "compose_message_img.*.mimes" =>
                //     "Each file must be a file of type: jpg, png, jpeg, pdf.",
                // "compose_message_img.*.max" =>
                //     "Each file must not exceed 2MB in size.",
            ]
        );

        $files = $request->compose_message_img;
        $file_paths = [];
        if ($files) {
            $group_image = $this->GmailIndividualMessageFiles($files, "images");
            $file_paths = $group_image;
        }

        try {
            DB::beginTransaction();
            $submit = $request->submit ?? 0;
            $time = Configurations::getcurrentDateTime();
            $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
            $msg_time = $current_time->format("h:i a");

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

            foreach ($request->to_users as $user) {
                $message_receivers = new GmaliIndividualMessageReceiversModel();
                $message_receivers->message_id = $message->id;
                $message_receivers->user_id = $user;
                $message_receivers->time = $msg_time;
                $message_receivers->save();
            }

            DB::commit();

            return response()->json(["status" => "Message Sent Successfully"]);
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            // dd($message);
            return $this->error($message, 500);
        }
    }

    public function Draft(Request $request)
    {
        $from_id = \Auth::user()->id;

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
                ->with("active_tab", "external_compose");
        }

        $files = $request->compose_message_img;
        $file_paths = [];
        if ($files) {
            $group_image = $this->GmailIndividualMessageFiles($files, "images");
            $file_paths = $group_image;
        }

        try {
            DB::beginTransaction();
            $time = Configurations::getcurrentDateTime();
            $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
            $msg_time = $current_time->format("h:i a");

            $message = new GmailIndividualMessages();
            $message->from_id = $from_id;
            $message->subject = $request->gmail_subject;
            $message->message = $request->gmail_description;
            if ($files) {
                $message->files = json_encode($file_paths);
            }
            $message->is_draft = 1;
            $message->time = $msg_time;
            $message->save();

            foreach ($request->to_users as $user) {
                $message_receivers = new GmaliIndividualMessageReceiversModel();
                $message_receivers->message_id = $message->id;
                $message_receivers->user_id = $user;
                $message_receivers->time = $msg_time;
                $message_receivers->save();
            }

            DB::commit();

            return response()->json([
                "status" => "Message Saved to Draft Successfully",
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );

            dd($message);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }
    }

    public function Starred(Request $request)
    {
        $from_id = \Auth::user()->id;
        $starred_id = $request->starred_id;

        $time = Configurations::getcurrentDateTime();
        $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
        $msg_time = $current_time->format("h:i a");

        $starred_msg = new StarredGmailMessagesModel();
        $starred_msg->message_id = $starred_id;
        $starred_msg->user_id = $from_id;
        $starred_msg->time = $msg_time;
        $starred_msg->save();
        if ($starred_msg->save()) {
            return response()->json(["status" => "star added"]);
        }
    }

    public function UnStarred(Request $request)
    {
        $from_id = \Auth::user()->id;
        $starred_id = $request->starred_id;

        $time = Configurations::getcurrentDateTime();
        $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
        $msg_time = $current_time->format("h:i a");

        $starred_msg = StarredGmailMessagesModel::where([
            "message_id" => $starred_id,
            "user_id" => $from_id,
        ])->delete();
        if ($starred_msg) {
            return response()->json(["star_status" => "star deleted"]);
        }
    }

    public function DraftSend(Request $request)
    {
        $from_id = \Auth::user()->id;

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
                ->with("active_tab", "external_compose");
        }

        $files = $request->compose_message_img;
        $file_paths = [];
        if ($files) {
            $group_image = $this->GmailIndividualMessageFiles($files, "images");
            $file_paths = $group_image;
        }

        try {
            DB::beginTransaction();
            $time = Configurations::getcurrentDateTime();
            $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
            $msg_time = $current_time->format("h:i a");

            $draft_id = $request->draft_id;
            $message = GmailIndividualMessages::find($draft_id);
            $message->from_id = $from_id;
            $message->subject = $request->gmail_subject;
            $message->message = $request->gmail_description;
            if (!empty($file_paths)) {
                if ($request->old_paths) {
                    $paths = array_merge($request->old_paths, $file_paths);
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
                    $is_exist = GmaliIndividualMessageReceiversModel::where([
                        "message_id" => $message->id,
                        "user_id" => $user,
                    ])->first();
                    if ($is_exist) {
                        $is_exist->time = $msg_time;
                        $is_exist->save();
                    } else {
                        $message_receivers = new GmaliIndividualMessageReceiversModel();
                        $message_receivers->message_id = $message->id;
                        $message_receivers->user_id = $user;
                        $message_receivers->time = $msg_time;
                        $message_receivers->save();
                    }
                }
            }

            DB::commit();

            return response()->json(["status" => "Message Sent Successfully"]);
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            dd($message);
            return redirect()
                ->back()
                ->withInput()
                ->with("exception_error", $message);
        }
    }

    public function ReplyMessage(Request $request)
    {
        $from_id = \Auth::user()->id;
        $validator = Validator::make(
            $request->all(),
            [
                "files" => "sometimes|array|max:3",
                "files.*" => "sometimes|file|mimes:jpg,png,jpeg,pdf|max:2048",
            ],
            [
                "files.max" => "You may not upload more than 3 files.",
                "files.*.mimes" =>
                    "Each file must be a file of type: jpg, png, jpeg, pdf.",
                "files.*.max" => "Each file must not exceed 2MB in size.",
            ]
        );
        $message_id = $request->message_id;
        $receivers = $request->reciever_id;
        $message = $request->message;
        $replay_files = $request->file("files");
        $paths = null;

        $time = Configurations::getcurrentDateTime();
        // dd($time);
        $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
        $msg_time = $current_time->format("h:i a");

        // dd($reciever_id);
        if ($replay_files) {
            $replay_image = $this->GmailIndividualMessageFiles(
                $replay_files,
                "images"
            );
            $paths = $replay_image;
            // dd($file_paths);
        }
        //  $receivers = explode(',', $reciever_id);
        foreach ($receivers as $receiver) {
            $message_map = new GmailIndividualMessageMappingModel();
            $message_map->message_id = $message_id;
            $message_map->senter = $from_id;
            $message_map->reciever = $receiver;
            $message_map->message = $message;
            if ($paths) {
                $message_map->files = json_encode($paths);
            }
            $message_map->time = $msg_time;
            $message_map->save();
        }
        return response()->json(["status" => "Reply sented successfully"]);
    }

    public function SentMessages(Request $request)
    {
        $user_id = \Auth::user()->id;

        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_config_ids = [];
        foreach ($group_config as $key => $data) {
            $group_config_ids[] = $data->id;
        }
        $eligibele_receptiants_ids = UserGroupMapModel::whereIn(
            "group_id",
            $group_config_ids
        )->pluck("user_id");
        // dd($user_id);
        $filtered_ids = $eligibele_receptiants_ids->diff($user_id)->all();
        // dd($filtered_ids);
        $eligibele_receptiants = UserModel::whereIn("id", $filtered_ids)
            ->select(
                "id",
                DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text")
            )
            ->get();

        $deleted_ids = DeletedMessagesModel::where("user_id", $user_id)->pluck(
            "message_id"
        );
        $sent_messages = GmailIndividualMessages::with("reciver", "senter")
            ->where("from_id", $user_id)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->where("external_message", "!=", 1)
            ->get();
        $sent_ids = $sent_messages->pluck("id");
        $sent_messages_count = $sent_messages->count();
        $reciver_details = GmaliIndividualMessageReceiversModel::with(
            "reciver_info"
        )
            ->whereIn("message_id", $sent_ids)
            ->get();
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
            "senter",
            "reciver"
        )
            ->whereIn("message_id", $sent_ids)
            ->groupBy("senter", "message_id", "created_at")
            ->get();

        $sent_messages_list = [];

        foreach ($sent_messages as $message) {
            $recivers_info = $reciver_details->where(
                "message_id",
                $message->id
            );
            $recivers = [];
            foreach ($recivers_info as $data) {
                $recivers[] = [
                    "id" => $data->user_id,
                    "image" => $data->reciver_info->images,
                    "name" => $data->reciver_info->name,
                ];
            }
            $date = $message->created_at;
            $dateTime = new DateTime($date);
            $year = $dateTime->format("Y");
            $currentYear = date("Y");
            $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

            if ($isCurrentDay) {
                $formattedDate = $message->time;
            } elseif ($year == $currentYear) {
                $formattedDate = $dateTime->format("F d");
            } else {
                $formattedDate = $dateTime->format("F d, Y");
            }

            $replys = $sent_messages_maping->where("message_id", $message->id);
            $reply_message = [];

            foreach ($replys as $reply) {
                $reply_date = $reply->created_at;
                $reply_dateTime = new DateTime($reply_date);
                $reply_year = $reply_dateTime->format("Y");
                $currentYear = date("Y");
                $isCurrentDay =
                    $reply_dateTime->format("Y-m-d") === date("Y-m-d");

                if ($isCurrentDay) {
                    $formatted_reply_Date = $reply->time;
                } elseif ($year == $currentYear) {
                    $formatted_reply_Date = $reply_dateTime->format("F d");
                } else {
                    $formatted_reply_Date = $reply_dateTime->format("F d, Y");
                }

                $file_paths = json_decode($reply->files);
                $reply_message[] = [
                    "sender" => $reply->senter,
                    "sender_name" => $reply->senter_info->name,
                    "message" => $reply->message,
                    "files" => $file_paths,
                    "date" => $formatted_reply_Date,
                ];
            }

            $file_paths = json_decode($message->files);

            $sent_messages_list[] = [
                "message_id" => $message->id,
                "sender_id" => $message->senter->id,
                "receivers" => $recivers,
                "subject" => $message->subject,
                "message" => $message->message,
                "files" => $file_paths,
                "date" => $formattedDate,
                "replay_messages" => $reply_message,
            ];
        }
        $page = request()->get("page", 1); // Get the current page or default to 1
        $perPage = 4; // Items per page

        $collection = collect($sent_messages_list);
        $currentPageItems = $collection
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $page,
            ["path" => request()->url(), "query" => request()->query()]
        );
        return response()->json([
            "sent_messages" => $paginator,
            "eligibele_receptiants" => $eligibele_receptiants,
        ]);
    }

    public function StarredMessages(Request $request)
    {
        $user_id = \Auth::user()->id;

        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_config_ids = [];
        foreach ($group_config as $key => $data) {
            $group_config_ids[] = $data->id;
        }
        $eligibele_receptiants_ids = UserGroupMapModel::whereIn(
            "group_id",
            $group_config_ids
        )->pluck("user_id");
        // dd($user_id);
        $filtered_ids = $eligibele_receptiants_ids->diff($user_id)->all();
        // dd($filtered_ids);
        $eligibele_receptiants = UserModel::whereIn("id", $filtered_ids)
            ->select(
                "id",
                DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text")
            )
            ->get();

        $deleted_ids = DeletedMessagesModel::where("user_id", $user_id)->pluck(
            "message_id"
        );

        $user_inbox = GmaliIndividualMessageReceiversModel::where(
            "user_id",
            $user_id
        )->pluck("message_id");
        $inbox_messages_list = GmailIndividualMessages::with("senter")
            ->whereIn("id", $user_inbox)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->get();

        $inbox_ids = $inbox_messages_list->pluck("id");

        $starred_ids = StarredGmailMessagesModel::where(
            "user_id",
            $user_id
        )->pluck("message_id");
        // dd($starred_ids);
        $starred_messages_list = GmailIndividualMessages::with(
            "reciver",
            "senter"
        )
            ->where("external_message", "!=", "1")
            ->whereIn("id", $starred_ids)
            ->whereNotIn("id", $deleted_ids)
            ->where("is_draft", "!=", "1")
            ->orderBy("id", "desc")
            ->get();
        //   dd($user_id,$starred_ids,$starred_messages_list);
        $starred_senter_ids = $starred_messages_list->pluck("from_id");
        //dd($senter_ids);
        $starred_senter_roles = UserGroupMapModel::with("usergroup")
            ->whereIn("user_id", $starred_senter_ids)
            ->get();

        $starred_messages_maping = GmailIndividualMessageMappingModel::with(
            "senter_info",
            "reciver"
        )
            ->whereIn("message_id", $inbox_ids)
            ->get();
        $starred_messages = [];

        foreach ($starred_messages_list as $message) {
            $post = $starred_senter_roles
                ->where("user_id", $message->from_id)
                ->first();
            // dd( $post);
            if ($post->usergroup && $post->usergroup->group == "Teacher") {
                $background = "#ccf0eb";
                $color = "#32ab13";
            } elseif (
                $post->usergroup &&
                $post->usergroup->group == "Student"
            ) {
                $background = "#f7e2ff";
                $color = "#D456FD";
            } elseif ($post->usergroup && $post->usergroup->group == "Parent") {
                $background = "#ffede0";
                $color = "rgba(var(--bs-warning-rgb), var(--bs-text-opacity));";
            } elseif (
                $post->usergroup &&
                $post->usergroup->group == "Super Admin"
            ) {
                $background = "#e1d5f5";
                $color = "#673ab7";
            } else {
                $background = "";
                $color = "";
            }

            $starred = "1";

            $date = $message->created_at;
            $dateTime = new DateTime($date);
            $year = $dateTime->format("Y");
            $currentYear = date("Y");
            $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

            if ($isCurrentDay) {
                $formattedDate = $message->time;
            } elseif ($year == $currentYear) {
                $formattedDate = $dateTime->format("F d");
            } else {
                $formattedDate = $dateTime->format("F d, Y");
            }

            $replys = $starred_messages_maping->where(
                "message_id",
                $message->id
            );
            $reply_message = [];

            foreach ($replys as $reply) {
                $reply_date = $reply->created_at;
                $reply_dateTime = new DateTime($reply_date);
                $year = $reply_dateTime->format("Y");
                $currentYear = date("Y");
                $isCurrentDay =
                    $reply_dateTime->format("Y-m-d") === date("Y-m-d");

                if ($isCurrentDay) {
                    $formatted_reply_Date = $reply->time;
                } elseif ($year == $currentYear) {
                    $formatted_reply_Date = $reply_dateTime->format("F d");
                } else {
                    $formatted_reply_Date = $reply_dateTime->format("F d, Y");
                }

                $file_paths = json_decode($reply->files);
                $reply_message[] = [
                    "sender" => $reply->senter,
                    "sender_name" => $reply->senter_info->name,
                    "message" => $reply->message,
                    "files" => $file_paths,
                    "date" => $formatted_reply_Date,
                ];
            }

            $file_paths = json_decode($message->files);

            $starred_messages[] = [
                "message_id" => $message->id,
                "sender_id" => $message->senter->id,
                "sender_image" => $message->senter->images,
                "sender_name" => $message->senter->name,
                "sender_gmail" => $message->senter->email,
                "sender_group" => $post->usergroup->group,
                "group_background" => $background,
                "group_color" => $color,
                "subject" => $message->subject,
                "message" => $message->message,
                "files" => $file_paths,
                "date" => $formattedDate,
                "starred" => $starred,
                "replay_messages" => $reply_message,
            ];
        }
        $page = request()->get("page", 1); // Get the current page or default to 1
        $perPage = 4; // Items per page

        $collection = collect($starred_messages);
        $currentPageItems = $collection
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $page,
            ["path" => request()->url(), "query" => request()->query()]
        );
        return response()->json([
            "starred_messages" => $paginator,
            "eligibele_receptiants" => $eligibele_receptiants,
        ]);
    }

    public function DraftMessages(Request $request)
    {
        $user_id = \Auth::user()->id;
        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_config_ids = [];
        foreach ($group_config as $key => $data) {
            $group_config_ids[] = $data->id;
        }
        $eligibele_receptiants_ids = UserGroupMapModel::whereIn(
            "group_id",
            $group_config_ids
        )->pluck("user_id");
        // dd($user_id);
        $filtered_ids = $eligibele_receptiants_ids->diff($user_id)->all();
        // dd($filtered_ids);
        $eligibele_receptiants = UserModel::whereIn("id", $filtered_ids)
            ->select(
                "id",
                DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text")
            )
            ->get();

        $deleted_ids = DeletedMessagesModel::where("user_id", $user_id)->pluck(
            "message_id"
        );
        $draft_messages = GmailIndividualMessages::with("reciver", "senter")
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
        $draft_messages_list = [];

        foreach ($draft_messages as $message) {
            $recivers_info = $draft_reciver_details->where(
                "message_id",
                $message->id
            );
            $recivers = [];
            foreach ($recivers_info as $data) {
                $recivers[] = [
                    "id" => $data->user_id,
                    "image" => $data->reciver_info->images,
                    "name" => $data->reciver_info->name,
                ];
            }

            $date = $message->created_at;
            $dateTime = new DateTime($date);
            $year = $dateTime->format("Y");
            $currentYear = date("Y");
            $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

            if ($isCurrentDay) {
                $formattedDate = $message->time;
            } elseif ($year == $currentYear) {
                $formattedDate = $dateTime->format("F d");
            } else {
                $formattedDate = $dateTime->format("F d, Y");
            }

            $replys = $draft_messages_maping->where("message_id", $message->id);
            $reply_message = [];

            foreach ($replys as $reply) {
                $reply_date = $reply->created_at;
                $reply_dateTime = new DateTime($reply_date);
                $reply_year = $reply_dateTime->format("Y");
                $currentYear = date("Y");
                $isCurrentDay =
                    $reply_dateTime->format("Y-m-d") === date("Y-m-d");

                if ($isCurrentDay) {
                    $formatted_reply_Date = $reply->time;
                } elseif ($year == $currentYear) {
                    $formatted_reply_Date = $reply_dateTime->format("F d");
                } else {
                    $formatted_reply_Date = $reply_dateTime->format("F d, Y");
                }

                $file_paths = json_decode($reply->files);
                $reply_message[] = [
                    "sender" => $reply->senter,
                    "sender_name" => $reply->senter_info->name,
                    "message" => $reply->message,
                    "files" => $file_paths,
                    "date" => $formatted_reply_Date,
                ];
            }

            $file_paths = json_decode($message->files);

            $draft_messages_list[] = [
                "message_id" => $message->id,
                "sender_id" => $message->senter->id,
                "receivers" => $recivers,
                "subject" => $message->subject,
                "message" => $message->message,
                "files" => $file_paths,
                "date" => $formattedDate,
                "replay_messages" => $reply_message,
            ];
        }
        $page = request()->get("page", 1); // Get the current page or default to 1
        $perPage = 4; // Items per page

        $collection = collect($draft_messages_list);
        $currentPageItems = $collection
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $page,
            ["path" => request()->url(), "query" => request()->query()]
        );
        return response()->json([
            "draft_messages" => $paginator,
            "eligibele_receptiants" => $eligibele_receptiants,
        ]);
    }

    public function BinMessages(Request $request)
    {
        $user_id = \Auth::user()->id;

        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_config_ids = [];
        foreach ($group_config as $key => $data) {
            $group_config_ids[] = $data->id;
        }
        $eligibele_receptiants_ids = UserGroupMapModel::whereIn(
            "group_id",
            $group_config_ids
        )->pluck("user_id");
        // dd($user_id);
        $filtered_ids = $eligibele_receptiants_ids->diff($user_id)->all();
        // dd($filtered_ids);
        $eligibele_receptiants = UserModel::whereIn("id", $filtered_ids)
            ->select(
                "id",
                DB::raw("CONCAT(username, ' - ', name, ' - ', email) as text")
            )
            ->get();

        $deleted_ids = DeletedMessagesModel::where("user_id", $user_id)->pluck(
            "message_id"
        );

        $user_inbox = GmaliIndividualMessageReceiversModel::where(
            "user_id",
            $user_id
        )->pluck("message_id");
        $inbox_messages_list = GmailIndividualMessages::with("senter")
            ->whereIn("id", $user_inbox)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->get();

        $inbox_ids = $inbox_messages_list->pluck("id");

        $bin_messages = GmailIndividualMessages::with("senter", "reciver")
            ->where("external_message", "!=", "1")
            ->where("is_draft", "!=", "1")
            ->whereIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->get();
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

        $bin_messages_list = [];

        foreach ($bin_messages as $message) {
            $date = $message->created_at;
            $dateTime = new DateTime($date);
            $year = $dateTime->format("Y");
            $currentYear = date("Y");
            $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

            if ($isCurrentDay) {
                $formattedDate = $message->time;
            } elseif ($year == $currentYear) {
                $formattedDate = $dateTime->format("F d");
            } else {
                $formattedDate = $dateTime->format("F d, Y");
            }

            $file_paths = json_decode($message->files);

            $bin_messages_list[] = [
                "message_id" => $message->id,
                "sender_id" => $message->senter->id,
                "subject" => $message->subject,
                "message" => $message->message,
                "files" => $file_paths,
                "date" => $formattedDate,
            ];
        }
        $page = request()->get("page", 1); // Get the current page or default to 1
        $perPage = 4; // Items per page

        $collection = collect($bin_messages_list);
        $currentPageItems = $collection
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $page,
            ["path" => request()->url(), "query" => request()->query()]
        );
        return response()->json(["bin_messages" => $paginator]);
    }

    public function DeleteMessages(Request $request)
    {
        $user_id = \Auth::user()->id;

        $time = Configurations::getcurrentDateTime();
        $current_time = \DateTime::createFromFormat("H:i:s", $time[1]);
        $msg_time = $current_time->format("h:i a");

        $type = $request->msg_type ?? 0;
        $check_ids_string = $request->check_ids ?? 0;
        if (is_array($check_ids_string)) {
            $check_ids = $check_ids_string;
        } else {
            $check_ids = explode(",", $check_ids_string);
        }

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
                $delete_list = DeletedMessagesModel::whereIn(
                    "message_id",
                    $check_ids
                )->delete();
            }
            return response()->json([
                "status" => "Message Deleted Successfully",
            ]);
        } elseif ($type == "restore") {
            foreach ($check_ids as $id) {
                $delete = DeletedMessagesModel::where([
                    "message_id" => $id,
                    "user_id" => $user_id,
                ]);
                $delete->delete();
            }

            return response()->json([
                "status" => "Message Restored Successfully",
            ]);
        } else {
            // dd($check_ids);
            foreach ($check_ids as $id) {
                $delete = new DeletedMessagesModel();
                $delete->message_id = $id;
                $delete->user_id = $user_id;
                $delete->time = $msg_time;
                $delete->save();
            }

            return "Message Saved to Bin Successfully";
        }
    }

    public function EligibleForGroupCreate(Request $request)
    {
        $user_id = \Auth::user()->id;
        $eligible = Configurations::GetGmailGroupEligibleRoles();

        $current_user_role = UserGroupMapModel::where("user_id", $user_id)
            ->pluck("group_id")
            ->first();

        $eligible_role = in_array($current_user_role, $eligible) ? "1" : "0";

        $eligible = ["Eligible" => $eligible_role];

        return $eligible;
    }

    public function CreateGroupModel(Request $request)
    {
        $user_id = \Auth::user()->id;
        $group_config = Configurations::GetGmailRoleTypes($request);
        $group_types = $group_config->pluck("group", "id");
        $group_types_array = [["id" => "all", "text" => "All"]];
        foreach ($group_types as $id => $text) {
            $group_types_array[] = ["id" => $id, "text" => $text];
        }
        $group_ids = $group_config->pluck("id");

        $user_id = UserGroupMapModel::whereIn("group_id", $group_ids)->pluck(
            "user_id"
        );

        $group_recipients = UserModel::whereIn("id", $user_id)
            ->select("name as text", "id")
            ->get();
        $group_recipients->prepend(["id" => "all", "text" => "All"]);

        return response()->json([
            "layout" => "create",
            "group_types" => $group_types_array,
            "group_recipients" => $group_recipients,
        ]);
    }

    public function CreateGroup(Request $request)
    {
        // dd($request->all());
        $this->validate(
            $request,
            [
                "group_image" => "mimes:jpg,jpeg,png|max:2048",
                "group_title" => "required|unique:gmail_group,title",
                "recipient" => "required|array|min:1",
            ],
            [
                "recipient.required" =>
                    "You must select at least one recipient.",
                "recipient.min" => "You must select at least one recipient.",
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

            return response()->json(["success" => "Group created Sucessfully"]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function EditGroupModel(Request $request)
    {
        $group_id = $request->group_id;
        $group = GmailGroupModel::where("id", $group_id)->first();
        $receptiants = GmailGroupReceptiantsModel::where(
            "gmail_group_id",
            $group_id
        )->pluck("user_id");

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

        $group_info = ["group" => $group, "receptiants" => $receptiants];

        return response()->json([
            "group_info" => $group_info,
            "group_types" => $group_types,
            "group_recipients" => $group_recipients,
            "layout" => "edit",
        ]);
    }

    public function UpdateGroup(Request $request)
    {
        // dd($request->all());
        $this->validate(
            $request,
            [
                "group_image" => "required",
                "group_image" => "mimes:jpg,jpeg,png|max:2048",
                "group_title" => "required",
                "recipient" => "required|array|min:1",
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
            $id = $request->group_id;
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

            return response()->json(["success" => "Group updated Sucessfully"]);
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
        }
    }

    public function DeleteGroup(Request $request)
    {
        // $group_id = $request->query('group_id',0);
        $group_id = $request->group_id;

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
        return response()->json(["success" => "Group Deleted Successfully"]);
    }

    public function GmailGroupList()
    {
        $user_id = \Auth::user()->id;

        $users_groups = GmailGroupReceptiantsModel::where(
            "user_id",
            $user_id
        )->pluck("gmail_group_id");
        $groups = GmailGroupModel::whereIn("id", $users_groups)->get();

        $group_list = [];

        foreach ($groups as $group) {
            if ($group->creater == $user_id) {
                $eleigible = "1";
            } else {
                $eleigible = "0";
            }

            $group_list[] = [
                "id" => $group->id,
                "title" => $group->title,
                "descripition" => $group->descripition,
                "image" => $group->image,
                "eligible" => $eleigible,
            ];
        }

        return response()->json(["group_list" => $group_list]);
    }

    public function GroupMessage(Request $request)
    {
        try {
            DB::beginTransaction();
            $user_id = $request->user()->id;
            $group_id = $request->group_id;
            $message = $request->message;
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

                $msg_info = [
                    "group_id" => $group_id,
                    "message" => $message,
                    "user" => $user,
                    "time" => $msg_time,
                    "file_paths" => $file_paths,
                ];
                DB::commit();
                return response()->json([
                    "success" => "message sent successfully",
                    "user_id" => $user_id,
                    "message_info" => $msg_info,
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            $message = str_replace(
                ["\r", "\n", "'", "`"],
                " ",
                $e->getMessage()
            );
            // dd($message);
            return $this->error($message, 500);
        }
    }

    public function GroupInfo(Request $request)
    {
        $id = $request->group_id;

        $group = GmailGroupModel::where("id", $id)
            ->select("id", "title", "descripition", "image")
            ->get();

        $members = GmailGroupReceptiantsModel::where("gmail_group_id", $id)
            ->select("user_id")
            ->get();
        $group_members = [];
        foreach ($members as $member) {
            $user = UserModel::where("id", $member->user_id)
                ->select("id", "name", "email")
                ->first();
            $group_members[] = [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
            ];
        }

        $messages = GmailGroupMessages::with("username")
            ->where("gmail_group_id", $id)
            ->get();
        $messages_info = [];
        foreach ($messages as $message) {
            $date = $message->created_at;
            $dateTime = new DateTime($date);
            $year = $dateTime->format("Y");
            $currentYear = date("Y");
            $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

            if ($isCurrentDay) {
                $formattedDate = $message->time;
            } elseif ($year == $currentYear) {
                $formattedDate = $dateTime->format("F d");
            } else {
                $formattedDate = $dateTime->format("F d, Y");
            }

            $messages_info[] = [
                "id" => $message->id,
                "senter_id" => $message->userid,
                "senter_name" => $message->username->name,
                "senter_gmail" => $message->username->email,
                "message" => $message->message,
                "files" => json_decode($message->files),
                "date" => $formattedDate,
            ];
        }
        return response()->json([
            "group_info" => $group,
            "members" => $group_members,
            "messages" => $messages_info,
        ]);
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
                $to_users = UserModel::whereIn("id", $request->to_users)->get();
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
                    $data = [
                        "name" => $to_user->name,
                        "content" => $message_info,
                        "files" => $file_paths,
                        "subject" => $request->subject,
                        "fromEmail" => $from_user->email,
                        "fromName" => $from_user->name,
                    ];
                    // dd($senter_mail,$senter_name);
                    // \CmsMail::setMailTrapConfig();
                    \CmsMail::setMailConfig();
                    $mail = Mail::to($to_user->email)->send(
                        new Externalmail(
                            $data["name"],
                            $data["content"],
                            $data["files"],
                            $data["subject"],
                            $data["fromEmail"],
                            $data["fromName"]
                        )
                    );
                }
            }

            DB::commit();
            return response()->json(["Status" => "Message Sent Successfully"]);
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

    public function ExternalSent(Request $request)
    {
        $user_id = \Auth::user()->id;
        $deleted_ids = DeletedMessagesModel::where("user_id", $user_id)->pluck(
            "message_id"
        );

        $external_sent_messages = GmailIndividualMessages::with(
            "reciver",
            "senter"
        )
            ->where("external_message", "=", "1")
            ->where("from_id", $user_id)
            ->where("is_draft", "!=", "1")
            ->whereNotIn("id", $deleted_ids)
            ->orderBy("id", "desc")
            ->get();
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

        $external_sent_messages_list = [];

        foreach ($external_sent_messages as $message) {
            $recivers = $external_reciver_det
                ->where("message_id", $message->id)
                ->pluck("user_id");

            $date = $message->created_at;
            $dateTime = new DateTime($date);
            $year = $dateTime->format("Y");
            $currentYear = date("Y");
            $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

            if ($isCurrentDay) {
                $formattedDate = $message->time;
            } elseif ($year == $currentYear) {
                $formattedDate = $dateTime->format("F d");
            } else {
                $formattedDate = $dateTime->format("F d, Y");
            }

            $file_paths = json_decode($message->files);

            $sent_messages_list[] = [
                "message_id" => $message->id,
                "sender_id" => $message->senter->id,
                "receivers" => $recivers,
                "subject" => $message->subject,
                "message" => $message->message,
                "files" => $file_paths,
                "date" => $formattedDate,
            ];
        }
        $page = request()->get("page", 1); // Get the current page or default to 1
        $perPage = 4; // Items per page

        $collection = collect($sent_messages_list);
        $currentPageItems = $collection
            ->slice(($page - 1) * $perPage, $perPage)
            ->values();

        $paginator = new LengthAwarePaginator(
            $currentPageItems,
            $collection->count(),
            $perPage,
            $page,
            ["path" => request()->url(), "query" => request()->query()]
        );
        return response()->json(["external_sent_messages" => $paginator]);
    }

    public function GmailMessage(Request $request)
    {
        $user_id = \Auth::user()->id;
        $msg_id = $request->message_id;

        $message = GmailIndividualMessages::with("senter")
            ->where("id", $msg_id)
            ->first();

        $replys = GmailIndividualMessageMappingModel::with("senter_info")
            ->where("message_id", $msg_id)
            ->get();

        $receivers = GmaliIndividualMessageReceiversModel::with("reciver_info")
            ->where("message_id", $msg_id)
            ->get();

        $reply_messages = [];

        foreach ($replys as $reply) {
            if ($reply->senter == $user_id) {
                $date = $reply->created_at;
                $dateTime = new DateTime($date);
                $year = $dateTime->format("Y");
                $currentYear = date("Y");
                $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

                if ($isCurrentDay) {
                    $formattedDate = $reply->time;
                } elseif ($year == $currentYear) {
                    $formattedDate = $dateTime->format("F d");
                } else {
                    $formattedDate = $dateTime->format("F d, Y");
                }
                if ($reply->files !== null) {
                    $reply_files = json_decode($reply->files);
                    $reply_files_info = [];
                    foreach ($reply_files as $file) {
                        $reply_files_info[] = ["file_name" => $file];
                    }
                } else {
                    $reply_files_info = null;
                }

                $reply_messages[] = [
                    "senter" => $reply->senter,
                    "senter_name" => $reply->senter_info->name,
                    "senter_image" => $reply->senter_info->images,
                    "message" => $reply->message,
                    "files" => $reply_files_info,
                    "date" => $formattedDate,
                ];
            } else {
                $date = $reply->created_at;
                $dateTime = new DateTime($date);
                $year = $dateTime->format("Y");
                $currentYear = date("Y");
                $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

                if ($isCurrentDay) {
                    $formattedDate = $reply->time;
                } elseif ($year == $currentYear) {
                    $formattedDate = $dateTime->format("F d");
                } else {
                    $formattedDate = $dateTime->format("F d, Y");
                }

                if ($reply->files !== null) {
                    $reply_files = json_decode($reply->files);
                    $reply_files_info = [];
                    foreach ($reply_files as $file) {
                        $reply_files_info[] = ["file_name" => $file];
                    }
                } else {
                    $reply_files_info = null;
                }

                $reply_messages[] = [
                    "senter" => $reply->senter,
                    "senter_name" => $reply->senter_info->name,
                    "senter_image" => $reply->senter_info->images,
                    "message" => $reply->message,
                    "files" => $reply_files_info,
                    "date" => $formattedDate,
                ];
            }
        }

        $receiver_info = [];

        foreach ($receivers as $receiver) {
            $receiver_info[] = [
                "id" => $receiver->user_id,
                "name" => $receiver->reciver_info->name,
                "gmail" => $receiver->reciver_info->email,
            ];
        }
        $date = $message->created_at;
        $dateTime = new DateTime($date);
        $year = $dateTime->format("Y");
        $currentYear = date("Y");
        $isCurrentDay = $dateTime->format("Y-m-d") === date("Y-m-d");

        if ($isCurrentDay) {
            $formattedDate = $message->time;
        } elseif ($year == $currentYear) {
            $formattedDate = $dateTime->format("F d");
        } else {
            $formattedDate = $dateTime->format("F d, Y");
        }
        if ($message->files !== null) {
            $files = json_decode($message->files);
            $files_info = [];
            foreach ($files as $file) {
                $files_info[] = ["file_name" => $file];
            }
        } else {
            $files_info = null;
        }

        $messages = [
            "id" => $message->id,
            "senter" => $message->from_id,
            "senter_name" => $message->senter->name,
            "senter_image" => $message->senter->images,
            "senter_gmail" => $message->senter->email,
            "subject" => $message->subject,
            "message" => $message->message,
            "files" => $files_info,
            "date" => $formattedDate,
        ];

        return response()->json([
            "message_info" => $messages,
            "receivers" => $receiver_info,
            "reply_messages" => $reply_messages,
        ]);
    }

    public function RestoreMessages(Request $request)
    {
        $user_id = \Auth::user()->id;
        foreach ($request->check_ids as $id) {
            $delete = DeletedMessagesModel::where([
                "message_id" => $id,
                "user_id" => $user_id,
            ]);
            $delete->delete();
        }
        return response()->json(["status" => "Message Restored successfully"]);
    }
}
