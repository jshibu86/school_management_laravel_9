import GeneralConfig from "./GeneralConfig.js";
import AcademicConfig from "./AcademicConfig.js";

let SelectedArray = [];
export default class FeeConfig {
    static Feeinit(notify_script = null, type = null) {
        console.log("feeinit");
        SelectedArray = window.SelectedFeeArray;

        $("#group_id").on("change", function () {
            let group_id = $(this).val();
            console.log("OK12");

            FeeConfig.getInfo(2, group_id, "group type");
            console.log("group");
            console.log(group_id);
        });

        $('select[name="user_group"]').on("change", function () {
            var selectedText = $(this).find("option:selected").text();

            if (selectedText == "Student") {
                $(".classselection").removeClass("d-none");
                $(".sectionselection").removeClass("d-none");
                $(".select_student").removeClass("d-none");
                $(".select_user").addClass("d-none");
                $('select[name="member_id[]"]')
                    .empty()
                    .select2({ placeholder: "select user..." });
                //AcademicConfig.getUsers($(this).val(), notify_script);
            } else {
                $(".classselection").addClass("d-none");
                $(".sectionselection").addClass("d-none");
                $(".select_student").addClass("d-none");
                $(".select_user").removeClass("d-none");

                $('select[name="student_id[]"]')
                    .empty()
                    .select2({ placeholder: "select user..." });
                AcademicConfig.getUsers($(this).val(), notify_script, type);
            }
        });
        $('select[name="payment_type"]').on("change", function () {
            let payment_type = $(this).val();

            console.log(payment_type);

            if (payment_type == 0) {
                // monthly
                $(".month_due").show();
                $(".full_due").hide();
                $(".terms__due").hide();
            } else if (payment_type == 1) {
                // term
                $(".month_due").hide();
                $(".full_due").hide();

                var acyear = $('select[name="academic_year"]').val();
                FeeConfig.setTerms(acyear);
            } else {
                // full payment

                $(".month_due").hide();
                $(".full_due").show();
                $(".terms__due").hide();
            }

            Pace.start();
        });

        if (type != "inventory") {
            $('select[name="class_id"]').on("change", function () {
                let class_id = $(this).val();
                let acyear = $('select[name="academic_year"]').val();

                if (acyear) {
                    AcademicConfig.getSection(class_id, notify_script);
                } else {
                    notify_script(
                        "Error",
                        "Please Select Academic year",
                        "error",
                        true
                    );
                }
            });
        }

        $('select[name="section_id"]').on("change", function () {
            let section_id = $(this).val();
            let class_id = $('select[name="class_id"]').val();

            if (section_id) {
                AcademicConfig.getStudents(
                    class_id,
                    section_id,
                    notify_script,
                    type
                );
            } else {
                notify_script(
                    "Error",
                    "Please Select Academic year",
                    "error",
                    true
                );
            }
        });

        $(".getfeeinfo").on("click", function () {
            let class_id =
                $('select[name="class_id"]').val() ||
                $('input[name="class_id"]').val();
            let section_id =
                $('select[name="section_id"]').val() ||
                $('input[name="section_id"]').val();
            let academic_year = $('select[name="academic_year"]').val();
            let student_id =
                $('select[name="student_id"]').val() ||
                $('input[name="student_id"]').val();

            if (class_id && section_id && academic_year) {
                FeeConfig.getFeeinfo(
                    class_id,
                    section_id,
                    academic_year,
                    student_id
                );
            } else {
                GeneralConfig.notify(
                    "Error",
                    "Please Select All Reuired Feilds",
                    "error",
                    true
                );
            }
        });

        $(".pay__button").on("click", function () {
            var type = $(this).attr("data-type");
            let class_id =
                $('select[name="class_id"]').val() ||
                $('input[name="class_id"]').val();
            let section_id =
                $('select[name="section_id"]').val() ||
                $('input[name="section_id"]').val();
            let academic_year = $('select[name="academic_year"]').val();

            let student_id =
                $('select[name="student_id"]').val() ||
                $('input[name="student_id"]').val();
            $(".selected_term").val($(this).attr("data-id"));
            $(".selected_term_amount").val($(this).attr("data-amount"));
            $(".selected_term_date").val($(this).attr("data-duedate"));
            if (type == "term") {
                var obj = {
                    type: type,
                    per: $(this).attr("data-per"),
                    paid_amount: $(this).attr("data-amount"),
                    selected_term_date: $(this).attr("data-duedate"),
                    class_id,
                    section_id,
                    academic_year,
                    student_id,
                    selected_term: $(this).attr("data-id"),
                };
                FeeConfig.ViewFeesPayment(obj);
            } else if (type == "month") {
                var obj = {
                    type: type,

                    paid_amount: $(this).attr("data-amount"),

                    class_id,
                    section_id,
                    academic_year,
                    student_id,
                    selected_month: $(this).attr("data-month"),
                    selected_year: $(this).attr("data-year"),
                };
                FeeConfig.ViewFeesPayment(obj);
            } else {
                var obj = {
                    type: type,

                    paid_amount: $(this).attr("data-amount"),
                    selected_term_date: $(this).attr("data-duedate"),
                    class_id,
                    section_id,
                    academic_year,
                    student_id,
                };
                FeeConfig.ViewFeesPayment(obj);
            }

            // $(".feefull_information").removeClass("d-none");
            //console.log($(this).attr("data-id"));
        });

        $('select[name = "school_type"]').on("change", function () {
            let school = $(this).val();
            let url = window.department + "?school=" + school;
            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data) {
                            if (response.data.is_applies == 1) {
                                $(".schooldepartment").removeClass("d-none");
                                console.log("one", response.data.is_applies);
                            } else {
                                $(".schooldepartment").addClass("d-none");
                            }
                        } else {
                            notify_script(
                                "Error",
                                "no response",
                                "error",
                                true
                            );
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            } else {
                notify_script("Error", "Url not found", "error", true);
            }
        });
    }

    static getInfo(type, id, placeholder) {
        let getUrl =
            window.getpayrollbulknfo +
            "?type=" +
            type +
            "&group_id=" +
            id +
            "&members_id[]=" +
            id +
            "&month=" +
            id;
        console.log(type, id, placeholder);
        if (id) {
            console.log("OK");
            axios
                .get(getUrl)
                .then((response) => {
                    console.log(response);
                    if (Object.keys(response.data).length) {
                        element;
                        $('select[name="members_id[]"]')
                            .empty()
                            .prepend('<option selected=""></option>')
                            .select2({
                                allowClear: true,
                                placeholder: `Select ${placeholder}`,
                                data: response.data,
                            });

                        Pace.stop();
                    } else {
                        $('select[name="members_id[]"')
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

    static PayFeepayment() {
        $('select[name="payment_method"]').on("change", function () {
            let paymethod = $(this).val();

            if (paymethod == 1) {
                $(".demanddraft").removeClass("d-none");
            } else {
                $(".demanddraft").addClass("d-none");
            }
        });
        var form = document.querySelector("#fee_form");

        const formObject = {};
        form.addEventListener("submit", function (e) {
            e.preventDefault();
            const data = new FormData(form);

            data.forEach((value, key) => {
                formObject[key] = value;
            });
            $(".confirm_payment").prop("disabled", true);
            $(".cancel_payment").prop("disabled", true);
            $(".confirm_payment").html("Payment Processing...");
            console.log(formObject, "from pay static");

            if (formObject) {
                let getUrl = window.storefee;
                Pace.start();
                axios
                    .post(getUrl, formObject)
                    .then((response) => {
                        if (response) {
                            console.log(response);
                            // notify_script("Success", "Deleted ", "success", true);
                            $(".pay__fee__information").empty();
                            $(".pay__fee__information").addClass("d-none");
                            $(".pay__fee__information__success").removeClass(
                                "d-none"
                            );

                            $("#receipt_url").attr("href", response.data.path);

                            //new PerfectScrollbar(".dashboard-social-list");
                            Pace.stop();
                        } else {
                            Pace.stop();
                        }
                    })
                    .catch((error) => {
                        Pace.stop();
                        let status = error;
                        $(".confirm_payment").prop("disabled", false);

                        console.log(status);
                    });
            }

            $(".done_payment").on("click", function (e) {
                e.preventDefault();
                $(".fees_details").empty();
                $("#view__fees").modal("hide");
                window.location.reload();
            });

            $(".success_close").on("click", function (e) {
                e.preventDefault();
                $(".fees_details").empty();
                $("#view__fees").modal("hide");
                window.location.reload();
            });
        });
    }

    static ViewFeesPayment(obj) {
        let getUrl = window.viewfeepayment + "?type=" + obj.type;
        if (obj) {
            Pace.start();
            axios
                .post(getUrl, obj)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".fees_details").empty();
                        $(".fees_details").html(response.data.viewfile);
                        $("#view__fees").modal("show");
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

    static FeeSetup(types, type = null) {
        var fees = [];
        $('select[name="fees_type"]').on("change", function () {
            var Selectdids = $(this).val();

            // remove

            //var selectedText = $(this).find("option:selected").text();

            Selectdids.map((selectid) => {
                if (!SelectedArray.includes(selectid) && selectid != 0) {
                    Object.entries(types).map((type, i) => {
                        if (type[0] == selectid) {
                            FeeConfig.ProcessTemplate(selectid, type[1]);
                        }
                    });
                } else {
                    return;
                }
            });
            //let exists_id = SelectedArray.some((item) => item == value);

            Selectdids.map((it) => {
                if (!SelectedArray.includes(it)) {
                    SelectedArray.push(it);
                }
            });
            //FeeConfig.ProcessTemplate(fees);

            SelectedArray.map((sarray) => {
                if (!Selectdids.includes(sarray) && sarray != 0) {
                    var val = $(`#remove_feesetup${sarray} .fee_amount`).val();

                    var total = $(`.total_amount`).val();
                    var currenttotal = Number(total) - Number(val);

                    $(`.total_amount_text`).html(
                        !isNaN(currenttotal) ? currenttotal.toFixed(2) : 0
                    );
                    $(`.total_amount`).val(
                        !isNaN(currenttotal) ? currenttotal.toFixed(2) : 0
                    );
                    $(`#remove_feesetup${sarray}`).remove();
                    SelectedArray = SelectedArray.filter(
                        (item) => item != sarray
                    );
                }
            });

            return;

            var selectedText = $(this).find("option:selected").text();

            let exists = SelectedArray.some((item) => item == value);

            console.log(exists);
            if (!exists) FeeConfig.ProcessTemplate(value, selectedText);

            SelectedArray.push(value);
        });
    }

    static getFeeinfo(class_id, section_id, academic_year, student_id) {
        let getUrl =
            window.feeinfo +
            "?academic_year=" +
            academic_year +
            "&class_id=" +
            class_id +
            "&section_id=" +
            section_id +
            "&student_id=" +
            student_id;

        if (getUrl) {
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    Pace.stop();
                    if (response.data.error) {
                        $(".fee_full_information").html("");
                        $(".fee_list_information").html("");
                        $(".error__feeinfo").html("");
                        $(".error__feeinfo").html(response.data.error);
                    } else {
                        $(".error__feeinfo").html("");
                        $(".fee_full_information").html("");
                        $(".fee_full_information").html(response.data.view);
                        $(".fee_list_information").html("");
                        $(".fee_list_information").html(response.data.feeview);
                    }

                    console.log(response);
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }

    static setTerms(acyear) {
        let getUrl = window.termurl + "?academic_year=" + acyear;
        if (acyear) {
            axios
                .get(getUrl)
                .then((response) => {
                    if (Object.keys(response.data).length) {
                        var length = Object.keys(response.data).length;
                        console.log(length);
                        $(".terms__due").show();

                        const html = response.data
                            .map((res, i) => {
                                return ` <div class="col-xs-12 col-sm-4 col-md-4">
                                            <div class="item form-group input-group mb-3"> <span class="input-group-text">${
                                                res.text
                                            }</span>
                                                    <span class="input-group-text">${
                                                        i === length - 1
                                                            ? "40%"
                                                            : "30%"
                                                    }</span>
                                                    <input type="hidden" name="due_term_dates[${
                                                        res.id
                                                    }][per]" value="${
                                    i === length - 1 ? "40" : "30"
                                }"/>
                                                    <input name="due_term_dates[${
                                                        res.id
                                                    }][date]" type="date" required class="form-control" aria-label="Dollar amount (with dot and two decimal places)">
                                            </div>
                                    </div>`;
                            })
                            .join("");
                        $(".terms__lists").html(html);

                        Pace.stop();
                    } else {
                        GeneralConfig.notify(
                            "Success",
                            "This Academic year Has No terms Available",
                            "success",
                            true
                        );

                        Pace.stop();
                    }
                })
                .catch((error) => {
                    let status = error.response;
                    console.log(status);
                    notify_script("Error", status, "error", true);
                });
        }
    }

    static ProcessTemplate(value, selectedText, selectedarray = []) {
        //console.log(value, "process");
        //return;
        var rowHtml = `<tr id="remove_feesetup${value}">
        <td><input type="hidden" name="fee_name[]" value="${selectedText}" />
        <input type="hidden" name="fee_id[]" value="${value}" />
        ${selectedText}
        </td>
        <td><div class="item form-group d-flex gap-4"><input class="form-control fee_amount" required type="number" name="fee_amount[]"  /><input type="button"  class="btn btn-danger" value="x" class="remove" id="${value}" onclick="FeeConfig.deleteRow(this)" /></div></td>
        <td> <div class="form-check form-switch"><input class="form-check-input is_compulsory_check" onchange="updateCompulsory(this)" type="checkbox" id="flexSwitchCheckChecked" checked> <input type="hidden" class="is_compulsory" name="is_compulsory[]" value="1"></div></td></tr>`;
        $("#fee__items").append(rowHtml);
        FeeConfig.CalculateSum();

        //console.log(SelectedArray);
    }
    static deleteRow(ele, is_delete = false, id = null) {
        var fee = $(ele).closest("tr").find(".fee_amount").val();
        var deselectid = $(ele).attr("id");
        SelectedArray = SelectedArray.filter((item) => item != deselectid);

        $(".multi-select-menuitem input:checked").each(function () {
            console.log($(this).val());
            if ($(this).val() == deselectid) {
                $(`#${$(this).attr("id")}`).prop("checked", false);
                $("#fees_type_select option").each(function () {
                    // Add $(this).val() to your list
                    if ($(this).val() == deselectid) {
                        $(this).prop("selected", false);
                        // $(this).trigger("change", [true]);
                    }
                });
            }
        });

        var table = $(`#fee__items`)[0];
        var rowCount = table.rows.length;
        if (rowCount <= 1) {
            alert("There is no row available to delete!");
            return;
        }
        if (ele) {
            //delete specific row
            $(ele).parent().parent().parent().remove();
        } else {
            //delete last row
            table.deleteRow(rowCount - 1);
        }

        var total = $(`.total_amount`).val();
        var currenttotal = Number(total) - Number(fee);

        $(`.total_amount_text`).html(
            !isNaN(currenttotal) ? currenttotal.toFixed(2) : 0
        );
        $(`.total_amount`).val(
            !isNaN(currenttotal) ? currenttotal.toFixed(2) : 0
        );
    }

    static CalculateSum() {
        const inputBoxes = document.querySelectorAll(".fee_amount");

        inputBoxes.forEach((inputBox) => {
            inputBox.addEventListener("keyup", function () {
                let sum = 0;
                inputBoxes.forEach((inputBox) => {
                    const value = parseFloat(inputBox.value);
                    if (!isNaN(value)) {
                        sum += value;
                    }
                });

                $(`.total_amount_text`).html(sum.toFixed(2));
                $(`.total_amount`).val(sum.toFixed(2));
            });
        });
    }

    static GetDepartment(class_id) {
        if (class_id) {
            let getUrl = window.departmentcheck + "?class_id=" + class_id;
            Pace.start();
            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        if (response.data.department) {
                            $(".schooldepartment").removeClass("d-none");
                        } else {
                            $(".schooldepartment").addClass("d-none");
                        }

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

    static SaleryTemplate() {
        $(".user_group_salery").on("change", function () {
            Pace.start();

            var id = $(this).attr("id");

            let getUrl =
                window.salerytemplateurl +
                "?group_id=" +
                $(this).val() +
                "&type=" +
                id;

            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        // console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".saleryTemplateUserdata").html("");
                        $(".saleryTemplateUserdata").html(response.data.view);
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
        });

        $(".getschedule").on("click", function () {
            let group_id = $('select[name="group"]').val();
            let month = $('select[name="month"]').val();
            let getUrl =
                window.getscheduleurl +
                "?group_id=" +
                group_id +
                "&month=" +
                month;

            axios
                .get(getUrl)
                .then((response) => {
                    if (response) {
                        // console.log(response);
                        // notify_script("Success", "Deleted ", "success", true);
                        $(".schedule__view").html("");
                        $(".schedule__view").html(response.data.view);
                        $(".exportclass").show();
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
        });
    }

    static StaffAttendance() {
        $(".user_group_staff").on("change", function () {
            Pace.start();

            var group_id = $(this).val();

            let getUrl =
                window.staffattendanceurl + "?group_id=" + $(this).val();
            if (group_id) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        if (response) {
                            //console.log(response.data.view);
                            // notify_script("Success", "Deleted ", "success", true);
                            $(".staffTemplateUserdata").html("");
                            $(".staffTemplateUserdata").html(
                                response.data.view
                            );
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
        });
    }
}
