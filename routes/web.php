<?php

use cms\core\user\Mail\TestMail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use cms\fees\Controllers\FeesController;
use cms\core\admin\Controllers\AdminAuth;
use cms\admission\Controllers\AdmissionController;
use App\Http\Controllers\WebsiteController;

/*

|--------------------------------------------------------------------------
| Web Routes central db
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// this is the place to the superadmin website routes come
Route::get("/", function () {
    return view("welcome");
})->name("website");

Route::get("logs", [
    \Rap2hpoutre\LaravelLogViewer\LogViewerController::class,
    "index",
])->name("logs");
Route::get("/clear-cache", function () {
    $exitCode = Artisan::call("cache:clear");
    return "clear";
})->name("clear-cache");

Route::get("/sendmail", function () {
    try {
        \CmsMail::setMailConfig();
        Mail::to("johnviju.412@gmail.com")->send(new TestMail());
        dd("done");
    } catch (\Exception $e) {
        dd($e);
    }
});
