// initialize a validator instance from the "FormValidator" constructor.
// A "<form>" element is optionally passed as an argument, but is not a must
var validator = validator;

// on form "submit" event
document.forms[0].onsubmit = function (e) {
    // e.preventDefault();

    var submit = true,
        validatorResult = validator.checkAll(this);

    console.log(validatorResult, "here");
    if (validatorResult) {
        this.submit();
    } else {
        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: "fontawesome",
            });
        }
        notify_script(
            "Error",
            "Please Fill Out All Required Feilds",
            "error",
            true
        );
        return !!validatorResult.valid;
    }
    // return !!validatorResult.valid;
};

// on form "reset" event
// document.forms[0].onreset = function (e) {
//     validator.reset();
// };

// stuff related ONLY for this demo page:
$(".toggleValidationTooltips")
    .change(function () {
        validator.settings.alerts = !this.checked;

        if (this.checked) $("form .alert").remove();
    })
    .prop("checked", false);
