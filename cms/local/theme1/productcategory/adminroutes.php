<?php
/*
 * get countries data
 */
Route::post('get-productcategory-data','ProductcategoryController@getData')->name('get_productcategory_data_from_admin');
/*
 * bulk action
 */
Route::any('do-status-change-for-productcategory/{action?}','ProductcategoryController@statusChange')->name('productcategory_action_from_admin');
/*
* resource controller
*/
Route::resource('productcategory','ProductcategoryController');
