export default class PromotionConfig {
    static PromotionInit(notify_script) {
        $(".academic_year_from").on("change", function () {
            let academic_year = $(this).val();
            let element = $('select[name="academic_year_to"]');

            PromotionConfig.getInfo(0, academic_year, element, "academic year");
        });

        $("#school_type").on("change", function () {
            let school_type_id = $(this).val();
            let element = $('select[name="school_type_to"]');
            PromotionConfig.getInfo(2, school_type_id, element, "school type");
            console.log("school");
            console.log(school_type_id);
        });

        $("#class_id_grade").on("change", function () {
            console.log("OK CLASS");
            let class_id = $(this).val();
            console.log(class_id);
            let element = $('select[name="class_id"]');
            // PromotionConfig.getSection(class_id);
            //Grade Report
            PromotionConfig.getSubject(1, class_id, element, "Subject");
        });

        $("#school_types_to").on("change", function () {
            let school_type_id = $(this).val();
            let element = $('select[name="school_types_to"]');
            PromotionConfig.getInfos(2, school_type_id, element, "school type");
            console.log("school");
            console.log(school_type_id);
        });

        $(".class_id_to").on("change", function () {
            let class_id = $(this).val();
            let element = $('select[name="class_id_to"]');
            PromotionConfig.getSection(class_id);
        });

        $(".section_id_from").on("change", function () {
            let section_id = $(this).val();
            let element = $('select[name="section_id_to"]');
            console.log(section_id);
        });

        //Grade Report

        $("#school_type_grade").on("change", function () {
            let school_type_id = $(this).val();
            let element = $('select[name="school_type"]');
            PromotionConfig.getInfoGrade(
                2,
                school_type_id,
                element,
                "school type"
            );
            console.log("Grade School Type");
            console.log(school_type_id);
        });

        $(".getpromotestudent").on("click", function () {
            let academic_year_from = $(".academic_year_from").val();
            let class_id_from = $(".class_id_from").val();
            let section_id_from = $(".section_id_from").val();
            let academic_year_to = $(".academic_year_to").val();
            let class_id_to = $(".class_id_to").val();
            let section_id_to = $(".section_id_to").val();
            let promotion_type = $(".promotion_type").val();

            if (
                academic_year_from &&
                class_id_from &&
                section_id_from &&
                academic_year_to &&
                class_id_to
            ) {
                let obj = {
                    academic_year_from,
                    class_id_from,
                    section_id_from,
                    academic_year_to,
                    class_id_to,
                    section_id_to,
                    promotion_type,
                };

                PromotionConfig.getpromotionStudents(obj);
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All required Feilds",
                    "error",
                    true
                );
            }
        });
    }

    static getInfoGrade(type, id, element, placeholder) {
        let getUrl =
            window.getgradeinfo +
            "?type=" +
            type +
            "&academic_year=" +
            id +
            "&school_type_grade=" +
            id +
            "&class_id=" +
            id +
            "&section_id=" +
            id;
        if (id) {
            console.log("OK1");
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);
                    if (Object.keys(response.data).length) {
                        let optionitems = response.data.map((item) => ({
                            id: item.id,
                            text: item.text,
                        }));

                        element;
                        $('select[name="class_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: `Select ${placeholder}`,
                                data: optionitems,
                            });

                        Pace.stop();
                    } else {
                        $('select[name="class_id"]')
                            .empty()
                            .select2({ placeholder: `Select ${placeholder}` });

                        Pace.stop();
                    }
                })
                .catch((error) => {
                    console.log(error);
                    //notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            element.empty().select2({ placeholder: `Select ${placeholder}` });
        }
    }

    static getSubject(type, id, element, placeholder) {
        let getUrl =
            window.getgradeinfo +
            "?type=" +
            type +
            "&academic_year=" +
            id +
            "&school_type_grade=" +
            id +
            "&class_id=" +
            id +
            "&section_id=" +
            id;
        if (id) {
            axios
                .get(getUrl)
                .then((response) => {
                    let selectElement = $('select[name="subject_id_grade"]');
                    console.log("OK3");
                    console.log(response);
                    if (response.data && typeof response.data === "object") {
                        let optionItems;

                        if (Array.isArray(response.data)) {
                            // Assuming the data is already in the correct format
                            optionItems = response.data;
                        } else {
                            // If the data is not an array, you may need to adapt this part
                            optionItems = Object.keys(response.data).map(
                                (key) => ({
                                    id: key,
                                    text: response.data[key],
                                })
                            );
                        }

                        console.log("OK4");
                        console.log(optionItems);

                        element;
                        $('select[name="subject_id_grade"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: `Select ${placeholder}`,
                                data: optionItems,
                            });

                        Pace.stop();
                    } else {
                        selectElement
                            .empty()
                            .select2({ placeholder: "Select subject..." });
                    }
                })
                .catch((error) => {
                    let status = error;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            element.empty().select2({ placeholder: `Select ${placeholder}` });
        }
    }
    static getInfo(type, id, element, placeholder) {
        let getUrl =
            window.getpromotioninfo +
            "?type=" +
            type +
            "&academic_year=" +
            id +
            "&school_type_from=" +
            id +
            "&class_id=" +
            id +
            "&section_id=" +
            id;
        if (id) {
            console.log("OK1");
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);
                    if (Object.keys(response.data).length) {
                        element;
                        $('select[name="class_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: `Select ${placeholder}`,
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        $('select[name="class_id"]')
                            .empty()
                            .select2({ placeholder: `Select ${placeholder}` });

                        Pace.stop();
                    }
                })
                .catch((error) => {
                    console.log(error);
                    //notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            element.empty().select2({ placeholder: `Select ${placeholder}` });
        }
    }

    static getInfos(type, id, element, placeholder) {
        let getUrl =
            window.getpromotioninfo +
            "?type=" +
            type +
            "&academic_year=" +
            id +
            "&school_type_from=" +
            id +
            "&class_id=" +
            id +
            "&section_id=" +
            id;
        if (id) {
            console.log("OK1");
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);
                    if (Object.keys(response.data).length) {
                        element;
                        $('select[name="class_id_to"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: `Select ${placeholder}`,
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        $('select[name="class_id_to"]')
                            .empty()
                            .select2({ placeholder: `Select ${placeholder}` });

                        Pace.stop();
                    }
                })
                .catch((error) => {
                    console.log(error);
                    //notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            element.empty().select2({ placeholder: `Select ${placeholder}` });
        }
    }

    static getSection(class_id) {
        let getUrl = window.sectionurl + "?class=" + class_id;

        if (class_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        element;
                        $('select[name="section_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select section...",
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        $('select[name="section_id"]')
                            .empty()
                            .select2({ placeholder: "select section..." });

                        Pace.stop();
                    }
                })
                .catch((error) => {
                    let status = error.response;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            $('select[name="section_id"]')
                .empty()
                .select2({ placeholder: "Pick a section..." });
        }
    }

    static getpromotionStudents(obj) {
        console.log(obj);

        let getUrl = window.getpromotionstudentinfo;

        if (class_id) {
            axios
                .post(getUrl, obj)
                .then((response) => {
                    if (response.data.message) {
                        console.log(response.data.message);
                        $(".promote_students").html("");
                    } else {
                        $(".promote_students").html("");
                        $(".promote_students").html(response.data.viewfile);
                    }
                })
                .catch((error) => {
                    let status = error.response;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            $('select[name="section_id"]')
                .empty()
                .select2({ placeholder: "Pick a section..." });
        }
    }
}
