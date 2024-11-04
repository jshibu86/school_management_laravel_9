<?php
/*
 * get countries data
 */
Route::post('get-department-data','DepartmentController@getData')->name('get_department_data_from_admin');
/*
 * bulk action
 */
Route::any('do-status-change-for-department/{action?}','DepartmentController@statusChange')->name('department_action_from_admin');
/*
* resource controller
*/
Route::resource('department','DepartmentController');
