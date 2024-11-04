<?php
/*
 * get countries data
 */
Route::post('get-productbrand-data','ProductbrandController@getData')->name('get_productbrand_data_from_admin');
/*
 * bulk action
 */
Route::any('do-status-change-for-productbrand/{action?}','ProductbrandController@statusChange')->name('productbrand_action_from_admin');
/*
* resource controller
*/
Route::resource('productbrand','ProductbrandController');
