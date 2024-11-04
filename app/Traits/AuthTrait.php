<?php

namespace App\Traits;

use Image;
use Auth;
use cms\core\user\Models\OtpVerificationModel;
use Session;
use File;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
trait AuthTrait
{
    public $successcode = 200;
    public $errorcode = 400;
    public $servererror = 500;

    public function SendotpMobile($phonenumber = null)
    {
        try {
            // $apiUrl =
            //     "http://sms.dhinatechnologies.com/api/sms_api.php?username=dhinatech&api_password=f6j6c33txvy&message=Your%20OTP%20for%20purpose%20is%20" .
            //     $otp .
            //     ".%20Please%20do%20not%20share%20this%20OTP.%20DHINAT&destination=" .
            //     $phonenumber .
            //     "&type=2&sender=DHINAT";

            // $response = Http::get($apiUrl);

            //$response->ok()

            $otp = $this->GenerateOtp();

            $otpsend = $this->SendOtp($otp, $phonenumber);

            if ($otpsend["success"]) {
                $exptime = date("Y-m-d H:i:s", time() + 3600);
                // before save new otp set null for previous all otp in this mobile number
                OtpVerificationModel::where("mobile", $phonenumber)->update([
                    "otpverify" => null,
                ]);
                $otp_info = new OtpVerificationModel();

                $otp_info->mobile = $phonenumber;
                $otp_info->exp_time = $exptime;
                $otp_info->send_time = Carbon::now();
                $otp_info->otpverify = $otp;
                $otp_info->save();

                $length = strlen($phonenumber);
                $obfuscatedMobile =
                    str_repeat("x", $length - 3) . substr($phonenumber, -3);

                if (true) {
                    return [
                        "message" =>
                            "Successfully Otp Send " . $obfuscatedMobile,
                        "code" => $this->successcode,
                        "otp" => $otp,
                        "phone_number" => $phonenumber,
                    ];
                }
            } else {
                return [
                    "message" => isset($otpsend["data"])
                        ? $otpsend["data"]
                        : "Whoops Something Wrong",
                    "code" => $this->servererror,
                ];
            }
        } catch (\Exception $e) {
            return [
                "message" => $e->getMessage(),
                "code" => $this->servererror,
            ];
        }
    }

    public function GenerateOtp()
    {
        $otp = mt_rand(10000, 99999);

        return $otp;
    }

    public function SendOtp(
        $otp,
        $phonenumber,
        $is_india = true,
        $type = "mobile"
    ) {
        if ($type == "mobile" && !$is_india) {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://www.bulksmsnigeria.com/api/v2/sms",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS =>
                    'body=Your%20one%20time%20pass%20is%204567.%20Please%2C%20don%5C\'t%20disclose%20this%20to%20anyone.&from=Schope%20&to=' .
                    $phonenumber .
                    "&api_token=oLSvorIsyccope0DETPAUVZYOV6YWdDnrW7KPpHkSiQaX8KryMHZjmwIND3b&gateway=direct-refund",
                CURLOPT_HTTPHEADER => [
                    "Accept: application/json",
                    "Content-Type: application/x-www-form-urlencoded",
                ],
            ]);

            $response = curl_exec($curl);

            curl_close($curl);

            if ($response) {
                // Check if the response contains data
                $responseData = json_decode($response, true); // Decode JSON

                if ($responseData["error"]["message"] == null) {
                    return ["success" => true, "data" => $responseData];
                } else {
                    return [
                        "success" => false,
                        "data" => $responseData["error"]["message"],
                    ];
                }
            }
        } else {
            return [
                "success" => true,
                "data" => "Otp Send Succesfully",
            ];
        }
    }
}
