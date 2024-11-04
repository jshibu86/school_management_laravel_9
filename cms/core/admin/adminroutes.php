<?php

Route::group([], function () {
    /*
     * backend dashboard
     */
    Route::get("/dashboard", "AdminAuth@dashboard")->name("backenddashboard");

    Route::get("logout", "AdminAuth@logout")->name("log_out_from_admin");

    Route::get("plans_list", "AdminAuth@PlanList")->name("plans_list");
});
