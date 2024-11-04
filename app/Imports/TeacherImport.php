<?php

namespace App\Imports;

use Hash;
use Mail;
use Image;
use Exception;
use Carbon\Carbon;
use Configurations;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use cms\core\user\Models\UserModel;
use cms\core\user\Mail\PasswordMail;
use cms\teacher\Models\TeacherModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use cms\core\usergroup\Models\UserGroupModel;
use cms\teacher\Models\DepartmentMappingModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\configurations\Traits\FileUploadTrait;

class TeacherImport implements ToModel, WithHeadingRow
{
    use FileUploadTrait;
    /**
     * @param Collection $collection
     */
    public $count;
    public $department;
    public function model(array $row)
    {
        ++$this->count;

        $is_already = TeacherModel::where("email", $row["email"])->first();

        if (is_null($is_already)) {
            try {
                DB::beginTransaction();

                $role = UserGroupModel::where("group", "Teacher")->first();

                $teacher_info = TeacherModel::withTrashed()
                    ->latest("id")
                    ->first();

                $password = Configurations::Generatepassword(4);
                $teacher_username = Configurations::GenerateUsername(
                    $teacher_info != null ? $teacher_info->employee_code : null,
                    "T"
                );

                //  dd($request->all());

                // add user

                $user = new UserModel();
                $user->name = $row["teacher_name"];
                $user->username = $teacher_username;
                $user->email = $row["email"];
                $user->mobile = $row["mobile"];
                $Hash = Hash::make($password);
                $user->password = $Hash;

                if ($user->save()) {
                    DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                    //user role set
                    $usertypemap = new UserGroupMapModel();
                    $usertypemap->user_id = $user->id;
                    $usertypemap->group_id = $role->id;
                    $usertypemap->save();

                    //save Teacher

                    $teacher = new TeacherModel();
                    $teacher->user_id = $user->id;

                    $teacher->email = $row["email"];
                    $teacher->mobile = $row["mobile"];
                    $teacher->work_exp = $row["work_exp"];
                    $teacher->employee_code = $teacher_username;
                    $teacher->teacher_name = $row["teacher_name"];
                    $teacher->gender = $row["gender"];
                    $teacher->dob = $row["dob"];
                    $teacher->national_id_number = $row["national_id_number"];
                    $teacher->qualification = $row["qualification"];

                    $teacher->date_ofjoin = $row["date_ofjoin"];

                    $teacher->reason_forleave = $row["reason_forleave"];
                    $teacher->guardian_name = $row["guardian_name"];
                    $teacher->relation = $row["relation"];
                    $teacher->guardian_mobile = $row["guardian_mobile"];
                    $teacher->blood_group = $row["blood_group"];

                    $teacher->handicapped = $row["handicapped"];
                    $teacher->maritial_status = $row["maritial_status"];
                    $teacher->religion = $row["religion"];
                    $teacher->emp_name = $row["emp_name"];
                    $teacher->job_role = $row["job_role"];
                    $teacher->net_pay = $row["net_pay"];
                    $teacher->location = $row["location"];
                    $teacher->start_date = $row["start_date"];
                    $teacher->end_date = $row["end_date"];

                    $teacher->kin_fullname = $row["kin_fullname"];
                    $teacher->kin_relationship = $row["kin_relationship"];
                    $teacher->kin_phonenumber = $row["kin_phonenumber"];
                    $teacher->kin_email = $row["kin_email"];
                    $teacher->kin_occupation = $row["kin_occupation"];
                    $teacher->kin_religion = $row["kin_religion"];
                    $teacher->kin_address = $row["kin_address"];
                    $teacher->work_exp = $row["work_exp"];

                    //getting address communication
                    $address_communication = [
                        "house_no" => $row["house_no"],
                        "street_name" => $row["street_name"],
                        "postal_code" => $row["postal_code"],
                        "province" => $row["province"],
                        "country" => $row["country"],
                    ];
                    $teacher->address_communication = json_encode(
                        $address_communication
                    );

                    if ($teacher->save()) {
                        if (sizeof($this->department)) {
                            foreach ($this->department as $department) {
                                $dept = new DepartmentMappingModel();

                                $dept->department_id = $department;
                                $dept->teacher_id = $teacher->id;

                                $dept->save();
                            }
                        }
                    }

                    DB::commit();
                    DB::statement("SET FOREIGN_KEY_CHECKS=0;");

                    if (config("app.env") == "production") {
                        \CmsMail::setMailConfig();

                        Mail::to($request->email)->send(
                            new PasswordMail($user, $password)
                        );
                    }
                }
            } catch (Exception $e) {
                $message = str_replace(
                    ["\r", "\n", "'", "`"],
                    " ",
                    $e->getMessage()
                );

                throw new Exception($message);
            }
        } else {
            try {
                DB::beginTransaction();

                $user_id = TeacherModel::find($is_already->id);

                //  dd($request->all());

                // add user

                $user = UserModel::find($user_id->user_id);
                $user->name = $row["teacher_name"];

                $user->email = $row["email"];
                $user->mobile = $row["mobile"];

                if ($user->save()) {
                    DB::statement("SET FOREIGN_KEY_CHECKS=0;");

                    //save Teacher

                    $teacher = TeacherModel::find($is_already->id);

                    $teacher->email = $row["email"];
                    $teacher->mobile = $row["mobile"];
                    $teacher->work_exp = $row["work_exp"];

                    $teacher->teacher_name = $row["teacher_name"];
                    $teacher->gender = $row["gender"];
                    $teacher->dob = date("Y-m-d", strtotime($row["dob"]));
                    $teacher->national_id_number = $row["national_id_number"];
                    $teacher->qualification = $row["qualification"];

                    $teacher->date_ofjoin = date(
                        "Y-m-d",
                        strtotime($row["date_ofjoin"])
                    );

                    $teacher->reason_forleave = $row["reason_forleave"];
                    $teacher->guardian_name = $row["guardian_name"];
                    $teacher->relation = $row["relation"];
                    $teacher->guardian_mobile = $row["guardian_mobile"];
                    $teacher->blood_group = $row["blood_group"];

                    $teacher->handicapped = $row["handicapped"];
                    $teacher->maritial_status = $row["maritial_status"];
                    $teacher->religion = $row["religion"];
                    $teacher->emp_name = $row["emp_name"];
                    $teacher->job_role = $row["job_role"];
                    $teacher->net_pay = $row["net_pay"];
                    $teacher->location = $row["location"];
                    $teacher->start_date = date(
                        "Y-m-d",
                        strtotime($row["start_date"])
                    );
                    $teacher->end_date = date(
                        "Y-m-d",
                        strtotime($row["end_date"])
                    );

                    $teacher->kin_fullname = $row["kin_fullname"];
                    $teacher->kin_relationship = $row["kin_relationship"];
                    $teacher->kin_phonenumber = $row["kin_phonenumber"];
                    $teacher->kin_email = $row["kin_email"];
                    $teacher->kin_occupation = $row["kin_occupation"];
                    $teacher->kin_religion = $row["kin_religion"];
                    $teacher->kin_address = $row["kin_address"];
                    $teacher->work_exp = $row["work_exp"];

                    //getting address communication
                    $address_communication = [
                        "house_no" => $row["house_no"],
                        "street_name" => $row["street_name"],
                        "postal_code" => $row["postal_code"],
                        "province" => $row["province"],
                        "country" => $row["country"],
                    ];
                    $teacher->address_communication = json_encode(
                        $address_communication
                    );

                    if ($teacher->save()) {
                        if (sizeof($this->department)) {
                            DepartmentMappingModel::where(
                                "teacher_id",
                                $is_already->id
                            )->delete();
                            foreach ($this->department as $department) {
                                $dept = new DepartmentMappingModel();

                                $dept->department_id = $department;
                                $dept->teacher_id = $teacher->id;

                                $dept->save();
                            }
                        }
                    }

                    DB::commit();
                    DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                }
            } catch (Exception $e) {
                $message = str_replace(
                    ["\r", "\n", "'", "`"],
                    " ",
                    $e->getMessage()
                );

                throw new Exception($message);
            }
        }
        //
    }

    public function getcount()
    {
        return $this->count;
    }
}
