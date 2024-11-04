<?php
/*
 * get countries data
 */
Route::post("get-gallery-data", "GalleryController@getData")->name(
    "get_gallery_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-gallery/{action?}",
    "GalleryController@statusChange"
)->name("gallery_action_from_admin");
/*
 * resource controller
 */
Route::resource("gallery", "GalleryController");
