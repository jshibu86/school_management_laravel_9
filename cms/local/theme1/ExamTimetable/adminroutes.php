<?php
/*
 * get countries data
 */
Route::post('get-ExamTimetable-data','ExamTimetableController@getData')->name('get_ExamTimetable_data_from_admin');
/*
 * bulk action
 */
// do-status-change-for-ExamTimetable/{action?}
Route::any('do-status-change-for-ExamTimetable/{action?}','ExamTimetableController@statusChange')->name('ExamTimetable_action_from_admin');
/*
* resource controller
*/
Route::resource('ExamTimetable','ExamTimetableController');

Route::post("examtimetable_store", "ExamTimetableController@store")->name(
    "examtimetable_store"
);

Route::any("examtimetable", "ExamTimetableController@index")->name(
    "examtimetable"
);

Route::any("examtimetable_create", "ExamTimetableController@create")->name(
    "examtimetable_create"
);

Route::any("examtimetable_calender/{id?}/{type?}", "ExamTimetableController@calender")->name(
    "examtimetable_calender"
);

Route::post("examtimetable_save", "ExamTimetableController@ExamTimetableSave")->name(
    "examtimetable_save"
);

Route::post('ExamTimetable/{id}', 'ExamTimetableController@update')->name('ExamTimetable.update');

Route::post('ExamTimetable_period_delete', 'ExamTimetableController@ExamTimeTablePeriodDelete')->name('examtimetable_period_delete');



Route::get("cloneexamtimetable/{id?}","ExamTimetableController@CloneExamTimetable"
)->name("cloneexamtimetable");  

Route::post("classtimetableclone","ExamTimetableController@TimetableClone"
)->name("classtimetableclone"); 
