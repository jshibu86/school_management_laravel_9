<?php
/*
 * get countries data
 */
Route::post("get-lclass-data", "LclassController@getData")->name(
    "get_lclass_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-lclass/{action?}",
    "LclassController@statusChange"
)->name("lclass_action_from_admin");
/*
 * resource controller
 */
Route::resource("lclass", "LclassController");
