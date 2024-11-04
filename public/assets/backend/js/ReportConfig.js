import AcademicConfig from "./AcademicConfig.js";

export default class ReportConfig {
    static ReportInit(notify_script, type = null) {
        // get mark report

        $(".report_mark").on("click", function (e) {
            e.preventDefault();

            // let class_id = $('select[name="class_id"]').val();
            // let section_id = $('select[name="section_id"]').val();
            let acyear = $('select[name="academic_year"]').val();
            let term = $('select[name="academic_term"]').val();
            // let exam_type = $('select[name="exam_type"]').val();
            let student_id =
                $('select[name="student_id"]').val() ||
                $('input[name="student_id"]').val();
            let type = $(this).attr("id");

            if (acyear && term && student_id) {
                ReportConfig.GetMarkReport(acyear, term, student_id, type);
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All Required Feilds",
                    "error",
                    true
                );
            }
        });

        $(".exam_score").on("input", function () {
            console.log("it input");
            var score = $(this).val();
            var regex = /^[0-9]+$/;
            if (score) {
                if (!score.match(regex)) {
                    $(this).val("");
                    notify_script(
                        "Error",
                        "Add Score field only accept numbers",
                        "error",
                        true
                    );
                }
            }
        });

        $('select[name="report_type"]').on("change", function () {
            let type = $(this).val();

            $(".startdate").val(" ");
            $(".enddate").val(" ");
            $(".month").val(" ");
            $(".day").val(" ");
            $(".year").val(" ");

            if (type == "daily") {
                $(".monthly").hide();
                $(".yearly").hide();
                $(".daily").show();
                $(".weekly").hide();
            } else if (type == "monthly") {
                $(".monthly").show();
                $(".yearly").hide();
                $(".daily").hide();
                $(".weekly").hide();
            } else if (type == "yearly") {
                $(".monthly").hide();
                $(".yearly").show();
                $(".daily").hide();
                $(".weekly").hide();
            } else if (type == "weekly") {
                $(".monthly").hide();
                $(".yearly").hide();
                $(".daily").hide();
                $(".weekly").show();
            }
        });

        $(".broadsheet_report_mark").on("click", function (e) {
            e.preventDefault();

            // let class_id = $('select[name="class_id"]').val();
            // let section_id = $('select[name="section_id"]').val();
            let acyear = $('select[name="academic_year"]').val();
            let term = $('select[name="academic_term"]').val();
            let class_id = $('select[name="class_id"]').val();
            let section_id = $('select[name="section_id"]').val();
            let exam_type = $('select[name="exam_type"]').val();

            let type = $(this).attr("id");

            if (acyear && term && class_id && section_id && exam_type) {
                ReportConfig.GetBroadSheetMarkReport(
                    acyear,
                    term,
                    class_id,
                    section_id,
                    exam_type
                );
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All Required Feilds",
                    "error",
                    true
                );
            }
        });

        $(".trasnportreport").on("click", function (e) {
            e.preventDefault();
            var form = $("#trasnportreport-form");

            const acyear = $('select[name="academic_year"]').val();
            const school_type = $('select[name="school_type"]').val();

            if (acyear && school_type) {
                const class_id = $('select[name="class_id"]').val();
                const section_id = $('select[name="section_id"]').val();
                const stop_id = $('select[name="transport_stop_id"]').val();
                const bus_id = $('select[name="transport_vehicle_id"]').val();
                const route_id = $('select[name="transport_route_id"]').val();
                const dormitory_id = $('select[name="dormitory_id"]').val();
                const room_id = $('select[name="room_id"]').val();

                var obj = {
                    class_id,
                    section_id,
                    stop_id,
                    bus_id,
                    route_id,
                    school_type,
                    acyear,
                    dormitory_id,
                    room_id,
                };
                console.log("transport report");
                // form.submit();
                $("#datatable-buttons1").hide();
                $("#datatable-buttons1").DataTable().destroy();
                ReportConfig.TransportReport(obj, 0, type);
                setTimeout(() => {
                    $("#datatable-buttons1").show();
                }, 1000);
            } else {
                notify_script(
                    "Error",
                    "Please Fill All Required Feild",
                    "error",
                    true
                );
            }
        });

        $("#leavestudent__report").on("click", function (e) {
            e.preventDefault();
            var form = $("#trasnportreport-form");

            const acyear = $('select[name="academic_year"]').val();
            const school_type = $('select[name="school_type"]').val();

            if (acyear && school_type) {
                const class_id = $('select[name="class_id"]').val();
                const section_id = $('select[name="section_id"]').val();

                var obj = {
                    class_id,
                    section_id,

                    school_type,
                    acyear,
                };

                // form.submit();
                $("#datatable-buttons1").hide();
                $("#datatable-buttons1").DataTable().destroy();
                ReportConfig.TransportReport(obj, 0, type);
                setTimeout(() => {
                    $("#datatable-buttons1").show();
                }, 1000);
            } else {
                notify_script(
                    "Error",
                    "Please Fill All Required Feild",
                    "error",
                    true
                );
            }
        });

        $("#payroll__report").on("click", function (e) {
            e.preventDefault();
            var form = $("#trasnportreport-form");

            const acyear = $('select[name="academic_year"]').val();
            const user_group = $('select[name="group"]').val();
            const member_id = $('select[name="member_id"]').val();

            if (acyear) {
                const month = $('input[name="month"]').val();

                var obj = {
                    user_group,
                    month,
                    member_id,
                    acyear,
                };
                console.log("payroll report");
                // form.submit();
                $("#datatable-buttons1").hide();
                $("#datatable-buttons1").DataTable().destroy();
                ReportConfig.TransportReport(obj, 0, type, true);
                setTimeout(() => {
                    $("#datatable-buttons1").show();
                }, 1000);
            } else {
                notify_script(
                    "Error",
                    "Please Fill All Required Feild",
                    "error",
                    true
                );
            }
        });

        $('select[name="service"]').on("change", function () {
            var val = $(this).val();

            if (val == 2) {
                // servive sales

                $("#sales_feilds").show();
            } else {
                $("#sales_feilds").hide();
            }
        });

        $("#tuckshop__report").on("click", function (e) {
            var service_id = $('select[name="service"]').val();

            var acyear = $('select[name="academic_year"]').val();

            if (!service_id || !acyear) {
                if (!start_date || !end_date) {
                    notify_script(
                        "Error",
                        "Please Select Service or Academic year Information",
                        "error",
                        true
                    );

                    return;
                }
            }
            var report_type = $('select[name="report_type"]').val();

            var day = $('input[name="day"]').val();
            var month = $('input[name="month"]').val();
            var start_date = $('input[name="start_date"]').val();
            var end_date = $('input[name="end_date"]').val();
            var year = $('input[name="year"]').val();
            // 1=>purchase,2=>sales

            // for sales feilds
            var payment_type = $('select[name="payment_type"]').val();
            var delivery_status = $('select[name="delivery_status"]').val();
            var payment_status = $('select[name="payment_status"]').val();

            //validation

            if (report_type == "weekly") {
                if (!start_date || !end_date) {
                    notify_script(
                        "Error",
                        "Please Fill out All Required Feilds Weekly",
                        "error",
                        true
                    );

                    return;
                }
            }
            if (report_type == "monthly") {
                if (!month) {
                    notify_script(
                        "Error",
                        "Please Fill out All Required Feilds Monthly",
                        "error",
                        true
                    );

                    return;
                }
            }
            if (report_type == "daily") {
                if (!day) {
                    notify_script(
                        "Error",
                        "Please Fill out All Required Feilds Daily",
                        "error",
                        true
                    );

                    return;
                }
            }
            if (report_type == "yearly") {
                if (!year) {
                    notify_script(
                        "Error",
                        "Please Fill out All Required Feilds yearly",
                        "error",
                        true
                    );

                    return;
                }
            }

            var obj = {
                service_id,
                report_type,
                day,
                month,
                start_date,
                end_date,
                year,
                payment_type,
                delivery_status,
                payment_status,
            };

            ReportConfig.getTuckShopReport(obj, 0, type, true);
            setTimeout(() => {
                $("#datatable-buttons1").show();
            }, 1000);

            return;
        });

        $('select[name="school_type"]').on("change", function () {
            let school_type = $(this).val();
            let academic_year = $('select[name="academic_year_grade"]').val();
            console.log(type);
            Pace.start();
            AcademicConfig.getClass(school_type, notify_script);

            // if (type != "hostel" && type != "transport" && type != "leave") {
            //     AcademicConfig.getStudents(
            //         0,
            //         0,
            //         notify_script,
            //         "inventory",
            //         school_type,
            //         academic_year
            //     );
            // }
        });

        $('select[name="section_id"]').on("change", function () {
            let academic_year = $('select[name="academic_year_grade"]').val();
            let academic_term = $('select[name="academic_term"]').val();
            let school_type = $('select[name="school_type"]').val();
            let class_id = $('select[name="class_id"]').val();
            let section_id = $('select[name="section_id"]').val();
            let getUrl =
                window.getgradeinfo +
                "?type=" +
                "5" +
                "&class_id=" +
                class_id +
                "&section_id=" +
                section_id +
                "&school_type=" +
                school_type +
                "&acyear_term=" +
                academic_term +
                "&academic_year=" +
                academic_year;
            if (getUrl) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (response.data.subjects) {
                            $('select[name="subject_id_grade"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "Select subject...",
                                    data: response.data.subjects,
                                });
                        } else {
                            $('select[name="subject_id_grade"]')
                                .empty()
                                .select2({
                                    placeholder: "Select Subject ...",
                                });

                            notify_script(
                                "Error",
                                "No Subjects Found ",
                                "error",
                                true
                            );
                        }
                    })
                    .catch((error) => {
                        let status = error;
                        console.log(status);
                        // notify_script("Error", status, "error", true);
                    });
            } else {
                // clear section list dropdown
                $('select[name="section_id"]')
                    .empty()
                    .select2({ placeholder: "Pick a section..." });
            }
        });
        $("#get_class_report").on("click", function (e) {
            e.preventDefault();
            const acyear = $('select[name="academic_year"]').val();
            const class_id = $('select[name="class_id"]').val();
            const section_id = $('select[name="section_id"]').val();
            const school_type = $('select[name="school_type"]').val();

            if (class_id && section_id && school_type) {
                ReportConfig.getClassreport(
                    class_id,
                    section_id,
                    school_type,
                    acyear
                );
            } else {
                notify_script(
                    "Error",
                    "Please Fill All Required Feild",
                    "error",
                    true
                );
            }
        });

        $(".gradereport").on("click", function (e) {
            e.preventDefault();

            console.log("here");
            e.preventDefault();
            const acyear = $('select[name="academic_year_grade"]').val();
            const acyear_term = $('select[name="academic_term"]').val();
            const class_id = $('select[name="class_id"]').val();
            const section_id = $('select[name="section_id"]').val();
            const school_type = $('select[name="school_type"]').val();
            const subject = $('select[name="subject_id_grade"]').val();
            if (subject) {
                if (class_id && section_id && school_type && subject) {
                    ReportConfig.GetGradeSubjectReport(
                        class_id,
                        section_id,
                        school_type,
                        acyear,
                        acyear_term,
                        subject
                    );
                } else {
                    notify_script(
                        "Error",
                        "Please Fill All Required Feild",
                        "error",
                        true
                    );
                }
            } else {
                if (class_id && section_id && school_type) {
                    ReportConfig.GetGradeReport(
                        class_id,
                        section_id,
                        school_type,
                        acyear,
                        acyear_term
                    );
                } else {
                    notify_script(
                        "Error",
                        "Please Fill All Required Feild",
                        "error",
                        true
                    );
                }
            }
        });

        // ReportConfig.TransportReport({}, 1, type, false);
    }
    static getStudentsMarkinfo(id, academic_year, position, term) {
        console.log(
            "Fetching student report for:",
            id,
            academic_year,
            position,
            term
        );
        let url = window.student_overall_report_info;
        console.log("Request URL:", url);

        if (url) {
            axios
                .post(url, {
                    id: id,
                    academic_year: academic_year,
                    position: position,
                    term_id: term,
                })
                .then((res) => {
                    console.log("Response:", res);
                    if (res && res.data && res.data.viewfile) {
                        $(".student_report_details").empty();
                        $(".student_report_details").html(res.data.viewfile);
                        $("#view_report").modal("show");
                    } else {
                        console.error("Invalid response data:", res);
                        // Handle the case where viewfile is not present in the response data
                    }
                })
                .catch((error) => {
                    console.error("Error fetching student report:", error);
                    // Handle AJAX error gracefully, e.g., display an error message to the user
                });
        } else {
            console.error("Invalid URL:", url);
        }
    }
    static getStudentsSubjectMarkinfo(
        id,
        academic_year,
        position,
        term,
        subject_id,
        class_id,
        section_id
    ) {
        console.log(
            "Fetching student report for:",
            id,
            academic_year,
            position,
            term,
            subject_id,
            class_id,
            section_id
        );
        let url = window.student_subject_report_info;
        console.log("Request URL:", url);

        if (url) {
            axios
                .post(url, {
                    id: id,
                    academic_year: academic_year,
                    position: position,
                    term_id: term,
                    subject: subject_id,
                    class: class_id,
                    section: section_id,
                })
                .then((res) => {
                    console.log("Response:", res);
                    if (res && res.data && res.data.viewfile) {
                        $(".student_report_details").empty();
                        $(".student_report_details").html(res.data.viewfile);
                        $("#view_report").modal("show");
                    } else {
                        console.error("Invalid response data:", res);
                        // Handle the case where viewfile is not present in the response data
                    }
                })
                .catch((error) => {
                    console.error("Error fetching student report:", error);
                    // Handle AJAX error gracefully, e.g., display an error message to the user
                });
        } else {
            console.error("Invalid URL:", url);
        }
    }
    static GetGradeReport(
        class_id,
        section_id,
        school_type,
        acyear,
        acyear_term
    ) {
        console.log("get grade report");
        let getUrl =
            window.getgradeinfo +
            "?type=" +
            "3" +
            "&class_id=" +
            class_id +
            "&section_id=" +
            section_id +
            "&school_type=" +
            school_type +
            "&acyear=" +
            acyear +
            "&acyear_term=" +
            acyear_term;

        if (getUrl) {
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);

                    if (
                        response.data &&
                        response.data.subjects &&
                        response.data.result
                    ) {
                        const subjects = response.data.subjects.map(
                            (item) => item.name
                        ); // Extract the 'name' property
                        const subject_ids = response.data.subject_id;
                        const exam_ids = response.data.exam_ids;
                        const academic_year = response.data.academic_year;
                        const term = response.data.term;
                        const class_ids = response.data.class;
                        Account.GradeBarChart(
                            subjects,
                            subject_ids,
                            exam_ids,
                            class_ids,
                            academic_year,
                            term
                        );

                        const results = response.data.result; // Directly use the result array
                        Account.GradePieChart(results);
                        $(".grade_report_body").show();
                        $("#datatable_buttons2").append(response.data.view);
                        // Initialize DataTables after the table content is loaded
                        $("#datatable_buttons2").DataTable({
                            dom: "Bfrtip",
                            buttons: ["copy", "csv", "pdf", "print"],
                            // Add any other DataTables configuration options here
                        });
                    } else {
                        console.error(
                            "Invalid response data or missing name property:",
                            response.data
                        );
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
    static GetGradeSubjectReport(
        class_id,
        section_id,
        school_type,
        acyear,
        acyear_term,
        subject
    ) {
        console.log("get grade report");
        let getUrl =
            window.getgradesubjectinfo +
            "?type=" +
            "4" +
            "&class_id=" +
            class_id +
            "&section_id=" +
            section_id +
            "&school_type=" +
            school_type +
            "&acyear=" +
            acyear +
            "&acyear_term=" +
            acyear_term +
            "&subject=" +
            subject;

        if (getUrl) {
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);

                    if (response) {
                        console.log("it enter sub");
                        const subjects = response.data.subjects;
                        // Extract the 'name' property
                        console.log(subjects);
                        const subject_ids = response.data.subject_id;
                        console.log(subject_ids);
                        const exam_ids = response.data.student_ids;
                        console.log(exam_ids);
                        const academic_year = response.data.academic_year;
                        console.log(academic_year);
                        const term = response.data.term;
                        console.log(term);
                        const class_ids = response.data.class;
                        console.log(class_ids);
                        Account.GradeBarChart1(
                            subjects,
                            subject_ids,
                            exam_ids,
                            class_ids,
                            academic_year,
                            term
                        );
                        console.log("it enter");
                        const results = response.data.result; // Directly use the result array
                        Account.GradePieChart1(results);
                        $(".grade_report_body").show();

                        $("#datatable_buttons1").html(response.data.view);
                        // Initialize DataTables after the table content is loaded
                        $("#datatable_buttons1").DataTable({
                            dom: "Bfrtip",
                            buttons: ["copy", "csv", "pdf", "print"],
                            // Add any other DataTables configuration options here
                        });
                    } else {
                        console.error(
                            "Invalid response data or missing name property:",
                            response.data
                        );
                    }
                })
                .catch((error) => {
                    let status = error.response;
                    console.log(status);
                });
        } else {
            // clear section list dropdown
            $('select[name="section_id"]')
                .empty()
                .select2({ placeholder: "Pick a section..." });
        }
    }
    static getTuckShopReport(obj = {}, initial = 1, type, load) {
        let url = window.tuckshopreport;
        console.log("get tuckshop report");
        axios
            .post(url, obj)
            .then((res) => {
                if (obj?.service_id == 1) {
                    $(".sales_overview").hide();
                    $(".purchase_overview").show();
                    $("#purchase_product").html(res?.data?.products);
                    $("#purchase_amount").html(res?.data?.total);
                    $(".purchasereport").html("");
                    $(".purchasereport").html(res?.data?.view);
                    $("#datatable-buttons-tuckshop-purchase").DataTable();
                } else {
                    $(".purchase_overview").hide();

                    $(".sales_overview").show();

                    $("#sales_customer").html(res?.data?.total_customer);
                    $("#sales_product").html(res?.data?.sales);
                    $("#sales_amount").html(res?.data?.total_amount);
                    $(".purchasereport").html("");
                    $(".purchasereport").html(res?.data?.view);
                    $("#datatable-buttons-tuckshop-purchase").DataTable();
                }
                console.log(res);
            })
            .catch((error) => {
                console.log(error);
            });
    }

    static getClassreport(class_id, section_id, school_type, acyear) {
        let getUrl =
            window.classreport +
            "?acyear=" +
            acyear +
            "&class_id=" +
            class_id +
            "&section_id=" +
            section_id +
            "&school_type=" +
            school_type;
        console.log("get class report");
        axios
            .get(getUrl)
            .then((res) => {
                $(".class_report_Details").html("");
                $(".class_report_Details").html(res?.data?.view);

                console.log(res);
            })
            .catch((err) => {
                console.log(err);
            });
    }

    static GetMarkReport(acyear, term, student_id, type) {
        let getUrl =
            window.getmarkreport +
            "?acyear=" +
            acyear +
            "&term=" +
            term +
            "&student_id=" +
            student_id +
            "&type=" +
            type;
        console.log("get mark report");
        if (student_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);

                    if (response.data.message) {
                        var html = `<p class="text-danger text-center">${response.data.message}</p>`;
                        $(".get_mark_report_details").html("");
                        $(".get_mark_report_details").html(html);
                    } else {
                        //!response?.data?.reportinfo

                        $(".info_report").show();

                        $(".get_mark_report_details").html("");
                        $(".get_mark_report_details").html(response.data.view);
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

    static EditreportCard() {
        console.log("edited");
        console.log("edit report");
        let acyear = $('select[name="academic_year"]').val();
        let term = $('select[name="academic_term"]').val();
        // let exam_type = $('select[name="exam_type"]').val();
        let student_id =
            $('select[name="student_id"]').val() ||
            $('input[name="student_id"]').val();
        let type = "edit";

        ReportConfig.GetMarkReport(acyear, term, student_id, type);
    }

    static GetBroadSheetMarkReport(
        acyear,
        term,
        class_id,
        section_id,
        exam_type
    ) {
        let getUrl =
            window.getbroadsheetmarkreport +
            "?acyear=" +
            acyear +
            "&term=" +
            term +
            "&class_id=" +
            class_id +
            "&section_id=" +
            section_id +
            "&exam_type=" +
            exam_type;
        console.log("broadsheet report");
        if (getUrl) {
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);
                    //return;

                    if (response.data.message) {
                        var html = `<p class="text-danger text-center">${response.data.message}</p>`;
                        $(".get_broadsheet_report_details").html("");
                        $(".get_broadsheet_report_details").html(html);
                    } else {
                        //!response?.data?.reportinfo

                        // $(".info_report").show();

                        $(".get_broadsheet_report_details").html("");
                        $(".get_broadsheet_report_details").html(
                            response.data.view
                        );
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

    static TransportReport(obj = {}, initial = 1, type, load) {
        console.log(type);
        console.log("transport report obj");
        let getUrl;
        console.log(getUrl);
        const {
            class_id,
            section_id,
            stop_id,
            bus_id,
            route_id,
            school_type,
            acyear,
            room_id,
            dormitory_id,
            user_group,
            month,
            member_id,
        } = obj;
        if (type == "transport") {
            getUrl =
                window.transportreport +
                "?initial=" +
                initial +
                "&school_type=" +
                school_type +
                "&class_id=" +
                class_id +
                "&section_id=" +
                section_id +
                "&stop_id=" +
                stop_id +
                "&bus_id=" +
                bus_id +
                "&route_id=" +
                route_id +
                "&acyear=" +
                acyear;
        } else if (type == "hostel") {
            console.log("is my type:".type);
            getUrl =
                window.hostelreport +
                "?initial=" +
                initial +
                "&school_type=" +
                school_type +
                "&class_id=" +
                class_id +
                "&section_id=" +
                section_id +
                "&room_id=" +
                room_id +
                "&dormitory_id=" +
                dormitory_id +
                "&acyear=" +
                acyear;
        } else if (type == "leave") {
            getUrl =
                window.leavereport +
                "?initial=" +
                initial +
                "&school_type=" +
                school_type +
                "&class_id=" +
                class_id +
                "&section_id=" +
                section_id +
                "&acyear=" +
                acyear;
        } else if (type == "payroll") {
            if (load) {
                let urltotal =
                    window.payrolltotalamount +
                    "?initial=" +
                    initial +
                    "&acyear=" +
                    acyear +
                    "&user_group=" +
                    user_group +
                    "&month=" +
                    month +
                    "&member_id=" +
                    member_id;
                axios
                    .get(urltotal)
                    .then((res) => {
                        $("#payrool_month").html(res?.data?.month);
                        $("#payrool_count").html(res?.data?.total_count);
                        $("#payrool_total").html(res?.data?.totalamount);
                    })
                    .catch((err) => {});
            }

            getUrl =
                window.payrollreport +
                "?initial=" +
                initial +
                "&acyear=" +
                acyear +
                "&user_group=" +
                user_group +
                "&month=" +
                month +
                "&member_id=" +
                member_id;
        }

        $("document").ready(function () {
            var element = $("#datatable-buttons1");
            var url = getUrl;
            if (type == "transport") {
                var column = [
                    {
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        searchable: false,
                        sortable: false,
                    },
                    {
                        data: "academicyear.year",
                        name: "academicyear.year",
                        className: "textcenter",
                    },
                    {
                        data: "student.first_name",
                        name: "student.first_name",
                        width: "15%",
                    },

                    {
                        data: "stop.stop_name",
                        name: "stop.stop_name",
                        className: "textcenter",
                    },
                    {
                        data: "route",
                        name: "c",
                        className: "textcenter",
                    },
                    {
                        data: "bus.bus_no",
                        name: "bus.bus_no",
                        className: "textcenter",
                    },
                    {
                        data: "date_of_reg",
                        name: "date_of_reg",
                        className: "textcenter",
                    },
                    {
                        data: "feestransport",
                        name: "feestransport",
                        className: "textcenter",
                    },
                ];
            } else if (type == "hostel") {
                var column = [
                    {
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        searchable: false,
                        sortable: false,
                    },
                    {
                        data: "academicyear.year",
                        name: "academicyear.year",
                        className: "textcenter",
                    },
                    {
                        data: "student.first_name",
                        name: "student.first_name",
                        width: "15%",
                    },

                    {
                        data: "dormitory.dormitory_name",
                        name: "dormitory.dormitory_name",
                        className: "textcenter",
                    },

                    {
                        data: "room.room_number",
                        name: "room.room_number",
                        className: "textcenter",
                    },
                    {
                        data: "date_of_reg",
                        name: "date_of_reg",
                        className: "textcenter",
                    },
                    {
                        data: "feeshostel",
                        name: "feeshostel",
                        className: "textcenter",
                    },
                ];
            } else if (type == "leave") {
                var column = [
                    {
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        searchable: false,
                        sortable: false,
                    },

                    {
                        data: "academicyear.year",
                        name: "academicyear.year",
                        width: "15%",
                    },

                    {
                        data: "applicantname",
                        name: "applicantname",
                        width: "15%",
                    },

                    {
                        data: "type",
                        name: "type",
                        className: "textcenter",
                    },
                    {
                        data: "fromto",
                        name: "fromto",
                        className: "textcenter",
                    },
                    {
                        data: "status",
                        name: "status",
                        className: "textcenter",
                    },
                ];
            } else if (type == "payroll") {
                var column = [
                    {
                        data: "DT_RowIndex",
                        name: "DT_RowIndex",
                        searchable: false,
                        sortable: false,
                    },

                    {
                        data: "academicyear.year",
                        name: "academicyear.year",
                        width: "15%",
                    },

                    {
                        data: "name",
                        name: "name",
                        width: "15%",
                    },

                    {
                        data: "net_salery",
                        name: "net_salery",
                        className: "textcenter",
                    },
                    {
                        data: "month",
                        name: "month",
                        className: "textcenter",
                    },
                    {
                        data: "year",
                        name: "year",
                        className: "textcenter",
                    },
                ];
            }

            var csrf = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            var options = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [
                    [15, 25, 50, 100, 250, 500, -1],
                    [15, 25, 50, 100, 250, 500, "ALL"],
                ],
                button: [
                    {
                        name: "Publish",
                        url: "{{route('transport_action_from_admin',1)}}",
                    },
                    {
                        name: "Un Publish",
                        url: "{{route('transport_action_from_admin',0)}}",
                    },
                    {
                        name: "Trash",
                        url: "{{route('transport_action_from_admin',-1)}}",
                    },
                    {
                        name: "Delete",
                        url: "{{route('transport.destroy',1)}}",
                        method: "DELETE",
                    },
                ],
            };

            dataTable(element, url, column, csrf, options);
        });
    }
}
