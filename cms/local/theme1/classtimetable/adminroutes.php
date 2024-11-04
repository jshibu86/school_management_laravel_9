<?php
/*
 * get countries data
 */
Route::post(
    "get-classtimetable-data",
    "ClasstimetableController@getData"
)->name("get_classtimetable_data_from_admin");

Route::post("get-period-data", "PeriodController@getData")->name(
    "get_period_data_from_admin"
);

Route::any("classtimetable","ClasstimetableController@index")->name("classtimetable");

Route::any("timetable_update/{id?}","ClasstimetableController@TimetableUpdate")->name("timetable_update");

Route::post("classtimetableperiods","ClasstimetableController@Periods")->name("classtimetableperiods");

Route::any("classtimetablecalender/{id?}/{type?}/{days?}/{section?}","ClasstimetableController@calender")->name("classtimetablecalender");
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-classtimetable/{action?}",
    "ClasstimetableController@statusChange"
)->name("classtimetable_action_from_admin");
/*
 * resource controller
 */
Route::resource("classtimetable", "ClasstimetableController");
Route::resource("period", "PeriodController"); 

Route::get("clonetimetable/{id?}","ClasstimetableController@CloneTimetable"
)->name("clonetimetable");  

Route::post("classtimetableclone","ClasstimetableController@TimetableClone"
)->name("classtimetableclone"); 


Route::post('Timetable_period_delete', 'ClasstimetableController@TimeTablePeriodDelete')->name('timetable_period_delete');


