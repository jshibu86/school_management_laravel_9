<?php
namespace cms\core\configurations\Controllers;

use DB;
use Cms;
use File;
use Session;
use Configurations;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\module\Models\ModuleModel;

//models
use cms\academicyear\Models\AcademicyearModel;
use cms\core\configurations\Models\ConfigurationModel;
use cms\core\configurations\Traits\FileUploadTrait;
use cms\exam\Models\ExamTermModel;
use cms\fees\Mail\FeeCollectionMail;
use cms\fees\Models\FeesModel;
use cms\mark\Models\GradeSystemModel;
use cms\mark\Models\MarkDistributionModel;
use cms\core\configurations\Models\HistoryModel;
use cms\core\usergroup\Models\UserGroupModel;

class ConfigurationsController extends Controller
{
    use FileUploadTrait;
    /********************************Module Configurations *******************************/
    /*
     * redirect module configuration view
     */
    public function module($module_id)
    {
        $config_data = ModuleModel::findorFail($module_id);
        $datas = "";
        if ($config_data->configuration_data) {
            $action = explode("@", $config_data->configuration_data);

            $class = $action[0];
            $function = $action[1];
            $obj = new $class();

            $datas = $obj->$function();
        }

        if ($config_data->configuration_parm) {
            $config_data->configuration_parm = json_decode(
                $config_data->configuration_parm
            );
        }
        return view("configurations::admin.module", [
            "data" => $config_data,
            "datas" => $datas,
        ]);
    }
    /*
     * save module configuration
     */
    public function moduleSave(Request $request)
    {
        $form_data = $request->all();
        $module_id = $form_data["module_id"];
        unset($form_data["_token"]);
        unset($form_data["module_id"]);
        unset($form_data["submit"]);

        $obj = ModuleModel::findorFail($module_id);
        $obj->configuration_parm = json_encode($form_data);
        $obj->save();

        Session::flash("success", "Success");
        return redirect()->back();
    }
    /*
     * module configuration view share module lists
     */
    public function getModuleList(View $view)
    {
        $module_list = ModuleModel::select("name", "id", "type")
            // ->where('type','=',DB::raw('(SELECT COUNT(*) FROM '.DB::getTablePrefix().(new ModuleModel)->getTable().' as b WHERE '.DB::getTablePrefix().(new ModuleModel)->getTable().'.name=b.name)'))
            ->where("status", 1)
            ->get();

        $view->with("module_list", $module_list);
    }
    /********************************** site Configurations ************************************/
    /*
     * redirect to view
     */
    public function site(Request $request)
    {
        // echo Cms::getCurrentTheme();exit;
        if ($request->session()->get("connection") == "central") {
            $list = File::directories(
                base_path() .
                    DIRECTORY_SEPARATOR .
                    "cms" .
                    DIRECTORY_SEPARATOR .
                    Cms::getModulesPath()
            );
            $themes = [];
            foreach ($list as $theme) {
                $ee = explode("\\", $theme);
                if (count((array) $ee) == 1) {
                    $ee = explode("/", $theme);
                }
                if (count((array) $ee) == 1) {
                    $ee = explode(DIRECTORY_SEPARATOR, $theme);
                }
                $themes[end($ee)] = end($ee);
            }
            $data = json_decode(
                @ConfigurationModel::where("name", "=", "site")->first()->parm
            );
            //dd($data);

            $status_data = json_decode(
                @ConfigurationModel::where("name", "=", "site")->first()->parm,
                true
            );
            return view("configurations::admin.admin_site", [
                "data" => $data,
                "themes" => $themes,
            ]);
        } else {
            $list = File::directories(
                base_path() .
                    DIRECTORY_SEPARATOR .
                    "cms" .
                    DIRECTORY_SEPARATOR .
                    Cms::getModulesPath()
            );
            $themes = [];
            foreach ($list as $theme) {
                $ee = explode("\\", $theme);
                if (count((array) $ee) == 1) {
                    $ee = explode("/", $theme);
                }
                if (count((array) $ee) == 1) {
                    $ee = explode(DIRECTORY_SEPARATOR, $theme);
                }
                $themes[end($ee)] = end($ee);
            }

            $data = json_decode(
                @ConfigurationModel::where("name", "=", "site")->first()->parm
            );
            //dd($data);

            $status_data = json_decode(
                @ConfigurationModel::where("name", "=", "site")->first()->parm,
                true
            );

            if (array_key_exists("onboard_sucess_message", $status_data)) {
                $onboardSuccessMessage = $status_data["onboard_sucess_message"];
            } else {
                $onboardSuccessMessage = "";
            }
            if (array_key_exists("admission_exam_status", $status_data)) {
                $admission_exam_status = true;
            } else {
                $admission_exam_status = false;
            }
            if (array_key_exists("emailexamscores", $status_data)) {
                $emailexamscores = true;
            } else {
                $emailexamscores = false;
            }

            //  dd($admission_exam_status);
            $eligible_role_types = [];
            if (@$data->eligible_role_types) {
                foreach (@$data->eligible_role_types as $key => $types) {
                    $eligible_role_types[] = $types;
                }
            }

            $markdata = json_decode(
                @ConfigurationModel::where("name", "=", "mark")->first()->parm
            );

            $feedata = json_decode(
                @ConfigurationModel::where("name", "=", "feestructure")->first()
                    ->parm
            );

            $terms_due = [];
            if ($feedata) {
                if ($feedata->payment_type == 1) {
                    foreach ($feedata->dueinfo as $id => $termdue) {
                        $terms_due[] = [
                            "id" => $id,
                            "date" => $termdue->date,
                            "per" => $termdue->per,
                            "termname" => ExamTermModel::find($id)
                                ->exam_term_name,
                        ];
                    }
                }
            }

            //  dd($terms_due);

            if (@$data->academic_year) {
                $academic_terms = ExamTermModel::where(
                    "academic_year",
                    $data->academic_year
                )
                    ->pluck("exam_term_name", "id")
                    ->toArray();
                $academic_terms_data = ExamTermModel::where(
                    "academic_year",
                    $data->academic_year
                )->get();
            } else {
                $academic_terms = [];
                $academic_terms_data = [];
            }

            $academic_years = Configurations::getAcademicyears();

            $distribution_types = MarkDistributionModel::where(
                "status",
                1
            )->get();

            $grade = GradeSystemModel::where("status", 1)
                ->pluck("grade_sys_name", "id")
                ->toArray();
            $roles = UserGroupModel::where("status", 1)
                ->pluck("group", "id")
                ->toArray();
            $roles_recept = UserGroupModel::where("status", 1)->pluck(
                "group",
                "id"
            );
            $roles_recept->prepend("All", "all");

            // dd($academic_terms);
            //dd($data->admission_exam_status);
            //echo $data->site_online;exit;

            return view("configurations::admin.site", [
                "data" => $data,
                "themes" => $themes,
                "academic_years" => $academic_years,
                "academic_terms" => $academic_terms,
                "distribution_types" => $distribution_types,
                "grade" => $grade,
                "markdata" => $markdata,
                "feedata" => $feedata,
                "terms_due" => $terms_due,
                "roles" => $roles,
                "receptiants" => $roles_recept,
                "academic_terms_data" => $academic_terms_data,
                "eligible_role_types" => $eligible_role_types,
                "admission_exam_status" => $admission_exam_status,
                "emailexamscores" => $emailexamscores,
                "onboardSuccessMessage" => $onboardSuccessMessage,
            ]);
        }
    }
    /*
     * site configuration save
     */
    public function sitesave(Request $request)
    {
        // dd($request->all());

        if ($request->distributiontype) {
            $count = MarkDistributionModel::whereIn(
                "id",
                $request->distributiontype
            )->sum("mark");

            if ($count > 100) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with(
                        "exception_error",
                        "Mark Distribution Maximum Mark Selection 100"
                    );
            }
        }

        // dd($request->role_id,$request->receptiants.$request->role_id[0]);
        $obj = ConfigurationModel::where("name", "=", "site")->first();
        $history = HistoryModel::where(
            "academic_year",
            $request->academic_year
        )->first();
        $markmeta = ConfigurationModel::where("name", "=", "mark")->first();
        $feemeta = ConfigurationModel::where(
            "name",
            "=",
            "feestructure"
        )->first();
        if (count((array) $obj) == 0) {
            $obj = new ConfigurationModel();
        }
        if (count((array) $history) == 0) {
            $history = new HistoryModel();
        }
        if (count((array) $markmeta) == 0) {
            $markmeta = new ConfigurationModel();
        }

        if (count((array) $feemeta) == 0) {
            $feemeta = new ConfigurationModel();
        }

        $form_data = $request->all();

        if ($request->imagec) {
            $form_data["imagec"] = $this->uploadImage(
                $request->imagec,
                "image"
            );
        } else {
            $form_data["imagec"] = $request->old_imagec;
        }
        if ($request->schoolicon) {
            $form_data["schoolicon"] = $this->uploadImage(
                $request->schoolicon,
                "image"
            );
        } else {
            $form_data["schoolicon"] = $request->old_schoolicon;
        }
        if ($request->role_id) {
            $form_data["gmail_role_configurations"] = [];
            foreach ($request->role_id as $key => $role) {
                $receptiants = $request->{"receptiants$role"};
                $form_data["gmail_role_configurations"][] = [
                    "role_id" => $role,
                    "receptiants" => $receptiants,
                ];
            }

            // dd($receptiants);
            //  dd( $form_data["gmail_role_configurations"]);
        }
        //dd($form_data);

        unset($form_data["_token"]);
        unset($form_data["submit_btn"]);

        unset($form_data["distributiontype"]);
        unset($form_data["grade_system"]);

        // save site info

        $obj->name = "site";
        $obj->parm = json_encode($form_data);

        $obj->save();
        if ($obj->save()) {
            unset($form_data["academic_year"]);
            $history->academic_year = $request->academic_year;
            $history->academic_year_history = json_encode($form_data);
            $history->save();
        }

        // save mark info

        $mark_data = [
            "grade_system" => $request->grade_system,
            "mark_distribution" => $request->distributiontype
                ? $request->distributiontype
                : [],
        ];

        $markmeta->name = "mark";
        $markmeta->parm = json_encode($mark_data);
        $markmeta->save();

        // save fee structuredata

        // check this fees type and academic year and term any payment done or not

        if ($request->payment_type || $request->payment_type == 0) {
            $feestructure = [
                "payment_type" => $request->payment_type,
                "dueinfo" =>
                    $request->payment_type == 1
                        ? $request->due_term_dates
                        : $request->full_pay_due,
            ];
            $fees = FeesModel::where([
                "academic_year" => $request->academic_year,
            ])->first();

            if ($fees) {
                // checkalready this academic year pay type donw any payments

                $year = AcademicyearModel::find($request->academic_year)->year;
                $paytype = Configurations::FEEPAYMENTTYPES[$fees->pay_type];

                //  if ($request->payment_type != $fees->pay_type) {
                //     return redirect()
                //         ->back()
                //         ->with(
                //             "exception_error",
                //             "Already Fee Collection Started in this Academic Year you Selected $year with Previously You setup Payment Type $paytype If you change now Already collected fess should be collapsed,or set this payment Type to Next Academic year"
                //         );
                // }

                if (true) {
                    $feemeta->name = "feestructure";
                    $feemeta->parm = json_encode($feestructure);
                    $feemeta->save();
                }
            } else {
                $feemeta->name = "feestructure";
                $feemeta->parm = json_encode($feestructure);
                $feemeta->save();
            }
        }

        //dd($feestructure);

        Session::flash("success", "Success");
        return redirect()->back();
    }
    /******************************** Mail Configuration ***************************************/
    /*
     * redirect to view
     */
    function mail()
    {
        $malier = \CmsMail::getMailerList();

        $mailer_names = [];
        foreach ($malier as $name => $value) {
            $mailer_names[$name] = $name;
        }
        $data = Configurations::getConfig("mail");
        //dd($data);

        return view("configurations::admin.mail", [
            "data" => $data,
            "mailer" => $mailer_names,
        ]);
    }
    /*
     * mail configuratoin save
     */
    function mailsave(Request $request)
    {
        $obj = ConfigurationModel::where("name", "=", "mail")->first();
        if (count((array) $obj) == 0) {
            $obj = new ConfigurationModel();
        }

        $form_data = $request->all();
        unset($form_data["_token"]);
        unset($form_data["submit_btn"]);
        $form_data["from_mail_password"] = base64_encode(
            $form_data["from_mail_password"]
        );

        $obj->name = "mail";
        $obj->parm = json_encode($form_data);
        $obj->save();

        Session::flash("success", "Success");
        return redirect()->back();
    }

    public function HistoryCheck(Request $request)
    {
        if ($request->ajax()) {
            $academic_year = $request->query("academic_year", 0);
            $history = HistoryModel::where("academic_year", $academic_year)
                ->pluck("academic_year_history")
                ->first();
            $data = json_decode($history);
            $payment_type = $data->payment_type;
            $existed = FeesModel::where([
                "academic_year" => $academic_year,
                "pay_type" => $payment_type,
            ])->first();
            if (isset($existed)) {
                $message =
                    "Sorry,The Fees payment of this Academic Year was already started .So unable to change the Payment Type now";
                return response()->json([
                    "data" => $data,
                    "payment_type" => $payment_type,
                    "message" => $message,
                ]);
            } else {
                return response()->json(["info" => "successfully changed"]);
            }
        }
    }
    public function AcademicYearTerms(Request $request)
    {
        $academic_year = $request->query("academic_year", 0);
        $academic_terms = ExamTermModel::where("academic_year", $academic_year)
            ->select("id", "exam_term_name as text")
            ->get();
        $terms = ExamTermModel::where("academic_year", $academic_year)->get();
        return response()->json([
            "academic_terms" => $academic_terms,
            "terms" => $terms,
        ]);
    }

    public function RoleTypes(Request $request)
    {
        if ($request->ajax()) {
            $roles = UserGroupModel::where("status", 1)
                ->pluck("group", "id")
                ->toArray();
            $roles_recept = UserGroupModel::where("status", 1)->pluck(
                "group",
                "id"
            );
            $roles_recept->prepend("All", "all");
            $view = view("configurations::admin.roleadd", [
                "roles" => $roles,
                "receptiants" => $roles_recept,
            ])->render();

            return response()->json(["view" => $view]);
        }
    }
}
