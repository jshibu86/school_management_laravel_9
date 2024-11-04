<?php
/*
 * get countries data
 */
Route::post(
    "get-virtualcomunication-data",
    "VirtualcomunicationController@getData"
)->name("get_virtualcomunication_data_from_admin");

Route::post(
    "get-pta-virtualcomunication-data",
    "VirtualcomunicationController@getPTAData"
)->name("get_pta_virtualcomunication_data_from_admin");
/*
 * bulk action
 */
Route::post(
    "do-status-change-for-virtualcomunication/{action?}",
    "VirtualcomunicationController@statusChange"
)->name("virtualcomunication_action_from_admin");
/*
 * resource controller
 */
Route::resource("virtualcomunication", "VirtualcomunicationController");

Route::get(
    "join_meeting/{id?}",
    "VirtualcomunicationController@JoinMeeting"
)->name("join_meeting");

Route::get(
    "get_participants",
    "VirtualcomunicationController@GetParticipants"
)->name("get_participants");

Route::get(
    "add_particiapants",
    "VirtualcomunicationController@AddParticipants"
)->name("add_particiapants");

Route::get(
    "participants_sections",
    "VirtualcomunicationController@Sections"
)->name("participants_sections");

Route::get(
    "get_participant_groups",
    "VirtualcomunicationController@GetParticipantGroups"
)->name("get_participant_groups");
