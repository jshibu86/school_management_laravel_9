<?php
/*
 * get countries data
 */
Route::post('get-subscription-data','SubscriptionController@getData')->name('get_subscription_data_from_admin');

//get module list 
Route::post('module-data', 'ModuleController@getData')->name('get_module_list_data');

// get plan list
Route::post('plan-data', 'SetupPlanController@getData')->name('get_plan_list_data');

// get plan price list
Route::post('plan-price-data', 'SetupPlanPriceController@getData')->name('get_plan_price_list_data');

// get plan setting data
Route::get('plan-setting-data', 'SubscriptionSettingController@getData')->name('get_plan_setting_data');

/*
 * bulk action
 */
Route::post('do-status-change-for-subscription/{action}','SubscriptionController@statusChange')->name('subscription_action_from_admin');
/*
* resource controller
*/
Route::resource('subscription','SubscriptionController');
Route::resource("setupplan", "SetupPlanController");
Route::resource("setupplanprice", "SetupPlanPriceController");
Route::resource("module", "ModuleController");
Route::resource('subscriptionsetting','SubscriptionSettingController');

// display plan create page.
Route::get('newplan/create', 'SetupPlanController@newPlanCreate')->name('create.newplan');

// display plan price setup page.
Route::get('newplanprice/create', 'SetupPlanController@newPlanPriceCreate')->name('create.newplanprice');

