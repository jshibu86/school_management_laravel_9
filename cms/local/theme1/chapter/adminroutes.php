<?php
/*
 * get countries data
 */
Route::post("get-chapter-data", "ChapterController@getData")->name(
    "get_chapter_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-chapter/{action?}",
    "ChapterController@statusChange"
)->name("chapter_action_from_admin");
/*
 * resource controller
 */
Route::resource("chapter", "ChapterController");

//add topics
Route::resource("chaptertopic", "ChapterTopicController");

Route::post(
    "get-chapter-topic-data/{id?}",
    "ChapterTopicController@getData"
)->name("get_chapter_topic_data_from_admin");
