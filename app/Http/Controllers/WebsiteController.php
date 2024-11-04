<?php

namespace App\Http\Controllers;

use cms\cmsmenu\Models\CmsmenuModel;
use Illuminate\Http\Request;
use cms\event\Models\EventModel;
use App\Models\EventsMenuModel;
use cms\gallery\Models\GalleryModel;
use cms\cmsmenu\Models\GalleryMenuModel;
use cms\cmsmenu\Models\ContactUsModel;
use cms\cmsmenu\Models\ContactUsMenuModel;
use Illuminate\Pagination\LengthAwarePaginator;
use cms\teacher\Models\TeacherModel;
use Carbon\Carbon;
use Session;
use Configurations;

class WebsiteController extends Controller
{
    public function index()
    {
        // dd("here");
        $cmsMenuModel = new CmsmenuModel();
        $activeRecord = CmsmenuModel::where("type", "=", "1")
            ->pluck("value", "key")
            ->toArray();
        $events = EventModel::where("status", 1)
            ->orderBy("created_at", "desc")
            ->limit(3)
            ->get()
            ->toArray();
        $teachers = TeacherModel::where("status", 1)
            ->orderBy("created_at", "desc")
            ->limit(6)
            ->get(["teacher_name", "image", "qualification"])
            ->toArray();

        return view("website.home", [
            "data" => $activeRecord,
            "events" => $events,
            "teachers" => $teachers,
        ]);
    }

    public function aboutus()
    {
        $data = CmsmenuModel::where("type", "=", "2")
            ->pluck("value", "key")
            ->toArray();

        $teachers = TeacherModel::where("status", 1)
            ->orderBy("created_at", "desc")
            ->limit(6)
            ->get(["teacher_name", "image", "qualification"])
            ->toArray();

        return view("website.about", [
            "data" => $data,
            "teachers" => $teachers,
        ]);
    }

    public function academics()
    {
        $acad_data = [];
        $acad_data = CmsmenuModel::where("type", "=", "3")
            ->pluck("value", "key")
            ->toArray();

        // dd($acad_data);
        return view("website.courses", [
            "data" => $acad_data,
        ]);
    }

    public function EventsPage()
    {
        $event_menu = EventsMenuModel::get();
        $events = EventModel::where("status", 1)
            ->orderBy("event_date", "desc")
            ->paginate(3);

        // dd($events);
        $menu = $event_menu->pluck("value", "key")->toArray();
        if (request()->ajax()) {
            return view("website.events", compact("events"))->render();
        }
        return view("website.events", [
            "menu" => $menu,
            "events" => $events,
        ]);
    }
    public function GalleryPage()
    {
        $gallery_menu = GalleryMenuModel::get();
        $gallery = GalleryModel::where("status", 1)
            ->orderBy("created_date", "desc")
            ->paginate(3);

        // dd($events);
        $menu = $gallery_menu->pluck("value", "key")->toArray();
        if (request()->ajax()) {
            return view("website.gallery", compact("gallery"))->render();
        }
        return view("website.gallery", [
            "menu" => $menu,
            "gallery" => $gallery,
        ]);
    }
    public function ContactUsPage()
    {
        $contactus_menu = ContactUsMenuModel::get();
        $school = Configurations::getConfig("site");
        $data = $contactus_menu->pluck("value", "key")->toArray();
        return view("website.contact", [
            "data" => $data,
            "info" => $school,
        ]);
    }

    public function SendMessage(Request $request)
    {
        $this->validate(
            $request,
            [
                "name" => "required",
                "email" => "required|email",
                "subject" => "required",
                "message" => "required",
            ],
            [
                "name.required" => "The name field is required.",
                "email.required" => "The email field is required.",
                "email.email" => "The email must be a valid email address.",
                "subject.required" => "The subject field is required.",
                "message.required" => "The message field is required.",
            ]
        );

        $message = new ContactUsModel();
        $message->name = $request->name;
        $message->email = $request->email;
        $message->subject = $request->subject;
        $message->message = $request->message;
        $message->date = Carbon::now();

        if ($message->save()) {
            Session::flash("success", "Message Sent Successfully!!");
            return redirect()->back();
        }
    }
}
