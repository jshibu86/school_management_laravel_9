<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\GeneralJsonException;
use Session;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Otpverification;
use Illuminate\Http\JsonResponse;
use cms\core\user\Models\UserModel;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\AuthTrait;
use cms\core\user\Models\OtpVerificationModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\usergroup\Models\UserGroupModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use cms\students\Models\StudentsModel;
use cms\attendance\Models\StudentAttendanceModel;
use cms\attendance\Models\AttendanceModel;
use cms\academicyear\Models\AcademicyearModel;
use cms\core\configurations\helpers\Configurations;
use cms\fees\Models\FeesModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use cms\subject\Models\SubjectTeacherMapping;
use cms\teacher\Models\TeacherModel;
use cms\classteacher\Models\ClassteacherModel;

/**
 * @group Authencation management
 *
 * APIs for managing users Authentication
 */

class AuthController extends Controller
{
    use ApiResponse, AuthTrait;

    /**
     * Getting Roles Information.
     */

    public function MobileappRoles(Request $request)
    {
        $roles = Configurations::MOBILEAPPROLES;

        $groups = UserGroupModel::whereIn("group", $roles)
            ->select("id", "group")
            ->get();

        return $this->success($groups, "Group fetched Successfully", 200);
    }

    /**
     * User Login.
     * @bodyParam role_id int required The id of the user. Example: 9
     * @bodyParam user_name string Example:SMS2023016.
     * @bodyParam password string
     */
    public function login(Request $request)
    {
        $request->validate(
            [
                "role_id" => "required",
                "user_name" => "required",
                "password" => "required",
            ],
            [
                "role_id.required" => "Please Provide Role",
                "user_name.required" => "Please Provide User Name",
            ]
        );

        $user = UserModel::where("username", $request->user_name)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error("The Provided Credential are Incorrect", 422);
        }

        // check this user has particular role
        $is_user_role_exists = UserGroupMapModel::where([
            "user_id" => $user->id,
            "group_id" => $request->role_id,
        ])->first();

        if ($is_user_role_exists) {
            $token = $user->createToken($user->username)->plainTextToken;

            return new JsonResponse([
                "token" => $token,
                "user" => $user,
                "token_type" => "Bearer",
            ]);
        } else {
            return $this->error("This User not a Role of Selected Role", 400);
        }
    }

    // forgot password

    public function ForgotPassword(Request $request)
    {
        $request->validate(
            [
                "user_name" => "required",
            ],
            [
                "user_name.required" => "Please Provide User Name",
            ]
        );

        try {
            // fetching information about user_name

            $user_info = UserModel::Where(
                "username",
                $request->user_name
            )->first();

            if (!$user_info) {
                return $this->error(
                    "We can't Find Information About This UserName $request->user_name",
                    400
                );
            }

            $otp = $this->SendotpMobile($user_info->mobile);

            $otp["user_name"] = $request->user_name;

            return $this->success($otp, $otp["message"], $otp["code"]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 400);
        }
    }

    public function ForgotOtpConfirm(Request $request)
    {
        $request->validate(
            [
                "otp" => "required",
                "phone_number" => "required",
                "user_name" => "required",
            ],
            [
                "otp.required" => "Please Provide Otp",
                "phone_number.required" => "Please Provide Phone Number",
            ]
        );

        if (
            OtpVerificationModel::where("mobile", "=", $request->phone_number)
                ->where("otpverify", "=", $request->otp)
                ->exists()
        ) {
            $phonenumber = $request->phone_number;
            $mytime = date("Y-m-d H:i:s"); // today
            $otp = $request->otp;
            $otpverify = OtpverificationModel::where(
                "otpverify",
                $request->otp
            )->first();
            if ($otpverify->exp_time >= $mytime) {
                $userverify = OtpVerificationModel::where([
                    ["mobile", "=", $request->phone_number],
                    ["otpverify", "=", $request->otp],
                ])->first();
                if ($userverify) {
                    OtpVerificationModel::where(
                        "mobile",
                        "=",
                        $request->phone_number
                    )->update(["otpverify" => null]);

                    return response(
                        [
                            "message" => "Suceesfully Verified",
                            "phonenumber" => $request->phone_number,
                            "username" => $request->user_name,
                        ],
                        200
                    );
                }

                // ok
            } else {
                OtpVerificationModel::where(
                    "mobile",
                    "=",
                    $request->phone_number
                )->update(["otpverify" => null]);
                return response(
                    [
                        "message" => "Your Otp Expired",
                        "phonenumber" => $request->phone_number,
                        "username" => $request->user_name,
                    ],
                    400
                );
            }
        } else {
            return response(
                [
                    "message" => "Please Enter Valid Otp",
                    "phonenumber" => $request->phone_number,
                    "username" => $request->user_name,
                ],
                400
            );
        }
    }

    public function ChangePassword(Request $request)
    {
        $request->validate([
            "password" =>
                "required_with:password_confirmation|string|confirmed",
            "password_confirmation" => "required",
            "phone_number" => "required",
            "user_name" => "required",
        ]);

        try {
            $password = Hash::make($request->password);
            $user = UserModel::where("username", $request->user_name)->first();

            if ($user) {
                UserModel::where("username", $request->user_name)
                    ->where("mobile", "=", $request->phone_number)
                    ->update([
                        "password" => $password,
                    ]);

                return response(
                    [
                        "message" => "Successfully Update Your Password",
                        "user" => $user,
                    ],
                    200
                );
            } else {
                return response()->json(["error" => "User Not Found"], 500);
            }
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function Getuser(Request $request)
    {
        $userdata = $request->user();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();
        $user_details = UserModel::where("id", $request->user()->id)
            ->with([
                "student.parent:id,student_id,username,father_name,father_email",
                "student.parent.wallet:id,parent_id,wallet_amount",
                "teacher",
                "student.class:id,name",
                "student.section:id,name",
                "student.librarysubscribed:id,student_id",
                "student.hostelsubscribed:id,student_id",
                "student.department:id,dept_name",
            ])
            ->get();
        $user_id = $request->user()->id;
        $role_id = UserGroupMapModel::where("user_id", $user_id)
            ->pluck("group_id")
            ->first();
        $role = UserGroupModel::where("id", $role_id)
            ->pluck("group")
            ->first();
        // Remove slashes from the address communication field
        if ($role == "Student") {
            $user_address = json_decode(
                $user_details[0]["student"]["address_communication"]
            );
        }
        if ($role == "Teacher") {
            $user_address = json_decode(
                $user_details[0]["teacher"]["address_communication"]
            );
        }
        // dd($user_address);
        $address_info = [
            "house_no" => $user_address->house_no,
            "street_name" => $user_address->street_name,
            "postal_code" => $user_address->postal_code,
            "province" => $user_address->province,
            "country" => $user_address->country,
        ];

        $user_details->transform(function ($item, $key) {
            if (
                isset($item["student"]) &&
                isset($item["student"]["address_communication"])
            ) {
                $address_communication = json_decode(
                    $item["student"]["address_communication"],
                    true
                );
                $formatted_address = implode(", ", $address_communication);
                $item["student"]["address_communication"] = $formatted_address;
            } else {
                $address_communication = json_decode(
                    $item["teacher"]["address_communication"],
                    true
                );
                $formatted_address = implode(", ", $address_communication);
                $item["teacher"]["address_communication"] = $formatted_address;

                $address_residence = json_decode(
                    $item["teacher"]["address_residence"],
                    true
                );
                $formatted_address_residence = implode(
                    ", ",
                    $address_residence
                );
                $item["teacher"][
                    "address_residence"
                ] = $formatted_address_residence;
            }
            return $item;
        });

        if ($role == "Student") {
            $user = StudentsModel::where("user_id", $user_id)->first();

            //Attendance Percentage
            $attendance_ids = AttendanceModel::where([
                "academic_year" => $user->academic_year,
                "class_id" => $user->class_id,
                "section_id" => $user->section_id,
            ])->pluck("id");
            $total_attendance = $attendance_ids->count();
            $attendance_student = 0;
            if ($total_attendance > 0) {
                $attendance_student = StudentAttendanceModel::where(
                    "student_id",
                    $user->id
                )
                    ->whereIn("attendance_id", $attendance_ids)
                    ->where("attendance", "=", "1")
                    ->count();
            }
            $attendance_percentage =
                $total_attendance > 0
                    ? round(($attendance_student / $total_attendance) * 100)
                    : 0;
            $attendance = [
                "title" => "Attendance",
                "percentage" => $attendance_percentage,
            ];

            //Fees Percentage
            $currentFeePayType = Configurations::getCurrentFeePaymentType();
            if ($currentFeePayType == 0) {
                $payment_type =
                    Configurations::FEEPAYMENTTYPES[$currentFeePayType];
                $current_academic_year = Configurations::getCurrentAcademicyear();
                $academic_dates = AcademicyearModel::where(
                    "id",
                    $current_academic_year
                )
                    ->select("start_date", "end_date")
                    ->first();

                $start = strtotime($academic_dates->start_date);
                $end = strtotime($academic_dates->end_date);

                $months = [];

                while ($start <= $end) {
                    $months[] = date("F Y", $start);
                    $start = strtotime("+1 month", $start);
                }
                $fees_paid = [];
                foreach ($months as $date) {
                    [$month, $year] = explode(" ", $date);

                    $fees = FeesModel::where([
                        "student_id" => $user->id,
                        "payment_month" => $month,
                        "payment_year" => $year,
                        "pay_type" => "0",
                    ])->first();
                    if ($fees) {
                        $fees_paid[] = 1;
                    }
                }

                $paid_count = count($fees_paid);
                $total_count = count($months);

                $fees_pay = [
                    "title" => "Fees Payment",
                    "payment_type" => $payment_type,
                    "total_count" => $total_count,
                    "paid_count" => $paid_count,
                ];
            } elseif ($current_pay_type == 1) {
                $payment_type =
                    Configurations::FEEPAYMENTTYPES[$currentFeePayType];
                $current_academic_year = Configurations::getCurrentAcademicyear();
                $academic_terms = ExamTermModel::where(
                    "academic_year",
                    $current_academic_year
                )->get();
                $total_terms = $academic_terms->pluck("id");
                $paid_terms = [];
                foreach ($academic_terms as $term) {
                    $formDate = Carbon::createFromFormat(
                        "m/d/Y",
                        $term->from_date
                    );

                    $endDate = Carbon::createFromFormat(
                        "m/d/Y",
                        $term->to_date
                    );

                    // Format the dates to Y/m/d format
                    $start_date = $formDate->format("Y/m/d");
                    $end_date = $endDate->format("Y/m/d");

                    $fees = FeesModel::where([
                        "student_id" => $user->id,
                        "pay_type" => "1",
                    ])
                        ->whereBetween("payment_date", [$start_date, $end_date])
                        ->first();

                    if ($fees) {
                        $paid_terms[] = "1";
                    }
                }

                $paid_count = count($total_terms);
                $total_count = count($paid_terms);

                $fees_pay = [
                    "title" => "Fees Payment",
                    "payment_type" => $payment_type,
                    "total_count" => $total_count,
                    "paid_count" => $paid_count,
                ];
            } else {
                $payment_type =
                    Configurations::FEEPAYMENTTYPES[$currentFeePayType];
                $current_academic_year = Configurations::getCurrentAcademicyear();

                $fees = FeesModel::where([
                    "student_id" => $user->id,
                    "academic_year" => $current_academic_year,
                    "pay_type" => "2",
                ])->first();
                if ($fees) {
                    $paid_count = 1;
                } else {
                    $paid_count = 0;
                }
                $fees_pay = [
                    "title" => "Fees Payment",
                    "payment_type" => $payment_type,
                    "total_count" => "1",
                    "paid_count" => $paid_count,
                ];
            }

            return response()->json([
                "user_details" => $user_details,
                "address_info" => $address_info,
                "attendance_details" => $attendance,
                "fees_details" => $fees_pay,
                "role" => $role,
            ]);
        }
        if ($role == "Teacher") {
            $user_id = $request->user()->id;
            $teacher_id = TeacherModel::where("user_id", $user_id)
                ->pluck("id")
                ->first();
            $subject_map = SubjectTeacherMapping::with(
                "subject",
                "class",
                "section"
            )
                ->where([
                    "teacher_id" => $teacher_id,
                    "academic_year" => $current_academic_year,
                ])
                ->get();

            $classteacher = ClassteacherModel::with("class", "section")
                ->where([
                    "academic_year" => $current_academic_year,
                    "teacher_id" => $teacher_id,
                ])
                ->first();
            $class = $classteacher ? $classteacher : 0;
            $is_class_teacher = $classteacher ? true : false;

            return response()->json([
                "user_details" => $user_details,
                "address_info" => $address_info,
                "role" => $role,
                "subjects" => $subject_map,
                "is_class_teacher" => $is_class_teacher,
                "class" => $class,
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();
        return response()->json(["message" => "Successfully Logout"]);
    }

    public function UpdateDeviceToken(Request $request)
    {
        $user = UserModel::find($request->user()->id);

        if ($user) {
            $user->update(["device_token" => $request->device_token]);
            return response()->json([
                "message" => "Successfully Updated Token",
            ]);
        } else {
            return response()->json(
                [
                    "message" => "user Not Found",
                ],
                400
            );
        }
    }
}
