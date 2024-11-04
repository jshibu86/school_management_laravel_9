<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EventsMenuModel;
use Illuminate\Support\Carbon;
use cms\core\configurations\Models\ConfigurationModel;
use cms\cmsmenu\Models\GalleryMenuModel;
use cms\cmsmenu\Models\ContactUsMenuModel;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $event = [
            [
                "key" => "banner_title",
                "value" => "Events / Activities",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
            [
                "key" => "banner_description",
                "value" =>
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
            [
                "key" => "banner_image",
                "value" => "images/bgimg7.jpg",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
        ];

        $gallery = [
            [
                "key" => "banner_title",
                "value" => "Gallery",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
            [
                "key" => "banner_description",
                "value" =>
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
            [
                "key" => "banner_image",
                "value" => "images/school3.jpg",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
        ];

        $contactus = [
            [
                "key" => "banner_title",
                "value" => "Contact Us",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
            [
                "key" => "banner_description",
                "value" =>
                    "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
            [
                "key" => "banner_image",
                "value" => "images/banner1.jpg",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
            [
                "key" => "location_link",
                "value" =>
                    "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2484.152182010552!2d-0.06130907572438291!3d51.49207476404589!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4876031847ea7ca1%3A0x707bc89a00580b87!2s43%20Raymouth%20Rd%2C%20London%203910%2C%20UK!5e0!3m2!1sen!2sin!4v1661592874787!5m2!1sen!2sin",
                "updated_at" => Carbon::now(),
                "created_at" => Carbon::now(),
            ],
        ];

        EventsMenuModel::insert($event);
        GalleryMenuModel::insert($gallery);
        ContactUsMenuModel::insert($contactus);
    }
}
