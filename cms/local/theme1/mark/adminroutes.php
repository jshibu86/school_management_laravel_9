<?php
/*
 * get countries data
 */
Route::post("get-mark-data", "MarkController@getData")->name(
    "get_mark_data_from_admin"
);

Route::post("get-grade-data", "GradeController@getData")->name(
    "get_grade_data_from_admin"
);

Route::post(
    "get-markdistribution-data",
    "MarkDistributionController@getData"
)->name("get_markdistribution_data_from_admin");

 Route::get("distribute_score/{id?}", "MarkDistributionController@distribute_score"
 )->name("distribute_score");
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-mark/{action?}",
    "MarkController@statusChange"
)->name("mark_action_from_admin");

Route::any(
    "do-status-change-for-grade/{action?}",
    "GradeController@statusChange"
)->name("grade_action_from_admin");

Route::any(
    "do-status-change-for-mdistribute/{action?}",
    "MarkDistributionController@statusChange"
)->name("mdistribute_action_from_admin");

Route::any("deletegrade", "GradeController@deletegrade")->name("deletegrade");

/*
 * resource controller
 */
Route::resource("mark", "MarkController");
Route::resource("grade", "GradeController");
Route::resource("markdistribution", "MarkDistributionController");
Route::resource("promotion", "PromotionController");
Route::resource("distribute_mark", "DistributeMarkController");
Route::any("getpromotstudents", "PromotionController@getpromotstudents")->name(
    "getpromotstudents"
);

Route::any("examtitleexists", "MarkController@examTitleexist")->name(
    "examTitleexist"
);
Route::post("getappend", "MarkController@getappend")->name(
    "getappend"
);

Route::get("add_mark_distribution", "DistributeMarkController@add_mark_distribution")->name(
    "add_mark_distribution"
);

Route::post("status_change", "DistributeMarkController@statusChange")->name(
    "status_change"
);
