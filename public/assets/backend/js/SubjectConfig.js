export default class SubjectConfig {
    static SubjectInit(notify_script) {
        $('select[name="school_type"]').on("change", function () {
            console.log("its wdsf");
            let school_type = $(this).val();
            let url =
                window.department +
                "?school_type=" +
                school_type +
                "&type=is_department";
            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        if (response.data) {
                            if (response.data.is_department == 1) {
                                $(".schooldepartment").removeClass("d-none");
                            } else {
                                $(".schooldepartment").addClass("d-none");
                            }
                        } else {
                            notify_script(
                                "Error",
                                "Response Not found.",
                                "error",
                                true
                            );
                        }
                    })
                    .catch((error) => {
                        let status = error;
                        console.log(status);
                        notify_script("Error", status, "error", true);
                    });
            } else {
                notify_script(
                    "Error",
                    "Please select school type.",
                    "error",
                    true
                );
            }
        });
    }
}
