export default class FeeStructureConfig {
    static FeeStructureInit(notify_script, type = null) {
        $('select[name="academic_year_grade"]').on("change", function () {
            var academic_year = $(this).val();
            console.log(academic_year);
            let getUrl =
                window.fees_paid_report +
                "?type=" +
                "3" +
                "&academic_year=" +
                academic_year;
            if (getUrl) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (response.data) {
                            var row = $(this).closest(".row");
                            row.find('select[name="academic_term"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "Select academic term...",
                                    data: response.data.academic_terms,
                                });
                            if (response.data.payment_type) {
                                var row = $(this).closest(".row");
                                var selectedValue = response.data.payment_type;
                                var selectElement = row.find(
                                    'select[name="payment_type"]'
                                );
                                console.log(selectedValue);
                                // Disable all options
                                selectElement
                                    .find("option")
                                    .prop("disabled", true);

                                // Enable the selected option
                                selectElement
                                    .find(
                                        'option[value="' + selectedValue + '"]'
                                    )
                                    .prop("disabled", false);

                                // Trigger change event
                                selectElement
                                    .val(selectedValue)
                                    .trigger("change");
                            } else {
                                // console.log("it will");
                                var row = $(this).closest(".row");
                                var paymentTypeSelect = row.find(
                                    'select[name="payment_type"]'
                                );
                                paymentTypeSelect.empty();

                                paymentTypeSelect.append(
                                    "<option selected></option>"
                                );

                                $.each(
                                    response.data.payment_types,
                                    function (index, paymentType) {
                                        paymentTypeSelect.append(
                                            '<option value="' +
                                                index +
                                                '">' +
                                                paymentType +
                                                "</option>"
                                        );
                                    }
                                );

                                paymentTypeSelect.select2({
                                    allowClear: true,
                                    placeholder: "Select payment type...",
                                });

                                // Trigger the change event if needed
                                paymentTypeSelect
                                    .val(response.data.type)
                                    .trigger("change");
                            }
                        } else {
                            var row = $(this).closest(".row");
                            row.find('select[name="academic_term"]')
                                .empty()
                                .select2({
                                    allowClear: true,
                                    placeholder: "Select academic term...",
                                });

                            notify_script(
                                "Error",
                                "No Academic Terms Found ",
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
                // clear section list dropdown
                $('select[name="academic_term"]')
                    .empty()
                    .select2({ placeholder: "Pick a academic year..." });
            }
        });

        $('select[name="school_type"]').on("change", function () {
            let school_type = $(this).val();
            let academic_year = $('select[name="academic_year_grade"]').val();
            console.log(type);
            Pace.start();
            let getUrl;

            getUrl = window.classurl + "?school_type=" + school_type;

            if (school_type) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        if (Object.keys(response.data).length) {
                            var row = $(this).closest(".row");
                            row.find('select[name="class_id"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "select class...",
                                    data: response.data, // assuming formattedOptions is defined elsewhere
                                });

                            Pace.stop();
                        } else {
                            var row = $(this).closest(".row");
                            row.find('select[name="class_id"]')
                                .empty()
                                .select2({
                                    allowClear: true,
                                    placeholder: "select class...",
                                });

                            Pace.stop();
                        }
                    })
                    .catch((error) => {
                        let status = error.response;
                        console.log(status);
                        //notify_script("Error", status, "error", true);
                    });
            } else {
                // clear section list dropdown
                var row = $(this).closest(".row");
                row.find('select[name="class_id"]').empty().select2({
                    allowClear: true,
                    placeholder: "select class...",
                });
            }
            // AcademicConfig.getClass(school_type, notify_script);

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

        $('select[name="class_id"]').on("change", function () {
            let academic_year = $("#academic").val()
                ? $("#academic").val()
                : $("#academic1").val();
            let academic_term = $("#examterm").val()
                ? $("#examterm").val()
                : $("#examterm1").val();
            let school_type = $("#school_type_grade").val()
                ? $("#school_type_grade").val()
                : $("#school_type_grade1").val();
            let class_id = $("#class_id_grade").val()
                ? $("#class_id_grade").val()
                : $("#class_id_grade1").val();
            let getUrl =
                window.getstudentperformanceinfo +
                "?type=" +
                "1" +
                "&class_id=" +
                class_id +
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
                        if (response.data.section) {
                            const sections = response.data.section;
                            const formattedOptions = sections.map(
                                (section) => ({
                                    id: section.id, // Assuming 'id' is the unique identifier for each option
                                    text: `${section.section_name} -  ${
                                        section.department_name
                                            ? section.department_name
                                            : "NA"
                                    }`, // Format the text for each option
                                })
                            );
                            var row = $(this).closest(".row");
                            row.find('select[name="sec_dep"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "Select section/department...",
                                    data: formattedOptions, // assuming formattedOptions is defined elsewhere
                                });
                        } else {
                            var row = $(this).closest(".row");
                            row.find('select[name="sec_dep"]').empty().select2({
                                allowClear: true,
                                placeholder: "Select section/department...",
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
                        notify_script("Error", status, "error", true);
                    });
            } else {
                // clear section list dropdown
                $('select[name="section_id"]')
                    .empty()
                    .select2({ placeholder: "Pick a section..." });
            }
        });

        $('select[name="payment_type"]').on("change", function () {
            let type = $(this).val();
            var row = $(this).closest(".row");
            if (type == "0") {
                row.find(".monthly").show();
                row.find(".academic_term").hide();
            } else if (type == "1") {
                row.find(".academic_term").show();
                row.find(".monthly").hide();
            } else {
                row.find(".academic_term").hide();
                row.find(".monthly").hide();
            }
        });

        $(".fees_paid_report").on("click", function () {
            let payment_type = $("#payment_type").val();
            let academic_year = $("#academic").val();
            let academic_term = $("#examterm").val();
            let school_type = $("#school_type_grade").val();
            let class_id = $("#class_id_grade").val();
            let section = $("#sec_dep").val();
            let month = $("#month").val();
            let url;

            if (
                academic_year &&
                class_id &&
                school_type &&
                section &&
                payment_type
            ) {
                if (payment_type == 0) {
                    url =
                        window.fees_paid_report +
                        "?payment_type=" +
                        payment_type +
                        "&academic_year=" +
                        academic_year +
                        "&class_id=" +
                        class_id +
                        "&school_type=" +
                        school_type +
                        "&section=" +
                        section +
                        "&month=" +
                        month +
                        "&type=" +
                        "1";
                } else if (payment_type == 1) {
                    url =
                        window.fees_paid_report +
                        "?payment_type=" +
                        payment_type +
                        "&academic_year=" +
                        academic_year +
                        "&class_id=" +
                        class_id +
                        "&school_type=" +
                        school_type +
                        "&section=" +
                        section +
                        "&academic_term=" +
                        academic_term +
                        "&type=" +
                        "1";
                } else {
                    url =
                        window.fees_paid_report +
                        "?payment_type=" +
                        payment_type +
                        "&academic_year=" +
                        academic_year +
                        "&class_id=" +
                        class_id +
                        "&school_type=" +
                        school_type +
                        "&section=" +
                        section +
                        "&type=" +
                        "1";
                }
                if (url) {
                    axios
                        .get(url)
                        .then((response) => {
                            console.log(response);
                            if (response.data.view) {
                                if (
                                    $.fn.DataTable.isDataTable(
                                        "#datatable-buttons1"
                                    )
                                ) {
                                    $("#datatable-buttons1")
                                        .DataTable()
                                        .destroy();
                                    $("#datatable-buttons1").empty();
                                }
                                $("#datatable-buttons1").append(
                                    response.data.view
                                );
                                $("#datatable-buttons1").DataTable({
                                    dom: "Bfrtip",
                                    buttons: ["csv", "pdf", "print"],
                                    paging: false,
                                    // Add any other DataTables configuration options here
                                });
                                console.log(
                                    "btn_exist",
                                    response.data.btn_exist
                                );
                                if (response.data.btn_exist == 1) {
                                    $(".print_btn").show();
                                } else {
                                    $(".print_btn").hide();
                                }
                            } else {
                                console.error(
                                    "Invalid response data or missing name property:",
                                    response.data
                                );
                            }
                        })
                        .catch((error) => {
                            let status = error;
                            console.log(status);
                            // notify_script("Error", status, "error", true);
                        });
                } else {
                    console.error(
                        "Invalid response data or missing name property:",
                        response.data
                    );
                }
            } else {
                notify_script(
                    "Error",
                    "Please Select required fields",
                    "error",
                    true
                );
            }
        });

        $(".fees_unpaid_report").on("click", function () {
            let payment_type = $("#payment_type1").val();
            let academic_year = $("#academic1").val();
            let academic_term = $("#examterm1").val();
            let school_type = $("#school_type_grade1").val();
            let class_id = $("#class_id_grade1").val();
            let section = $("#sec_dep1").val();
            let month = $("#month1").val();
            let url;
            if (
                academic_year &&
                class_id &&
                school_type &&
                section &&
                payment_type
            ) {
                if (payment_type == 0) {
                    url =
                        window.fees_paid_report +
                        "?payment_type=" +
                        payment_type +
                        "&academic_year=" +
                        academic_year +
                        "&class_id=" +
                        class_id +
                        "&school_type=" +
                        school_type +
                        "&section=" +
                        section +
                        "&month=" +
                        month +
                        "&type=" +
                        "2";
                } else if (payment_type == 1) {
                    url =
                        window.fees_paid_report +
                        "?payment_type=" +
                        payment_type +
                        "&academic_year=" +
                        academic_year +
                        "&class_id=" +
                        class_id +
                        "&school_type=" +
                        school_type +
                        "&section=" +
                        section +
                        "&academic_term=" +
                        academic_term +
                        "&type=" +
                        "2";
                } else {
                    url =
                        window.fees_paid_report +
                        "?payment_type=" +
                        payment_type +
                        "&academic_year=" +
                        academic_year +
                        "&class_id=" +
                        class_id +
                        "&school_type=" +
                        school_type +
                        "&section=" +
                        section +
                        "&type=" +
                        "2";
                }
                if (url) {
                    axios
                        .get(url)
                        .then((response) => {
                            console.log(response);
                            if (response.data.view) {
                                if (
                                    $.fn.DataTable.isDataTable(
                                        "#datatable-buttons2"
                                    )
                                ) {
                                    $("#datatable-buttons2")
                                        .DataTable()
                                        .destroy();
                                    $("#datatable-buttons2").empty();
                                }
                                $("#datatable-buttons2").empty();
                                $("#datatable-buttons2").append(
                                    response.data.view
                                );
                                $("#datatable-buttons2").DataTable({
                                    dom: "Bfrtip",
                                    buttons: ["csv", "pdf", "print"],
                                    paging: false,
                                    // Add any other DataTables configuration options here
                                });
                                $("#fee_reminder").show();
                            } else {
                                console.error(
                                    "Invalid response data or missing name property:",
                                    response.data
                                );
                            }
                        })
                        .catch((error) => {
                            let status = error;
                            console.log(status);
                            // notify_script("Error", status, "error", true);
                        });
                } else {
                    console.error(
                        "Invalid response data or missing name property:",
                        response.data
                    );
                }
            } else {
                notify_script(
                    "Error",
                    "Please Select required fields",
                    "error",
                    true
                );
            }
        });

        $("#pills-unpaid-tab").on("click", function () {
            $(".table_paid").hide();
            $(".table_unpaid").show();
            $(".fees_unpaid_report").show();
            $(".fees_paid_report").hide();
            $(".paid_row").hide();
            $(".unpaid_row").show();
            $("#fee_reminder").hide();
            $(".print_btn").hide();
            $(".print_btn_div").hide();
        });
        $("#pills-paid-tab").on("click", function () {
            let activegrp = $(".active_group").val();
            if (activegrp != "Student") {
                $(".table_paid").show();
                $(".table_unpaid").hide();
                $(".fees_paid_report").show();
                $(".fees_unpaid_report").hide();
                $(".paid_row").show();
                $(".unpaid_row").hide();
                $("#fee_reminder").hide();
                $(".print_btn").show();
                $(".print_btn_div").show();
            }
        });

        $("#fee_reminder").on("click", function () {
            let payment_type = $("#payment_type1").val();
            let academic_year = $("#academic1").val();
            let academic_term = $("#examterm1").val();
            let school_type = $("#school_type_grade1").val();
            let class_id = $("#class_id_grade1").val();
            let section = $("#sec_dep1").val();
            let month = $("#month1").val();
            let unpaid_amounts = [];

            $(".unpaid_amount").each(function () {
                let studentId = $(this).data("student-id");
                let amount = $(this).val();
                unpaid_amounts.push({ studentId: studentId, amount: amount });
            });
            console.log("unpaid_amounts", unpaid_amounts);

            let formData = new FormData();
            for (let i = 0; i < unpaid_amounts.length; i++) {
                formData.append(
                    "unpaid_amount[" + unpaid_amounts[i].studentId + "]",
                    unpaid_amounts[i].amount
                );
            }

            let url;

            if (
                academic_year &&
                class_id &&
                school_type &&
                section &&
                payment_type
            ) {
                if (payment_type == 0) {
                    url =
                        window.fees_paid_report +
                        "?payment_type=" +
                        payment_type +
                        "&academic_year=" +
                        academic_year +
                        "&class_id=" +
                        class_id +
                        "&school_type=" +
                        school_type +
                        "&section=" +
                        section +
                        "&month=" +
                        month +
                        "&type=" +
                        "2" +
                        "&reminder=" +
                        "1";
                } else if (payment_type == 1) {
                    url =
                        window.fees_paid_report +
                        "?payment_type=" +
                        payment_type +
                        "&academic_year=" +
                        academic_year +
                        "&class_id=" +
                        class_id +
                        "&school_type=" +
                        school_type +
                        "&section=" +
                        section +
                        "&academic_term=" +
                        academic_term +
                        "&type=" +
                        "2" +
                        "&reminder=" +
                        "1";
                } else {
                    url =
                        window.fees_paid_report +
                        "?payment_type=" +
                        payment_type +
                        "&academic_year=" +
                        academic_year +
                        "&class_id=" +
                        class_id +
                        "&school_type=" +
                        school_type +
                        "&section=" +
                        section +
                        "&type=" +
                        "2" +
                        "&reminder=" +
                        "1";
                }

                if (url) {
                    axios
                        .post(url, formData)
                        .then((response) => {
                            console.log(response);
                            if (response.data.view) {
                                $(".homework_details").empty();
                                $(".homework_details").html(response.data.view);
                                $("#view__report").modal("show");
                            } else {
                                console.error(
                                    "Invalid response data:",
                                    response
                                );
                                // Handle the case where viewfile is not present in the response data
                            }
                        })
                        .catch((error) => {
                            console.error(
                                "Error fetching student report:",
                                error
                            );
                            // Handle AJAX error gracefully, e.g., display an error message to the user
                        });
                } else {
                    console.error("Invalid URL:", url);
                }
            }
        });

        $(".view_fees_unpaid").on("click", function () {
            console.log("its enter");
            let student_id = $(this).data("student-id");
            let unpaid_start = $(this).data("unpaid-id") ?? 0;
            let url;
            if (unpaid_start == 1) {
                var row = $(this).closest(".row");
                let name = row.find(".name").html();
                let reg_no = row.find(".reg_no").html();
                let unpaid_amount = row.find(".unpaid_amount").html();
                let total_amount = $(this).data("total-amount");
                let payment_type = $(this).data("payment_type");
                url =
                    window.fees_reminder +
                    "?student_id=" +
                    student_id +
                    "&name=" +
                    name +
                    "&reg_no=" +
                    reg_no +
                    "&unpaid_amount=" +
                    unpaid_amount +
                    "&total_amount=" +
                    total_amount +
                    "&payment_type=" +
                    payment_type +
                    "&unpaid_start=" +
                    unpaid_start +
                    "&type=" +
                    "1";
            } else {
                let name = $(".student_name" + student_id).val();
                let reg_no = $(".student_reg_no" + student_id).val();
                let unpaid_amount = $(".unpaid_amount" + student_id).val();
                let payment_type = $(".payment_type").val();
                let monthly_amount =
                    $(".monthly_amount" + student_id).val() ?? 0;
                let term_amount = $(".term_amount" + student_id).val() ?? 0;
                let one_pay_amount =
                    $(".one_pay_amount" + student_id).val() ?? 0;
                let scholarship = $(".student_scholarship" + student_id).val();
                let total_amount = $(".total_amount").val();

                url =
                    window.fees_reminder +
                    "?student_id=" +
                    student_id +
                    "&name=" +
                    name +
                    "&reg_no=" +
                    reg_no +
                    "&payment_type=" +
                    payment_type +
                    "&unpaid_amount=" +
                    unpaid_amount +
                    "&one_pay_amount=" +
                    one_pay_amount +
                    "&monthly_amount=" +
                    monthly_amount +
                    "&term_amount=" +
                    term_amount +
                    "&payment_type=" +
                    payment_type +
                    "&scholarship=" +
                    scholarship +
                    "&total_amount=" +
                    total_amount +
                    "&type=" +
                    "1" +
                    "&unpaid_start=" +
                    unpaid_start;
            }

            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.view) {
                            $(".homework_details").empty();
                            $(".homework_details").html(response.data.view);
                            $("#view__report").modal("show");
                        } else {
                            console.error("Invalid response data:", response);
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
        });
    }
}
