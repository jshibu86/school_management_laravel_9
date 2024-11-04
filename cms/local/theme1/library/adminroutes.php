<?php
/*
 * get countries data
 */
Route::post("get-library-data", "LibraryController@getData")->name(
    "get_library_data_from_admin"
);

Route::post(
    "get-library-history--data/{id?}",
    "LibraryController@getDatahistoryBook"
)->name("get_historybook_data_from_admin");

Route::post(
    "get-library-member-history--data/{id?}",
    "MemberController@getDatahistoryMember"
)->name("get_historymember_data_from_admin");

Route::post("get-librarymember-data", "MemberController@getData")->name(
    "get_library_member_data_from_admin"
);
Route::post("get-library-cat-data", "BookCategoryController@getData")->name(
    "get_library_data_cat_from_admin"
);

Route::post("get-library-issued-data", "IssuedBookController@getData")->name(
    "get_library_data_issued_from_admin"
);

Route::get("history-book/{id?}", "LibraryController@historyBook")->name(
    "libraryhistorybook"
);

Route::get("history-member/{id?}", "MemberController@historyMember")->name(
    "memberhistorybook"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-library/{action?}",
    "LibraryController@statusChange"
)->name("library_action_from_admin");

Route::post(
    "library-book-status-change",
    "LibraryController@BookStatusChange"
)->name("BookStatusChange");

Route::any(
    "do-status-change-for-category/{action?}",
    "BookCategoryController@statusChange"
)->name("category_action_from_admin");

Route::any(
    "do-status-change-for-member/{action?}",
    "MemberController@statusChange"
)->name("member_action_from_admin");

Route::any(
    "do-status-change-for-issued/{action?}",
    "IssuedBookController@statusChange"
)->name("issued_action_from_admin");
/*
 * resource controller
 */
Route::get("issuedbooks", "LibraryController@issuedBooks")->name("issuedBooks");
Route::resource("library", "LibraryController");

Route::resource("bookcategory", "BookCategoryController");

Route::get("member/show/{id?}/{print?}", [
    "as" => "member.show",
    "uses" => "MemberController@show",
]);
Route::resource("member", "MemberController", ["except" => "show"]);

Route::resource("ebook", "EbookController");

Route::resource("issuebook", "IssuedBookController");

Route::get("return-book/{id?}", "IssuedBookController@returnBook")->name(
    "issuebook.returnBook"
);
