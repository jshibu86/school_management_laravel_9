import AcademicConfig from "./AcademicConfig.js";
import GeneralConfig from "./GeneralConfig.js";

export default class AcademicYearConfig {
    static AcademicyearInit() {
        $(".termacademicyear").on("change", function () {
            let academic_year = $(this).val();
            AcademicYearConfig.getTerms(academic_year);
        });
    }

    static getTerms(academic_year) {
        console.log("here");
        let getUrl =
            window.fetchstudents +
            "?type=" +
            "4" +
            "&academic_year=" +
            academic_year;
        if (academic_year) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        $('select[name="academic_term"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select term...",
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        $('select[name="academic_term"]')
                            .empty()
                            .select2({ placeholder: "select term..." });
                        GeneralConfig.notify(
                            "Error",
                            "This academic year has no terms. Please add terms to proceed. ",
                            "error",
                            true
                        );

                        Pace.stop();
                    }
                })
                .catch((error) => {
                    console.log(error);
                    //notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            $('select[name="academic_term"]')
                .empty()
                .select2({ placeholder: "Pick a Term..." });
        }
    }
}
