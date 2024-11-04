export default class VirtualCommunicationConfig {
    static VirtualCommunicationInit(notify_script, type = null) {
        $('select[name="participants_group"]').on("change", function () {
            var group_id = $(this).val();
            var meeting_type = $("#meeting_type").val();
            var type = $(this).attr("data-type") ?? 0;
            console.log(group_id);
            if (group_id == 4 || group_id == 5) {
                $(".stud_div").show();
                $("#participants").empty().select2({
                    placeholder: "Pick a class and section...",
                });
            } else {
                let getUrl =
                    window.getparticipants +
                    "?group_id=" +
                    group_id +
                    "&meeting_type=" +
                    meeting_type +
                    "&type=" +
                    "no";
                if (getUrl) {
                    axios
                        .get(getUrl)
                        .then((response) => {
                            console.log(response);
                            if (response.data.users) {
                                $(".stud_div").hide();
                                console.log("type:" + type);
                                if (type == "edit") {
                                    console.log("its not edit");
                                    $("#participants")
                                        .empty()
                                        // .prepend(
                                        //     '<option selected=""></option>'
                                        // )
                                        .select2({
                                            allowClear: true,
                                            data: response.data.users,
                                            placeholder: "select participants",
                                        });

                                    $("#participants")
                                        .val(null)
                                        .trigger("change");
                                } else {
                                    // console.log("its not edit");
                                    $("#participants")
                                        .empty()
                                        .prepend(
                                            '<option selected=""></option>'
                                        )
                                        .select2({
                                            data: response.data.users,
                                            theme: "bootstrap4",
                                            allowClear: true,
                                            placeholder: "select",
                                            dropdownParent: $(
                                                ".add_participants_model"
                                            ),
                                        });
                                }
                            } else {
                                notify_script(
                                    "Error",
                                    "No Participants Found ",
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
                    $('select[name="participants_group"]').empty().select2({
                        placeholder: "Pick a Participants Group...",
                    });
                }
            }
        });

        $('select[name="participants_model_group"]').on("change", function () {
            var group_id = $(this).val();
            var meeting_id = $("#meeting_id").val();
            let getUrl =
                window.getparticipants +
                "?group_id=" +
                group_id +
                "&meeting_id=" +
                meeting_id +
                "&type=no";
            if (getUrl) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (response.data.users) {
                            $(".multiple-select")
                                .empty()
                                // .prepend('<option selected=""></option>')
                                .select2({
                                    data: response.data.users,
                                    // allowClear: true,
                                    // dropdownParent: $('.add_participant_model')
                                });

                            $(".multiple-select").select2({
                                theme: "bootstrap4",
                                width: $(this).data("width")
                                    ? $(this).data("width")
                                    : $(this).hasClass("w-100")
                                    ? "100%"
                                    : "style",
                                placeholder: $(this).data("placeholder"),
                                allowClear: true,
                                dropdownParent: $(".add_participant_model"),
                            });
                            $(".multiple-select").val("").trigger("change");
                            $(".select2-selection__choice").each(function () {
                                if ($(this).text().trim() === "") {
                                    $(this).remove();
                                }
                            });
                        }
                    })
                    .catch((error) => {
                        let status = error;
                        console.log(status);
                        notify_script("Error", status, "error", true);
                    });
            } else {
                $('select[name="participants_group"]').empty().select2({
                    placeholder: "Pick a Participants Group...",
                });
            }
        });

        $("#class_id").on("change", function () {
            let class_id = $(this).val();
            let url = window.getparticipantssections + "?class_id=" + class_id;
            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.sections) {
                            $("#section")
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "select section",
                                    data: response.data.sections,
                                });
                        } else {
                            notify_script(
                                "Error",
                                "No Sections Found ",
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
                $('select[name="class_id"]')
                    .empty()
                    .select2({ placeholder: "Pick a class..." });
            }
        });

        $("#section").on("change", function () {
            let section = $(this).val();
            let class_id = $("#class_id").val();
            let group_id = $("#participants_group").val();
            let type;
            if (group_id == 4) {
                type = "student";
            } else {
                type = "parent";
            }
            let getUrl =
                window.getparticipants +
                "?group_id=" +
                group_id +
                "&section=" +
                section +
                "&class_id=" +
                class_id +
                "&type=" +
                type;
            if (getUrl) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (response.data.users) {
                            $("#participants")
                                .empty()
                                // .prepend('<option selected=""></option>')
                                .select2({
                                    allowClear: true,
                                    placeholder: "select participants",
                                    data: response.data.users,
                                });
                            $(".single-select").select2({
                                theme: "bootstrap4",
                                width: $(this).data("width")
                                    ? $(this).data("width")
                                    : $(this).hasClass("w-100")
                                    ? "100%"
                                    : "style",
                                allowClear: Boolean(
                                    $(this).data("allow-clear")
                                ),
                            });
                            $("#participants").val(null).trigger("change");
                        }
                    })
                    .catch((error) => {
                        let status = error;
                        console.log(status);
                        notify_script("Error", status, "error", true);
                    });
            } else {
                // clear section list dropdown
                $('select[name="participants_group"]')
                    .empty()
                    .select2({ placeholder: "Pick a Participants Group..." });
            }
        });

        $(".add_participant").on("click", function () {
            let id = $(this).attr("data-id");
            let group_id = $("#participants_group").val();
            let selectedOptions = document.querySelectorAll(
                "#participants option:checked"
            );
            console.log("selectedOptions", selectedOptions);
            if (selectedOptions.length !== 0) {
                // Extract the values of the selected options
                let participants = Array.from(selectedOptions).map(
                    (option) => option.value
                );

                // Convert the array of selected values to a query string format
                let participantsParam = participants
                    .map(
                        (participant) =>
                            `participants[]=${encodeURIComponent(participant)}`
                    )
                    .join("&");
                console.log("js:" + participants, participantsParam);
                let url = `${window.addparticipants}?id=${id}&group_id=${group_id}&${participantsParam}`;
                if (url) {
                    axios
                        .get(url)
                        .then((response) => {
                            console.log(response);
                            if (response.data.view) {
                                $("#participants_list_ul").append(
                                    response.data.view
                                );
                                $("#list_accordion").removeClass("collapsed");
                                $("#collapseOne").addClass("show");
                                $(".add_participants_model").modal("hide");
                                $("#participants").val([]).trigger("change");
                            } else {
                                notify_script(
                                    "Error",
                                    "No response Found ",
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
                    notify_script("Error", "Error in Url", "error", true);
                }
            } else {
                notify_script(
                    "Error",
                    "Please select any one of participants",
                    "error",
                    true
                );
            }
        });

        $('select[name="meeting_type"]').on("change", function () {
            let meeting_type = $(this).val();
            let getUrl =
                window.getparticipantgroups + "?meeting_type=" + meeting_type;

            if (getUrl) {
                axios
                    .get(getUrl)
                    .then((response) => {
                        console.log(response);
                        if (response.data.groups) {
                            $("#participants_group")
                                .empty()
                                .prepend('<option selected=""></option>')
                                .select2({
                                    data: response.data.groups,
                                    theme: "bootstrap4",
                                    allowClear: true,
                                    placeholder: "select group",
                                });
                        } else {
                            notify_script(
                                "Error",
                                "No Participants Found ",
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
                $('select[name="meeting_type"]').empty().select2({
                    placeholder: "Select meeting type...",
                });
            }
        });

        $(".close_btn").on("click", function () {
            $('select[name="participants_model_group"]')
                .val("")
                .trigger("change");
            $("#participants").val("").trigger("change");
        });

        document
            .getElementById("click_btn")
            .addEventListener("click", async () => {
                var meeting_title = $("#meeting_title").val();
                var meeting_date = $("#meeting_date").val();
                var meet_time = $("#meet_time").val();
                var meeting_type = $("#meeting_type").val();
                var participants_group = $("#participants_group").val();
                var participants = $("#participants").val();
                var meeting_description = $("#meeting_description").val();
                if (
                    !meeting_title ||
                    !meeting_date ||
                    !meet_time ||
                    !meeting_type ||
                    !participants_group ||
                    !participants ||
                    !meeting_description
                ) {
                    notify_script(
                        "Error",
                        "Please Fill Required Fields",
                        "error",
                        true
                    );
                } else {
                    console.log("its enter");
                    // API call to create meeting
                    const url = `https://api.videosdk.live/v2/rooms`;
                    const TOKEN =
                        "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiJmMjdlZTA2YS0xNGI4LTRlNDAtODcxMy00YjFhYzhiNzgxYzQiLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTcxOTkxNjgyNSwiZXhwIjoxNzUxNDUyODI1fQ.8pmisp_gM9PHFMu4BECtav4szA9FLlLA1WA1CvVK79s";
                    const options = {
                        method: "POST",
                        headers: {
                            Authorization: TOKEN,
                            "Content-Type": "application/json",
                        },
                    };

                    try {
                        // $meeting_title = document.getElementById('meeting_title').value;
                        // $meeting_date = document.getElementById('meeting_date').value;
                        const response = await fetch(url, options);
                        if (!response.ok) {
                            throw new Error("Network response was not ok");
                        }
                        const { roomId } = await response.json();
                        console.log(roomId);

                        // Update DOM elements after successful API call
                        document.getElementById("token_div").style.display =
                            "block";
                        document.getElementById("meeting_token").value = roomId;
                        document.getElementById("click_btn").style.display =
                            "none";
                        document.getElementById("submit_btn").style.display =
                            "inline-block";
                    } catch (error) {
                        console.error(
                            "There has been a problem with your fetch operation:",
                            error
                        );
                        alert("error: " + error.message);
                    }
                }
            });
    }
}
