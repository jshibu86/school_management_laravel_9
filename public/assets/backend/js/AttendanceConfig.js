import AcademicConfig from "./AcademicConfig.js";
import AcademicYearConfig from "./AcdemicYearConfig.js";

export default class AttendanceConfig {
    static AttendanceInit(notify_script, type = null) {
        $('select[name="class_id"]').on("change", function () {
            let class_id = $(this).val();
            let school_type = $('select[name="school_type"]').val();
            let academic_year = $('select[name="academic_year"]').val();
            console.log(class_id);
            Pace.start();
            if (type == "idcard") {
                AcademicConfig.getStudents(
                    class_id,
                    0,
                    notify_script,
                    "inventory",
                    school_type,
                    academic_year
                );
            }

            AcademicConfig.getSection(class_id, notify_script, null);
        });
        if (type == null) {
            $('select[name="academic_year"]').on("change", function () {
                let academic_year = $(this).val();
                Pace.start();
                AcademicConfig.getTerms(academic_year, notify_script);
            });
        } else {
            $("#timetableacyear").on("change", function () {
                let academic_year = $(this).val();

                Pace.start();
                AcademicYearConfig.getTerms(academic_year);
            });
        }

        // getstudents for transport stop

        $('select[name="transport_stop_id"]').on("change", function () {
            let stop_id = $(this).val();
            console.log(stop_id);
            Pace.start();
            AttendanceConfig.getVehicle(stop_id, notify_script);
        });
        $(".assignstopbtn").on("click", function (e) {
            e.preventDefault();

            const acyear = $('select[name="academic_year"]').val();
            const class_id = $('select[name="class_id"]').val();
            const section_id = $('select[name="section_id"]').val();
            const stop_id = $('select[name="transport_stop_id"]').val();
            const bus_id = $('select[name="transport_vehicle_id"]').val();
            const route_id = $('select[name="transport_route_id"]').val();
            const semester_id = $('select[name="term_id"]').val();
            if (
                acyear &&
                class_id &&
                section_id &&
                stop_id &&
                bus_id &&
                route_id &&
                semester_id
            ) {
                let getUrl =
                    window.transportstudent +
                    "?academic_year=" +
                    acyear +
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
                    "&semester_id=" +
                    semester_id;

                AttendanceConfig.getStudentsforassignstop(
                    getUrl,
                    notify_script
                );
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All required feilds",
                    "error",
                    true
                );
            }
        });

        $("#getattendancereport").on("click", function (e) {
            e.preventDefault();

            console.log("ck");

            const acyear = $('select[name="academic_year"]').val();
            const class_id = $('select[name="class_id"]').val();
            const section_id = $('select[name="section_id"]').val();
            const student_id = $('select[name="student_id[]"]').val();
            const month = $('input[name="month"]').val();

            if (acyear && class_id && section_id) {
                let getUrl =
                    window.getAttendanceReportUrl +
                    "?academic_year=" +
                    acyear +
                    "&class_id=" +
                    class_id +
                    "&section_id=" +
                    section_id +
                    "&student_id=" +
                    student_id +
                    "&month=" +
                    month;

                AttendanceConfig.getAttendancereport(getUrl, notify_script);
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All required feilds",
                    "error",
                    true
                );
            }
        });

        // select all attendance present/absent/late

        $(".attendencesdefault").on("change", function (e) {
            console.log("yes change");
            var value = $(this).val();

            console.log(value);
        });

        $(".attendancebtn").on("click", function (e) {
            e.preventDefault();
            // for checking this is edit or create
            var name = e.target.getAttribute("name");

            console.log(name);
            const acyear = $('select[name="academic_year"]').val();
            const class_id = $('select[name="class_id"]').val();
            const section_id = $('select[name="section_id"]').val();
            const term_id = $('select[name="academic_term"]').val();

            if (acyear && class_id && section_id && term_id) {
                let getUrl =
                    window.attendanceurl +
                    "?academic_year=" +
                    acyear +
                    "&class_id=" +
                    class_id +
                    "&section_id=" +
                    section_id +
                    "&dataid=" +
                    name +
                    "&term_id=" +
                    term_id;

                if (name == "hourly") {
                    AttendanceConfig.getAttendanceInfo(
                        getUrl,
                        notify_script,
                        name
                    );
                } else {
                    AttendanceConfig.getdailyAttendanceinfo(
                        getUrl,
                        notify_script,
                        name
                    );
                }
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All required feilds",
                    "error",
                    true
                );
            }
        });

        // get hourly attendance

        $(".gethourlyattendance").on("click", function () {
            const acyear = $("#academic_year").val();
            const subject_id = $('select[name="subject_id"]').val();
            const month = $("#hrmonth").val();
            const student_id = $("#student_id").val();

            if (acyear && subject_id && month) {
                let url =
                    window.hourlyattendanceurl +
                    "?academic_year=" +
                    acyear +
                    "&subject_id=" +
                    subject_id +
                    "&month=" +
                    month +
                    "&student_id=" +
                    student_id;
                month;
                AttendanceConfig.getHourlyattendance(url, notify_script);
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All required feilds",
                    "error",
                    true
                );
            }

            console.log(acyear, subject_id, month);
        });
    }

    static getVehicle(stop_id, notify_script) {
        if (stop_id) {
            let url = window.getvehicle + "?stop_id=" + stop_id;

            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response.data.vehicles);

                        if (Object.keys(response.data.vehicles).length) {
                            $('select[name="transport_vehicle_id"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "select vehicle...",
                                    data: response.data.vehicles,
                                });
                            $(".action_btn button").attr("disabled", false);
                            Pace.stop();
                        } else {
                            $('select[name="transport_vehicle_id"]')
                                .empty()
                                .select2({ placeholder: "select vehicle..." });

                            notify_script(
                                "Error",
                                "No Vehicle Available",
                                "error",
                                true
                            );
                            Pace.stop();
                        }

                        if (Object.keys(response.data.routes).length) {
                            $('select[name="transport_route_id"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "select route...",
                                    data: response.data.routes,
                                });
                            $(".action_btn button").attr("disabled", false);
                            Pace.stop();
                        } else {
                            $('select[name="transport_route_id"]')
                                .empty()
                                .select2({ placeholder: "select route..." });

                            notify_script(
                                "Error",
                                "No Route Available Kindly add this Stop to Any Routes",
                                "error",
                                true
                            );
                            Pace.stop();
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        }
    }

    static getdailyAttendanceinfo(url, notify_script, name) {
        if (url) {
            axios
                .get(url)
                .then((response) => {
                    if (response.data?.error) {
                        notify_script(
                            "Error",
                            "No Periods Found Kindly add Any Period",
                            "error",
                            true
                        );

                        return;
                    }
                    console.log(response);
                    $(".atnaccodrdian").removeClass("show");
                    $(".attendanceinfo").empty();
                    $(".attendanceinfo").html(response.data.viewfile);
                })
                .catch((error) => {
                    $(".attendanceinfo").html("");
                    console.log(error);
                });
        }
    }

    static getAttendanceInfo(url, notify_script, name) {
        if (url) {
            axios
                .get(url)
                .then((response) => {
                    if (response.data?.error) {
                        notify_script(
                            "Error",
                            "No Periods Found Kindly add Any Period",
                            "error",
                            true
                        );

                        return;
                    }
                    console.log(response);
                    $(".atnaccodrdian").removeClass("show");
                    $(".attendanceinfo").empty();
                    $(".attendanceinfo").html(response.data.viewfile);
                })
                .catch((error) => {
                    $(".attendanceinfo").html("");
                    console.log(error);
                });
        }
    }

    static getDailyattendance(student_id) {
        //$(".loader").css("display", "none");
        $(".loader").css("display", "block");
        console.log(student_id, "from daily");
        let url = window.dailyattendanceurl + "?student_id=" + student_id;
        if (student_id) {
            axios
                .get(url)
                .then((response) => {
                    if (response.data?.error) {
                        // notify_script(
                        //     "Error",
                        //     "Something Went wrong",
                        //     "error",
                        //     true
                        // );
                        console.log("error");

                        return;
                    }
                    console.log(response);

                    $(".daily_attendance").empty();
                    $(".daily_attendance").html(response.data.viewfile);
                    $(".loader").css("display", "none");
                })
                .catch((error) => {
                    $(".daily_attendance").html("");
                    console.log(error);
                    $(".loader").css("display", "none");
                });
        }
    }

    static getHourlyattendance(url, notify_script) {
        //$(".loader").css("display", "block");

        if (url) {
            axios
                .get(url)
                .then((response) => {
                    if (response.data.error) {
                        console.log(response.data.error);
                        notify_script(
                            "Error",
                            response.data.error,
                            "error",
                            true
                        );
                        console.log("error");

                        return;
                    }
                    console.log(response);
                    // return;

                    $(".hourlyattendancereport").empty();
                    $(".hourlyattendancereport").html(response.data.viewfile);
                    //$(".loader").css("display", "none");
                })
                .catch((error) => {
                    $(".hourlyattendancereport").html("");
                    console.log(error);
                    // $(".loader").css("display", "none");
                });
        }
    }

    static getAttendancereport(url, notify_script) {
        if (url) {
            axios
                .get(url)
                .then((response) => {
                    if (response.data?.error) {
                        notify_script("Error", "No Data Found", "error", true);

                        return;
                    }
                    console.log(response);
                    $(".displayattendancereport").empty();
                    $(".displayattendancereport").html(response.data.viewfile);
                    $("#total_count").text(response.data.total_user);
                    $("#present_percentage").text(
                        `${response.data.total_percent_present} %`
                    );
                    $("#absent_percentage").text(
                        `${response.data.total_percent_absent} %`
                    );

                    $("#exportexcel").show();
                    $("#exportprint").show();
                })
                .catch((error) => {
                    $(".displayattendancereport").html("");
                    console.log(error);
                });
        }
    }
    static getStudentsforassignstop(url, notify_script) {
        if (url) {
            axios
                .get(url)
                .then((response) => {
                    if (response.data?.error) {
                        notify_script("Error", "No Data Found", "error", true);

                        return;
                    }
                    console.log(response);
                    $(".atnaccodrdian").removeClass("show");
                    $(".get_students_stop_assign").empty();
                    $(".get_students_stop_assign").html(response.data.viewfile);
                })
                .catch((error) => {
                    $(".get_students_stop_assign").html("");
                    console.log(error);
                });
        }
    }

    static attendancedailycount(class_id, section_id, academic_year, type) {
        let url =
            window.attendancedailycount +
            "?class_id=" +
            class_id +
            "&section_id=" +
            section_id +
            "&acyear=" +
            academic_year +
            "&type=" +
            type;
        axios
            .get(url)
            .then((response) => {
                if (response.data?.error) {
                    return;
                }
                document.querySelector(".total_students").textContent =
                    response.data.totalstudents;
                document.querySelector(".total_present").textContent =
                    response.data.totalpresent;
                document.querySelector(".total_absent").textContent =
                    response.data.totalabsent;
                console.log(response);
            })
            .catch((error) => {
                console.log(error);
            });
    }
}
