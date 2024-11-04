<?php

namespace cms\core\admin\Controllers;

use User;
use Hash;
use Session;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Configurations;

//models
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use App\Http\Controllers\Controller;
use cms\homework\Models\HomeworkModel;
use cms\core\schoolmanagement\Models\SchoolProfile;
use cms\students\Models\ParentModel;
use cms\students\Models\StudentsModel;
use cms\teacher\Models\TeacherModel;
use Ramesh\Cms\Facades\Cms;
use cms\core\subscription\Models\SubscriptionModel;

class AdminAuth extends Controller
{
    public function showLoginForm(Request $request)
    {
        if ($request->has("redirect")) {
            session(["url.intended" => $request->redirect]);
        } else {
            session(["url.intended" => url()->previous()]);
        }
        return view("admin::login");
    }
    /*
     * back end login
     */
    public function login()
    {
        //dd(Configurations::getCoreModuleMigrationPath());
        $theme = Configurations::getCurrentTheme();
        //dd($theme);
        $view_path = "";

        if ($theme == "theme2") {
            $view_path = "admin::login2";
        } elseif ($theme == "theme3") {
            $view_path = "admin::login3";
        } elseif ($theme == "theme4") {
            $view_path = "admin::login4";
        } else {
            $view_path = "admin::login";
        }
        if (session("inactive_error")) {
            $inactive = 1;
        } else {
            $inactive = 0;
        }
        return view($view_path, ["inactive" => $inactive]);
    }
    /*
     * back end do login
     */
    public function dologin(Request $request)
    {
        // dd("here");
        // $users = UserModel::all();
        // dd($users);
        $this->validate($request, [
            "username" => "required|exists:users,username|max:191",
            "password" => "required",
        ]);

        $users = UserModel::withTrashed()
            ->where("username", "=", $request->username)
            ->first();

        if ($users->trashed() || $users->status == -1) {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors([
                    "Sorry ! Your Account is trashed or Deleted State Contact Administrator",
                ]);
        }

        $user = User::check([
            "username" => $request->username,
            "password" => $request->password,
        ]);

        if ($user) {
            $users = UserModel::where(
                "username",
                "=",
                $request->username
            )->first();

            $groupmapmodel = UserGroupMapModel::where(
                "user_id",
                "=",
                $users->id
            )->first();

            $group = UserGroupModel::where(
                "id",
                "=",
                $groupmapmodel->group_id
            )->first();

            // dd($group);
            Session::put([
                "ACTIVE_USER" => strval($users->id),
                "ACTIVE_USERNAME" => $users->username,
                "ACTIVE_GROUP" => $group->group,
                "ACTIVE_EMAIL" => $users->email,
                "ACTIVE_MOBILE" => $users->mobile,
                "ACTIVE_USERIMAGE" => $users->images,
            ]);

            //change offline to online
            $users->online = 1;
            $users->ip = request()->ip();
            $users->lastactive = Carbon::now();
            $users->save();
            $intendedUrl = session("url.intended");
            if ($intendedUrl && $intendedUrl == route("fees.create")) {
                return redirect()->route("fees.create");
            } else {
                return redirect()->route("backenddashboard");
            }
        } else {
            return redirect()
                ->back()
                ->withInput($request->input())
                ->withErrors(["Wrong Information"]);
        }
    }
    /*
     * back end dashboard
     */
    public function dashboard()
    {
        // dd(Session::get("connection"));
        if (Session::get("connection") == "central") {
            //if central db connection
            $schoolcount = SchoolProfile::count();
            $activecount = SchoolProfile::where("status", 1)->count();
            $inactivecount = SchoolProfile::where("status", 0)->count();
            $plans = SubscriptionModel::where("status", 1)->get();
            if (Session::get("ACTIVE_GROUP") == "Super Admin") {
                return view(
                    "admin::superadmin-dashboard",
                    compact(
                        "schoolcount",
                        "activecount",
                        "inactivecount",
                        "plans"
                    )
                );
            }

            return view(
                "admin::superadmin-dashboard",
                compact("schoolcount", "activecount", "inactivecount")
            );
        } else {
            $stucount = StudentsModel::where("status", 1)->count();
            $parentcount = ParentModel::where("status", 1)->count();
            $staffcount = TeacherModel::where("status", 1)->count();
            if (Session::get("ACTIVE_GROUP") == "Super Admin") {
                return view(
                    "admin::main",
                    compact("stucount", "parentcount", "staffcount")
                );
            }

            if (Session::get("ACTIVE_GROUP") == "Student") {
                $active_student = Configurations::Activestudent();

                $homeworks = HomeworkModel::where([
                    "class_id" => $active_student->class_id,
                    "status" => 1,
                ])->count();
                return view("admin::main", compact("homeworks"));
            }

            return view(
                "admin::main",
                compact("stucount", "parentcount", "staffcount")
            );
        }
    }
    /*
     * back end log out
     *
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

        Session::flash("success", "Logout Successfull");
        return redirect("administrator/login");
    }

    public function PlanList(Request $request)
    {
        $plans = SubscriptionModel::where("status", 1)->get();
        $total_count = SchoolProfile::where("status", 1)->count();
        $result = [];
        foreach ($plans as $plan) {
            $plan_count = SchoolProfile::where([
                "status" => 1,
                "plan_id" => $plan->id,
            ])->count();
            $plan_percent = ($plan_count / $total_count) * 100;

            $result[] = [
                "id" => $plan->id,
                "name" => $plan->plan_name,
                "percentage" => $plan_percent,
            ];
        }

        return response()->json(["result" => $result]);
    }
}
