<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use cms\core\user\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use cms\fees\Controllers\FeesController;
use cms\core\admin\Controllers\AdminAuth;
use cms\admission\Controllers\AdmissionController;
use App\Http\Controllers\WebsiteController;
use App\Http\Middleware\VerifyTenantStatus;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    "web",
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
    VerifyTenantStatus::class,
])->group(function () {
    // Route::get("/", function () {
    //     return "This is your multi-tenant application. The id of the current tenant is " .
    //         tenant("id");
    // });
    Route::get("/", [WebsiteController::class, "index"])->name("website");
    Route::get("/error", function () {
        return view("errors.statuserror");
    })->name("errorPage");
    Route::get("/about", [WebsiteController::class, "aboutus"])->name(
        "website.about"
    );

    Route::get("/contact", function () {
        return View::make("website.contact");
    });

    // Route::get("/events", function () {
    //     return View::make("website.events");
    // })->name("events");

    Route::get("/events", [WebsiteController::class, "EventsPage"])->name(
        "events"
    );

    // Route::get("/gallery", function () {
    //     return View::make("website.gallery");
    // });
    Route::get("/gallery_page", [
        WebsiteController::class,
        "GalleryPage",
    ])->name("gallery_page");

    Route::get("/contactus_page", [
        WebsiteController::class,
        "ContactUsPage",
    ])->name("contactus_page");

    Route::post("/send_message", [
        WebsiteController::class,
        "SendMessage",
    ])->name("send_message");

    // Route::get("/courses", function () {
    //     return View::make("website.courses");
    // });

    Route::get("/courses", [WebsiteController::class, "academics"])->name(
        "courses"
    );

    Route::get("/schooling", function () {
        return View::make("website.schooling");
    });

    Route::get("/faq", function () {
        return View::make("website.faq");
    });

    Route::get("/single", function () {
        return View::make("website.single");
    });

    Route::get("/casestudy", function () {
        return View::make("website.casestudy");
    });

    Route::get("/admission", [
        AdmissionController::class,
        "getClassList",
    ])->name("admission");

    Route::post("/admission/create", [
        AdmissionController::class,
        "store",
    ])->name("admission.create");
});
