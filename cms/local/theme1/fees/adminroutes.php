<?php
/*
 * get countries data
 */
Route::resource("fees", "FeesController");

Route::post("get-fees-data", "FeesController@getData")->name(
    "get_fees_data_from_admin"
);
Route::post(
    "get_fees_unpaid_data_from_admin",
    "FeesController@getUnpaidData"
)->name("get_fees_unpaid_data_from_admin");
Route::post("get-feestype-data", "FeeTypeController@getData")->name(
    "get_feestype_data_from_admin"
);

Route::post("get-feessetup-data", "FeeSetupController@getData")->name(
    "get_feessetup_data_from_admin"
);

Route::post("get-schooltype-data", "SchoolTypeController@getData")->name(
    "get_schooltype_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-fees/{action?}",
    "FeesController@statusChange"
)->name("fees_action_from_admin");

Route::any(
    "do-status-change-for-feetype/{action?}",
    "FeeTypeController@statusChange"
)->name("feetype_action_from_admin");

Route::any(
    "do-status-change-for-feesetup/{action?}",
    "FeeSetupController@statusChange"
)->name("feesetup_action_from_admin");

Route::any(
    "do-status-change-for-schooltype/{action?}",
    "SchoolTypeController@statusChange"
)->name("schooltype_action_from_admin");

Route::any("sendpdf", "FeesController@sendPdf")->name("sendPdf");
Route::post("payfeepayment", "FeesController@payfeepayment")->name(
    "payfeepayment"
);
/*
 * resource controller
 */

Route::resource("feetype", "FeeTypeController");
Route::resource("feesetup", "FeeSetupController");
Route::resource("schooltype", "SchoolTypeController");

Route::any("fees_payment", "FeesController@FeesPayment")->name("fees_payment");

Route::get("fees_reminder", "FeesController@FeesReminder")->name(
    "fees_reminder"
);

Route::post(
    "confirm_fees_reminder",
    "FeesController@ConfirmFeesReminder"
)->name("confirm_fees_reminder");

Route::get(
    "unpaid_view/{id?}/{payment_type?}/{total_amount?}",
    "FeesController@UnpaidView"
)->name("unpaid_view");

Route::post("bulkprint", "FeesController@BulkPrint")->name("bulk_print");

Route::get(
    "is_department_applies",
    "FeeSetupController@CheckDepartmentApplies"
)->name("is_department_applies");
