<?php
/*
 * get countries data
 */
Route::group(["prefix" => "schoolonboard"], function () {
    Route::any(
        "do-status-change-for-students/{action?}",
        "SchoolmanagementController@statusChange"
    )->name("status_change_from_admin");

    Route::post(
        "get_school_data_from_admin",
        "SchoolmanagementController@getData"
    )->name("get_school_data_from_admin");

    Route::post(
        "get_school_tenant_data_from_admin",
        "TenantInfoController@getData"
    )->name("get_school_tenant_data_from_admin");

    /*
     * bulk action
     */
    Route::post(
        "do-status-change-for-schoolmanagement/{action}",
        "SchoolmanagementController@statusChange"
    )->name("schoolmanagement_action_from_admin");
    /*
     * resource controller
     */
    Route::resource("schoolmanagement", "SchoolmanagementController");

    Route::resource("tenant_info", "TenantInfoController");

    // filter module list
    Route::post(
        "/filter-modules",
        "SchoolmanagementController@filterModuleList"
    )->name("schoolmanagement.filtermodulelist");

    Route::post(
        "/onboardapproval",
        "SchoolmanagementController@onboardApproval"
    )->name("schoolmanagement.onboardapproval");

    Route::post(
        "/approveSchool",
        "SchoolmanagementController@approveSchool"
    )->name("schoolmanagement.approveSchool");

    // payment calculation
    Route::post(
        "/calculate-payment",
        "SchoolmanagementController@paymentCalculation"
    )->name("schoolmanagement.paymentcalculation");

    Route::get("/revenue", "SchoolmanagementController@revenue")->name(
        "revenue"
    );
});
