<?php
/*
 * get countries data
 */
use cms\cmsmenu\Controllers\AboutUsController;
use cms\cmsmenu\Controllers\EventsMenuController;
use cms\cmsmenu\Controllers\GalleryMenuController;
use cms\cmsmenu\Controllers\ContactUsMenuController;

Route::post("get-cmsmenu-data", "CmsmenuController@getData")->name(
    "get_cmsmenu_data_from_admin"
);
/*
 * bulk action
 */
Route::post(
    "do-status-change-for-cmsmenu/{action}",
    "CmsmenuController@statusChange"
)->name("cmsmenu_action_from_admin");
/*
 * resource controller
 */
Route::resource("cmsmenu", "CmsmenuController");

Route::resource("aboutus", "AboutUsController");

Route::get("eventsmenu", "EventsMenuController@Index")->name(
    "eventsmenu_index"
);

Route::post("eventsmenu_store", "EventsMenuController@Store")->name(
    "eventsmenu_store"
);

Route::resource("gallerymenu", "GalleryMenuController");

Route::resource("academicsmenu", "AcademicsController");

Route::resource("contactusmenu", "ContactUsMenuController");
