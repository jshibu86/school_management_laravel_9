<?php
/*
 * get countries data
 */
Route::post("get-staff-data", "StaffController@getData")->name(
    "get_staff_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-staff/{action?}",
    "StaffController@statusChange"
)->name("staff_action_from_admin");
/*
 * resource controller
 */
Route::resource("staff", "StaffController");

Route::any("staffattendance", "StaffController@StaffAttendance")->name(
    "staff.attendance"
);
