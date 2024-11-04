import Academic from "./AcademicConfig.js";

export default class GeneralConfig {
    static notify(title, text, type, hide) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            hide: hide,
            styling: "fontawesome",
        });
    }
    static generalinit(notify_script) {
        console.log("general");

        var aca_start_year;
        var aca_end_year;
        let aca_year;

        let academicYearStartDate;
        let academicYearEndDate;

        $('button[type="reset"]').click(function () {
            window.location.reload;
            console.log("yes reser");

            // prevent reset button from resetting again
        });

        $(".clear_button").on("click", function () {
            $(".datepicker").val("");

            console.log("clicked");
        });

        $(document).on("click", ".delete", function (event) {
            //datepicker
            event.preventDefault();
            event.stopImmediatePropagation();

            console.log("here");

            //$("#modalbox").modal("show");
            var target = $(this).closest("form");
            //var link = target.attr("href");
            console.log(target.attr("href"));
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    target.submit();
                }
            });
        });

        // colorpicker

        $("#break_colorpicker").iris({
            width: 300, // the width in pixel

            hide: true, // hide the color picker by default

            palettes: ["#125", "#459", "#78b", "#ab0", "#de3", "#f0f"], // custom palette
        });

        $("#text_colorpicker").iris({
            width: 300, // the width in pixel

            hide: true, // hide the color picker by default

            palettes: ["#125", "#459", "#78b", "#ab0", "#de3", "#f0f"], // custom palette
        });
        $("#border_colorpicker").iris({
            width: 300, // the width in pixel

            hide: true, // hide the color picker by default

            palettes: ["#125", "#459", "#78b", "#ab0", "#de3", "#f0f"], // custom palette
        });

        $("#id_card_headerpicker").iris({
            width: 300, // the width in pixel

            hide: true, // hide the color picker by default

            palettes: ["#125", "#459", "#78b", "#ab0", "#de3", "#f0f"], // custom palette
        });

        $("#fee_receipt_headerpicker").iris({
            width: 300, // the width in pixel

            hide: true, // hide the color picker by default

            palettes: ["#125", "#459", "#78b", "#ab0", "#de3", "#f0f"], // custom palette
        });

        $("#salary_receipt_headerpicker").iris({
            width: 300, // the width in pixel

            hide: true, // hide the color picker by default

            palettes: ["#125", "#459", "#78b", "#ab0", "#de3", "#f0f"], // custom palette
        });

        //timepicker
        //$(".btimepicker").datepicker();

        $("#timepicker").datetimepicker({
            datepicker: false,
            ampm: true, // FOR AM/PM FORMAT
            format: "g:i A",
        });
        $("#timepicker_daystart_").datetimepicker({
            format: "LT",
        });

        $("#timepicker_dayend_").datetimepicker({
            format: "LT",
        });
        $("#timepicker_dayend").bootstrapMaterialDatePicker({
            date: false,
            format: "h:mm a",
        });
        $("#timepicker_daystart").bootstrapMaterialDatePicker({
            date: false,
            format: "h:mm a",
        });
        //yearonly picker
        $(".datepicker_academic_start").datepicker();
        $(".datepicker")
            .datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                immediateUpdates: true,
                todayBtn: true,
                todayHighlight: true,
                minDate: new Date(),
            })
            .on(function () {
                var dateValue = $(this).val();
                if (dateValue) {
                    $(this).datepicker("setDate", dateValue);
                } else {
                    $(this).datepicker("setDate", "0");
                }
            });
        $(".datepickerwithoutselected").datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true,
        });

        $(".reminderdate").datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true,
            minDate: new Date(),
        });

        $(".dobdate").each(function () {
            let dateValue = $("#dob").val();
            $(this).datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayBtn: true,
                todayHighlight: true,
                maxDate: new Date(),
                defaultViewDate: new Date(dateValue),
            });
        });
        function getWeekStartDate() {
            const today = new Date();
            const dayOfWeek = today.getDay();
            const diff =
                today.getDate() - dayOfWeek + (dayOfWeek === 0 ? -6 : 1);
            const weekStart = new Date(today.setDate(diff));
            return weekStart;
        }

        function getWeekEndDate() {
            const weekStart = getWeekStartDate();
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekStart.getDate() + 6);
            return weekEnd;
        }
        $(".weekdate").each(function () {
            console.log("its week", getWeekStartDate(), getWeekEndDate());
            $(this).datepicker({
                autoclose: true,
                format: "mm/dd/yyyy",
                todayBtn: true,
                todayHighlight: true,
                maxDate: new Date(getWeekEndDate()),
                minDate: new Date(getWeekStartDate()),
            });
        });

        $(".meetdate").datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true,
            minDate: new Date(),
        });

        $(".datepick").datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true,
            minDate: new Date(),
        });

        $(".month-picker").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: "MM yy",
            onClose: function (dateText, inst) {
                $(this).datepicker(
                    "setDate",
                    new Date(inst.selectedYear, inst.selectedMonth, 1)
                );
            },
        });
        let start_date = $("#start_date").val()
            ? $.datepicker.parseDate("mm/dd/yy", $("#start_date").val())
            : null;
        let end_date = $("#end_date").val()
            ? $.datepicker.parseDate("mm/dd/yy", $("#end_date").val())
            : null;

        $(".month-picker-attend").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: "MM yy",
            minDate: start_date,
            maxDate: end_date,
            onClose: function (dateText, inst) {
                $(this).datepicker(
                    "setDate",
                    new Date(inst.selectedYear, inst.selectedMonth, 1)
                );
            },
        });
        $(".month-picker-attend").focus(function () {
            $(".ui-datepicker-calendar").hide();
        });

        $(".month-picker").focus(function () {
            $(".ui-datepicker-calendar").hide();
        });
        $(".month-picker-pay").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: "MM yy",
            onClose: function (dateText, inst) {
                $(this).datepicker(
                    "setDate",
                    new Date(inst.selectedYear, inst.selectedMonth, 1)
                );
            },
        });

        $(".month-picker-pay").focus(function () {
            $(".ui-datepicker-calendar").hide();
        });

        $("#month_year").datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: "MM yy",
            onClose: function (dateText, inst) {
                $(this).datepicker(
                    "setDate",
                    new Date(inst.selectedYear, inst.selectedMonth, 1)
                );
            },
        });

        $("#month_year").focus(function () {
            $(".ui-datepicker-calendar").hide();
        });

        $(".year-picker").datepicker({
            changeYear: true,
            showButtonPanel: true,
            dateFormat: "yy",
            onClose: function (dateText, inst) {
                var year = $(
                    "#ui-datepicker-div .ui-datepicker-year :selected"
                ).val();
                $(this).datepicker("setDate", new Date(year, 1));
            },
        });

        $(".year-picker").focus(function () {
            $(".ui-datepicker-calendar").hide();
        });
        //image size
        $(document).on("input", ".size_img", function () {
            console.log("its enter");
            let msg_p = $(this).closest("div").find(".error_msg");
            msg_p.text("");
            const maxSizeInMB = 4;
            const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
            // "application/pdf", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
            const allowedMimes = ["image/jpeg", "image/png", "image/jpg"];
            for (let i = 0; i < this.files.length; i++) {
                if (allowedMimes.includes(this.files[i].type)) {
                    if (this.files[i].size > maxSizeInBytes) {
                        console.log("its enter if");
                        // notify_script(
                        //     "Error",
                        //     "File size exceeds the maximum limit of " +
                        //         maxSizeInMB +
                        //         " MB.",
                        //     "error",
                        //     true
                        // );

                        console.log(msg_p);
                        msg_p.text(
                            "File size exceeds the maximum limit of " +
                                maxSizeInMB +
                                " MB."
                        );
                        this.value = "";
                        return;
                    }
                }
            }
        });

        $(document).on("input", ".tex_img", function () {
            console.log("its tex img");
            let msg_p = $(this).closest("div").find(".error_msg");
            msg_p.text("");
            const maxSizeInMB = 4;
            const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
            // "application/pdf", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
            const allowedMimes = [
                "image/jpeg",
                "image/png",
                "image/jpg",
                "application/pdf",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            ];
            for (let i = 0; i < this.files.length; i++) {
                if (allowedMimes.includes(this.files[i].type)) {
                    if (this.files[i].size > maxSizeInBytes) {
                        console.log("its enter if");
                        // notify_script(
                        //     "Error",
                        //     "File size exceeds the maximum limit of " +
                        //         maxSizeInMB +
                        //         " MB.",
                        //     "error",
                        //     true
                        // );

                        console.log(msg_p);
                        msg_p.text(
                            "File size exceeds the maximum limit of " +
                                maxSizeInMB +
                                " MB."
                        );
                        this.value = "";
                        return;
                    }
                } else {
                    var input = this;
                    var img = $(input).closest(".row").find(".redcol .file");
                    img.attr("src", " ");
                    input.value = "";
                    $(this).prop("disabled", true);
                    setTimeout(() => $(this).prop("disabled", false), 100);
                    msg_p.text(
                        "File accepted: .docx, .pdf, .jpeg, .jpg, .png. Please choose a suitable file format."
                    );
                    return;
                }
            }
        });

        $(document).on("input", ".home_img", function () {
            console.log("its tex img");
            let msg_p = $(this).closest("div").find(".error_msg");
            msg_p.text("");
            const maxSizeInMB = 4;
            const maxSizeInBytes = maxSizeInMB * 1024 * 1024;
            // "application/pdf", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
            const allowedMimes = [
                "image/jpeg",
                "image/png",
                "image/jpg",
                "application/pdf",
            ];
            for (let i = 0; i < this.files.length; i++) {
                if (allowedMimes.includes(this.files[i].type)) {
                    if (this.files[i].size > maxSizeInBytes) {
                        console.log("its enter if");
                        // notify_script(
                        //     "Error",
                        //     "File size exceeds the maximum limit of " +
                        //         maxSizeInMB +
                        //         " MB.",
                        //     "error",
                        //     true
                        // );

                        console.log(msg_p);
                        msg_p.text(
                            "File size exceeds the maximum limit of " +
                                maxSizeInMB +
                                " MB."
                        );
                        this.value = "";
                        return;
                    }
                } else {
                    var input = this;
                    var img = $(input).closest(".row").find(".redcol .file");
                    img.attr("src", " ");
                    input.value = "";
                    $(this).prop("disabled", true);
                    setTimeout(() => $(this).prop("disabled", false), 100);
                    msg_p.text(
                        "File accepted: .pdf, .jpeg, .jpg, .png. Please choose a suitable file format."
                    );
                    return;
                }
            }
        });

        // LEAVE DATEPICKER FOR CALCULATING DAYS

        $(".from_datepicker").datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true,
            minDate: new Date(),
            onSelect: function (dateText) {
                var from_date = this.value;
                var to_date = $("#to_date").val();
                if (to_date) {
                    if (from_date > to_date) {
                        notify_script(
                            "Error",
                            "From date greaterthan to date",
                            "error",
                            true
                        );
                        $("#from_date").val("");
                        $("#no_days").val("");
                        return;
                    } else {
                        var days = GeneralConfig.datediff(
                            GeneralConfig.parseDate(from_date),
                            GeneralConfig.parseDate(to_date)
                        );
                        days == 0
                            ? $("#no_days").val(1)
                            : $("#no_days").val(days);
                    }
                }
            },
        });
        $(".to_datepicker").datepicker({
            autoclose: true,
            format: "yyyy-mm-dd",
            immediateUpdates: true,
            todayBtn: true,
            todayHighlight: true,
            minDate: new Date(),
            onSelect: function (dateText) {
                var to_date = this.value;
                var from_date = $("#from_date").val();
                if (from_date) {
                    if (to_date < from_date) {
                        notify_script(
                            "Error",
                            "To date smallerthan From date",
                            "error",
                            true
                        );
                        $("#to_date").val("");
                        $("#no_days").val("");
                        return;
                    } else {
                        var days = GeneralConfig.datediff(
                            GeneralConfig.parseDate(from_date),
                            GeneralConfig.parseDate(to_date)
                        );
                        days == 0
                            ? $("#no_days").val(1)
                            : $("#no_days").val(days);
                    }
                }
            },
        });
        // LEAVE DATEPICKER END FOR CALCULATING DAYS
        $(".datepicker_academic_end").datepicker();

        $("#year_pick_from").datepicker({
            changeYear: true,
            showButtonPanel: true,
            dateFormat: "yy",
            beforeShow: function (input) {
                $(input).datepicker("widget").addClass("hide-calendar");
            },
            onClose: function (dateText, inst) {
                $(this).datepicker(
                    "setDate",
                    new Date(inst.selectedYear, inst.selectedMonth, 1)
                );
                $(this).datepicker("widget").removeClass("hide-calendar");
            },
        });
        $("#year_pick_to").datepicker({
            changeYear: true,
            showButtonPanel: true,
            dateFormat: "yy",
            beforeShow: function (input) {
                $(input).datepicker("widget").addClass("hide-calendar");
            },
            onClose: function (dateText, inst) {
                $(this).datepicker(
                    "setDate",
                    new Date(inst.selectedYear, inst.selectedMonth, 1)
                );
                $(this).datepicker("widget").removeClass("hide-calendar");
            },
        });

        $(".datepicker_term_from")
            .first()
            .datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                immediateUpdates: true,
                todayBtn: true,
                todayHighlight: true,
                changeMonth: true,
                changeYear: true,
                onSelect: function (selected) {
                    var term_start_date = selected;
                    var term_end_date = $(".datepicker_term_to").val();
                    var term_end_date_var = $(".datepicker_term_to");
                    console.log(term_end_date);
                    if (
                        term_start_date !== undefined &&
                        term_start_date !== null
                    ) {
                        console.log(term_end_date);
                        if (
                            term_end_date !== undefined &&
                            term_end_date !== null
                        ) {
                            console.log(term_end_date);
                            if (
                                term_end_date.length > 0 &&
                                term_start_date.length > 0
                            ) {
                                console.log(term_start_date, term_end_date);
                                if (
                                    new Date(term_start_date) <
                                    new Date(term_end_date)
                                ) {
                                    $(this).removeClass("error_term");
                                    term_end_date_var.removeClass("error_term");
                                    if ($(".error_term").length) {
                                        $(".submit_btn").hide();
                                    } else {
                                        $(".submit_btn").show();
                                    }
                                } else {
                                    console.log(term_end_date);
                                    $(".submit_btn").hide();
                                    $(this).addClass("error_term");
                                    notify_script(
                                        "Error",
                                        "Start Date should be Less than End Date",
                                        "error",
                                        true
                                    );
                                }
                            }
                        }
                    }
                },
            });

        $(".datepicker_term_from")
            .not(":first")
            .each(function (index) {
                var $this = $(this);
                var idx = index;
                $this.datepicker({
                    onSelect: function (selected) {
                        // Get the corresponding "End Date" datepicker input
                        var ind = idx + 1;
                        var $endDateInput = $(".datepicker_term_to").eq(idx);
                        var endDateInputFor = $(".datepicker_term_to").eq(ind);

                        // Get the value of the "End Date" datepicker
                        var endDateValue = $endDateInput.val();
                        var endDateInputForVal = endDateInputFor.val();

                        if (
                            endDateValue !== undefined &&
                            endDateValue !== null
                        ) {
                            if (endDateValue.length > 0) {
                                if (
                                    new Date(endDateValue) >= new Date(selected)
                                ) {
                                    notify_script(
                                        "Error",
                                        "Start Date should be Greater than Previous Term End Date",
                                        "error",
                                        true
                                    );

                                    $(".datepicker_term_to").prop(
                                        "disabled",
                                        true
                                    );
                                    $(".datepicker_term_from").prop(
                                        "disabled",
                                        true
                                    );
                                    $this.prop("disabled", false);
                                } else {
                                    if (endDateInputForVal.length > 0) {
                                        if (
                                            endDateValue !== undefined &&
                                            endDateValue !== null
                                        ) {
                                            if (endDateValue.length > 0) {
                                                if (
                                                    new Date(endDateValue) >
                                                    new Date(selected)
                                                ) {
                                                    $(this).removeClass(
                                                        "error_term"
                                                    );
                                                    endDateInputFor.removeClass(
                                                        "error_term"
                                                    );
                                                    if (
                                                        $(".error_term").length
                                                    ) {
                                                        $(".submit_btn").hide();
                                                    } else {
                                                        $(".submit_btn").show();
                                                    }
                                                } else {
                                                    $(".submit_btn").hide();
                                                    $(this).addClass(
                                                        "error_term"
                                                    );

                                                    notify_script(
                                                        "Error",
                                                        "Start Date should be Less than End Date",
                                                        "error",
                                                        true
                                                    );
                                                }
                                            }
                                        }
                                    } else {
                                        $(".datepicker_term_to").prop(
                                            "disabled",
                                            false
                                        );
                                        $(".datepicker_term_from").prop(
                                            "disabled",
                                            false
                                        );
                                    }
                                }
                            }
                        }
                    },
                });
            });

        $(".datepicker_term_to")
            .first()
            .datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                immediateUpdates: true,
                todayBtn: true,
                todayHighlight: true,
                changeMonth: true,
                changeYear: true,
                onSelect: function (selected) {
                    var term_end_date = selected;
                    var term_start_date = $(".datepicker_term_from").val();
                    var term_start_date_var = $(".datepicker_term_from");

                    if (term_end_date !== undefined && term_end_date !== null) {
                        if (
                            term_start_date !== undefined &&
                            term_start_date !== null
                        ) {
                            if (
                                term_end_date.length > 0 &&
                                term_start_date.length > 0
                            ) {
                                if (
                                    new Date(term_start_date) <
                                    new Date(term_end_date)
                                ) {
                                    $(this).removeClass("error_term");
                                    term_start_date_var.removeClass(
                                        "error_term"
                                    );
                                    if ($(".error_term").length) {
                                        $(".submit_btn").hide();
                                    } else {
                                        $(".submit_btn").show();
                                    }
                                } else {
                                    $(".submit_btn").hide();
                                    $(this).addClass("error_term");
                                    notify_script(
                                        "Error",
                                        "Start Date should be Less than End Date",
                                        "error",
                                        true
                                    );
                                }
                            }
                        }
                    }
                },
            });

        $(".datepicker_term_to")
            .not(":first")
            .each(function (index) {
                var $this = $(this);
                var idx = index + 1;
                $this.datepicker({
                    onSelect: function (selected) {
                        // Get the corresponding "End Date" datepicker input
                        var $startDateInput = $(".datepicker_term_from").eq(
                            idx
                        );
                        // Get the value of the "End Date" datepicker
                        var startDateValue = $startDateInput.val();

                        if (
                            startDateValue !== undefined &&
                            startDateValue !== null
                        ) {
                            if (startDateValue.length > 0) {
                                if (
                                    new Date(startDateValue) <
                                    new Date(selected)
                                ) {
                                    $(this).removeClass("error_term");
                                    $startDateInput.removeClass("error_term");
                                    if ($(".error_term").length) {
                                        $(".submit_btn").hide();
                                    } else {
                                        $(".submit_btn").show();
                                    }
                                } else {
                                    $(".submit_btn").hide();
                                    $(this).addClass("error_term");
                                    notify_script(
                                        "Error",
                                        "Start Date should be Less than End Date",
                                        "error",
                                        true
                                    );
                                }
                            }
                        }
                    },
                });
            });

        //designationtype
        $("#designation_type").change(function () {
            var value = this.value;

            if (value == "0") {
                $(".designation_select_feild").hide();
                $(".designation_type_feild").show();
                $(".type_feild").removeAttr("disabled");
            } else {
                console.log("no");
            }
        });
        //select back to type
        $(".back_to").click(function () {
            $(".designation_select_feild").show();
            $(".designation_type_feild").hide();
            $(".type_feild").attr("disabled", "disabled");
        });
        //remove image
        $(".remove").click(function () {
            var input = this;
            var dataid = input.getAttribute("data-id");
            var dataclass = input.getAttribute("data-class");
            console.log($(`#${dataid}_img`));
            $(`#${dataid}_img_${dataclass}`).val("");
            $(`#${dataid}holder`).attr("src", "");
            $(`#${dataid}holder`).hide();
            $(this).hide();
        });
        $("#remove_img").click(function () {
            $("#imagec-thumbnail").val("");
            $("#imagecholder").attr("src", "");
            $("#imagecholder").hide();
            $(this).hide();
        });
        $("#remove_img_mother").click(function () {
            $("#mother_image-thumbnail").val("");
            $("#mother_imageholder").attr("src", "");
            $("#mother_imageholder").hide();
            $(this).hide();
        });
        $("#remove_img_father").click(function () {
            $("#father_image-thumbnail").val("");
            $("#father_imageholder").attr("src", "");
            $("#father_imageholder").hide();
            $(this).hide();
        });
        $("#remove_img_guardian").click(function () {
            $("#guardian_image-thumbnail").val("");
            $("#guardian_imageholder").attr("src", "");
            $("#guardian_imageholder").hide();
            $(this).hide();
        });
        $("#remove_pdf_birth").click(function () {
            $("#birth_certificate-thumbnail").val("");

            $(this).hide();
        });
        $("#remove_pdf_tranfer").click(function () {
            $("#tranfer_certificate-thumbnail").val("");

            $(this).hide();
        });
        $("#remove_pdf_mark").click(function () {
            $("#mark_sheet-thumbnail").val("");

            $(this).hide();
        });
        $("#remove_pdf_nat").click(function () {
            $("#nat_id-thumbnail").val("");

            $(this).hide();
        });

        $("#imagec-thumbnail").change(function () {
            $("#remove_img").show();
        });
        $("#mother_image-thumbnail").change(function () {
            $("#remove_img_mother").show();
        });
        $("#father_image-thumbnail").change(function () {
            $("#remove_img_father").show();
        });
        $("#guardian_image-thumbnail").change(function () {
            $("#remove_img_guardian").show();
        });
        $("#birth_certificate-thumbnail").change(function () {
            $("#remove_pdf_birth").show();
        });
        $("#tranfer_certificate-thumbnail").change(function () {
            $("#remove_pdf_tranfer").show();
        });
        $("#mark_sheet-thumbnail").change(function () {
            $("#remove_pdf_mark").show();
        });
        $("#nat_id-thumbnail").change(function () {
            $("#remove_pdf_nat").show();
        });

        $(".thumb").change(function () {
            //console.log(this);
            var input = this;
            var dataid = input.getAttribute("name");
            $(`#${dataid}holder`).show();
            GeneralConfig.readIMG(this);
        });

        // $('select[name="school_type"]').on("change", function () {
        //     var value = $(this).val();
        //     if (value == 1) {
        //         $(".schooldepartment").removeClass("d-none");
        //     } else {
        //         $(".schooldepartment").addClass("d-none");
        //     }
        // });

        $('select[name="academic_year"]').on("change", function () {
            $(".tab").show();
            $(".btnadd1").show();
            console.log("its academic");
            var academic_year = $(this).val();
            //alert(academic_year);

            let element = "";

            getInfo(0, academic_year, element, "academic year");
        });

        function getInfo(type, id, element, placeholder) {
            let getUrl =
                window.getacademicinfo +
                "?type=" +
                type +
                "&academic_year=" +
                id;
            if (id) {
                console.log("OK1");
                axios
                    .get(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (Object.keys(response.data).length) {
                            aca_start_year = response.data.start_date;
                            aca_end_year = response.data.end_date;

                            console.log(aca_start_year);
                            console.log(aca_end_year);

                            academicYearStartDate = aca_start_year;
                            academicYearEndDate = aca_end_year;

                            if (academicYearStartDate) {
                                // Set minDate and maxDate dynamically

                                var dateParts1 =
                                    academicYearStartDate.split("/");
                                var dateParts2 = academicYearEndDate.split("/");

                                academicYearStartDate = new Date(
                                    dateParts1[2],
                                    dateParts1[1] - 1,
                                    dateParts1[0]
                                ); // Month is 0-based
                                academicYearEndDate = new Date(
                                    dateParts2[2],
                                    dateParts2[1] - 1,
                                    dateParts2[0]
                                ); // Month is 0-based

                                $(".datepicker_term_from").datepicker(
                                    "option",
                                    "minDate",
                                    academicYearStartDate
                                );
                                $(".datepicker_term_from").datepicker(
                                    "option",
                                    "maxDate",
                                    academicYearEndDate
                                );

                                $(".datepicker_term_to").datepicker(
                                    "option",
                                    "minDate",
                                    academicYearStartDate
                                );
                                $(".datepicker_term_to").datepicker(
                                    "option",
                                    "maxDate",
                                    academicYearEndDate
                                );

                                //alert("AFTER");

                                //alert(academicYearStartDate);
                                //alert(academicYearEndDate);
                            }
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        //notify_script("Error", status, "error", true);
                    });
            } else {
                // clear section list dropdown
                element
                    .empty()
                    .select2({ placeholder: `Select ${placeholder}` });
            }
        }
    }

    static FormsubmitDisabled(element = null) {
        $("form").bind("submit", function () {
            $(this).find(":input").prop("disabled", false);
        });
    }

    static parseDate(str) {
        var mdy = str.split("/");
        return new Date(mdy[2], mdy[0] - 1, mdy[1]);
    }

    static datediff(first, second) {
        // Take the difference between the dates and divide by milliseconds per day.
        // Round to nearest whole number to deal with DST. per day :86400000

        // return Math.round((second - first) / (1000 * 60 * 60 * 24));
        const arr = [];
        for (
            const dt = new Date(first);
            dt <= new Date(second);
            dt.setDate(dt.getDate() + 1)
        ) {
            arr.push(new Date(dt));
        }
        console.log("arr", arr.length);
        return arr.length;
    }

    static readIMG(input) {
        var element = input.getAttribute("name");
        var dataid = input.getAttribute("data-id");

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(`#${element}holder`)
                    .attr("src", e.target.result)
                    .width(80)
                    .height(80);
            };

            reader.readAsDataURL(input.files[0]);

            $(`#remove_img_${dataid}`).show();
        }
    }

    static onTimeChange(element) {
        var inputEle = document.getElementById(`${element}`);
        var timeSplit = inputEle.value.split(":"),
            hours,
            minutes,
            meridian;
        hours = timeSplit[0];
        minutes = timeSplit[1];
        if (hours > 12) {
            meridian = "PM";
            hours -= 12;
        } else if (hours < 12) {
            meridian = "AM";
            if (hours == 0) {
                hours = 12;
            }
        } else {
            meridian = "PM";
        }
        var time = hours + ":" + minutes + " " + meridian;
        inputEle.value = time;
        //alert(hours + ":" + minutes + " " + meridian);
    }

    static getInfoacademic(type, id, element, placeholder) {
        console.log("OKelement", element);
        let getUrl =
            window.getacademicinfo + "?type=" + type + "&academic_year=" + id;
        if (id) {
            console.log("OK1");
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);
                    if (Object.keys(response.data).length) {
                        var aca_start_year = response.data.start_date;
                        var aca_end_year = response.data.end_date;

                        console.log(aca_start_year);
                        console.log(aca_end_year);

                        var academicYearStartDate = aca_start_year;
                        var academicYearEndDate = aca_end_year;

                        if (academicYearStartDate) {
                            // Set minDate and maxDate dynamically

                            var dateParts1 = academicYearStartDate.split("/");
                            var dateParts2 = academicYearEndDate.split("/");

                            academicYearStartDate = new Date(
                                dateParts1[2],
                                dateParts1[1] - 1,
                                dateParts1[0]
                            ); // Month is 0-based
                            academicYearEndDate = new Date(
                                dateParts2[2],
                                dateParts2[1] - 1,
                                dateParts2[0]
                            ); // Month is 0-based

                            $(".datepicker_term_from").datepicker(
                                "option",
                                "minDate",
                                academicYearStartDate
                            );
                            $(".datepicker_term_from").datepicker(
                                "option",
                                "maxDate",
                                academicYearEndDate
                            );

                            $(".datepicker_term_to").datepicker(
                                "option",
                                "minDate",
                                academicYearStartDate
                            );
                            $(".datepicker_term_to").datepicker(
                                "option",
                                "maxDate",
                                academicYearEndDate
                            );

                            //alert("AFTER");

                            //alert(academicYearStartDate);
                            //alert(academicYearEndDate);
                        }
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

    static moveNextTab(nextTab) {
        var schoolName = $("#school_name").val();
        $("#preview_school_name").val(schoolName);
        var schoolEmail = $("#email").val();
        $("#preview_school_email").val(schoolEmail);
        var schoolPhone = $("#phoneno").val();
        $("#preview_school_phoneno").val(schoolPhone);
        var subscPlan = $('select[name="subscription_plan"]').val();
        $("#preview_subscription_plan").val(subscPlan);
        var billcycle = $('select[name="billing_cycle"]').val();
        $("#preview_billing_cycle").val(billcycle);
        var studentCount = $("#student_count").val();
        $("#preview_student_count").val(studentCount);

        $(nextTab).tab("show");
    }
    static movePreviousTab(prevTab) {
        $(prevTab).tab("show");
    }
    static movePlanNextTab(nextTab) {
        var subscPlanName = $("#subscription_plan_name").val();
        $("#subscription_plan_display_name").val(subscPlanName);
        $(nextTab).tab("show");
    }
    static movePlanPreviousTab(prevTab) {
        $(prevTab).tab("show");
    }
}
