export default class ClassTimetable {
    static ClassTimetableInit(notify_script, type = null) {
        $(".add_class_periods").on("click", function () {
            console.log('its in');
            let academic_year = $('select[name="academic_year"]').val();
            let class_id = $('select[name="class_id"]').val();
            let section_id = $('select[name="section_id"]').val();
            let no_days = $("#nodays").val();
            let url =
                window.append_new_periods +
                "?type=" +
                "2" +
                "&academic_year=" +
                academic_year +
                "&class_id=" +
                class_id +
                "&section_id=" +
                section_id +
                "&no_days=" +
                no_days ;
               

            if (
                url &&
                academic_year &&
                class_id &&
                section_id &&
                no_days 
            ) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.view) {
                            $(".append_card").show();
                            $("#append_div").empty();
                            $("#append_div").append(response.data.view);
                            $(".btn_submit").show();
                            $(".single-select").each(function () {
                                $(this).select2({
                                    theme: "bootstrap4",
                                    width: $(this).data("width")
                                        ? $(this).data("width")
                                        : $(this).hasClass("w-100")
                                        ? "100%"
                                        : "style",
                                    placeholder: $(this).data("placeholder"),
                                    allowClear: Boolean(
                                        $(this).data("allow-clear")
                                    ),
                                });
                            });
                        } else {
                            $(".append_card").show();
                            $("#append_div").empty();
                            $("#append_div").append(response.data.message);
                            $(".btn_submit").show();
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                        // Handle errors here
                    });
            } else {
                notify_script(
                    "Error",
                    "Please Fill Required Fields",
                    "error",
                    true
                );
            }
        });

        $("#add_new_button").on("click", function () {
            console.log("yes");
            let url = window.append_new_periods + "?type=" + "1";
            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        if (response.data.view) {
                            $("#append_div").append(response.data.view);
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                        // Handle errors here
                    });
            } else {
                notify_script(
                    "Error",
                    "No Append period Found ",
                    "error",
                    true
                );
            }
        });

        $(".delete-row-period").on("click", function () {
            var period_id = $(this).attr("id");
            let url = window.delete_period + "?id=" + period_id + "&type=" + "4";

            if (url) {
                axios;

                Swal.fire({
                    title: "Are you sure?",
                    text: "This period has used in Some classes Timetable if You deleted Here Timetable Period Also Deleted",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .post(url)
                            .then((response) => {
                                $(this).closest(".row").remove();
                                notify_script(
                                    "Success",
                                    "Period Deleted Successfully",
                                    "success",
                                    true
                                );
                            })
                            .catch((error) => {
                                console.log("error");
                            });
                        //target.submit();
                    }
                });
            }
        });
    }
}