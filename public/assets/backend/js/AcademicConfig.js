export default class AcademicConfig {
    static notify(title, text, type, hide) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            hide: hide,
            styling: "fontawesome",
        });
    }
    static academicinit(notify_script) {
        $('select[name="class_id"]').on("change", function () {
            let class_id = $(this).val();
            let acyear = $('select[name="academic_year"]').val();

            if (acyear) {
                AcademicConfig.getSection(class_id, notify_script);
                AcademicConfig.getTeacher(class_id, notify_script);
            } else {
                notify_script(
                    "Error",
                    "Please Select Academic year",
                    "error",
                    true
                );
            }
        });
        $('select[name="section_id"]').on("change", function () {
            let class_id = $('select[name="class_id"]').val();
            let section_id = $(this).val();
            let acyear = $('select[name="academic_year"]').val();
            console.log("its enter");
            AcademicConfig.checkAssign(
                class_id,
                section_id,
                acyear,
                notify_script
            );
        });

        $(".getsubjects").on("click", function (e) {
            e.preventDefault();

            Pace.start();
            $(".subjectmapping-form").submit();
            Pace.stop();
        });
    }
    static studentinit(notify_script) {
        $('select[name="class_id"]').on("change", function () {
            let class_id = $(this).val();
            console.log(class_id);
            Pace.start();
            AcademicConfig.getSection(class_id, notify_script);
        });

        $('select[name="section_id"]').on("change", function () {
            let class_id = $('select[name="class_id"]').val();
            let section_id = $(this).val();
            console.log(class_id, section_id);
            Pace.start();
            AcademicConfig.getDept(class_id, section_id, notify_script);
        });

        $(".assigen_parent").on("click", function () {
            let student_id = $(this).attr("id");
            console.log(student_id, "name");
            Pace.start();
            // AcademicConfig.ParentAssigen(student_id);
        });

        $('select[name="parent_id"]').on("change", function () {
            let parent_id = $(this).val();
            $(".parent__Details").show();

            AcademicConfig.getParentinfo(parent_id, notify_script);
        });
    }

    static Subjectmapping(notify_script) {
        $('select[name="class_id"]').on("change", function () {
            Pace.start();
            let class_id = $(this).val();
            AcademicConfig.getSection(class_id, notify_script);
        });
    }

    static Walletinit(notify_script) {
        $('select[name="wallet_type"]').on("change", function () {
            Pace.start();
            let wallet_type = $(this).val();
            if (wallet_type == "direct") {
                $(".challan").hide(1000);
                $(".direct").show(1000);
            } else {
                $(".direct").hide(1000);
                $(".challan").show(1000);
                // AcademicConfig.getUsers(member_type, notify_script);
            }
        });
        $('select[name="parent_id"]').on("change", function () {
            Pace.start();
            let parent_id = $(this).val();
            let getUrl = window.parenturl + "?parent=" + parent_id;
            if (parent_id) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        //var add = response.data.address_residence;
                        $("#invoice").show(1000);
                        var com = response.data.address_communication;
                        console.log(response.data);
                        document.getElementById(
                            "father_name_details"
                        ).textContent = response.data.father_name;
                        var url =
                            response.data.father_image == null
                                ? "/assets/images/default.jpg"
                                : response.data.father_image;
                        $("#father_image").attr("src", url);

                        var html = ` <div id="father_city_details">${com.province}, ${com.postal_code}, ${com.country}</div>
                        <div id="father_mobile_details">${response.data.father_mobile}</div>
                        <div id="father_email_details">${response.data.father_email}</div>`;

                        var element = document.querySelector(".parent_details");
                        element.innerHTML = "";

                        element.insertAdjacentHTML("beforeend", html);
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
    }

    static Leaveinit(notify_script) {
        $('select[name="member_type"]').on("change", function () {
            Pace.start();
            let member_type = $(this).val();

            AcademicConfig.getUsers(member_type, notify_script);
        });

        $('select[name="group"]').on("change", function () {
            Pace.start();
            let member_type = $(this).val();

            AcademicConfig.getUsers(member_type, notify_script);
        });
        $('select[name="group_id"]').on("change", function () {
            Pace.start();
            let member_type = $(this).val();

            AcademicConfig.getUsers(member_type, notify_script, "inventory");
        });
    }
    static Libraryinit(notify_script) {
        $('select[name="member_type"]').on("change", function () {
            Pace.start();
            let member_type = $(this).val();
            if (member_type == 4) {
                $(".others_info").hide(1000);
                $(".students_info").show(1000);
            } else {
                $(".students_info").hide(1000);
                $(".others_info").show(1000);
                AcademicConfig.getUsers(member_type, notify_script);
            }
        });
        $('select[name="class_id"]').on("change", function () {
            Pace.start();
            let class_id = $(this).val();
            AcademicConfig.getSection(class_id, notify_script);
        });
        $('select[name="section_id"]').on("change", function () {
            Pace.start();
            let section_id = $(this).val();
            let class_id = $('select[name="class_id"]').val();
            console.log(section_id, class_id, "from libraryinit");
            AcademicConfig.getStudents(class_id, section_id, notify_script);
        });
    }

    static getStudents(
        class_id,
        section_id,
        notify_script,
        type = null,
        school_type = null,
        academic_year = null
    ) {
        let getUrl;

        let element =
            type == "inventory"
                ? $('select[name="student_id[]"]')
                : $('select[name="student_id"]');

        let option =
            type == "inventory"
                ? `<option value="0">Select All</option>`
                : `<option selected=""></option>`;

        getUrl =
            window.studentsurl +
            "?class_id=" +
            class_id +
            "&section_id=" +
            section_id +
            "&school_type=" +
            school_type +
            "&academic_year=" +
            academic_year;

        if (getUrl) {
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);
                    if (response.data.length > 0) {
                        if (school_type) {
                            element.empty().select2({
                                allowClear: true,
                                placeholder: "select student...",
                                data: response.data,
                            });
                        } else {
                            // Check if type is not "inventory" before adding the "Select All" option
                            if (type !== "inventory") {
                                element.empty().prepend(option).select2({
                                    allowClear: true,
                                    placeholder: "select student...",
                                    data: response.data,
                                });
                            } else {
                                element.empty().select2({
                                    allowClear: true,
                                    placeholder: "select student...",
                                    data: response.data,
                                });
                            }
                        }
                    } else {
                        element
                            .empty()
                            .select2({ placeholder: "select student..." });

                        notify_script("Error", "No students", "error", true);
                    }
                })
                .catch((error) => {
                    let status = error;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            element.empty().select2({ placeholder: "Pick a students..." });
        }
    }

    static getUsers(member_type, notify_script, type = null) {
        let getUrl;
        //$("#users_select").multiSelect("refresh");

        getUrl = window.usersurl + "?member_id=" + member_type;

        if (member_type) {
            axios
                .get(getUrl)
                .then((response) => {
                    //console.log(response);
                    if (response.data.length > 0) {
                        if (type == "inventory") {
                            //console.log("here");
                            //$("#users_select").empty();

                            // AcademicConfig.removeOptions(
                            //     $('select[name="member_id"]')
                            // );
                            // $.each(response.data, function (key, value) {
                            //     console.log(value);

                            //     $('select[name="member_id[]"]').append(
                            //         `<option value="${value?.id}">${value?.text}</option>`
                            //     );
                            // });

                            // $("#users_select").multiselect().setIsEnabled(true);
                            // document
                            //     .multiselect("#users_select")
                            //     .setIsEnabled(true);
                            $('select[name="member_id[]"]')
                                .empty()
                                .prepend(
                                    '<option value="0">Select All</option>'
                                )
                                .select2({
                                    allowClear: true,
                                    placeholder: "select user...",
                                    data: response.data,
                                });
                        } else {
                            $('select[name="member_id"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "select user...",
                                    data: response.data,
                                });
                        }
                    } else {
                        if (type == "inventory") {
                            $('select[name="member_id[]"]')
                                .empty()
                                .select2({ placeholder: "select user..." });
                        } else {
                            $('select[name="member_id"]')
                                .empty()
                                .select2({ placeholder: "select user..." });
                        }

                        notify_script("Error", "No Users", "error", true);
                    }
                })
                .catch((error) => {
                    let status = error;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            $('select[name="member_id"]')
                .empty()
                .select2({ placeholder: "Pick a member..." });
        }
    }

    static removeOptions(selectElement) {
        //$("#users_select").multiSelect("destroy");
        console.log(selectElement.options ? selectElement.options.length : "");
        if (selectElement.options)
            var i,
                L = selectElement.options.length - 1;
        for (i = L; i >= 0; i--) {
            selectElement.remove(i);
        }
    }
    static CommonClassSectionSubjects(notify_script, type = null) {
        $('select[name="class_id"]').on("change", function () {
            console.log(type, "from homwwork");
            let class_id = $(this).val();

            AcademicConfig.getSection(class_id, notify_script, type);
            AcademicConfig.getsubjects(class_id, notify_script, type);
        });
    }
    static getParentinfo(parent_id, notify_script) {
        let getUrl = window.parenturl + "?parent=" + parent_id;
        if (parent_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    //var add = response.data.address_residence;
                    var com = response.data.address_communication;
                    console.log(response.data);
                    // document.getElementById("building_name_res").value =
                    //     add.building_name;
                    // document.getElementById("subbuilding_name_res").value =
                    //     add.subbuilding_name;
                    // document.getElementById("house_no_res").value =
                    //     add.house_no;
                    // document.getElementById("street_name_res").value =
                    //     add.street_name;
                    // document.getElementById("postal_code_res").value =
                    //     add.postal_code;
                    // document.getElementById("province_res").value =
                    //     add.province;
                    // document.getElementById("country_res").value = add.country;
                    // console.log(document.getElementById("building_name"));

                    // document.getElementById("building_name").value =
                    //     com.building_name;
                    // document.getElementById("subbuilding_name").value =
                    //     com.subbuilding_name;
                    document.getElementById("house_no").value = com.house_no;
                    document.getElementById("street_name").value =
                        com.street_name;
                    document.getElementById("postal_code").value =
                        com.postal_code;
                    document.getElementById("province").value = com.province;
                    document.getElementById("country").value = com.country;

                    document.getElementById("father_name_details").textContent =
                        response.data.father_name;
                    document.getElementById(
                        "father_email_details"
                    ).textContent = response.data.father_email;
                    document.getElementById(
                        "father_mobile_details"
                    ).textContent = response.data.father_mobile;

                    // if (response.data.address_check == "1") {
                    //     document.getElementById(
                    //         "address__check"
                    //     ).checked = true;
                    // }
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
    }

    static getsubjects(class_id, notify_script, type = null) {
        let getUrl;
        if (type) {
            getUrl = window.subjecturl + "?class=" + class_id + "&type=" + type;
        } else {
            getUrl = window.subjecturl + "?class=" + class_id;
        }

        if (class_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        $('select[name="subject_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select subject...",
                                data: response.data,
                            });
                    } else {
                        $('select[name="subject_id"]')
                            .empty()
                            .select2({ placeholder: "select subject..." });

                        if (type == "homework") {
                            notify_script(
                                "Error",
                                "You have not assigned any Subjects in this class",
                                "error",
                                true
                            );
                        } else {
                            notify_script(
                                "Error",
                                "This Class has No subject",
                                "error",
                                true
                            );
                        }
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
    }

    static getSection(class_id, notify_script, type = null) {
        let getUrl;

        if (type == "timetable") {
            getUrl = window.sectionurl + "?class=" + class_id + "&type=" + type;
        } else {
            getUrl = window.sectionurl + "?class=" + class_id;
        }

        if (class_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        $('select[name="section_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select section...",
                                data: response.data,
                            });
                        $(".action_btn button").attr("disabled", false);
                        Pace.stop();
                    } else {
                        $('select[name="section_id"]')
                            .empty()
                            .select2({ placeholder: "select section..." });

                        if (type === "timetable") {
                            notify_script(
                                "Error",
                                "This Class has No Section or All Section have Already Assigned Time table",
                                "error",
                                true
                            );
                        } else {
                            notify_script(
                                "Error",
                                "This Class has No Section",
                                "error",
                                true
                            );
                        }

                        $(".action_btn button").attr("disabled", true);
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
    static getDept(class_id, section_id, notify_script) {
        let getUrl =
            window.depturl + "?class=" + class_id + "&section=" + section_id;
        if (class_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        console.log("ok");
                        $('select[name="stu_department"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select department...",
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        console.log("else");
                        $('select[name="stu_department"]')
                            .empty()
                            .select2({ placeholder: "select department..." });
                        // notify sceript here

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
    static getTeacher(class_id, notify_script) {
        let getUrl = window.teacherurl + "?class=" + class_id;
        if (class_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        $('select[name="teacher_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "Select teacher...",
                                data: response.data,
                            });
                    } else {
                        $('select[name="teacher_id"]')
                            .empty()
                            .select2({ placeholder: "Select teacher..." });
                        notify_script(
                            "Error",
                            "This Class has No Section",
                            "error",
                            true
                        );
                    }
                })
                .catch((error) => {
                    let status = error.response.statusText;

                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            $('select[name="teacher_id"]')
                .empty()
                .select2({ placeholder: "Pick a teacher..." });
        }
    }
    static checkAssign(class_id, section_id, acyear, notify_script) {
        let getUrl =
            window.checkassign +
            "?class=" +
            class_id +
            "&section=" +
            section_id +
            "&acyear=" +
            acyear;
        if (class_id && section_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (response.data != 0) {
                        notify_script(
                            "Error",
                            "This section Already Assign Teacher ",
                            "error",
                            true
                        );
                    }
                })
                .catch((error) => {
                    let status = error.response.statusText;

                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            $('select[name="teacher_id"]')
                .empty()
                .select2({ placeholder: "Pick a teacher..." });
        }
    }

    static DeleteContent(id, notify_script) {
        let getUrl = window.deletecontent + "?content=" + id;
        if (id) {
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        // notify_script("Success", "Deleted ", "success", true);
                        Pace.stop();
                    } else {
                        Pace.stop();
                    }
                })
                .catch((error) => {
                    Pace.stop();
                    let status = error;

                    notify_script("Error", status, "error", true);
                });
        }
        console.log(id, "from content id");
    }

    static ViewpaymentVerify(id) {
        let getUrl = window.viewpaymenturl + "?id=" + id;
        if (id) {
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".homework_details").empty();
                        $(".homework_details").html(response.data.viewfile);
                        $("#view__homeworks").modal("show");
                        //new PerfectScrollbar(".dashboard-social-list");
                        Pace.stop();
                    } else {
                        Pace.stop();
                    }
                })
                .catch((error) => {
                    Pace.stop();
                    let status = error;

                    console.log(status);
                });
        }
    }

    static Viewevaluation(id, event) {
        console.log(id, event.getAttribute("data-student"), "from academic");
        let getUrl =
            window.viewevaluationurl +
            "?id=" +
            id +
            "&student=" +
            event.getAttribute("data-student");
        if (id) {
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".homework_details").empty();
                        $(".homework_details").html(response.data.viewfile);
                        $("#view__homeworks").modal("show");
                        new PerfectScrollbar(".dashboard-social-list");
                        Pace.stop();
                    } else {
                        Pace.stop();
                    }
                })
                .catch((error) => {
                    Pace.stop();
                    let status = error;

                    console.log(status);
                });
        }
    }

    static Viewhomework(class_id, section_id, subject_id, notify_script) {
        let getUrl =
            window.viewhomeworkurl +
            "?class=" +
            class_id +
            "&section=" +
            section_id +
            "&subject=" +
            subject_id;
        if (class_id) {
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".homework_details").empty();
                        $(".homework_details").html(response.data.viewfile);
                        $("#view__homeworks").modal("show");
                        new PerfectScrollbar(".dashboard-social-list");
                        Pace.stop();
                    } else {
                        Pace.stop();
                    }
                })
                .catch((error) => {
                    Pace.stop();
                    let status = error;

                    notify_script("Error", status, "error", true);
                });
        }
    }

    static Viewleave(leave_id) {
        let getUrl = window.viewleaveurl + "?leave=" + leave_id;
        if (leave_id) {
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".homework_details").empty();
                        $(".homework_details").html(response.data.viewfile);
                        $("#view__homeworks").modal("show");
                        new PerfectScrollbar(".dashboard-social-list");
                        Pace.stop();
                    } else {
                        Pace.stop();
                    }
                })
                .catch((error) => {
                    Pace.stop();
                    let status = error;

                    console.log(status);
                });
        }
    }

    static ParentAssigen(id) {
        let getUrl = window.assigenparent + "?studentid=" + id;

        if (id) {
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".students_details").empty();
                        $(".students_details").html(response.data.viewfile);
                        $("#assigen__parent").modal("show");
                        Pace.stop();
                    } else {
                        Pace.stop();
                    }
                })
                .catch((error) => {
                    Pace.stop();
                    let status = error;

                    notify_script("Error", status, "error", true);
                });
        }

        console.log("from academic js", id);
    }

    // search products

    static searchContact() {
        const url = window.Productsearch;

        $("body").on("keyup", "#product_search", function () {
            let text = $("#product_search").val();
            //console.log(text);

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            $.ajax({
                data: {
                    search: text,
                },

                url: url,
                method: "get",
                beforSend: function (request) {
                    return request.setReuestHeader(
                        "X-CSRF-Token",
                        "meta[name='csrf-token']"
                    );
                },
                success: function (result) {
                    $("#search_result").html("");

                    $("#search_result").html(result);
                },
            }); // end ajax
        }); // end one
    }

    static ViewLibraryBookStatus(id, status) {
        console.log(id, status, "from book status");
        let getUrl = window.bookstatus + "?id=" + id + "&status=" + status;
        if (id) {
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".homework_details").empty();
                        $(".homework_details").html(response.data.viewfile);
                        $("#view__homeworks").modal("show");
                        //new PerfectScrollbar(".dashboard-social-list");
                        Pace.stop();
                    } else {
                        Pace.stop();
                    }
                })
                .catch((error) => {
                    Pace.stop();
                    let status = error;

                    console.log(status);
                });
        }
    }

    static examInit(notify_script) {
        console.log("exam init");

        // fill_in the blanks
        var fill_blanks_mark = document.querySelectorAll(
            ".mark_ip_fill_blanks"
        );

        var total_fill_blanks = document.querySelector(
            ".total_mark_fill_blanks"
        );

        // choose best
        var choose_best_mark = document.querySelectorAll(
            ".mark_ip_choose_best"
        );

        var total_choose_best = document.querySelector(
            ".total_mark_choose_best"
        );
    }

    static Dormitoryinit(notify_script) {
        $('select[name="dormitory_id"]').on("change", function () {
            let dormitory_id = $(this).val();
            AcademicConfig.getRooms(dormitory_id, notify_script);
        });

        $(".submit_dormitory").on("click", function (e) {
            e.preventDefault();
            console.log("yes");
            const form = $(".dormitoryastudentform");
            const dormitory_id = $('select[name="dormitory_id"]').val();
            const room_id = $('select[name="room_id"]').val();
            var le = document.querySelectorAll(
                'input[name="students[]"]:checked'
            ).length;

            let getUrl = window.dormitorystudent;
            "&dormitory_id=" +
                dormitory_id +
                "&room_id=" +
                room_id +
                "&type=" +
                "room";

            if (getUrl) {
                axios
                    .get(url)
                    .then((response) => {
                        if (response.data?.error) {
                            notify_script(
                                "Error",
                                "No Data Found",
                                "error",
                                true
                            );

                            return;
                        }

                        if (response.data.count == 0) {
                            notify_script(
                                "Error",
                                "No Beds Available in this Rooms",
                                "error",
                                true
                            );
                        } else {
                            form.submit();
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        });

        $(".assigndormitorybtn").on("click", function (e) {
            e.preventDefault();

            const acyear = $('select[name="academic_year"]').val();
            const class_id = $('select[name="class_id"]').val();
            const section_id = $('select[name="section_id"]').val();
            const dormitory_id = $('select[name="dormitory_id"]').val();
            const room_id = $('select[name="room_id"]').val();
            const semester_id = $('select[name="term_id"]').val();
            if (
                acyear &&
                class_id &&
                section_id &&
                dormitory_id &&
                room_id &&
                semester_id
            ) {
                let getUrl =
                    window.dormitorystudent +
                    "?academic_year=" +
                    acyear +
                    "&class_id=" +
                    class_id +
                    "&section_id=" +
                    section_id +
                    "&dormitory_id=" +
                    dormitory_id +
                    "&room_id=" +
                    room_id +
                    "&semester_id=" +
                    semester_id;
                AcademicConfig.getStudentsforassigndormitory(
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
    }

    static submitDormitory() {
        // console.log("yes");
        const form = $(".dormitoryastudentform");
        const dormitory_id = $('select[name="dormitory_id"]').val();
        const room_id = $('select[name="room_id"]').val();
        const acyear = $('select[name="academic_year"]').val();
        var length = document.querySelectorAll(
            'input[name="students[]"]:checked'
        ).length;
        console.log(length);

        let getUrl =
            window.dormitorystudent +
            "?dormitory_id=" +
            dormitory_id +
            "&room_id=" +
            room_id +
            "&type=" +
            "room" +
            "&length=" +
            length +
            "&academic_year=" +
            acyear;

        if (getUrl) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (response.data?.error) {
                        notify_script("Error", "No Data Found", "error", true);

                        return;
                    }

                    console.log(response.data, "from dormitory submit");

                    if (response.data.count) {
                        console.log(response.data.count);

                        form.submit();
                    } else {
                        new PNotify({
                            title: "Error",
                            text: "No Beds Available in this Rooms",
                            type: "error",
                            hide: true,
                            styling: "fontawesome",
                        });
                    }
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }
    static getStudentsforassigndormitory(url, notify_script) {
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
                    $(".get_students_dormitory_assign").empty();
                    $(".get_students_dormitory_assign").html(
                        response.data.viewfile
                    );
                })
                .catch((error) => {
                    $(".get_students_dormitory_assign").html("");
                    console.log(error);
                });
        }
    }

    static getRooms(dormitory_id, notify_script) {
        let getUrl = window.getrooms + "?dormitory_id=" + dormitory_id;
        if (dormitory_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        $('select[name="room_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "Select room...",
                                data: response.data,
                            });
                    } else {
                        $('select[name="room_id"]')
                            .empty()
                            .select2({ placeholder: "Select room..." });
                        notify_script(
                            "Error",
                            "This Dormitory has no Rooms",
                            "error",
                            true
                        );
                    }
                })
                .catch((error) => {
                    let status = error.response.statusText;

                    notify_script("Error", status, "error", true);
                });
        } else {
            // clear section list dropdown
            $('select[name="teacher_id"]')
                .empty()
                .select2({ placeholder: "Pick a teacher..." });
        }
    }

    static Timetableinit(notify_script) {
        $(".periodsubmit").on("click", (e) => {
            var form = $(".period-form");
            console.log(form);
            $("#collapseOne").addClass("show");
            $("#collapseTwo").addClass("show");
            e.preventDefault();
            form.validate();
            if (form.valid() === true) {
                console.log("timetable");
                form.submit();
            } else {
                console.log("no");
                notify_script(
                    "Error",
                    "Please Fill out All required feilds",
                    "error",
                    true
                );
            }
        });

        $('select[name="academic_year"]').on("change", function () {
            let academic_year = $(this).val();
            AcademicConfig.getTerms(academic_year, notify_script);
        });

        $('select[name="class_id"]').on("change", function () {
            let class_id = $(this).val();
            let acyear = $('select[name="academic_year"]').val();

            if (acyear) {
                AcademicConfig.getSection(class_id, notify_script, "timetable");
            } else {
                notify_script(
                    "Error",
                    "Please Select Academic year",
                    "error",
                    true
                );
            }
        });

        $('select[name="section_id"]').on("change", function () {
            let section_id = $(this).val();
            if (section_id) {
                AcademicConfig.getSectionDepartments(section_id);
            }
        });

        $(".timetablebtn").on("click", function (e) {
            e.preventDefault();
            // for checking this is edit or create
            var name = e.target.getAttribute("name");

            console.log(name);
            const acyear = $('select[name="academic_year"]').val();
            const class_id = $('select[name="class_id"]').val();
            const section_id = $('select[name="section_id"]').val();
            const termid = $('select[name="term_id"]').val();
            const deptid = $('select[name="dept_id"]').val();
            const days = $('input[name="no_days"]').val();

            if (acyear && class_id && section_id && termid && days) {
                console.log(acyear, class_id, section_id, termid, deptid, days);

                let getUrl =
                    window.calenderurl +
                    "?academic_year=" +
                    acyear +
                    "&class_id=" +
                    class_id +
                    "&section_id=" +
                    section_id +
                    "&term_id=" +
                    termid +
                    "&dept_id=" +
                    deptid +
                    "&days=" +
                    days +
                    "&dataid=" +
                    name;

                AcademicConfig.getCalender(getUrl, notify_script, name);
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All required feilds",
                    "error",
                    true
                );
            }
        });

        // hover effect

        const elements_hover = document.querySelectorAll(".editsedule_box_td");

        elements_hover.forEach((element) => {
            element.addEventListener("mouseover", (event) => {
                $(
                    `#clearcell${event.target.id}${event.target.getAttribute(
                        "data-key"
                    )}`
                ).css("display", "block");
            });

            element.addEventListener("mouseout", (event) => {
                $(
                    `#clearcell${event.target.id}${event.target.getAttribute(
                        "data-key"
                    )}`
                ).css("display", "none");
            });
        });

        // $(".clearcell").on("click", function (e) {
        //     e.preventDefault();
        //     // for checking this is edit or create
        //     var uniqueid = e.target.getAttribute("name");
        //     var dayid = e.target.getAttribute("data-day");

        //     console.log(name);

        //     $(`.schedule_sub${uniqueid}${dayid}`).text("");
        //     $(`.sub_txt${uniqueid}${dayid}`).text("");
        //     $(`#sub_id${uniqueid}${dayid}`).val("");
        //     $(`#teacher_id${uniqueid}${dayid}`).val("");
        // });
    }

    static Clearcell(uniqueid, dayid, type = null) {
        // var uniqueid = e.target.getAttribute("name");
        // var dayid = e.target.getAttribute("data-day");
        // style="border-bottom:3px solid #55D6FF;background-color:#F2F6FF"
        console.log(uniqueid, dayid);

        var msg =
            type === "add"
                ? "Confirm to Delete Cell"
                : "If you Delete this Timetable ,Perviously Added Hourly Attendance also Deleted or Collapsed";

        Swal.fire({
            title: "Are you sure?",
            text: msg,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $(`.schedule_sub${uniqueid}${dayid}`).text("");
                $(`.sub_txt${uniqueid}${dayid}`).text("");
                $(`#sub_id${uniqueid}${dayid}`).val("");
                $(`#teacher_id${uniqueid}${dayid}`).val("");
                $(`#color_id${uniqueid}${dayid}`).val("");
                $(`#bgcolor_id${uniqueid}${dayid}`).val("");
                // $(`#timetable_id${uniqueid}${dayid}`).val("");
                $(`.schedule_box${uniqueid}${dayid}`).css(
                    "background-color",
                    "white"
                );
                $(`.schedule_box${uniqueid}${dayid}`).css("border", "none");
                if (type == "add") {
                    $(`#clearcell${uniqueid}${dayid}`).css("display", "none");
                } else {
                    $(`#clearcell${uniqueid}${dayid}`).remove();
                }
            }
        });
    }

    static timtablePerioddelete(event, period_id, academic_year, class_id) {
        let getUrl =
            window.perioddeleteurl +
            "?period_id=" +
            period_id +
            "&academic_year=" +
            academic_year +
            "&class_id=" +
            class_id;
        if (period_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (response.data.error) {
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
                                let Url =
                                    window.perioddeleteurl +
                                    "?period_id=" +
                                    period_id +
                                    "&academic_year=" +
                                    academic_year +
                                    "&class_id=" +
                                    class_id +
                                    "&type=" +
                                    1;
                                axios
                                    .get(Url)
                                    .then((response) => {
                                        event.closest(".period_row").remove();
                                        $(".inc_span").each(function (i) {
                                            $(this).html(i + 1);
                                        });

                                        const root =
                                            document.querySelectorAll(
                                                ".period_row"
                                            ).length;
                                        const root1 =
                                            document.getElementById("inc");
                                        root1.innerHTML = root;
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
                    } else {
                        event.closest(".period_row").remove();
                        $(".inc_span").each(function (i) {
                            $(this).html(i + 1);
                        });

                        const root =
                            document.querySelectorAll(".period_row").length;
                        const root1 = document.getElementById("inc");
                        root1.innerHTML = root;
                        notify_script(
                            "Success",
                            "Period Deleted Successfully",
                            "success",
                            true
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

    static getSectionDepartments(section_id) {
        let getUrl = window.depturl + "?section=" + section_id;
        if (class_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        console.log("ok");
                        $('select[name="dept_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select department...",
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        console.log("else");
                        $('select[name="dept_id"]')
                            .empty()
                            .select2({ placeholder: "select department..." });
                        // notify sceript here

                        Pace.stop();
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

    static getCalenderPopup(
        uniqueid,
        class_id,
        section,
        day,
        dayid,
        selectdsubject = null,
        selectdteacher = null
    ) {
        console.log(selectdsubject);
        $(`.assigen_time${uniqueid}`).text(day);
        $(`#colorModal${uniqueid}${dayid}`).modal("show");
        let url;
        if (selectdsubject) {
            $(`#subject_id${uniqueid}${dayid}`)
                .val(selectdsubject)
                .trigger("change");

            let url =
                window.subjectteachers +
                "?subject_id=" +
                selectdsubject +
                "&class=" +
                class_id +
                "&section_id=" +
                section +
                "&type=timetable";
            AcademicConfig.getAssigendTeachers(
                url,
                uniqueid,
                dayid,
                selectdteacher
            );
        }

        $(`#subject_id${uniqueid}${dayid}`).on("change", function () {
            let subject_id = $(this).val();
            url =
                window.subjectteachers +
                "?subject_id=" +
                subject_id +
                "&class=" +
                class_id +
                "&section_id=" +
                section +
                "&type=timetable";
            //    here axios

            AcademicConfig.getAssigendTeachers(url, uniqueid, dayid);
        });

        var colors = document.querySelectorAll(
            `.round_colors${uniqueid}${dayid}`
        );

        colors.forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log(e.target.getAttribute("name"));

                if (e.target.id) {
                    const elemetdata = document.querySelector(
                        `.schedule_box${uniqueid}${dayid}`
                    );

                    $(`#color_id${uniqueid}${dayid}`).val(e.target.id);
                    $(`#bgcolor_id${uniqueid}${dayid}`).val(
                        e.target.getAttribute("name")
                    );
                    const classlists = elemetdata.classList;

                    // elemetdata.classList.remove(classlists[2]);
                    //elemetdata.classList.add(`schedule_box_${e.target.id}`);
                    elemetdata.style.borderBottom = `3px solid ${e.target.id}`;
                    elemetdata.style.backgroundColor =
                        e.target.getAttribute("name");

                    //console.log(classlists);
                } else {
                    console.log("here");
                }
            });
        });

        $(`#colrpicker${uniqueid}${dayid}`).on("click", function () {
            const nextElement = $(this).nextAll();

            if (nextElement[1].classList.contains("pickclass")) {
                nextElement[1].classList.remove("pickclass");
                nextElement[1].classList.add("rpickclass");
            } else {
                nextElement[1].classList.remove("rpickclass");
                nextElement[1].classList.add("pickclass");
            }
            console.log(nextElement[1].classList);

            // $(`#colrpick${uniqueid}${dayid}`).focus();
            // $(`#colrpick${uniqueid}${dayid}`).blur();
        });

        // colorpicker

        $(`#colrpick${uniqueid}${dayid}`).iris({
            width: 300, // the width in pixel

            hide: true, // hide the color picker by default

            palettes: ["#125", "#459", "#78b", "#ab0", "#de3", "#f0f"], // custom palette

            change: function (event, ui) {
                const { ColorTranslator } = colortranslator;
                const tints = ColorTranslator.getTints(ui.color.toString(), 5);
                //f8e3e3
                // tints.forEach((c) => {
                //     const box = document.createElement("div");
                //     box.style.backgroundColor = c;
                //     container.appendChild(box);
                // });

                $(`#color_id${uniqueid}${dayid}`).val(ui.color.toString());
                $(`#bgcolor_id${uniqueid}${dayid}`).val(
                    tints[4] ? tints[4] : "#f8e3e3"
                );

                const elemetdata = document.querySelector(
                    `.schedule_box${uniqueid}${dayid}`
                );

                elemetdata.style.borderBottom = `3px solid ${ui.color.toString()}`;
                elemetdata.style.backgroundColor = tints[4]
                    ? tints[4]
                    : "#f8e3e3";
            },
        });

        document
            .querySelector(`.assignsubject${uniqueid}${dayid}`)
            .addEventListener("click", (e) => {
                e.preventDefault();

                var subject_id = $(`#subject_id${uniqueid}${dayid}`)
                    .find(":selected")
                    .val();
                var teacher_id = $(`#staff_id${uniqueid}${dayid}`)
                    .find(":selected")
                    .val();

                console.log(subject_id, teacher_id);

                let curl =
                    window.subjectteachers +
                    "?subject_id=" +
                    subject_id +
                    "&teacher_id=" +
                    teacher_id +
                    "&type=getnames";

                axios
                    .get(curl)
                    .then((response) => {
                        $(`.schedule_sub${uniqueid}${dayid}`).text(
                            response.data?.subject
                        );
                        $(`.sub_txt${uniqueid}${dayid}`).text(
                            response.data?.teacher
                        );
                        $(`#sub_id${uniqueid}${dayid}`).val(subject_id);
                        $(`#teacher_id${uniqueid}${dayid}`).val(teacher_id);

                        $(`#clearcell${uniqueid}${dayid}`).css(
                            "display",
                            "block"
                        );

                        $(`.closemodel${uniqueid}${dayid}`).click();
                    })
                    .catch((error) => console.log(error));
            });
    }

    static getAssigendTeachers(url, uniqueid, dayid, selectdteacher = null) {
        axios
            .get(url)
            .then((response) => {
                $(`#staff_id${uniqueid}${dayid}`).find("option").remove();

                if (Object.keys(response.data).length) {
                    for (const key in response.data) {
                        if (
                            response.data.hasOwnProperty.call(
                                response.data,
                                key
                            )
                        ) {
                            const element = response.data[key];
                            // console.log(element);

                            $(`#staff_id${uniqueid}${dayid}`).append(
                                `<option value='${element.id}'>${element.text}</option>`
                            );
                            if (selectdteacher) {
                                $(`#staff_id${uniqueid}${dayid}`)
                                    .val(selectdteacher)
                                    .trigger("change");
                            }
                        }
                    }
                    // $(`#staff_id${uniqueid}`)
                    //     .empty()
                    //     .prepend('<option selected=""></option>')
                    //     .select2({
                    //         allowClear: true,
                    //         placeholder: "select Staf...",
                    //         data: response.data,
                    //     });
                } else {
                    AcademicConfig.notify(
                        "Error",
                        "No Staff Found",
                        "error",
                        true
                    );
                }
            })
            .catch((error) => {
                console.log(error);
            });
    }

    static getCalender(url, notify_script, name) {
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
                    $(".timetableaccordian").removeClass("show");
                    $(".getcalender").empty();
                    $(".getcalender").html(response.data.viewfile);
                })
                .catch((error) => {
                    $(".getcalender").html("");
                    console.log(error);
                });
        }
    }

    static getTerms(academic_year, notify_script) {
        let getUrl = window.termurl + "?academic_year=" + academic_year;
        if (academic_year) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        $('select[name="term_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select Semester...",
                                data: response.data,
                            });

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
                        $('select[name="term_id"]')
                            .empty()
                            .select2({ placeholder: "select Semester..." });
                        notify_script(
                            "Error",
                            "This academic year has no terms. Please add terms to proceed. ",
                            "error",
                            true
                        );
                        $(".action_btn button").attr("disabled", true);
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
            $('select[name="term_id"]')
                .empty()
                .select2({ placeholder: "Pick a section..." });
        }
    }

    static ClassInit(notify_script) {
        $('select[name="class_id"]').on("change", function () {
            let class_id = $(this).val();
            console.log(class_id);

            if (class_id != 0 && class_id != "All") {
                Pace.start();
                AcademicConfig.getSection(class_id, notify_script);
            }
        });
        $('select[name="school_type"]').on("change", function () {
            let school_type = $(this).val();
            console.log(school_type);

            Pace.start();
            AcademicConfig.getClass(school_type, notify_script);
        });
    }

    static getClass(school_type, notify_script) {
        let getUrl;

        getUrl = window.classurl + "?school_type=" + school_type;

        if (school_type) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        $('select[name="class_id"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: "select class...",
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        $('select[name="class_id"]')
                            .empty()
                            .select2({ placeholder: "select class..." });

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
            $('select[name="class_id"]')
                .empty()
                .select2({ placeholder: "Pick a class..." });
        }
    }
}
