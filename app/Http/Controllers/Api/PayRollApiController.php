<?php

namespace App\Http\Controllers\Api;

use App\Exports\PayrollSheduleExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\payrool\Models\PayrollModel;

use Yajra\DataTables\Facades\DataTables;

use Session;
use DB;
use CGate;
use Configurations;
use cms\teacher\Models\TeacherModel;
use cms\core\user\Models\UserModel;
use cms\core\usergroup\Models\UserGroupMapModel;
use cms\core\usergroup\Models\UserGroupModel;
use cms\payrool\Models\SaleryPayrollPayment;
use cms\payrool\Models\SaleryTemplateModel;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;
use cms\payrool\Mail\PayrollPaySlip;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use App\Traits\ApiResponse;

class PayRollApiController extends Controller
{
    use ApiResponse;
    //

    public function PaymentHistory(Request $request)
    {
        $start_date = $request->query("start_date") ?? null;
        $end_date = $request->query("end_date") ?? null;
        $user_id = $request->user()->id;
        $group_id = UserGroupMapModel::where("user_id", $user_id)
            ->pluck("group_id")
            ->first();
        $user = UserModel::with("salerypayrollpayment.grade")
            ->where("status", 1)
            ->whereNull("deleted_at")
            ->where("id", $user_id)

            ->first();
        $user_data = UserModel::where("status", 1)
            ->whereNull("deleted_at")
            ->where("id", $user_id)

            ->first();
        if ($start_date != null && $end_date != null) {
            $start = Carbon::parse($start_date)->format("Y/m/d");
            $end = Carbon::parse($end_date)->format("Y/m/d");
        } else {
            $timezone = Configurations::getConfig("site")->time_zone;
            $month = Carbon::now($timezone)->format("M");
            $year = Carbon::now($timezone)->format("Y");
            $month_year = Carbon::now($timezone)->format("M Y");
        }
        $is_month = false;
        $is_year = false;
        $formated_month = $is_month && $is_year ? $month_year : "-";
        $sallery =
            $is_month && $is_year
                ? $user->salerypayrollpayment->basic_salery ?? "NIL"
                : "-";
        $currency_symbol = Configurations::getConfig("site")->currency_symbol;
        $slip =
            $is_month && $is_year
                ? $user->salerypayrollpayment->id
                : "Not Available";
        if ($start_date != null && $end_date != null) {
            $history = SaleryPayrollPayment::where("user_id", $user_id)
                ->whereBetween(DB::raw("DATE(payment_date)"), [$start, $end])
                ->get();
            // dd($history);
            $payment_history = [];
            foreach ($history as $data) {
                $payment_history[] = [
                    "month" => $data->month . " " . $data->year,
                    "sallery" => $data->basic_salery,
                    "currency_symbol" => $currency_symbol,
                    "sallery_slip_id" => $data->id,
                ];
            }
        } else {
            $payment_history = null;
        }
        $data = ["user" => $user_data, "payment_history" => $payment_history];

        return $this->success($data, "Data Fetched Successfully", 200);
    }

    public function ViewPayslip(Request $request, $id = null)
    {
        // dd($id);

        $salery_payment = SaleryPayrollPayment::find($id);
        $config = Configurations::getConfig("site");

        $teacher = TeacherModel::where(
            "user_id",
            $salery_payment->user_id
        )->get();

        $salery_payment_details = SaleryPayrollPayment::where(
            "user_id",
            $salery_payment->user_id
        )->get();

        $host = request()->getHttpHost();

        $user = UserModel::find($salery_payment->user_id);
        $config = Configurations::getConfig("site");
        $image = parse_url($config->imagec, PHP_URL_PATH);
        $pdf = Pdf::loadView("payrool::admin.includes.payslip", [
            "user" => $user,
            "config" => $config,
            "teacher" => $teacher,
            "view" => true,
            "grade" => [],
            "salery_payment" => $salery_payment,
            "salery_payment_details" => $salery_payment_details,
            "current_url" => $host,
            "image" => $image,
        ]);
        $fileName = "payslip_" . $id . ".pdf";
        $filePath = public_path("school/payslips/");
        $fullFilePath = $filePath . $fileName;

        if (!file_exists($fullFilePath)) {
            $pdf = Pdf::loadView("payrool::admin.includes.payslip", [
                "user" => $user,
                "config" => $config,
                "teacher" => $teacher,
                "view" => true,
                "grade" => [],
                "salery_payment" => $salery_payment,
                "salery_payment_details" => $salery_payment_details,
                "current_url" => $host,
                "image" => $image,
            ]);

            if (!file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }

            $pdf->save($fullFilePath);
        }

        $pdfUrl = asset("school/payslips/" . $fileName);

        return $this->success(
            $pdfUrl,
            "PDF generated and saved successfully",
            200
        );
    }
}
