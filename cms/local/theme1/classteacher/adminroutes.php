<?php
/*
 * get countries data
 */
Route::post('get-classteacher-data','ClassteacherController@getData')->name('get_classteacher_data_from_admin');
/*
 * bulk action
 */
Route::any('do-status-change-for-classteacher/{action?}','ClassteacherController@statusChange')->name('classteacher_action_from_admin');
/*
* resource controller
*/
Route::resource('classteacher','ClassteacherController');
