import GeneralConfig from "./GeneralConfig.js";
export default class DynamicRow {
    static Rowinit() {
        console.log("row inited");
    }

    static addNewRow(htmlelement, appendelement) {
        // GeneralConfig.notify("Error", "No products found", "error", true);
        $(`#${appendelement}`).append(htmlelement);
    }

    static deleteRow(ele, table, is_delete = false, id = null, calc = false) {
        var table = $(`#${table}`)[0];
        var rowCount = table.rows.length;
        if (rowCount <= 1) {
            alert("There is no row available to delete!");
            return;
        }
        if (ele) {
            //delete specific row
            $(ele).parent().parent().remove();
        } else {
            //delete last row
            table.deleteRow(rowCount - 1);
        }

        if (is_delete) {
            let getUrl = window.deleteurl + "?id=" + id;
            Pace.start();

            if (id) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        Pace.stop();
                        GeneralConfig.notify(
                            "Success",
                            "Data Deleted Successfully",
                            "success",
                            true
                        );
                    })
                    .catch((error) => {
                        let status = error;
                        Pace.stop();
                        console.log(status);
                    });
            }
        }

        if (calc) {
            console.log("yes cal");
            DynamicRow.CalculateSum();
        }
    }

    static OnchangeDeduction(element, id) {
        if ($("#basic_salery").val() == "") {
            GeneralConfig.notify(
                "Error",
                "Please Fill Basic Salery",
                "error",
                true
            );
        } else {
            var basic_salery = Number($("#basic_salery").val());

            var percentageamount = (basic_salery * $(element).val()) / 100;

            if (percentageamount >= basic_salery) {
                GeneralConfig.notify(
                    "Error",
                    "Deduction greater Than Basic Salery",
                    "error",
                    true
                );
                return;
            }
            $(`#deduct${id}`).val(percentageamount);

            setTimeout(() => {
                DynamicRow.CalculateSum();
            }, 100);
        }
        console.log($(element).val(), id);
    }

    static CalculateSum() {
        const inputBoxes = document.querySelectorAll(".deduction_amount");
        var basic_salery = Number($("#basic_salery").val());
        let sum = 0;
        inputBoxes.forEach((inputBox) => {
            const value = parseFloat(inputBox.value);
            if (!isNaN(value)) {
                sum += value;
            }
        });

        $(`#deduction_amount`).html(sum.toFixed(2));
        $(`#total_deduction`).val(sum.toFixed(2));
        $(`#actual_salery`).html(Number(basic_salery) - sum);
        $(`#salery_with_particulars`).val(Number(basic_salery) - sum);
    }

    static Checkbox(active, id, element) {
        $(`#${element}${id}`).val(active);
        console.log(active, id);
    }
}
