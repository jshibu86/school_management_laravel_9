<?php
use cms\admissionform\Controllers\AdmissionformController;
/*
 * get countries data
 */
Route::post('get-admission-data','AdmissionController@getData')->name('get_admission_data_from_admin');
/*
 * bulk action
 */
Route::post('do-status-change-for-admission/{action}','AdmissionController@statusChange')->name('admission_action_from_admin');
/*
* resource controller
*/
Route::resource('admission','AdmissionController');

Route::any('/administrator/admission/new','AdmissionController@create')->name('admission.new');

Route::get('/administrator/admission/admissionform', [AdmissionformController::class, 'index'])->name('admissionform');

Route::post('/administrator/admission/reject', 'AdmissionController@reject')->name('admission.reject');




