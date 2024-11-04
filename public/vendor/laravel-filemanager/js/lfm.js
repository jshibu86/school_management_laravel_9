(function ($) {
    $.fn.filemanager = function (type, options) {
        type = type || "file";
        console.log(type, options);

        this.on("click", function (e) {
            var route_prefix =
                options && options.prefix
                    ? options.prefix
                    : "/laravel-filemanager";
            localStorage.setItem("target_input", $(this).data("input"));
            localStorage.setItem("target_preview", $(this).data("preview"));
            window.open(
                route_prefix + "?type=" + options.type + "?&multiple=true" ||
                    "file",
                "FileManager",
                "width=900,height=600"
            );
            window.SetUrl = function (url, file_path) {
                console.log(url);
                //set the value of the desired input to image url
                var target_input = $(
                    "#" + localStorage.getItem("target_input")
                );
                target_input.val(file_path).trigger("change");

                //set or change the preview image src
                var target_preview = $(
                    "#" + localStorage.getItem("target_preview")
                );
                target_preview.attr("src", url).trigger("change");
            };
            return false;
        });
    };
})(jQuery);
