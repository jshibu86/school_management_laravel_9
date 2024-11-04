export default class ExamTimetable {
    static ExamTimetableInit(notify_script, type = null) {
        $("#add_new_button").on("click", function () {
            console.log("yes");
            let url = window.append_new_period + "?type=" + "1";
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
        $(".add_exam_periods").on("click", function () {
            let academic_year = $('select[name="academic_year_grade"]').val();
            let academic_term = $('select[name="academic_term"]').val();
            let school_type = $('select[name="school_type"]').val();
            let class_id = $('select[name="class_id"]').val();
            let section_id = $('select[name="sec_dep"]').val();
            let start_date = $("#start_date").val();
            let end_date = $("#end_date").val();
            let url =
                window.append_new_period +
                "?type=" +
                "2" +
                "&academic_year=" +
                academic_year +
                "&academic_term=" +
                academic_term +
                "&school_type=" +
                school_type +
                "&class_id=" +
                class_id +
                "&section_id=" +
                section_id +
                "&start_date=" +
                start_date +
                "&end_date=" +
                end_date;

            if (
                url &&
                academic_year &&
                academic_term &&
                school_type &&
                class_id &&
                section_id &&
                start_date &&
                end_date
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

                ExamTimetable.getCalender(getUrl, notify_script, name);
            } else {
                notify_script(
                    "Error",
                    "Please Fill out All required feilds",
                    "error",
                    true
                );
            }
        });

        $(".delete-row-period").on("click", function () {
            var period_id = $(this).attr("id");
            let url = window.deleteperiod + "?id=" + period_id + "&type=" + "4";

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
    static getCalenderPopup(
        uniqueid,
        class_id,
        section,
        day,
        dayid,
        selectdsubject = null,
        selectdteacher = null
    ) {
        console.log("in enter");
        var uni_id = uniqueid;
        var day_id = dayid;

        console.log(uni_id, day_id);
        $(`.assigen_time${uni_id}${day_id}`).text(day);
        $(`#colorModal${uni_id}${day_id}`).modal("show");
        console.log(`.assigen_time${uni_id}${day_id}`);
        let url;
        // if (selectdsubject) {
        //     $(`#subject_id${uni_id}${day_id}`)
        //         .val(selectdsubject)
        //         .trigger("change");

        //     let url =
        //         window.subjectteachers +
        //         "?subject_id=" +
        //         selectdsubject +
        //         "&class=" +
        //         class_id +
        //         "&section_id=" +
        //         section +
        //         "&type=timetable";
        //     ExamTimetable.getAssigendTeachers(
        //         url,
        //         uni_id,
        //         day_id,
        //         selectdteacher
        //     );
        // }

        // $(`#subject_id${uni_id}`).on("change", function () {
        //     let subject_id = $(this).val();
        //     url =
        //         window.subjectteachers +
        //         "?subject_id=" +
        //         subject_id +
        //         "&class=" +
        //         class_id +
        //         "&section_id=" +
        //         section +
        //         "&type=timetable";
        //     //    here axios

        //     ExamTimetable.getAssigendTeachers(url, uni_id, day_id);
        // });

        console.log(uni_id, day_id);
        var colors = document.querySelectorAll(
            `.round_colors${uni_id}${day_id}`
        );

        colors.forEach((element) => {
            element.addEventListener("click", (e) => {
                console.log(e.target.getAttribute("name"));
                console.log(e.target.id);
                if (e.target.id) {
                    const elemetdata = document.querySelector(
                        `.schedule_box${uni_id}${day_id}`
                    );
                    console.log(elemetdata);
                    $(`#color_id${uni_id}${day_id}`).val(e.target.id);
                    $(`#bgcolor_id${uni_id}${day_id}`).val(
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

        $(`#colrpicker${uni_id}${day_id}`).on("click", function () {
            const nextElement = $(this).nextAll();

            if (nextElement[1].classList.contains("pickclass")) {
                nextElement[1].classList.remove("pickclass");
                nextElement[1].classList.add("rpickclass");
            } else {
                nextElement[1].classList.remove("rpickclass");
                nextElement[1].classList.add("pickclass");
            }
            console.log(nextElement[1].classList);

            // $(`#colrpick${uni_id}${day_id}`).focus();
            // $(`#colrpick${uni_id}${day_id}`).blur();
        });

        // colorpicker

        $(`#colrpick${uni_id}${day_id}`).iris({
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

                $(`#color_id${uni_id}${day_id}`).val(ui.color.toString());
                $(`#bgcolor_id${uni_id}${day_id}`).val(
                    tints[4] ? tints[4] : "#f8e3e3"
                );

                const elemetdata = document.querySelector(
                    `.schedule_box${uni_id}${day_id}`
                );

                elemetdata.style.borderBottom = `3px solid ${ui.color.toString()}`;
                elemetdata.style.backgroundColor = tints[4]
                    ? tints[4]
                    : "#f8e3e3";
            },
        });
        console.log("before");
        document
            .querySelector(`.assignsubject${uni_id}${day_id}`)
            .addEventListener("click", (e) => {
                e.preventDefault();
                console.log("after");
                console.log(`.assignsubject${uni_id}${day_id}`);
                var subject_id = $(`#subject_id${uni_id}${day_id}`)
                    .find(":selected")
                    .val();

                console.log(subject_id);

                let curl =
                    window.subjectteachers +
                    "?subject_id=" +
                    subject_id +
                    "&type=3";

                axios
                    .get(curl)
                    .then((response) => {
                        $(`.schedule_sub${uni_id}${day_id}`).text(
                            response.data?.subject
                        );

                        $(`#sub_id${uni_id}${day_id}`).val(subject_id);
                        $(`#clearcell${uni_id}${day_id}`).css(
                            "display",
                            "none"
                        );
                        $(`#clearcell${uni_id}${day_id}`).css(
                            "display",
                            "block"
                        );

                        $(`.closemodel${uni_id}${day_id}`).click();
                    })
                    .catch((error) => console.log(error));
            });
    }
    static getAssigendTeachers(url, uni_id, day_id, selectdteacher = null) {
        axios
            .get(url)
            .then((response) => {
                $(`#staff_id${uni_id}${day_id}`).find("option").remove();

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

                            $(`#staff_id${uni_id}${day_id}`).append(
                                `<option value='${element.id}'>${element.text}</option>`
                            );
                            if (selectdteacher) {
                                $(`#staff_id${uni_id}${day_id}`)
                                    .val(selectdteacher)
                                    .trigger("change");
                            }
                        }
                    }
                    // $(`#staff_id${uni_id}`)
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
    static Clearcell(uni_id, day_id, type = null) {
        // var uni_id = e.target.getAttribute("name");
        // var day_id = e.target.getAttribute("data-day");
        // style="border-bottom:3px solid #55D6FF;background-color:#F2F6FF"
        console.log(uni_id, day_id);

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
                $(`.schedule_sub${uni_id}${day_id}`).text("");
                $(`.sub_txt${uni_id}${day_id}`).text("");
                $(`#sub_id${uni_id}${day_id}`).val("");
                $(`#teacher_id${uni_id}${day_id}`).val("");
                $(`#color_id${uni_id}${day_id}`).val("");
                $(`#bgcolor_id${uni_id}${day_id}`).val("");
                // $(`#timetable_id${uni_id}${day_id}`).val("");
                $(`.schedule_box${uni_id}${day_id}`).css(
                    "background-color",
                    "white"
                );
                $(`.schedule_box${uni_id}${day_id}`).css("border", "none");
                if (type == "add") {
                    $(`#clearcell${uni_id}${day_id}`).css("display", "none");
                } else {
                    $(`#clearcell${uni_id}${day_id}`).remove();
                }
            }
        });
    }
}
