<?php
/*
 * get countries data
 */
Route::post("get-academicyear-data", "AcademicyearController@getData")->name(
    "get_academicyear_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-academicyear/{action?}",
    "AcademicyearController@statusChange"
)->name("academicyear_action_from_admin");
/*
 * resource controller
 */
Route::resource("academicyear", "AcademicyearController");
Route::resource("academicyearpopup", "AcademicyearPopupController");
