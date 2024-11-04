export default class StudentPerformance {
    static StudentPerformanceInit(notify_script, type = null) {
        $('select[name="academic_year_grade"]').on("change", function () {
            var academic_year = $(this).val();
            console.log(academic_year);
            let getUrl =
                window.getstudentperformanceinfo +
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
                            $('select[name="academic_term"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "Select academic term...",
                                    data: response.data,
                                });
                        } else {
                            $('select[name="academic_term"]').empty().select2({
                                placeholder: "elect academic term...",
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
        $('select[name="academic_year"]').on("change", function () {
            var academic_year = $(this).val();
            console.log(academic_year);
            let getUrl =
                window.getstudentperformanceinfo +
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
                            $('select[name="academic_term"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "Select academic term...",
                                    data: response.data,
                                });
                        } else {
                            $('select[name="academic_term"]').empty().select2({
                                placeholder: "elect academic term...",
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

        $('select[name="class_id"]').on("change", function () {
            let academic_year = $('select[name="academic_year_grade"]').val();
            let academic_term = $('select[name="academic_term"]').val();
            let school_type = $('select[name="school_type"]').val();
            let class_id = $('select[name="class_id"]').val();
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
                            $('select[name="sec_dep"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "Select section/department...",
                                    data: formattedOptions,
                                });
                        } else {
                            $('select[name="sec_dep"]').empty().select2({
                                placeholder: "Select class ...",
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

        $('select[name="period_type"]').on("change", function () {
            let type = $('select[name="period_type"]').val();

            if (type == "monthly") {
                $(".monthly").show();
                $(".weekly").hide();
            } else if (type == "weekly") {
                $(".weekly").show();
                $(".monthly").hide();
            } else {
                $(".weekly").hide();
                $(".monthly").hide();
            }
        });

        $(".students_performance").on("click", function () {
            let period = $('select[name="period_type"]').val();
            let academic_year = $('select[name="academic_year_grade"]').val();
            let term = $('select[name="academic_term"]').val();
            let school_type = $('select[name="school_type"]').val();
            let class_id = $('select[name="class_id"]').val();
            let section_id = $('select[name="sec_dep"]').val();
            let date;
            if (period == "weekly") {
                let start_date = $("#start_date").val();
                let end_date = $("#end_date").val();
                date = [start_date, end_date];
            } else {
                date = $("#month").val();
            }

            console.log(date);
            let getUrl =
                window.getstudentperformanceinfo +
                "?type=" +
                "2" +
                "&academic_year=" +
                academic_year +
                "&term=" +
                term +
                "&school_type=" +
                school_type +
                "&section_id=" +
                section_id +
                "&class_id=" +
                class_id +
                "&date=" +
                date +
                "&period=" +
                period;
            if (getUrl && period && date) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (response.data.view) {
                            if (
                                $.fn.DataTable.isDataTable(
                                    "#students_performance_table"
                                )
                            ) {
                                $("#students_performance_table")
                                    .DataTable()
                                    .destroy();
                                $("#students_performance_table").empty();
                            }
                            $("#students_performance_table").append(
                                response.data.view
                            );
                            $("#students_performance_table").DataTable({
                                dom: "frtip",
                                buttons: false,
                                paging: false,
                                // Add any other DataTables configuration options here
                            });
                            if (period == "monthly") {
                                $(".btn_submit").show();
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
                        notify_script("Error", status, "error", true);
                    });
            } else {
                if (!date) {
                    notify_script(
                        "Error",
                        "Please Enter the Required Fields",
                        "error",
                        true
                    );
                } else {
                    notify_script(
                        "Error",
                        "Please Select required fields",
                        "error",
                        true
                    );
                }
            }
        });
    }

    static prventMaxvalue(ele, student_id) {
        console.log(student_id);
        var studentid = student_id;
        console.log(studentid);
        const inputBoxes = document.querySelectorAll(
            `.studenttotalcalculate${studentid}`
        );
        var tot_count = document.querySelectorAll(
            `.studenttotalcalculate${studentid}`
        ).length;
        console.log(inputBoxes, tot_count);
        console.log("No is not big");
        let sum = 0;
        inputBoxes.forEach((inputBox) => {
            const value = parseFloat(inputBox.value);
            if (!isNaN(value)) {
                sum += value;
            }
        });
        console.log("its enter");
        $(`.studenttotal${studentid}`).val(sum);
        console.log("its value", sum);
        StudentPerformance.StudentPerformanceChart(sum, studentid, tot_count);
    }

    static StudentPerformanceChart(sum, studentid, tot_count) {
        console.log(sum, studentid, tot_count);
        var student = studentid;
        var elements = document.querySelectorAll(
            ".student_performance_chart" + student
        );
        console.log(elements.length);
        var percentage_sum = sum;
        var count = tot_count * 100;
        var result = (percentage_sum / count) * 100;
        var percentage = Math.round(result * 100) / 100;
        console.log(percentage_sum, count, result, percentage);
        elements.forEach(function (element) {
            var options = {
                series: [percentage],

                chart: {
                    height: 120,
                    type: "radialBar",
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            size: "55%", // Adjust the size of the hollow
                        },
                        track: {
                            strokeWidth: "10px",
                        },
                        dataLabels: {
                            name: {
                                offsetY: 10,
                                show: true,
                                color: "#888",
                                fontSize: "10px",
                                formatter: function (val) {
                                    return "Average";
                                },
                            },
                            value: {
                                offsetY: -20,
                                fontSize: "12px",
                                color: "#000000",
                                fontWeight: 700,
                                formatter: function (val) {
                                    return val + "%";
                                },
                            },
                        },
                    },
                },
                colors: ["#14E5AB"],
            };

            var chart = new ApexCharts(element, options);
            chart.render().then(() => {
                chart.updateSeries([percentage]);
            });
        });
    }
}
