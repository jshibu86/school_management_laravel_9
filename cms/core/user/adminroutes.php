<?php
Route::group(["prefix" => "users"], function () {
    Route::get("/", function () {
        return redirect()->route("user.index");
    });
    //get users list
    Route::post("data", "UserController@getData")->name(
        "get_user_data_from_admin"
    );

    //bulk option
    Route::any("action/{action?}", "UserController@statusChange")->name(
        "user_action_from_admin"
    );

    Route::resource("user", "UserController");

    Route::get("profile", "UserController@Profile")->name("profile");
    Route::get("edit_profile", "UserController@EditProfile")->name(
        "edit_profile"
    );
    Route::post("updateprofile", "UserController@UpdateProfile")->name(
        "updateprofile"
    );
    //readnotification

    Route::get(
        "readnotifications",
        "NotificationController@readNotifications"
    )->name("readNotifications");
});
