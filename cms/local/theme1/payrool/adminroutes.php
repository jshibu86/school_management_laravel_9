<?php
/*
 * get countries data
 */
Route::post("get-payrool-data", "PayroolController@getData")->name(
    "get_payrool_data_from_admin"
);

Route::post(
    "get-particulars-data",
    "SaleryParticularsController@getData"
)->name("get_particulars_data_from_admin");

Route::post("get-template-data", "SaleryTemplateController@getData")->name(
    "get_tempate_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-payrool/{action?}",
    "PayroolController@statusChange"
)->name("payrool_action_from_admin");

Route::any(
    "do-status-change-for-particulars/{action?}",
    "SaleryParticularsController@statusChange"
)->name("particulars_action_from_admin");

Route::any(
    "do-status-change-for-template/{action?}",
    "SaleryTemplateController@statusChange"
)->name("template_action_from_admin");
/*
 * resource controller
 */
Route::resource("payroll", "PayroolController");
Route::resource("payrolldeduction", "PayrollDeductionController");
Route::resource("saleryparticulars", "SaleryParticularsController");
Route::resource("salerytemplate", "SaleryTemplateController");

Route::any("payrollschedule", "PayroolController@PayrollSchedule")->name(
    "PayrollSchedule"
);

Route::any("payrollmakepayment", "PayroolController@PayrollMakePayment")->name(
    "PayrollMakePayment"
);

Route::any("paymenthistory", "PayroolController@PaymentHistory")->name(
    "PaymentHistory"
);

Route::get("viewpayslip/{id?}", "PayroolController@ViewPayslip")->name(
    "viewpayslip"
);
