<?php
namespace cms\report\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use cms\core\configurations\helpers\Configurations;
use cms\shop\Models\ProductModel;
use cms\shop\Models\PurchaseOrderModel;
use DB;
use Carbon\Carbon;
use cms\shop\Controllers\PurchaseOrderController;
use cms\shop\Models\OrderItemsModel;
use Yajra\DataTables\Facades\DataTables;
use CGate;

class TuckShopController extends Controller
{
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
        $academicyears = Configurations::getAcademicyears();

        if ($request->ajax()) {
            $purchase_order_Controller = new PurchaseOrderController();
            if ($request->service_id == 1) {
                // purchase report

                $final_data = $purchase_order_Controller->getreportdata(
                    $request,
                    true,
                    false
                );

                $total_purchase = $final_data->sum("purchase_price");

                $total_products = !empty($final_data) ? sizeof($final_data) : 0;

                $view = view("report::admin.report.tuckshop.purchasetable", [
                    "final_data" => $final_data,
                    "type" => $request->report_type,
                    "day" => $request->day,
                    "month" => $request->month,
                    "year" => $request->year,
                    "start_date" => $request->start_date,
                    "end_date" => $request->end_date,
                ])->render();

                // sales

                return response()->json([
                    "view" => $view,
                    "total" => $total_purchase,
                    "products" => $total_products,
                ]);
            } else {
                $sales_final_data = $purchase_order_Controller->getreportdata(
                    $request,
                    false,
                    true
                );

                $total_customer = array_unique(
                    $sales_final_data->pluck("user_id")->toArray()
                );

                $order_ids = $sales_final_data->pluck("id")->toArray();

                $total_Sales = OrderItemsModel::whereIn(
                    "order_id",
                    $order_ids
                )->sum("qty");

                $total_Sale_amount = $sales_final_data->sum("order_amount");

                //return $total_customer;

                $view = view("report::admin.report.tuckshop.salestable", [
                    "final_data" => $sales_final_data,
                ])->render();

                return response()->json([
                    "view" => $view,
                    "total_customer" => sizeof($total_customer),
                    "sales" => $total_Sales,
                    "total_amount" => $total_Sale_amount,
                ]);
            }
            return "ok";
        }

        $academic_years = Configurations::getAcademicyears();
        $current_academic_year = Configurations::getCurrentAcademicyear();
        $current_academic_term = Configurations::getCurrentAcademicterm();

        $examterms = Configurations::getCurentAcademicTerms();
        return view(
            "report::admin.report.tuckshop.index",
            compact(
                "academicyears",
                "current_academic_year",
                "current_academic_term",
                "examterms"
            )
        );
    }

    public function getData(Request $request)
    {
    }
}
