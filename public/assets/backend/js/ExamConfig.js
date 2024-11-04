import AcademicConfig from "./AcademicConfig.js";

export default class ExamConfig {
    static examinit(notify_script) {
        var today = new Date().toISOString().split("T")[0];

        var datefeild = document.getElementById("exdate");
        if (datefeild) {
            document.getElementById("exdate").setAttribute("min", today);
        }

        $(".mark_cls").on("input", function (event) {
            this.value = this.value.replace(/[^0-9]/g, "");
        });

        var element = document.querySelectorAll(".mark_cls");
        console.log(element, "mark");

        // if (element.length > 0) {
        //     console.log(element, "mark");
        //     element.forEach((ele) => {
        //         ele.keyup(function (e) {
        //             console.log("here");
        //             var charCode = e.which ? e.which : event.keyCode;
        //             console.log(charCode);

        //             if (String.fromCharCode(charCode).match(/[^0-9]/g))
        //                 return false;
        //         });
        //     });
        // }
        $(".distribution_status").on("change", function (e) {
            let distribution_id = $("#distribution_id").val();
            let getUrl =
                window.distribution_status +
                "?status=" +
                $(this).val() +
                "&id=" +
                distribution_id;
            if (getUrl) {
                axios
                    .post(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (response) {
                            notify_script(
                                "Success",
                                "Status Changed Successfully",
                                "success",
                                true
                            );
                        } else {
                            notify_script(
                                "Error",
                                "Status Not Changed",
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
            }
        });
        $('select[name="school_type"]').on("change", function (e) {
            // console.log("yes");
            let getUrl =
                window.getexams +
                "?type=" +
                `3` +
                "&school_type=" +
                $(this).val();

            if (getUrl) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (response.data.class) {
                            $(".attendance_col").hide();
                            $(".exam_col").hide();
                            console.log(
                                "data",
                                response.data.attendance,
                                response.data.exam
                            );
                            if (response.data.attendance == 1) {
                                $(".attendance_col").show();
                            }
                            if (response.data.exam == 1) {
                                $(".exam_col").show();
                            }

                            $('select[name="class_id"]')
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "Select Class...",
                                    data: response.data.class,
                                });
                        } else {
                            $('select[name="class_id"]').empty().select2({
                                placeholder: "Select School Type...",
                            });

                            notify_script(
                                "Error",
                                "No Class Found or No distributions found",
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
                $(".markentrysubjectid")
                    .empty()
                    .select2({ placeholder: "Pick a subject..." });
            }
        });
        // $(".markentrysubjectid").on("change", function (e) {
        //     let getUrl =
        //         window.getexams +
        //         "?type=" +
        //         `1` +
        //         "&subject_id=" +
        //         $(this).val();

        //     if (getUrl) {
        //         axios
        //             .get(getUrl)
        //             .then((response) => {
        //                 console.log(response);
        //                 if (response.data.length > 0) {
        //                     $('select[name="exam_type"]')
        //                         .empty()
        //                         .prepend('<option selected=""></option>')
        //                         .select2({
        //                             allowClear: true,
        //                             placeholder: "select exam...",
        //                             data: response.data,
        //                         });
        //                 } else {
        //                     $('select[name="exam_type"]').empty().select2({
        //                         placeholder: "select exam type...",
        //                     });

        //                     notify_script(
        //                         "Error",
        //                         "No Exam Found",
        //                         "error",
        //                         true
        //                     );
        //                 }
        //             })
        //             .catch((error) => {
        //                 let status = error;
        //                 console.log(status);
        //                 notify_script("Error", status, "error", true);
        //             });
        //     } else {
        //         // clear section list dropdown
        //         $(".markentrysubjectid")
        //             .empty()
        //             .select2({ placeholder: "Pick a subject..." });
        //     }
        // });
        $('select[name="exam_type"]').on("change", function (e) {
            console.log("its enter");
            let url = window.getexams;
            if (url) {
                let getUrl =
                    url +
                    "?type=" +
                    `2` +
                    "&subject_id=" +
                    $("#subject_id").val() +
                    "&exam_type=" +
                    $(this).val() +
                    "&exam_status=" +
                    $("#exam_status").val();

                if (getUrl) {
                    axios
                        .get(getUrl)
                        .then((response) => {
                            console.log(response);
                            if (response.data.length > 0) {
                                $('select[name="exam_id"]')
                                    .empty()
                                    .prepend('<option selected=""></option>')
                                    .select2({
                                        allowClear: true,
                                        placeholder: "select exam...",
                                        data: response.data,
                                    });
                            } else {
                                $('select[name="exam_id"]').empty().select2({
                                    placeholder: "select exam type...",
                                });

                                notify_script(
                                    "Error",
                                    "No Exam Types Found",
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
                    $("#exam_type")
                        .empty()
                        .select2({ placeholder: "Pick a exam type..." });
                }
            }
        });

        $("#exam_entry").on("change", function () {
            let url = window.append;
            let layout = $(this).val(); // Assuming $layout comes from the value of #exam_entry
            let exams = ""; // You can adjust this based on your requirements
            let exam_type = $('select[name="exam_type"]').val();
            let subject_id = $('select[name="subject_id"]').val();
            let school_type = $('select[name="school_type"]').val();
            // online or offline
            let exam_status = $('select[name="exam_status"]').val();
            let exams_entry = $(this).val();

            axios
                .post(url, {
                    layout: layout,
                    exams: exams,
                    exam_type: exam_type,
                    subject_id: subject_id,
                    exam_status: exam_status,
                    school_type: school_type,
                })
                .then((res) => {
                    $("#auto").empty();
                    if (exams_entry == "2") {
                        $("#auto").html(res.data.viewfile);
                        $("#auto").show();

                        // console.log($exams);
                        // $("#auto").addClass("row");
                        $(".single-select").select2();
                    } else {
                        $("#auto").hide();
                    }
                })
                .catch((error) => console.log(error));
        });
        $(".entry_mark").on("click", function (e) {
            e.preventDefault();

            let class_id = $('select[name="class_id"]').val();
            let section_id = $('select[name="section_id"]').val();
            let acyear = $('select[name="academic_year"]').val();
            let term = $('select[name="academic_term"]').val();
            let exam_type = $('select[name="exam_type"]').val();
            let subject_id = $('select[name="subject_id"]').val();
            let exam_id = $('select[name="exam_id"]').val();
            let attendance_type = $('select[name="attendence"]').val();
            let exam_field = $('select[name="exam_field"]').val();
            let exam_status = $('select[name="exam_status"]').val();
            let exam_entry = $('select[name="exam_entry"]').val();
            let school_type = $('select[name="school_type"]').val();

            if (exam_entry == "2") {
                if (
                    class_id &&
                    section_id &&
                    acyear &&
                    term &&
                    exam_type &&
                    subject_id &&
                    exam_id &&
                    attendance_type &&
                    exam_field &&
                    exam_status &&
                    exam_entry &&
                    school_type
                ) {
                    ExamConfig.EntryMark(
                        class_id,
                        section_id,
                        acyear,
                        term,
                        exam_type,
                        subject_id,
                        exam_id,
                        attendance_type,
                        exam_field,
                        exam_status,
                        exam_entry,
                        school_type
                    );
                } else {
                    notify_script(
                        "Error",
                        "Please Fill out All Required Feilds",
                        "error",
                        true
                    );
                }
            } else {
                if (
                    class_id &&
                    section_id &&
                    acyear &&
                    term &&
                    subject_id &&
                    attendance_type &&
                    exam_entry &&
                    school_type
                ) {
                    let exam_type = null;
                    let exam_id = null;
                    let exam_field = null;
                    let exam_status = null;

                    let getUrl =
                        window.entrymark +
                        "?class=" +
                        class_id +
                        "&section=" +
                        section_id +
                        "&acyear=" +
                        acyear +
                        "&term=" +
                        term +
                        "&exam_type=" +
                        exam_type +
                        "&exam_id=" +
                        exam_id +
                        "&attendance_type=" +
                        attendance_type +
                        "&exam_field=" +
                        exam_field +
                        "&exam_status=" +
                        exam_status +
                        "&exam_entry=" +
                        exam_entry +
                        "&subject_id=" +
                        subject_id +
                        "&school_type=" +
                        school_type;
                    if (class_id) {
                        axios
                            .get(getUrl)
                            .then((response) => {
                                console.log(response);

                                if (response.data.message) {
                                    var html = `<p class="text-danger text-center">${response.data.message}</p>`;
                                    $(".mark_entry_details").html("");
                                    $(".mark_entry_details").html(html);
                                } else {
                                    $(".mark_entry_details").html("");
                                    $(".mark_entry_details").html(
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
                } else {
                    notify_script(
                        "Error",
                        "Please Fill out All Required Feilds",
                        "error",
                        true
                    );
                }
            }
        });

        $(".add_mark_distribution").on("click", function (e) {
            e.preventDefault();

            let getUrl = window.addMarkDistribution;

            if (getUrl) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (response.data.view) {
                            $("#append").append(response.data.view);
                        }
                    })
                    .catch((error) => {
                        console.error(error);
                        // Handle errors here
                    });
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All Required Fields",
                    "error",
                    true
                );
            }
        });

        $(".prevbtn").on("click", (e) => {
            e.preventDefault();

            console.log("click");
            //return;
            var form = $("#exam-form");
            var subform = document.querySelector("#exam-form");
            var full_data = [];
            document.querySelector("#hidden-preview").value = "preview";
            form.validate();
            if (form.valid() === true) {
                subform.target = "_blank";
                subform.submit();
                subform.removeAttribute("target");
                document.querySelector("#hidden-preview").value = "normal";

                //console.log(new FormData(form));
                //console.log($("#exam-form").serializeArray());
                let data = new FormData(subform);
                for (let [key, value] of data) {
                    full_data[key] = value;
                    // console.log(key);
                    // console.log(value);
                }
                console.log(full_data);
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All Required Feilds",
                    "error",
                    true
                );
            }
        });
        $('select[name="class_id"]').on("change", function () {
            let class_id = $(this).val();
            let acyear = $('select[name="academic_year"]').val();
            $("#stu_include div").remove();
            $("#stu_exclude div").remove();

            if (acyear) {
                ExamConfig.getSection(class_id, notify_script);
                AcademicConfig.getsubjects(class_id, notify_script);
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

            $("#stu_include div").remove();
            $("#stu_exclude div").remove();

            if (acyear) {
                ExamConfig.Fetchstudents(class_id, section_id, notify_script);
            } else {
                notify_script(
                    "Error",
                    "Please Select Academic year",
                    "error",
                    true
                );
            }
        });
        // getting students information

        $(".selecttype").on("click", function (e) {
            if (e.target.value === "question") {
                $(".createquestiontab").show();
                $(".uploadquestiontab").hide();
            } else {
                $(".createquestiontab").hide();
                $(".uploadquestiontab").show();
            }
        });
    }

    static onlineexaminit(notify_script, totalMinutes) {
        console.log("online exam ini");
        var totalSeconds = 60 * totalMinutes; // 90 minutes in seconds
        var secondsRemaining = totalSeconds;

        var timer = setInterval(function () {
            secondsRemaining--;
            // Update the timer display on the page
            document.getElementById("onlineexamtimer").innerHTML =
                ExamConfig.formatTime(secondsRemaining);

            if (secondsRemaining == 0) {
                clearInterval(timer);
                // Automatically submit the exam
                document.getElementById("exam-form").submit();
            }
        }, 1000);

        // window.addEventListener("beforeunload", function (event) {
        //     if (window.document.getElementById("exam-form")) {
        //         // Display a confirmation message to the user

        //         event.preventDefault();
        //         event.stopPropagation();

        //         event.returnValue = "";
        //         window.opener.location.reload(true);

        //         // Submit the form when the user confirms
        //         window.confirm(
        //             "Are you sure you want to close this window and submit the form?"
        //         ) && window.document.getElementById("exam-form").submit();
        //     }
        // });

        $(".clear_answer").on("click", function () {
            var dataid = $(".active").attr("data-id");
            var textelement = document.querySelector(
                `.fill_blanks_text${dataid}`
            );

            var radioyesorno = document.querySelectorAll(
                `.take_exam_radio${dataid}`
            );

            var radiochoose = document.querySelectorAll(
                `.take_exam_radio_choose${dataid}`
            );
            if (textelement) {
                textelement.value = "";
                $(`#tabright${dataid}`).removeClass("qusactiveblue");
                $(`#tabright${dataid}`).addClass("qusactive");
            }

            if (radioyesorno.length > 0) {
                console.log(radioyesorno);
                radioyesorno.forEach((element) => {
                    element.checked = false;
                });
                $(`#tabright${dataid}`).removeClass("qusactiveblue");
                $(`#tabright${dataid}`).addClass("qusactive");
            }

            if (radiochoose.length > 0) {
                console.log(radiochoose);
                radiochoose.forEach((element) => {
                    element.checked = false;
                });
                $(`#tabright${dataid}`).removeClass("qusactiveblue");
                $(`#tabright${dataid}`).addClass("qusactive");
            }
            console.log($(".active").attr("data-id"));
            //console.log($("#TabList .ui-tabs-panel:visible").attr("id"));
        });

        $(".next_question").on("click", function () {
            console.log("next");

            var dataid = $(".active").attr("data-id");
            var textelement = document.querySelector(
                `.fill_blanks_text${dataid}`
            );

            console.log(textelement, dataid);

            var radioyesorno = document.querySelectorAll(
                `.take_exam_radio${dataid}`
            );

            var radiochoose = document.querySelectorAll(
                `.take_exam_radio_choose${dataid}`
            );

            var i,
                items = $(".onlineexamnav"),
                pane = $(".tab-pane");
            for (i = 0; i < items.length; i++) {
                if ($(items[i]).hasClass("active") == true) {
                    break;
                }
            }

            if (i < items.length - 1) {
                if (textelement && textelement.value != "") {
                    $(items[i]).addClass("qusactiveblue");
                } else {
                    $(items[i]).addClass("qusactive");
                }
                if (radiochoose.length > 0) {
                    radiochoose.forEach((element) => {
                        if (element.checked === true) {
                            $(items[i]).addClass("qusactiveblue");
                        } else {
                            $(items[i]).addClass("qusactive");
                        }
                    });
                }
                if (radioyesorno.length > 0) {
                    radioyesorno.forEach((element) => {
                        if (element.checked === true) {
                            $(items[i]).addClass("qusactive");
                        } else {
                            $(items[i]).addClass("qusactiveblue");
                        }
                    });
                }
                $(items[i]).removeClass("active");
                $(items[i + 1]).addClass("active");
                $(pane[i]).removeClass("show active");

                $(pane[i + 1]).addClass("show active");
            }
        });
    }

    static formatTime(seconds) {
        var hours = Math.floor(seconds / 3600);
        var minutes = Math.floor((seconds - hours * 3600) / 60);
        var seconds = seconds - hours * 3600 - minutes * 60;

        var formattedTime =
            hours.toString().padStart(2, "0") +
            ":" +
            minutes.toString().padStart(2, "0") +
            ":" +
            seconds.toString().padStart(2, "0");
        return `${formattedTime}`;
    }

    static deletequestion(exam_id, question_id) {
        let getUrl =
            window.deletequestion +
            "?exam_id=" +
            exam_id +
            "&question_id=" +
            question_id;
        Pace.start();

        if (question_id) {
            // return;
            axios
                .get(getUrl)
                .then((response) => {
                    Pace.stop();
                })
                .catch((error) => {
                    let status = error;
                    Pace.stop();
                    console.log(status);
                });
        }
    }
    static deletesection(exam_id, section) {
        let getUrl =
            window.deletesection +
            "?exam_id=" +
            exam_id +
            "&section_id=" +
            section;
        Pace.start();

        if (section) {
            axios
                .get(getUrl)
                .then((response) => {
                    Pace.stop();
                })
                .catch((error) => {
                    let status = error;
                    Pace.stop();
                    console.log(status);
                });
        }
    }
    static Fetchstudents(class_id, section_id, notify_script) {
        let getUrl =
            window.fetchstudents +
            "?class=" +
            class_id +
            "&section=" +
            section_id;
        if (class_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(
                        response.data.students_exclude,
                        response.data.students_include
                    );
                    window.students_exclude = response.data.students_exclude;

                    window.students_include = response.data.students_include;

                    // if (Object.keys(response.data.students_exclude).length) {
                    //     $('select[name="exclude_students[]"]')
                    //         .empty()

                    //         .select2({
                    //             allowClear: true,

                    //             data: response.data.students_exclude,
                    //         });

                    //     Pace.stop();
                    // } else {
                    //     $('select[name="exclude_students[]"]').empty().select2({
                    //         placeholder: "select exclude students...",
                    //     });
                    // }
                    // if (Object.keys(response.data.students_include).length) {
                    //     $('select[name="include_students[]"]')
                    //         .empty()

                    //         .select2({
                    //             allowClear: true,

                    //             data: response.data.students_include,
                    //         });

                    //     Pace.stop();
                    // } else {
                    //     $('select[name="include_students[]"]').empty().select2({
                    //         placeholder: "select include students...",
                    //     });
                    // }
                })
                .catch((error) => {
                    let status = error.response;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        }
    }
    static getSection(class_id, notify_script, type = null) {
        let getUrl = window.sectionurl + "?class=" + class_id;
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
                        notify_script(
                            "Error",
                            "This Class has No Section",
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
            $('select[name="section_id"]')
                .empty()
                .select2({ placeholder: "Pick a section..." });
        }
    }

    static prventMaxvalue(ele, value) {
        var changedValue = $(ele).val();
        var studentid = $(ele).attr("data-id");
        if (Number(changedValue) > Number(value)) {
            console.log("yes is big");
            $(ele).val(0);
        } else {
            const inputBoxes = document.querySelectorAll(
                `.studenttotalcalculate${studentid}`
            );
            console.log("No is not big");
            let sum = 0;
            inputBoxes.forEach((inputBox) => {
                const value = parseFloat(inputBox.value);
                if (!isNaN(value)) {
                    sum += value;
                }
            });

            $(`.studenttotaltext${studentid}`).html(sum);
            $(`.studenttotal${studentid}`).val(sum);
        }
    }

    static EntryMark(
        class_id,
        section_id,
        acyear,
        term,
        exam_type,
        subject_id,
        exam_id,
        attendance_type,
        exam_field,
        exam_status,
        exam_entry,
        school_type
    ) {
        let getUrl =
            window.entrymark +
            "?class=" +
            class_id +
            "&section=" +
            section_id +
            "&acyear=" +
            acyear +
            "&term=" +
            term +
            "&exam_type=" +
            exam_type +
            "&exam_id=" +
            exam_id +
            "&attendance_type=" +
            attendance_type +
            "&exam_field=" +
            exam_field +
            "&exam_status=" +
            exam_status +
            "&exam_entry=" +
            exam_entry +
            "&subject_id=" +
            subject_id +
            "&school_type=" +
            school_type;
        if (class_id) {
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);

                    if (response.data.message) {
                        var html = `<p class="text-danger text-center">${response.data.message}</p>`;
                        $(".mark_entry_details").html("");
                        $(".mark_entry_details").html(html);
                    } else {
                        $(".mark_entry_details").html("");
                        $(".mark_entry_details").html(response.data.view);
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

    static async CheckExamtitleExist(element, type, examid) {
        var title = $(`.${element}`).val();

        console.log(title, type, examid);

        let result;

        let getUrl =
            window.examtitleexists + "?exam_id=" + examid + "&type=" + type;

        if (title) {
            await axios
                .post(getUrl, { title: title })
                .then((response) => {
                    console.log(response);

                    if (response.data.exists) {
                        var html = `<p class="text-danger text-center">${response.data.message}</p>`;
                        $(".mark_title_details").html("");
                        $(".mark_title_details").html(html);
                    }

                    result = response.data.exists;
                })
                .catch((error) => {
                    let status = error.response;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        }
        return result;
    }

    static getQuestionsinfo(element, id) {
        let url = window.questioninfo;
        let submission_id = $(element).attr("data-submission");
        let onlineexam_id = $(element).attr("data-onlineexam");

        axios
            .post(url, {
                id: id,
                submission_id: submission_id,
                onlineexam_id: onlineexam_id,
            })
            .then((res) => {
                $(".homework_details").empty();
                $(".homework_details").html(res.data.viewfile);
                $("#view__report").modal("show");
            })
            .catch((error) => console.log(error));
    }
}
