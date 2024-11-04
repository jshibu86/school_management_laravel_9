<?php
/*
 * get countries data
 */
Route::post("get-account-data", "AccountController@getData")->name(
    "get_account_data_from_admin"
);

Route::post(
    "get-accountcategory-data",
    "IncomeExpenseCategoryController@getData"
)->name("get_accountcategory_data_from_admin");

Route::post("get-expense-data", "ExpenseController@getData")->name(
    "get_expense_data_from_admin"
);
Route::post("get-income-data", "IncomeController@getData")->name(
    "get_income_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-account/{action?}",
    "AccountController@statusChange"
)->name("account_action_from_admin");

Route::any(
    "do-status-change-for-accountcategory/{action?}",
    "IncomeExpenseCategoryController@statusChange"
)->name("accountcategory_action_from_admin");

Route::any(
    "do-status-change-for-expense/{action?}",
    "ExpenseController@statusChange"
)->name("expense_action_from_admin");

Route::any(
    "do-status-change-for-income/{action?}",
    "IncomeController@statusChange"
)->name("income_action_from_admin");
/*
 * resource controller
 */
Route::resource("account", "AccountController");
Route::resource("accountcategory", "IncomeExpenseCategoryController");
Route::resource("income", "IncomeController");
Route::resource("expense", "ExpenseController");

Route::any(
    "incomeexpensecollectionreport",
    "AccountController@IncomeExpenseCollectionReport"
)->name("IncomeExpenseCollectionReport");

Route::any(
    "incomeexpensereportview",
    "AccountController@IncomeExpenseReportView"
)->name("IncomeExpenseReportView");
