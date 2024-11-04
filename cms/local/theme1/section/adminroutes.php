<?php
/*
 * get countries data
 */
Route::post("get-section-data", "SectionController@getData")->name(
    "get_section_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-section/{action?}",
    "SectionController@statusChange"
)->name("section_action_from_admin");
/*
 * resource controller
 */
Route::resource("section", "SectionController");
