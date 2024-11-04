<?php
/*
 * get countries data
 */
Route::post('get-StudentPerformance-data','StudentPerformanceController@getData')->name('get_StudentPerformance_data_from_admin');
/*
 * bulk action
 */
Route::post('do-status-change-for-StudentPerformance/{action}','StudentPerformanceController@statusChange')->name('StudentPerformance_action_from_admin');
/*
* resource controller
*/
Route::resource('StudentPerformance','StudentPerformanceController');

Route::any("studentperformance", "StudentPerformanceController@index")->name(
    "studentperformance"
);

Route::post("studentperformance_store", "StudentPerformanceController@store")->name(
    "studentperformance_store"
);