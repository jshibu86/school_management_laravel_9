<?php
/*
 * get countries data
 */
Route::post("get-transport-data", "TransportController@getData")->name(
    "get_transport_data_from_admin"
);

Route::post(
    "get-transport-staff-data",
    "TransportStaffController@getData"
)->name("get_transportstaff_data_from_admin");

Route::post("get-transport-stop-data", "TransportStopController@getData")->name(
    "get_transportstop_data_from_admin"
);

Route::post(
    "get-transport-route-data",
    "TransportRouteController@getData"
)->name("get_transportroute_data_from_admin");
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-transport/{action?}",
    "TransportController@statusChange"
)->name("transport_action_from_admin");

Route::any(
    "do-status-change-for-transportstaff/{action?}",
    "TransportStaffController@statusChange"
)->name("transportstaff_action_from_admin");

Route::any(
    "do-status-change-for-transportstop/{action?}",
    "TransportStopController@statusChange"
)->name("transportstop_action_from_admin");

Route::any(
    "do-status-change-for-transportroute/{action?}",
    "TransportRouteController@statusChange"
)->name("transportroute_action_from_admin");

Route::get("getstopvehicle", "TransportStudentController@getstopvehicle")->name(
    "getstopvehicle"
);
/*
 * resource controller
 */
Route::resource("transport", "TransportController");
Route::resource("transportroute", "TransportRouteController");
Route::resource("transportstop", "TransportStopController");
Route::resource("transportstudent", "TransportStudentController");
Route::resource("transportstaff", "TransportStaffController");
