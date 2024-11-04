<?php

namespace cms\core\user\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

use Yajra\DataTables\Facades\DataTables;

//helpers
use DB;
use User;
use Session;
use Cms;
use Roles;
use Carbon\Carbon;
use Plugins;
use Configurations;
use Event;
use Mail;
use CGate;
use cms\core\configurations\Traits\FileUploadTrait;
use Image;
//events
use cms\core\user\Events\UserRegisteredEvent;
//models
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\students\Models\StudentsModel;
use cms\teacher\Models\TeacherModel;
//mail
use cms\core\user\Mail\ForgetPasswordMail;
class UserController extends Controller
{
    use FileUploadTrait;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            CGate::resouce("user");
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $member_id = $request->query->get("member_id", 0);

            // getting all group users
            $user_ids = UserGroupMapModel::where("group_id", $member_id)->pluck(
                "user_id"
            );

            $users = [];

            $users_data = UserModel::where("status", 1)
                ->whereNull("deleted_at")
                ->whereIn("id", $user_ids)
                ->select([
                    "users.id as id",
                    DB::raw(
                        "CONCAT(users.username, ' - ', users.name,'-',users.email) as text"
                    ),
                ])
                ->get();

            foreach ($users_data as $data) {
                $users[$data->id] = $data->username;
            }

            return $users_data;

            // $classes = SubjectModel::select("id", "name as text")

            //     ->where("status", 1)
            //     ->where("class_id", $class_id)
            //     ->orderBy("name", "asc")
            //     ->get();
            // return $classes;
        }
        return view("user::admin.index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $group = UserGroupModel::where("status", 1)
            ->orderBy("group", "Asc")
            ->whereNotIn("id", [3, 4, 5])
            ->pluck("group", "id");
        return view("user::admin.edit", [
            "layout" => "create",
            "group" => $group,
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
        $this->validate($request, [
            "email" => "required|unique:users,email|max:191|email",
            "password" => "required|same:password2",
            "password2" => "required",
            "name" => 'required|regex:/^[a-zA-Z\s]*$/',
            "username" =>
                'required|unique:users,username|max:191|regex:/^[a-zA-Z\s]*$/',
            "mobile" => "required|min:9|numeric",
            "group" => "required|exists:user_groups,id",
        ]);

        try {
            DB::beginTransaction();

            $user = new UserModel();
            $user->name = $request->name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            if (session("connection") == "central") {
                $user->approval_process = $request->approval_process;
            }

            $user->password = Hash::make($request->password);

            if ($request->hasFile("imagec")) {
                $mediaId = $this->uploadUserImage(
                    $request->file("imagec"),
                    "image"
                );
                $user->images = $mediaId;
            }

            $user->save();

            // Map user to group
            $usertypemap = new UserGroupMapModel();
            $usertypemap->user_id = $user->id;
            $usertypemap->group_id = $request->group;
            $usertypemap->save();

            DB::commit();

            // Determine the redirect route and flash message
            if ($request->has("submit_cat_continue")) {
                return redirect()
                    ->route("user.create")
                    ->with(
                        "success",
                        "User saved successfully, you can continue."
                    );
            }

            return redirect()
                ->route("user.index")
                ->with("success", "User saved successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            // Handle the error or log it
            dd($e);
            return redirect()
                ->route("user.create")
                ->with(
                    "error",
                    "Something went wrong! Please try again later."
                );
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
        $data = UserModel::with("group")->find($id);
        //dd($data);
        return view("user::admin.show", ["data" => $data]);
    }

    public function Profile(Request $request)
    {
        $user_id = User::getUser()->id;

        $student = User::getUser()->group[0]["group"];
        if ($student == "Student") {
            $user_data = UserModel::with("student")
                ->where("id", $user_id)
                ->first();
            $user_data->address = json_decode(
                $user_data->student->address_communication
            );
        } else {
            $user_data = UserModel::with("teacher")
                ->where("id", $user_id)
                ->first();
            $user_data->address = json_decode(
                $user_data->teacher->address_communication
            );
        }
        // dd($user_data);
        return view("user::admin.profile", ["data" => $user_data]);
    }

    public function EditProfile(Request $request)
    {
    }

    public function UpdateProfile(Request $request)
    {
        $this->validate(
            $request,
            [
                "imagec" => "mimes:jpg,jpeg,png",
                "house_no" => "required",
                "street_name" => 'required|regex:/^[a-zA-Z\s]*$/',
                "province" => 'required|regex:/^[a-zA-Z\s]*$/',
                "country" => 'required|regex:/^[a-zA-Z\s]*$/',
            ],
            [
                "imagec.mimes" =>
                    "The image must be a file of type: jpg, jpeg, png.",
                "house_no.required" => "The house number is required.",
                "street_name.required" => "The street name is required.",
                "street_name.regex" =>
                    "The street name can only contain letters and spaces.",
                "province.required" => "The province is required.",
                "province.regex" =>
                    "The province can only contain letters and spaces.",
                "country.required" => "The country is required.",
                "country.regex" =>
                    "The country can only contain letters and spaces.",
            ]
        );
        try {
            $user_id = User::getUser()->id;

            $student = User::getUser()->group[0]["group"];
            // dd($request->imagec);
            $user = UserModel::find($user_id);
            if ($request->imagec) {
                $user->images = $this->uploadImage($request->imagec, "image");
                $user->save();
            }
            if ($student == "Student") {
                $student = StudentsModel::where("user_id", $user_id)->first();
                if ($request->imagec) {
                    $student->image = $user->images;
                }
                $address_communication = [
                    "house_no" => $request->house_no,
                    "street_name" => $request->street_name,
                    "postal_code" => $request->postal_code,
                    "province" => $request->province,
                    "country" => $request->country,
                ];
                $student->address_communication = json_encode(
                    $address_communication
                );

                $student->save();
            } else {
                $teacher = TeacherModel::where("user_id", $user_id)->first();
                if ($request->imagec) {
                    $teacher->image = $user->images;
                }
                $address_communication = [
                    "house_no" => $request->house_no,
                    "street_name" => $request->street_name,
                    "postal_code" => $request->postal_code,
                    "province" => $request->province,
                    "country" => $request->country,
                ];
                $teacher->address_communication = json_encode(
                    $address_communication
                );

                $teacher->save();
            }
            Session::flash("success", "Profile updated successfully");
        } catch (\Exception $e) {
            dd($e);
            Session::flash(
                "error",
                "Something went wrong! Please try again later."
            );
        }

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = UserModel::with("group")->find($id);
        // dd($data);
        //print_r($data->group[0]->group);exit;
        $group = UserGroupModel::where("status", 1)
            ->orderBy("group", "Asc")
            ->pluck("group", "id");
        return view("user::admin.edit", [
            "layout" => "edit",
            "group" => $group,
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
            "email" => "required|email|unique:users,email," . $id,
            "password" => "sometimes|same:password2",
            "password2" => "sometimes",
            "name" => 'required|regex:/^[a-zA-Z\s]*$/',
            "username" =>
                'required|regex:/^[a-zA-Z\s]*$/|unique:users,username,' . $id,
            "mobile" => "required|min:9|numeric",
            "group" => "required|exists:user_groups,id",
        ]);

        try {
            $data = UserModel::findOrFail($id);

            // Update user information
            $data->name = mb_convert_case(
                $request->name,
                MB_CASE_TITLE,
                "UTF-8"
            );
            $data->username = $request->username;
            $data->email = $request->email;
            $data->mobile = $request->mobile;

            // Handle image upload or deletion
            if ($request->hasFile("imagec")) {
                // Delete the existing image if it exists
                if ($data->images) {
                    $this->deleteImage(null, $data->images);
                }
                // Upload the new image
                $data->images = $this->uploadImage(
                    $request->file("imagec"),
                    "image"
                );
            } elseif (!$request->imagec && $data->images) {
                // If no new image provided and an image exists, delete it
                $this->deleteImage(null, $data->images);
                $data->images = null;
            }

            // Update password if provided
            if ($request->filled("password")) {
                $data->password = Hash::make($request->password);
            }

            $data->status = 1;
            $data->save();

            // Update user group mapping
            UserGroupMapModel::where("user_id", $id)->delete();
            $usertypemap = new UserGroupMapModel();
            $usertypemap->user_id = $data->id;
            $usertypemap->group_id = $request->group;
            $usertypemap->save();

            // Success message
            Session::flash("success", "User updated successfully");
        } catch (\Exception $e) {
            // Log the error or handle it accordingly
            Session::flash(
                "error",
                "Something went wrong! Please try again later."
            );
        }

        return redirect()->route("user.index");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if (!empty($request->selected_users)) {
            if (($key = array_search(1, $request->selected_users)) !== false) {
                $request->selected_users = array_except(
                    $request->selected_users,
                    [$key]
                );
            }
            $delObj = new UserModel();
            foreach ($request->selected_users as $k => $v) {
                //echo $v;
                if ($delItem = $delObj->find($v)) {
                    $delItem->delete();
                }
            }
        }

        if ($id) {
            $delObj = new UserModel();
            $delItem = $delObj->find($id);
            $delItem->delete();
        }

        Session::flash("success", "User Deleted Successfully!!");
        return redirect()->route("user.index");
    }

    /*
     * *********************additional methods*************************
     */

    /*
     * get user data
     */
    public function getData(Request $request)
    {
        CGate::authorize("view-user");

        $sTart = ctype_digit($request->get("start"))
            ? $request->get("start")
            : 0;

        DB::statement(DB::raw("set @rownum=" . $sTart));

        $data = UserModel::select(
            DB::raw("@rownum  := @rownum  + 1 AS rownum"),
            "users.id as id",
            "users.name",
            "username",
            "email",
            "mobile",
            "user_groups.group as group",
            DB::raw(
                "(CASE WHEN " .
                    DB::getTablePrefix() .
                    (new UserModel())->getTable() .
                    '.status = "0" THEN "Disabled" 
            WHEN ' .
                    DB::getTablePrefix() .
                    (new UserModel())->getTable() .
                    '.status = "-1" THEN "Trashed"
            ELSE "Enabled" END) AS status'
            ),
            "images"
        )
            ->whereNotIn("user_groups.id", [3, 4, 5])
            ->join("user_group_map", "user_group_map.user_id", "=", "users.id")
            ->join(
                "user_groups",
                "user_groups.id",
                "=",
                "user_group_map.group_id"
            )

            ->orderBy("users.created_at", "asc");

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
                        "route" => "user",
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
        CGate::authorize("edit-user");

        if ($request->ajax()) {
            UserModel::find($request->id)->update([
                "status" => $request->status,
            ]);
            return response()->json([
                "success" => "Status change successfully.",
            ]);
        }

        if (!empty($request->selected_users)) {
            if (($key = array_search(1, $request->selected_users)) !== false) {
                $request->selected_users = array_except(
                    $request->selected_users,
                    [$key]
                );
            }

            $obj = new UserModel();
            foreach ($request->selected_users as $k => $v) {
                //echo $v;
                if ($item = $obj->find($v)) {
                    $item->status = $request->action;
                    $item->save();
                }
            }
        }

        Session::flash("success", "User Status changed Successfully!!");
        return redirect()->back();
    }
    /*
     * user registration from frond end using ajax
     */
    public function ajaxRegister(Request $request)
    {
        $this->validate($request, [
            "email" => "required|unique:users,email|email|max:191",
            "password" => "required|min:4",
            "username" => "required|unique:users,username|max:191",
        ]);

        $data = new UserModel();
        $data->name = mb_convert_case(
            $request->username,
            MB_CASE_TITLE,
            "UTF-8"
        );
        $data->username = $request->username;
        $data->email = $request->email;

        $Hash = Hash::make($request->password);
        $data->password = $Hash;

        $config = Configurations::getParm("user", 1);
        $verification_type = $config->register_verification;
        if ($verification_type == 0) {
            $data->status = 1;
        } else {
            $data->status = 0;
        }

        $data->remember_token = md5(time() . rand());

        if ($data->save()) {
            $usertypemap = new UserGroupMapModel();
            $usertypemap->user_id = $data->id;
            $usertypemap->group_id = 2;
            $usertypemap->save();
            Event::fire(new UserRegisteredEvent($data->id));
            $msg = "Users save successfully,Please Chack Your Mail Id";
        } else {
            $msg = "Something went wrong !! Please try again later !!";
        }
        $url = @Configurations::getParm("user", 1)->login_redirection_url;
        if (!$url) {
            $url = route("home");
        } else {
            $url = url("/") . $url;
        }

        return ["status" => 1, "message" => $msg, "url" => $url];
    }
    /*
     * user registration from frond end
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            "email" => "required|unique:users,email|email|max:191",
            "password" => "required",
            "username" => "required|unique:users,username|max:191",
        ]);

        if (Configurations::getParm("user", 1)->allow_user_registration != 1) {
            Session::flash("error", "Register is blocked");
            return redirect()->route("home");
        }
        $this->validate($request, [
            "email" => "required|unique:users,email|max:191",
            "password" => "required|min:4",
            "username" => "required|unique:users,username|max:191",
        ]);

        $data = new UserModel();
        $data->name = mb_convert_case(
            $request->username,
            MB_CASE_TITLE,
            "UTF-8"
        );
        $data->username = $request->username;
        $data->email = $request->email;

        $Hash = Hash::make($request->password);
        $data->password = $Hash;
        $config = Configurations::getParm("user", 1);
        $verification_type = @$config->register_verification;
        if ($verification_type == 0) {
            $data->status = 1;
        } else {
            $data->status = 0;
        }

        $data->remember_token = md5(time() . rand());

        if ($data->save()) {
            $usertypemap = new UserGroupMapModel();
            $usertypemap->user_id = $data->id;
            $usertypemap->group_id = 2;
            $usertypemap->save();
            Event::fire(new UserRegisteredEvent($data->id));
            $msg = "Users save successfully,Please Chack Your Mail Id";
        } else {
            $msg = "Something went wrong !! Please try again later !!";
        }

        Session::flash("success", $msg);
        return redirect()->back();

        //return ['status'=>1,'message'=>$msg];
    }
    /*
     * user login using ajax
     */
    public function ajaxLogin(Request $request)
    {
        $this->validate($request, [
            "username" => "required|exists:users,username|max:191",
            "password" => "required",
        ]);

        $user = User::check([
            "username" => $request->username,
            "password" => $request->password,
            "status" => 1,
        ]);

        if ($user) {
            $users = UserModel::where(
                "username",
                "=",
                $request->username
            )->first();
            Session::put([
                "ACTIVE_USER" => strval($users->id),
                "ACTIVE_USERNAME" => $users->username,
                "ACTIVE_EMAIL" => $users->email,
            ]);
            //change offline to online
            $users->online = 1;
            $users->ip = request()->ip();
            $users->lastactive = Carbon::now();
            $users->save();

            $url = @Configurations::getParm("user", 1)->login_redirection_url;

            if (!$url) {
                $url = route("home");
            } else {
                $url = url("/") . $url;
            }

            return ["status" => 1, "message" => "Success", "url" => $url];
        } else {
            return [
                "status" => 0,
                "message" => "user name and password is missmatch",
            ];
        }
    }
    /*
     * user login
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            "username" => "required|exists:users,username|max:191",
            "password" => "required",
        ]);

        $user = User::check([
            "username" => $request->username,
            "password" => $request->password,
            "status" => 1,
        ]);

        if ($user) {
            $users = UserModel::where(
                "username",
                "=",
                $request->username
            )->first();
            Session::put([
                "ACTIVE_USER" => strval($users->id),
                "ACTIVE_USERNAME" => $users->username,
                "ACTIVE_EMAIL" => $users->email,
            ]);
            //change offline to online
            $users->online = 1;
            $users->ip = request()->ip();
            $users->lastactive = Carbon::now();
            $users->save();

            $url = @Configurations::getParm("user", 1)->login_redirection_url;

            if (!$url) {
                $url = route("home");
            } else {
                $url = url("/") . $url;
            }

            Session::flash("success", "Login Successfull");
            return redirect($url);
        } else {
            Session::flash("error", "user name or password is missmatch");
            return redirect()->back();
        }
    }
    /*
     * activate user
     */
    public function activate($token)
    {
        $users = UserModel::where("remember_token", "=", $token)->first();
        if (count((array) $users)) {
            $users->status = 1;
            $users->remember_token = "";
            $users->save();
            Session::flash("success", "Account activated Successfully");
        } else {
            Session::flash("error", "Wrong Datas");
        }

        return redirect()->route("home");
    }
    /*
     * forget password
     */
    public function forgetPassword(Request $request)
    {
        $users = UserModel::with("group")
            ->where("email", "=", $request->email)
            ->first();
        if (count((array) $users)) {
            $user_group = User::getUserGroup($users->id);

            if (in_array(1, $user_group)) {
                return ["status" => 0, "message" => "Restricted Area"];
            }
            $users->remember_token = md5(time() . rand());
            $users->save();
            \CmsMail::setMailConfig();
            Mail::to($users->email)->queue(new ForgetPasswordMail($users));
            Session::flash("success", "Please Check Your Mail");

            if ($request->ajax()) {
                return ["status" => 1, "message" => "Please Check Your Mail"];
            }
        } else {
            Session::flash("error", "Wrong Email");
        }

        if ($request->ajax()) {
            return ["status" => 0, "message" => "Wrong Email"];
        }

        return redirect()->route("home");
    }
    /*
     * verifyForgetPassword from mail
     */
    public function verifyForgetPassword($token)
    {
        $users = UserModel::where("remember_token", "=", $token)->first();
        if (count((array) $users)) {
            return view("user::site.password_change", ["token" => $token]);
        } else {
            Session::flash("error", "Wrong Datas,Please Try agin Later");
        }

        return redirect()->route("home");
    }
    public function dochangePassword(Request $request)
    {
        $this->validate($request, [
            "password" => "required|same:re-enter-password",
            "re-enter-password" => "required",
            "token" => "required",
        ]);

        $users = UserModel::where(
            "remember_token",
            "=",
            $request->token
        )->first();
        if (count((array) $users)) {
            $users->remember_token = "";
            $Hash = Hash::make($request->password);
            $users->password = $Hash;
            $users->save();
            Session::flash("success", "Password Update Successfully");
        } else {
            Session::flash("error", "Wrong Datas,Please Try agin Later");
        }

        return redirect()->route("home");
    }
    /*
     * user logout
     */
    public function logout(Request $request)
    {
        $user = User::getUser();
        $users = UserModel::find($user->id);

        //change online to offline
        $users->online = 1;
        $users->ip = request()->ip();
        $users->lastactive = Carbon::now();
        $users->save();

        $request->session()->flush();

        $url = @Configurations::getParm("user", 1)->logout_redirection_url;
        if (!$url) {
            $url = "/";
        }
        Session::flash("success", "Logout Successfull");
        return redirect($url);
    }
    /*
     * my account page
     */
    public function account()
    {
        $user = User::getUser();

        return view("user::site.user", ["data" => $user]);
    }
    /*
     * update account
     */
    public function updateAccount(Request $request)
    {
        $id = User::getUser()->id;
        $this->validate($request, [
            "email" => "required|email|unique:users,email," . $id,
            "password" => "sometimes",
            "name" => "required",
            "username" => "required|unique:users,username," . $id,
            "mobile" => "min:9|max:15",
        ]);

        $data = UserModel::find($id);
        $data->name = mb_convert_case($request->name, MB_CASE_TITLE, "UTF-8");
        $data->username = $request->username;
        $data->email = $request->email;
        if ($request->mobile) {
            $data->mobile = $request->mobile;
        }
        if ($request->image) {
            $user_obj = new User();
            $img = $user_obj->imageCreate(
                $request->image,
                "user" . DIRECTORY_SEPARATOR
            );
            $data->images = $img;
        }
        if ($request->password) {
            $Hash = Hash::make($request->password);
            $data->password = $Hash;
        }

        if ($data->save()) {
            $msg = "Account updated successfully";
            $class_name = "success";
        } else {
            $msg = "Something went wrong !! Please try again later !!";
            $class_name = "error";
        }

        Session::flash($class_name, $msg);
        return redirect()->back();
    }
    /*
     * configurations option
     */
    public function getConfigurationData()
    {
        $group = UserGroupModel::where("status", 1)
            ->where("id", "!=", 1)
            ->orderBy("group", "Asc")
            ->pluck("group", "id");

        return ["user_group" => $group];
    }
}
