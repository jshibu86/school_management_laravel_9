<?php

namespace App\Imports;

use Image;
use Exception;
use Carbon\Carbon;
use cms\core\configurations\Traits\FileUploadTrait;
use Configurations;
use Hash;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use cms\core\user\Models\UserModel;
use cms\students\Models\ParentModel;
use cms\students\Models\StudentsModel;
use Maatwebsite\Excel\Concerns\ToModel;
use cms\core\usergroup\Models\UserGroupModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use cms\core\usergroup\Models\UserGroupMapModel;

class StudentImport implements ToModel, WithHeadingRow
{
    use FileUploadTrait;
    /**
     * @param Collection $collection
     */
    public $class_id;
    public $section_id;
    public $count;
    public function model(array $row)
    {
        ++$this->count;

        // Already students

        $is_already = StudentsModel::where("email", $row["email"])->first();

        if (is_null($is_already)) {
            // insert students
            DB::beginTransaction();
            try {
                $student_role = UserGroupModel::where(
                    "group",
                    "Student"
                )->first();
                $parent_role = UserGroupModel::where(
                    "group",
                    "Parent"
                )->first();
                $student_info = StudentsModel::withTrashed()
                    ->latest()
                    ->first();

                $student_password = Configurations::Generatepassword(4);

                $student_username = Configurations::GenerateUsername(
                    $student_info != null ? $student_info->username : null,
                    "S"
                );

                //todo create student user

                $student_user = new UserModel();
                $student_name = $row["first_name"] . " " . $row["last_name"];
                $student_user->name = $student_name;
                $student_user->username = $student_username;
                $student_user->email = $row["email"];
                $student_user->mobile = $row["mobile"];
                $Hash = Hash::make($student_password);
                $student_user->password = $Hash;
                if (isset($row["student_image"])) {
                    $student_user->images =
                        "/school/profiles/" . $row["student_image"];
                }

                if ($student_user->save()) {
                    $usertypemap = new UserGroupMapModel();
                    $usertypemap->user_id = $student_user->id;
                    $usertypemap->group_id = $student_role->id;
                    $usertypemap->save();
                }

                if (isset($row["is_new_parent"])) {
                    if ($row["is_new_parent"] == 1) {
                        // create parent user

                        $parent_info = ParentModel::withTrashed()
                            ->latest()
                            ->first();
                        $parent_username = Configurations::GenerateUsername(
                            $parent_info != null
                                ? $parent_info->username
                                : null,
                            "P"
                        );
                        $parent_password = Configurations::Generatepassword(4);
                        $parent_user = new UserModel();
                        $parent_name = "";

                        if ($row["father_name"]) {
                            $parent_name = $row["father_name"];
                        } else {
                            $parent_name = $parent_username;
                        }

                        $parent_user->name = $parent_name;
                        $parent_user->username = $parent_username;
                        $parent_user->email = $row["father_email"];

                        $parent_user->mobile = $row["father_mobile"];

                        $Hash = Hash::make($parent_password);
                        $parent_user->password = $Hash;
                        if ($row["father_image"]) {
                            $parent_user->images =
                                "/school/profiles/" . $row["father_image"];
                        }
                        if ($parent_user->save()) {
                            $usertypemap = new UserGroupMapModel();
                            $usertypemap->user_id = $parent_user->id;
                            $usertypemap->group_id = $parent_role->id;
                            $usertypemap->save();
                        }
                    }
                    // add student
                    $student = new StudentsModel();
                    $student->user_id = $student_user->id;
                    $student->username = $student_username;
                    $student->academic_year = Configurations::getCurrentAcademicyear();
                    $student->class_id = $this->class_id;
                    $student->section_id = $this->section_id;
                    $student->reg_no = $student_username;
                    $student->roll_no = $row["roll_no"];
                    $student->first_name = $row["first_name"];
                    $student->last_name = $row["last_name"];
                    $student->email = $row["email"];
                    $student->mobile = $row["mobile"];
                    $student->gender = $row["gender"];
                    $student->dob = $row["dob"];
                    $student->blood_group = $row["blood_group"];
                    $student->student_type = $row["student_type"];
                    $student->admission_date = $row["admission_date"];
                    $student->passport_no = $row["passport_no"];
                    $student->national_id_number = $row["national_id_number"];
                    if ($row["student_image"]) {
                        $student->image = $student_user->images;
                    }
                    $student->handicapped = $row["handicapped"];
                    $student->transportation = $row["transportation"];
                    $student->transportation_zone = $row["transportation_zone"];

                    $student->previous_ins_percentage =
                        $row["previous_ins_percentage"];
                    $student->address_check = $row["is_new_parent"];
                    $student->religion = $row["religion"];
                    //getting address communication
                    $address_communication = [
                        "house_no" => $row["house_no"],
                        "street_name" => $row["street_name"],
                        "postal_code" => $row["postal_code"],
                        "province" => $row["province"],
                        "country" => $row["country"],
                    ];
                    $student->address_communication = json_encode(
                        $address_communication
                    );
                    if ($student->save()) {
                        if ($row["is_new_parent"] == 1) {
                            $parent = new ParentModel();
                            $parent->username = $parent_username;
                            $parent->user_id = $parent_user->id;
                            $parent->student_id = $student->id;
                            //save father details
                            $parent->father_name = $row["father_name"];
                            $parent->father_email = $row["father_email"];
                            $parent->father_mobile = $row["father_mobile"];
                            $parent->father_occupation =
                                $row["father_occupation"];
                            if ($row["father_image"]) {
                                $parent->father_image = $parent_user->images;
                            }

                            $parent->fathernat_id = $row["fathernat_id"];

                            $parent->religion = $row["father_religion"];

                            $address_communication = [
                                "house_no" => $row["house_no"],
                                "street_name" => $row["street_name"],
                                "postal_code" => $row["postal_code"],
                                "province" => $row["province"],
                                "country" => $row["country"],
                            ];
                            $parent->address_communication = json_encode(
                                $address_communication
                            );
                            $parent->address_check = $row["is_new_parent"];
                            if ($parent->save()) {
                                DB::statement("SET FOREIGN_KEY_CHECKS=0;");
                                $find_student = StudentsModel::find(
                                    $student->id
                                )->update([
                                    "parent_id" => $parent->id,
                                ]);
                                DB::statement("SET FOREIGN_KEY_CHECKS=1;");
                            }
                        }
                    }
                    DB::commit();
                } else {
                    DB::rollback();
                    throw new Exception(
                        "In Your Excel is_new_parent Feild is Missing"
                    );
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
            // Update students
            DB::beginTransaction();
            try {
                $user_id = StudentsModel::find($is_already->id);
                $parent = ParentModel::where(
                    "id",
                    $user_id->parent_id ? $user_id->parent_id : 0
                )->first();

                if (is_null($parent)) {
                    DB::rollBack();
                    $name_ = $user_id->first_name . $user_id->last_name;
                    $message = "Student $name_ Not Assigen Parent Please Assign Parent";
                    throw new Exception($message);
                } else {
                    $student_user = UserModel::find($user_id->user_id);
                    $student_name =
                        $row["first_name"] . " " . $row["last_name"];
                    $student_user->name = $student_name;

                    $student_user->email = $row["email"];
                    $student_user->mobile = $row["mobile"];
                    if (isset($row["student_image"])) {
                        $this->deleteImage(
                            null,
                            $student_user->images ? $student_user->images : null
                        );
                        $student_user->images =
                            "/school/profiles/" . $row["student_image"];
                    }

                    if ($student_user->save()) {
                        $get_parent = ParentModel::find($user_id->parent_id);
                        $parent_user = UserModel::find($get_parent->user_id);
                        $parent_name = "";

                        $parent_user->name = $row["father_name"]
                            ? $row["father_name"]
                            : $get_parent->father_name;

                        $parent_user->email = $row["father_email"]
                            ? $row["father_email"]
                            : $get_parent->father_email;

                        $parent_user->mobile = $row["father_mobile"]
                            ? $row["father_mobile"]
                            : $get_parent["father_mobile"];

                        if ($row["father_image"]) {
                            $this->deleteImage(
                                null,
                                $parent_user->images
                                    ? $parent_user->images
                                    : null
                            );
                            $parent_user->images =
                                "/school/profiles/" . $row["father_image"];
                        }
                        if ($parent_user->save()) {
                            // add student
                            $student = StudentsModel::find($is_already->id);

                            $student->academic_year = Configurations::getCurrentAcademicyear();
                            $student->class_id = $this->class_id;
                            $student->section_id = $this->section_id;

                            $student->roll_no = $row["roll_no"];
                            $student->first_name = $row["first_name"];
                            $student->last_name = $row["last_name"];
                            $student->email = $row["email"];
                            $student->mobile = $row["mobile"];
                            $student->gender = $row["gender"];
                            $student->dob = $row["dob"];
                            $student->blood_group = $row["blood_group"];
                            $student->student_type = $row["student_type"];
                            $student->admission_date = $row["admission_date"];
                            $student->passport_no = $row["passport_no"];
                            $student->national_id_number =
                                $row["national_id_number"];
                            if ($row["student_image"]) {
                                $student->image = $student_user->images;
                            }
                            $student->handicapped = $row["handicapped"];
                            $student->transportation = $row["transportation"];
                            $student->transportation_zone =
                                $row["transportation_zone"];

                            $student->previous_ins_percentage =
                                $row["previous_ins_percentage"];
                            $student->address_check = $row["is_new_parent"];
                            $student->religion = $row["religion"];
                            //getting address communication
                            $address_communication = [
                                "house_no" => $row["house_no"],
                                "street_name" => $row["street_name"],
                                "postal_code" => $row["postal_code"],
                                "province" => $row["province"],
                                "country" => $row["country"],
                            ];
                            $student->address_communication = json_encode(
                                $address_communication
                            );
                            if ($student->save()) {
                                $parent_ = ParentModel::where(
                                    "id",
                                    $user_id->parent_id
                                )->first();
                                $parent = ParentModel::find(
                                    $user_id->parent_id
                                );

                                $parent->user_id = $parent_user->id;
                                $parent->student_id = $student->id;
                                //save father details
                                $parent->father_name = $row["father_name"]
                                    ? $row["father_name"]
                                    : $parent_->father_name;
                                $parent->father_email = $row["father_email"]
                                    ? $row["father_email"]
                                    : $parent_->father_email;
                                $parent->father_mobile = $row["father_mobile"]
                                    ? $row["father_mobile"]
                                    : $parent_->father_mobile;
                                $parent->father_occupation = $row[
                                    "father_occupation"
                                ]
                                    ? $row["father_occupation"]
                                    : $parent_->father_occupation;
                                if ($row["father_image"]) {
                                    $parent->father_image =
                                        $parent_user->images;
                                }

                                $parent->fathernat_id = $row["fathernat_id"]
                                    ? $row["fathernat_id"]
                                    : $parent_->fathernat_id;

                                $parent->religion = $row["father_religion"]
                                    ? $row["father_religion"]
                                    : $parent_->father_religion;

                                $address_communication = [
                                    "house_no" => $row["house_no"],
                                    "street_name" => $row["street_name"],
                                    "postal_code" => $row["postal_code"],
                                    "province" => $row["province"],
                                    "country" => $row["country"],
                                ];
                                $parent->address_communication = json_encode(
                                    $address_communication
                                );
                                $parent->address_check = $row["is_new_parent"];
                                $parent->save();
                            }
                        }
                    }
                    DB::commit();
                }
            } catch (Exception $e) {
                DB::rollBack();
                $message = str_replace(
                    ["\r", "\n", "'", "`"],
                    " ",
                    $e->getMessage()
                );

                throw new Exception($message);
            }
        }
    }

    public function getcount(): int
    {
        return count([$this->count]);
    }
}
