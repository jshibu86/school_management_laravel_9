<?php
use cms\core\configurations\Traits\FileUploadTrait;
use Illuminate\Http\Request;
/*
 * get countries data
 */
Route::post("get-students-data", "StudentsController@getData")->name(
    "get_students_data_from_admin"
);
/*
 * bulk action
 */
Route::any(
    "do-status-change-for-students/{action?}",
    "StudentsController@statusChange"
)->name("students_action_from_admin");
/*
 * resource controller
 */
Route::resource("students", "StudentsController");

Route::any("DeleteAttachment", "StudentsController@DeleteAttachment")->name(
    "DeleteAttachment"
);
Route::any("bulk-upload/{action?}", "StudentsController@Bulkupload")->name(
    "students.bulkupload"
);
Route::any("Assigenparent", "StudentsController@Assigenparent")->name(
    "students.Assigenparent"
);

Route::get(
    "student/idcard/{student_id?}",
    "StudentsController@Printidcard"
)->name("students.printidcard");


Route::post('/student/forgetPassword', 'StudentsController@forgetPassword')->name('students.forgetpassword');
