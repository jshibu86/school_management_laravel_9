<?php
/*
 * get countries data
 */
Route::post('get-Demo-data','DemoController@getData')->name('get_Demo_data_from_admin'); 

Route::post('get-Demo-schedule-data','DemoController@getScheduleData')->name('get_Demo_schedule_data_from_admin'); 

Route::post('get-Demo-attendant-data','DemoController@getAttendantData')->name('get_Demo_attendant_data_from_admin'); 



/*
 * bulk action
 */
Route::post('do-status-change-for-Demo/{action}','DemoController@statusChange')->name('Demo_action_from_admin');
/*
* resource controller
*/
Route::resource('Demo','DemoController');

Route::post('/scheduleDemo','DemoController@scheduleDemo')->name('demo.scheduleDemo');


Route::post('/saveAttendance','DemoController@saveAttendance')->name('demo.saveAttendance');

Route::post('/saveSettingMessage','DemoController@saveSettingMessage')->name('demo.saveSettingMessage');

