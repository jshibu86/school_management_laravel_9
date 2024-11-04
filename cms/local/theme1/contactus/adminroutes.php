<?php
/*
 * get countries data
 */
Route::post("get-contactus-data", "ContactusController@getData")->name(
    "get_contactus_data_from_admin"
);
/*
 * bulk action
 */
Route::post(
    "do-status-change-for-contactus/{action?}",
    "ContactusController@statusChange"
)->name("contactus_action_from_admin");
/*
 * resource controller
 */
Route::resource("contactus", "ContactusController");
