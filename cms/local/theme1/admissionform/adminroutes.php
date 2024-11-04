<?php
/*
 * get countries data
 */
Route::post('get-admissionform-data','AdmissionformController@getData')->name('get_admissionform_data_from_admin');
/*
 * bulk action
 */
Route::post('do-status-change-for-admissionform/{action}','AdmissionformController@statusChange')->name('admissionform_action_from_admin');
/*
* resource controller
*/
Route::resource('admissionform','AdmissionformController');

Route::get("update_status","AdmissionformController@update")->name("update_status");


