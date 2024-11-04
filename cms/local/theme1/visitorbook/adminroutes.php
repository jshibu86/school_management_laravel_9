<?php
/*
 * get countries data
 */
Route::post("get-visitorbook-data", "VisitorbookController@getData")->name(
    "get_visitorbook_data_from_admin"
);

Route::post("get-complaintsbook-data", "ComplaintsController@getData")->name(
    "get_complaints_data_from_admin"
);

Route::post("get-catalog-data", "PhoneCatalogeController@getData")->name(
    "get_catalog_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-visitorbook/{action?}",
    "VisitorbookController@statusChange"
)->name("visitorbook_action_from_admin");

Route::any(
    "do-status-change-for-complaints/{action?}",
    "ComplaintsController@statusChange"
)->name("complaints_action_from_admin");

Route::any(
    "do-status-change-for-catalog/{action?}",
    "PhoneCatalogeController@statusChange"
)->name("catalog_action_from_admin");
/*
 * resource controller
 */
Route::resource("visitorbook", "VisitorbookController");
Route::resource("complaints", "ComplaintsController");
Route::resource("phonecatalog", "PhoneCatalogeController");
