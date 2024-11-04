<?php
/*
 * get countries data
 */
Route::post("get-exam-data", "ExamController@getData")->name(
    "get_exam_data_from_admin"
);

Route::post("get-onlineexam-data", "OnlineExamController@getData")->name(
    "get_onlineexam_data_from_admin"
);
Route::post("get-quizexam-data", "OnlineExamController@getQuizData")->name(
    "get_quizexam_data_from_admin"
);
Route::any("get_admissionexam_data", "AdmisssionExamController@getData")->name(
    "get_admissionexam_data_from_admin"
);
Route::post("get-homework-data", "HomeworkDataController@getData")->name(
    "get_homework_data_from_admin"
);
Route::post("get-exam-type-data", "ExamTypeController@getData")->name(
    "get_exam_type_data_from_admin"
);

Route::post("get-exam-term-data", "ExamTermController@getData")->name(
    "get_exam_term_data_from_admin"
);

Route::get("deletequestion", "ExamController@deletequestion")->name(
    "exam.deletequestion"
);
Route::get("deletesection", "ExamController@deletesection")->name(
    "exam.deletesection"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-exam/{action?}",
    "ExamController@statusChange"
)->name("exam_action_from_admin");

Route::any(
    "do-status-change-for-exam-type/{action?}",
    "ExamTypeController@statusChange"
)->name("exam_type_action_from_admin");

Route::any(
    "do-status-change-for-exam-term/{action?}",
    "ExamTermController@statusChange"
)->name("exam_term_action_from_admin");
/*
 * resource controller
 *
 * R
 */
Route::get("/dummy", function () {
    return view("exam::admin.dummy");
});

Route::resource("admissionexam", "AdmisssionExamController")->except("show");
Route::resource("exam", "ExamController");
Route::resource("examtype", "ExamTypeController");
Route::resource("examterm", "ExamTermController");
Route::resource("homeworkdata", "HomeworkDataController");
Route::resource("onlineexam", "OnlineExamController")->except("show");

Route::get("onlineexam/{id}", "OnlineExamController@show")
    ->name("onlineexam.show")
    ->middleware("signed");
Route::get("admissionexam/{id}", "AdmisssionExamController@show")
    ->name("admissionexam.show")
    ->middleware("signed");
Route::any("mandatory", "OnlineExamController@mandatoryclosure")->name(
    "mandatoryclosure"
);

Route::get(
    "onlineexam/results/{examid}/{studentid}",
    "OnlineExamController@onlineexamResults"
)
    ->name("onlineexam.results")
    ->middleware("signed");

Route::get(
    "admissionexam/results/{examid}/{admissionid}",
    "AdmisssionExamController@onlineexamResults"
)
    ->name("admissionexam.results")
    ->middleware("signed");

// dubplicate exam
Route::any("duplicateexam/{id?}/{type?}", "ExamController@edit")->name(
    "duplicatEexam"
);

Route::any("offlinexamreport/{id?}", "ExamController@offlinexamreport")->name(
    "offlinexamreport"
);

Route::any("onlinexamreport/{id?}", "ExamController@onlinexamreport")->name(
    "onlinexamreport"
);

Route::post("submitofflineexam", "ExamController@SubmitofflineExamMark")->name(
    "SubmitofflineExamMark"
);

Route::post("getquestionsinfo", "ExamController@getQuestionsinfo")->name(
    "getQuestionsinfo"
);
Route::any("academictermdetails", "ExamTermController@index")->name(
    "academictermdetails"
);

Route::get("homework_data", "HomeworkDataController@index")->name(
    "homework_index"
);

Route::post("homework_submit", "HomeworkDataController@HomeworkSubmit")->name(
    "homework_submit"
);

Route::get(
    "evaluate_homework",
    "HomeworkDataController@HomeworkEvaluate"
)->name("evaluate_homework");

Route::post(
    "homework_evaluate",
    "HomeworkDataController@EvaluateHomework"
)->name("homework_evaluate");

Route::get(
    "homework_submit_view/{id?}/{exam_id?}",
    "HomeworkDataController@HomeworkSubmitView"
)->name("homework_submit_view");
