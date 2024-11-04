<?php
/*
 * get countries data
 */
Route::post(
    "get-homework-data/{class_id?}/{section_id?}/{subject_id?}",
    "HomeworkController@getData"
)->name("get_homework_data_from_admin");

Route::post(
    "get-homework-eval-data/{homework_id?}",
    "HomeworkController@getDataEvaluation"
)->name("get_homework_eval_data_from_admin");
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-homework/{action?}",
    "HomeworkController@statusChange"
)->name("homework_action_from_admin");
/*
 * resource controller
 */
Route::resource("homework", "HomeworkController");

Route::get(
    "homework_submission/{id?}",
    "HomeworkController@homeworksubmissions"
)->name("homeworksubmissions");

Route::any(
    "homework_evaluation/{id?}/{student?}",
    "HomeworkController@homeworkevaluations"
)->name("homeworkevaluations");

Route::post(
    "homework_submission_submit",
    "HomeworkController@homeworksubmissionsSubmit"
)->name("homeworksubmissionsSubmit");

