const mix = require("laravel-mix");

// Compile your JavaScript files
mix.js("resources/js/vue/app.js", "public/js/vue").vue();
mix.js("resources/js/app.js", "public/js").postCss(
    "resources/css/app.css",
    "public/css",
    [
        //
    ]
);

// Compile individual CSS files into one
mix.styles(
    [
        "public/skin/theme1/theme/vendors/plugins/vectormap/jquery-jvectormap-2.0.2.css",
        "public/skin/theme1/theme/vendors/plugins/simplebar/css/simplebar.css",
        "public/skin/theme1/theme/vendors/plugins/perfect-scrollbar/css/perfect-scrollbar.css",
        "public/skin/theme1/theme/vendors/plugins/metismenu/css/metisMenu.min.css",
        "public/skin/theme1/theme/vendors/css/pace.min.css",
        "public/skin/theme1/theme/vendors/plugins/datetimepicker/css/classic.time.css",
        "public/skin/theme1/theme/vendors/plugins/datetimepicker/css/classic.css",
    ],
    "public/css/all.css" // The output path for the combined CSS file
);

// mix.js(
//     [
//         "public/assets/backend/js/videosdk/index.js",
//         "https://sdk.videosdk.live/js-sdk/0.0.82/videosdk.js",
//         "public/assets/backend/js/videosdk/config.js",
//     ],
//     "public/js/videomeet.js"
// );

// Optionally, if you want to keep them separate, you can use mix.styles() for each one
// mix.styles('resources/css/vectormap/jquery-jvectormap-2.0.2.css', 'public/css/jquery-jvectormap-2.0.2.css');
// mix.styles('resources/css/simplebar.css', 'public/css/simplebar.css');
// ... and so on for each CSS file

// Enable versioning for cache-busting (optional)
if (mix.inProduction()) {
    mix.version();
}
