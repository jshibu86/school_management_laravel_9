<?php
/*
 * get countries data
 */
Route::post('get-event-data','EventController@getData')->name('get_event_data_from_admin');
/*
 * bulk action
 */
Route::any('do-status-change-for-event/{action?}','EventController@statusChange')->name('event_action_from_admin');
/*
* resource controller
*/
Route::resource('event','EventController');
