<?php
/*
 * get countries data
 */
Route::post("get-leave-data", "LeaveController@getData")->name(
    "get_leave_data_from_admin"
);

Route::post("get-leave-type-data", "LeaveController@getDataLeavetype")->name(
    "get_leave_type_data_from_admin"
);

Route::post(
    "get-leave-type-data/{id?}",
    "LeaveController@gethistoryLeave"
)->name("gethistoryLeave");

/*
 * bulk action
 */
Route::any(
    "do-status-change-for-leave/{id?}/{action?}",
    "LeaveController@statusChange"
)->name("leave_action_from_admin");
/*
 * resource controller
 */
Route::resource("leave", "LeaveController");
Route::any("leavetypes", "LeaveController@leavetypes")->name(
    "leave.leavetypes"
);

Route::any("approvedrequests", "LeaveController@approvedrequests")->name(
    "leave.approvedrequests"
);

Route::any("leavetypeStore", "LeaveController@leavetypeStore")->name(
    "leave.leavetypeStore"
);

Route::any("leavetypeEdit/{id?}", "LeaveController@leavetypeEdit")->name(
    "leavetype.edit"
);

Route::any("leavetypecreate", "LeaveController@leavetypecreate")->name(
    "leavetype.create"
);

Route::any("leavetypeDestroy/{id?}", "LeaveController@leavetypeDestroy")->name(
    "leavetype.destroy"
);

Route::get("leave-print/{id?}", "LeaveController@leaveprint")->name(
    "leaveprint"
);
