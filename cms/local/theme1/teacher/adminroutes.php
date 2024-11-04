<?php
/*
 * get countries data
 */
Route::post("get-teacher-data", "TeacherController@getData")->name(
    "get_teacher_data_from_admin"
);
/*
 * get designation data
 */
Route::post("GetdesignationData", "TeacherController@GetdesignationData")->name(
    "GetdesignationData"
);
/*
 * bulk action
 */
/*
 * get teacher subjects data
 */
Route::post(
    "Getteachersubjects/{id?}",
    "TeacherController@Getteachersubjects"
)->name("Getteachersubjects");
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-teacher/{action?}",
    "TeacherController@statusChange"
)->name("teacher_action_from_admin");

/*
 * bulk action designation
 */
Route::any(
    "do-status-change-for-designation/{action?}",
    "TeacherController@statusChangeDesignation"
)->name("designation_action_from_admin");
/*
 * resource controller
 */
Route::any(
    "designation_delete/{id?}",
    "TeacherController@designation_delete"
)->name("designation_delete");
Route::resource("teacher", "TeacherController");

Route::any(
    "designationcreate/{id?}",
    "TeacherController@designationcreate"
)->name("designationcreate");

Route::get("designation_view", "TeacherController@designationview")->name(
    "designationview"
);

Route::any(
    "bulk-upload-teacher/{action?}",
    "TeacherController@Bulkupload"
)->name("teacher.bulkupload");

Route::any(
    "DeleteAttachmentTeacher",
    "TeacherController@DeleteAttachmentTeacher"
)->name("DeleteAttachmentTeacher");
