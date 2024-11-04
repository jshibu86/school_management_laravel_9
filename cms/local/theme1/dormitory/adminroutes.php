<?php
/*
 * get countries data
 */
Route::post("get-dormitory-data", "DormitoryController@getData")->name(
    "get_dormitory_data_from_admin"
);

Route::post(
    "get-dormitoryroomtype-data",
    "DormitoryRoomTypeController@getData"
)->name("get_dormitoryroomtype_data_from_admin");

Route::post("get-dormitoryroom-data", "DormitoryRoomController@getData")->name(
    "get_dormitoryroom_data_from_admin"
);

Route::post(
    "get-dormitorystudent-data",
    "DormitoryStudentController@getData"
)->name("get_dormitorystudent_data_from_admin");
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-dormitory/{action?}",
    "DormitoryController@statusChange"
)->name("dormitory_action_from_admin");

Route::any(
    "do-status-change-for-dormitoryroomtype/{action?}",
    "DormitoryRoomTypeController@statusChange"
)->name("dormitoryroomtype_action_from_admin");

Route::any(
    "do-status-change-for-dormitoryroom/{action?}",
    "DormitoryRoomController@statusChange"
)->name("dormitoryroom_action_from_admin");

Route::any(
    "do-status-change-for-dormitoryroomstudent/{action?}",
    "DormitoryStudentController@statusChange"
)->name("dormitorystudent_action_from_admin");
/*
 * resource controller
 */
Route::resource("dormitory", "DormitoryController");
Route::resource("dormitoryroom", "DormitoryRoomController");
Route::resource("dormitoryroomtype", "DormitoryRoomTypeController");
Route::resource("dormitorystudent", "DormitoryStudentController");
