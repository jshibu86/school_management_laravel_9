<?php
/*
 * get countries data
 */
Route::post("get-attendance-data", "AttendanceController@getData")->name(
    "get_attendance_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-attendance/{action?}",
    "AttendanceController@statusChange"
)->name("attendance_action_from_admin");
/*
 * resource controller
 */

Route::get("attendance/show/{id}/{type?}", [
    "as" => "attendance.show",
    "uses" => "AttendanceController@show",
]);
Route::get("attendance/create/{type?}", [
    "as" => "attendance.create",
    "uses" => "AttendanceController@create",
]);
Route::resource("attendance", "AttendanceController")->except([
    "create",
    "show",
]);

Route::any(
    "addhourlyattendance",
    "AttendanceController@addhourlyattendance"
)->name("addhourlyattendance");

Route::any(
    "attendancedailycount",
    "AttendanceController@attendancedailycount"
)->name("attendancedailycount");

Route::any(
    "getdailyattendance",
    "AttendanceController@getdailyattendance"
)->name("getdailyattendance");

Route::any(
    "gethourlyattendance",
    "AttendanceController@gethourlyattendance"
)->name("gethourlyattendance");

Route::get("hourlyindex", "AttendanceController@hourlyindex")->name(
    "attendance.hourlyindex"
);

Route::any(
    "attendance_delete/{id?}/{attendance?}",
    "AttendanceController@deleteAttendance"
)->name("attendance_delete");
