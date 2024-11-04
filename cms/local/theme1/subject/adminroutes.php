<?php
/*
 * get countries data
 */
Route::post("get-subject-data", "SubjectController@getData")->name(
    "get_subject_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-subject/{action?}",
    "SubjectController@statusChange"
)->name("subject_action_from_admin");
/*
 * resource controller
 */
Route::resource("subject", "SubjectController");

Route::any(
    "subject-teacher-mapping/{getsubject?}",
    "SubjectController@subjectteacherMapping"
)->name("subjectteacherMapping");

Route::post(
    "storesubject-teacher-mapping/",
    "SubjectController@storesubjectteacherMapping"
)->name("storesubjectteacherMapping");
