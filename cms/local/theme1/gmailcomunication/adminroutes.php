<?php
/*
 * get countries data
 */
Route::post('get-gmailcomunication-data','GmailcomunicationController@getData')->name('get_gmailcomunication_data_from_admin');
/*
 * bulk action
 */
Route::post('do-status-change-for-gmailcomunication/{action}','GmailcomunicationController@statusChange')->name('gmailcomunication_action_from_admin');
/*
* resource controller
*/
Route::resource('gmailcomunication','GmailcomunicationController');

Route::get('create_group_model','GmailcomunicationController@CreateGroupModel')->name('create_group_model');
Route::post('create_group','GmailcomunicationController@CreateGroup')->name('create_group');

Route::get('get_receptiants','GmailcomunicationController@GetReceptiants')->name('get_receptiants');

Route::get('edit_group_model','GmailcomunicationController@EditGroupModel')->name('edit_group_model');
Route::post('edit_group/{id}','GmailcomunicationController@UpdateGroup')->name('edit_group');

Route::get('delete_group','GmailcomunicationController@DeleteGroup')->name('delete_group');

Route::post('group_message','GmailcomunicationController@GroupMessage')->name('group_message');

Route::post('individual_message','GmailcomunicationController@IndividualMessage')->name('individual_message');

Route::get("delete_messages","GmailcomunicationController@DeleteMessages")->name("delete_messages");

Route::post('external_message','GmailcomunicationController@ExternalMessage')->name('external_message');

Route::get("receptiants","GmailcomunicationController@Receptiants")->name("receptiants");
