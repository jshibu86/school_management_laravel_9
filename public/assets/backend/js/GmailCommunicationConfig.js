export default class GmailCommunicationConfig {
    static GmailCommunicationInit(notify_script, type = null) {
        $("#group_image").on("change", function () {
            let input = $(this);
            var element = input.attr("name");
            var dataid = input.attr("data-id");
            if (input[0].files && input[0].files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(`#${element}holder`)
                        .attr("src", e.target.result)
                        .width(120)
                        .height(70);
                };
                reader.readAsDataURL(input[0].files[0]);
                $(`#remove_img_${dataid}`).show();
            }
        });

        $("#create_group").on("click", function () {
            let section = $("#sec_dep1").val();
            let month = $("#month1").val();
            let url = window.creategroup + "?type=" + "model";

            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.view) {
                            $(".group_form").empty();
                            $(".group_form").html(response.data.view);
                            $("#create_group_model").modal("show");
                        } else {
                            console.error("Invalid response data:", response);
                            // Handle the case where viewfile is not present in the response data
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                        // Handle AJAX error gracefully, e.g., display an error message to the user
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        });

        $(".compose_btn").on("click", function () {
            $(".tab-pane").removeClass("active show");
            $("#pills-compose").addClass("active show");
        });

        $('select[name="group_type"]').on("change", function () {
            let group_type = $(this).val();
            let url = window.get_receptiants + "?group_type=" + group_type;
            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.receptiants) {
                            $("#recipient")
                                .empty()
                                .prepend()
                                .select2({
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
                                    dropdownParent: $(".recipient"),
                                    placeholder: false,
                                    data: response.data.receptiants,
                                });

                            $("#recipient").val(null).trigger("change");
                        } else {
                            console.error("Invalid response data:", response);
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                        // Handle AJAX error gracefully, e.g., display an error message to the user
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        });

        $(".group_edit").on("click", function () {
            let group_id = $(this).attr("id");
            let url = window.editgroup + "?group_id=" + group_id;

            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.view) {
                            $(".group_form").empty();
                            $(".group_form").html(response.data.view);
                            $("#create_group_model").modal("show");
                        } else {
                            console.error("Invalid response data:", response);
                            // Handle the case where viewfile is not present in the response data
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                        // Handle AJAX error gracefully, e.g., display an error message to the user
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        });

        $(".group_delete").on("click", function () {
            let group_id = $(this).attr("id");
            let url = window.deletegroup + "?group_id=" + group_id;

            if (url) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "This Group has used to send Some Informations if You deleted Here the Informations  Also Deleted",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios
                            .get(url)
                            .then((response) => {
                                console.log(response);
                                if (response.data.success) {
                                    notify_script(
                                        "Success",
                                        "Group Deleted Successfully",
                                        "success",
                                        true
                                    );
                                    setTimeout(function () {
                                        location.reload();
                                    }, 600);
                                }
                            })
                            .catch((error) => {
                                console.log("error");
                            });
                        //target.submit();
                    }
                });
            }
        });

        $(".message_img").on("change", function () {
            let input = $(this);
            let group_id = $(this).attr("data-id");
            // console.log(input);
            if (group_id) {
                $(".viewimg" + group_id + "div").empty();
                if (input[0].files && input[0].files[0]) {
                    var files = input[0].files;
                    for (let i = 0; i < files.length; i++) {
                        console.log(input[0].files[i]);
                        console.log("its enter");
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var file_extension = input[0].files[i].name
                                .split(".")
                                .pop()
                                .toLowerCase();

                            if (
                                file_extension == "jpg" ||
                                file_extension == "png" ||
                                file_extension == "gif"
                            ) {
                                var img_tag = $("<img>").attr({
                                    src: e.target.result,
                                    id: "msg_img" + group_id + "img",
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height message" +
                                        i +
                                        group_id,
                                });

                                var remove_button = $("<span>")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        "data-id": group_id,
                                        id: "remove_img" + group_id,
                                        "data-index": i,
                                    })
                                    .text("X");

                                // $('#msg_img'+group_id+'img')
                                // .attr("src", e.target.result)
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'img').show();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'audio').hide();
                                // $('#msg_img'+group_id+'file').hide();
                                // $('#remove_img'+group_id).show();
                            } else if (
                                file_extension == "mp4" ||
                                file_extension == "avi" ||
                                file_extension == "mov"
                            ) {
                                var img_tag = $("<img>").attr({
                                    src: window.videoUrl,
                                    id: "msg_img" + group_id + "video",
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height message" +
                                        i +
                                        group_id,
                                });

                                var remove_button = $("span")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + group_id,
                                        "data-id": group_id,
                                        "data-index": i,
                                    })
                                    .text("X");
                                // $('#msg_img'+group_id+'video')
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'video').show();
                                // $('#msg_img'+group_id+'img').hide();
                                // $('#msg_img'+group_id+'audio').hide();
                                // $('#msg_img'+group_id+'file').hide();
                                // $('#remove_img'+group_id).show();
                            } else if (file_extension == "mp3") {
                                var img_tag = $("<img>").attr({
                                    src: window.audioUrl,
                                    id: "msg_img" + group_id + "audio",
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height message" +
                                        i +
                                        group_id,
                                });

                                var remove_button = $("<span>")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + group_id,
                                        "data-id": group_id,
                                        "data-index": i,
                                    })
                                    .text("X");
                                // $('#msg_img'+group_id+'audio')
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'audio').show();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'img').hide();
                                // $('#msg_img'+group_id+'file').hide();
                                // $('#remove_img'+group_id).show();
                            } else {
                                var img = window.fileUrl;
                                var img_tag = $("<img>").attr({
                                    src: img,
                                    id: "msg_img" + group_id + "file",
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height message" +
                                        i +
                                        group_id,
                                });

                                var remove_button = $("<span>")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + group_id,
                                        "data-id": group_id,
                                        "data-index": i,
                                    })
                                    .text("X");
                                // console.log("its enter");
                                // $('#msg_img'+group_id+'file')
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'file').show();
                                // $('#msg_img'+group_id+'audio').hide();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'img').hide();
                                // $('#remove_img'+group_id).show();
                            }
                            var container = $("<div>").append(
                                img_tag,
                                remove_button
                            );
                            $(".viewimg" + group_id + "div").append(container);
                        };

                        reader.readAsDataURL(input[0].files[i]);
                    }
                }
            } else {
                $(".viewimgdiv").empty();
                if (input[0].files && input[0].files[0]) {
                    let draft_message_id = $(this).attr("data-message_id");
                    var files = input[0].files;
                    for (let i = 0; i < files.length; i++) {
                        console.log(input[0].files[i]);
                        console.log("its enter");
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var file_extension = input[0].files[i].name
                                .split(".")
                                .pop()
                                .toLowerCase();

                            if (
                                file_extension == "jpg" ||
                                file_extension == "png" ||
                                file_extension == "gif"
                            ) {
                                var img_tag = $("<img>").attr({
                                    src: e.target.result,
                                    id: "msg_file_img" + i,
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height messagecompose" +
                                        i,
                                });
                                if (draft_message_id) {
                                    var remove_button = $("<span>")
                                        .attr({
                                            class: "remove_img back_to remove",
                                            id: "remove_img" + i,
                                            "data-index": i,
                                            "data-draft-id": draft_message_id,
                                        })
                                        .text("X");
                                } else {
                                    var remove_button = $("<span>")
                                        .attr({
                                            class: "remove_img back_to remove",
                                            id: "remove_img" + i,
                                            "data-index": i,
                                        })
                                        .text("X");
                                }

                                // $('#msg_img'+group_id+'img')
                                // .attr("src", e.target.result)
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'img').show();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'audio').hide();
                                // $('#msg_img'+group_id+'file').hide();
                                // $('#remove_img'+group_id).show();
                            } else if (
                                file_extension == "mp4" ||
                                file_extension == "avi" ||
                                file_extension == "mov"
                            ) {
                                var img_tag = $("<img>").attr({
                                    src: window.videoUrl,
                                    id: "msg_file_video" + i,
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height messagecompose" +
                                        i,
                                });

                                if (draft_message_id) {
                                    var remove_button = $("<span>")
                                        .attr({
                                            class: "remove_img back_to remove",
                                            id: "remove_img" + i,
                                            "data-index": i,
                                            "data-draft-id": draft_message_id,
                                        })
                                        .text("X");
                                } else {
                                    var remove_button = $("<span>")
                                        .attr({
                                            class: "remove_img back_to remove",
                                            id: "remove_img" + i,
                                            "data-index": i,
                                        })
                                        .text("X");
                                }
                            } else if (file_extension == "mp3") {
                                var img_tag = $("<img>").attr({
                                    src: window.audioUrl,
                                    id: "msg_audio_audio" + i,
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height messagecompose" +
                                        i,
                                });

                                if (draft_message_id) {
                                    var remove_button = $("<span>")
                                        .attr({
                                            class: "remove_img back_to remove",
                                            id: "remove_img" + i,
                                            "data-index": i,
                                            "data-draft-id": draft_message_id,
                                        })
                                        .text("X");
                                } else {
                                    var remove_button = $("<span>")
                                        .attr({
                                            class: "remove_img back_to remove",
                                            id: "remove_img" + i,
                                            "data-index": i,
                                        })
                                        .text("X");
                                }
                            } else {
                                var img = window.fileUrl;
                                var img_tag = $("<img>").attr({
                                    src: img,
                                    id: "msg_img_file_file" + i,
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height messagecompose" +
                                        i,
                                });

                                if (draft_message_id) {
                                    var remove_button = $("<span>")
                                        .attr({
                                            class: "remove_img back_to remove",
                                            id: "remove_img" + i,
                                            "data-index": i,
                                            "data-draft-id": draft_message_id,
                                        })
                                        .text("X");
                                } else {
                                    var remove_button = $("<span>")
                                        .attr({
                                            class: "remove_img back_to remove",
                                            id: "remove_img" + i,
                                            "data-index": i,
                                        })
                                        .text("X");
                                }
                            }
                            var container = $("<div>").append(
                                img_tag,
                                remove_button
                            );
                            $(".viewimgdiv").append(container);
                        };

                        reader.readAsDataURL(input[0].files[i]);
                    }
                }
            }
        });
        $(".replay_message_img").on("change", function () {
            console.log("its enter replay");
            let input = $(this);
            let msg_id = $(this).attr("data-id");
            // console.log(input);
            console.log("msg_id:".msg_id);
            if (msg_id) {
                console.log("its enter if msg_id");
                if (input[0].files && input[0].files[0]) {
                    var files = input[0].files;
                    for (let i = 0; i < files.length; i++) {
                        console.log(input[0].files[i]);
                        console.log("its enter for");
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var file_extension = input[0].files[i].name
                                .split(".")
                                .pop()
                                .toLowerCase();

                            if (
                                file_extension == "jpg" ||
                                file_extension == "png" ||
                                file_extension == "gif"
                            ) {
                                var img_tag = $("<img>").attr({
                                    src: e.target.result,
                                    id: "msg_img" + msg_id + "img",
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height message" +
                                        i +
                                        msg_id,
                                });

                                var remove_button = $("<span>")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        "data-msg_id": msg_id,
                                        id: "remove_img" + msg_id,
                                        "data-index": i,
                                        "data-parent": "index",
                                    })
                                    .text("X");

                                // $('#msg_img'+group_id+'img')
                                // .attr("src", e.target.result)
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'img').show();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'audio').hide();
                                // $('#msg_img'+group_id+'file').hide();
                                // $('#remove_img'+group_id).show();
                            } else if (
                                file_extension == "mp4" ||
                                file_extension == "avi" ||
                                file_extension == "mov"
                            ) {
                                var img_tag = $("<img>").attr({
                                    src: window.videoUrl,
                                    id: "msg_img" + msg_id + "video",
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height message" +
                                        i +
                                        msg_id,
                                });

                                var remove_button = $("span")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + msg_id,
                                        "data-msg_id": msg_id,
                                        "data-index": i,
                                        "data-parent": "index",
                                    })
                                    .text("X");
                            } else if (file_extension == "mp3") {
                                var img_tag = $("<img>").attr({
                                    src: window.audioUrl,
                                    id: "msg_img" + msg_id + "audio",
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height message" +
                                        i +
                                        msg_id,
                                });

                                var remove_button = $("<span>")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + msg_id,
                                        "data-msg_id": msg_id,
                                        "data-index": i,
                                        "data-parent": "index",
                                    })
                                    .text("X");
                                // $('#msg_img'+group_id+'audio')
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'audio').show();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'img').hide();
                                // $('#msg_img'+group_id+'file').hide();
                                // $('#remove_img'+group_id).show();
                            } else {
                                var img = window.fileUrl;
                                var img_tag = $("<img>").attr({
                                    src: img,
                                    id: "msg_img" + msg_id + "file",
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height message" +
                                        i +
                                        msg_id,
                                });

                                var remove_button = $("<span>")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + msg_id,
                                        "data-msg_id": msg_id,
                                        "data-index": i,
                                        "data-parent": "index",
                                    })
                                    .text("X");
                                // console.log("its enter");
                                // $('#msg_img'+group_id+'file')
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'file').show();
                                // $('#msg_img'+group_id+'audio').hide();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'img').hide();
                                // $('#remove_img'+group_id).show();
                            }
                            var container = $("<div>").append(
                                img_tag,
                                remove_button
                            );
                            $(".viewreplayimg" + msg_id + "div").append(
                                container
                            );
                        };

                        reader.readAsDataURL(input[0].files[i]);
                    }
                }
            } else {
                if (input[0].files && input[0].files[0]) {
                    var files = input[0].files;
                    for (let i = 0; i < files.length; i++) {
                        console.log(input[0].files[i]);
                        console.log("its enter");
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var file_extension = input[0].files[i].name
                                .split(".")
                                .pop()
                                .toLowerCase();

                            if (
                                file_extension == "jpg" ||
                                file_extension == "png" ||
                                file_extension == "gif"
                            ) {
                                var img_tag = $("<img>").attr({
                                    src: e.target.result,
                                    id: "msg_file_img" + i,
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height messagecompose" +
                                        i,
                                });

                                var remove_button = $("<span>")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + i,
                                        "data-index": i,
                                    })
                                    .text("X");

                                // $('#msg_img'+group_id+'img')
                                // .attr("src", e.target.result)
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'img').show();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'audio').hide();
                                // $('#msg_img'+group_id+'file').hide();
                                // $('#remove_img'+group_id).show();
                            } else if (
                                file_extension == "mp4" ||
                                file_extension == "avi" ||
                                file_extension == "mov"
                            ) {
                                var img_tag = $("<img>").attr({
                                    src: window.videoUrl,
                                    id: "msg_file_video" + i,
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height messagecompose" +
                                        i,
                                });

                                var remove_button = $("span")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + i,
                                        "data-id": group_id,
                                        "data-index": i,
                                    })
                                    .text("X");
                                // $('#msg_img'+group_id+'video')
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'video').show();
                                // $('#msg_img'+group_id+'img').hide();
                                // $('#msg_img'+group_id+'audio').hide();
                                // $('#msg_img'+group_id+'file').hide();
                                // $('#remove_img'+group_id).show();
                            } else if (file_extension == "mp3") {
                                var img_tag = $("<img>").attr({
                                    src: window.audioUrl,
                                    id: "msg_audio_audio" + i,
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height messagecompose" +
                                        i,
                                });

                                var remove_button = $("<span>")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + i,
                                        "data-index": i,
                                    })
                                    .text("X");
                                // $('#msg_img'+group_id+'audio')
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'audio').show();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'img').hide();
                                // $('#msg_img'+group_id+'file').hide();
                                // $('#remove_img'+group_id).show();
                            } else {
                                var img = window.fileUrl;
                                var img_tag = $("<img>").attr({
                                    src: img,
                                    id: "msg_img_file_file" + i,
                                    width: 150,
                                    height: 100,
                                    class:
                                        "img-thumbnail img_height messagecompose" +
                                        i,
                                });

                                var remove_button = $("<span>")
                                    .attr({
                                        class: "remove_img back_to remove",
                                        id: "remove_img" + i,
                                        "data-index": i,
                                    })
                                    .text("X");
                                // console.log("its enter");
                                // $('#msg_img'+group_id+'file')
                                // .width(150)
                                // .height(100);
                                // $('#msg_img'+group_id+'file').show();
                                // $('#msg_img'+group_id+'audio').hide();
                                // $('#msg_img'+group_id+'video').hide();
                                // $('#msg_img'+group_id+'img').hide();
                                // $('#remove_img'+group_id).show();
                            }
                            var container = $("<div>").append(
                                img_tag,
                                remove_button
                            );
                            $(".viewimgdiv").append(container);
                        };

                        reader.readAsDataURL(input[0].files[i]);
                    }
                }
            }
        });
        // $('.removedraft_img').on("click",function(){
        //     console.log("removedraft_img");
        //     let draft_id = $(this).attr('data-draft_id');
        //     let index = $(this).attr('data-index');
        //     let files = [];
        //     $(".draft_paths" + draft_id).each(function() {
        //         files.push($(this).val());
        //     });
        //     console.log(files);
        //     let input_compose = $('#message_img')[0];
        //     let files_compose = input_compose.files;

        //     // Create a new DataTransfer object
        //     let newDataTransfer = new DataTransfer();

        //     // Add all files except the one to be removed to the new DataTransfer object
        //     for (let i = 0; i < files_compose.length; i++) {
        //         if (i != index) {
        //             $('.viewimgdiv').empty();
        //             newDataTransfer.items.add(files_compose[i]);
        //         }
        //     }
        //      // Assign the new DataTransfer object to the file input
        //      input_compose.files = newDataTransfer.files;

        //      // Trigger change event to update the displayed files
        //      $(input_compose).trigger('change');
        // });
        $(document).on("click", ".remove_img", function () {
            console.log("its enter remove img");
            let group_id = $(this).attr("data-id");
            let index = $(this).attr("data-index");
            console.log("its enter remove");
            if (group_id) {
                console.log("its enter if");
                let input = $("#message_img" + group_id)[0];
                let files = input.files;
                // Create a new DataTransfer object
                let newDataTransfer = new DataTransfer();

                // Add all files except the one to be removed to the new DataTransfer object
                for (let i = 0; i < files.length; i++) {
                    if (i != index) {
                        $(".viewimg" + group_id + "div").empty();
                        newDataTransfer.items.add(files[i]);
                    }
                }

                // Assign the new DataTransfer object to the file input
                input.files = newDataTransfer.files;

                // Trigger change event to update the displayed files
                $(input).trigger("change");
            } else {
                console.log("its enter else");
                let data_parent = $(this).attr("data-parent");
                if (data_parent) {
                    console.log("its enter data-parent if");
                    let msg_id = $(this).attr("data-msg_id");
                    let input_replay = $("#replay_message_img" + msg_id)[0];
                    let files_replay = input_replay.files;
                    console.log("its enter files_replay");

                    // Create a new DataTransfer object
                    let newDataTransfer = new DataTransfer();
                    let files_lenth = files_replay ? files_replay.length : 0;
                    console.log(msg_id, index, files_lenth);
                    // Add all files except the one to be removed to the new DataTransfer object
                    for (let i = 0; i < files_lenth; i++) {
                        console.log("its enter in for");
                        if (i != index) {
                            console.log("its enter for if");
                            $(".viewreplayimg" + msg_id + "div").empty();
                            newDataTransfer.items.add(files_replay[i]);
                        }
                    }
                    if (files_lenth == "0" || files_lenth == "1") {
                        $(".viewreplayimg" + msg_id + "div").empty();
                    }

                    // Assign the new DataTransfer object to the file input
                    input_replay.files = newDataTransfer.files;
                    console.log("its enter for replay");
                    // Trigger change event to update the displayed files
                    $(input_replay).trigger("change");
                } else {
                    console.log("its enter else-> else");
                    let draft_id = $(this).attr("data-draft-id");
                    let input_compose;
                    if (draft_id) {
                        console.log("its enter else-> else->if(draft)");
                        input_compose = $("#message_img" + draft_id)[0];
                    } else {
                        input_compose = $("#message_img")[0];
                    }

                    let files_compose = input_compose.files;
                    console.log(files_compose.length);
                    // Create a new DataTransfer object
                    let newDataTransfer = new DataTransfer();

                    // Add all files except the one to be removed to the new DataTransfer object
                    for (let i = 0; i < files_compose.length; i++) {
                        console.log(i, index);
                        if (i != index) {
                            console.log("for->if");
                            $(".viewimgdiv").empty();
                            newDataTransfer.items.add(files_compose[i]);
                        }
                    }
                    if (
                        files_compose.length == "0" ||
                        files_compose.length == "1"
                    ) {
                        $(".viewimgdiv").empty();
                    }

                    // Assign the new DataTransfer object to the file input
                    input_compose.files = newDataTransfer.files;
                    console.log("transfer");
                    // Trigger change event to update the displayed files
                    $(input_compose).trigger("change");
                    console.log("change");
                }
            }
        });

        $(".draft_remove_img").on("click", function () {
            let draft_id = $(this).attr("data-draft_id");
            let index = $(this).attr("data-index");
            $("#old_paths" + draft_id + index).remove();
            $("#draft_img_container" + draft_id + index).hide();
        });

        $(".close-sent").on("click", function () {
            let sent_id = $(this).attr("data-id");
            console.log(sent_id);
            $("#pills-sent" + sent_id).removeClass("active");
            $("#pills-sent-tab" + sent_id).removeClass("active");
            $("#pills-sent").addClass("active");
            $("#pills-sent").addClass("show");
            $("#pills-sent-tab").addClass("active");
        });
        $(".close-draft").on("click", function () {
            let sent_id = $(this).attr("data-id");
            console.log(sent_id);
            $("#pills-draft" + sent_id).removeClass("active");
            $("#pills-draft-tab" + sent_id).removeClass("active");
            $("#pills-draft").addClass("active");
            $("#pills-draft").addClass("show");
            $("#pills-draft-tab").addClass("active");
        });

        $(".sent_link").on("click", function () {
            $("#pills-sent-tab").addClass("active");
        });
        $(".draft_link").on("click", function () {
            $("#pills-draft-tab").addClass("active");
        });

        $(".close_inbox").on("click", function () {
            let sent_id = $(this).attr("data-id");
            console.log(sent_id);
            $("#pills-inbox" + sent_id).removeClass("active");
            $("#pills-inbox-tab" + sent_id).removeClass("active");
            $("#pills-inbox").addClass("active");
            $("#pills-inbox").addClass("show");
            $("#pills-inbox-tab").addClass("active");
        });

        $(".inbox_link").on("click", function () {
            $("#pills-inbox-tab").addClass("active");
            $(".close_starred").hide();
        });

        $(".starred_link").on("click", function () {
            $("#pills-starred-tab").addClass("active");
            $(".close_inbox").hide();
            $(".close_starred").show();
        });

        $(".close_starred").on("click", function () {
            let star_id = $(this).attr("data-id");
            console.log("star_id" + star_id);
            $("#pills-inbox" + star_id).removeClass("active");
            $(".pills-starred-tab" + star_id).removeClass("active");
            $("#pills-starred").addClass("active");
            $("#pills-starred").addClass("show");
            $("#pills-starred-tab").addClass("active");
        });

        $(".replaing_message").on("click", function () {
            let msg_id = $(this).attr("data-id");
            let senter_id = $("#senter_id" + msg_id).val();
            let msg_type = $(".message_type" + msg_id).val();
            let editor =
                CKEDITOR.instances["gmail_individual_message" + msg_id];
            let message = editor.getData();
            let input = $("#replay_message_img" + msg_id);
            let files_msg = input[0].files ?? 0;

            console.log("message");
            console.log(senter_id);
            const maxFiles = 3;
            const maxSizeInMB = 2; // Maximum file size in MB
            const maxSizeInBytes = maxSizeInMB * 1024 * 1024; // Convert MB to bytes
            const fileInputs =
                document.getElementsByClassName("replay_message_img");
            let fileInputElements;

            if (fileInputs.length > 0) {
                fileInputElements = fileInputs;
            } else {
                const fileInputById =
                    document.getElementById("replay_message_img");
                if (fileInputById) {
                    fileInputElements = [fileInputById]; // Make it an array for consistency
                } else {
                    fileInputElements = []; // No elements found
                }
            }
            const allowedMimes = ["image/jpeg", "image/png", "application/pdf"];
            for (let i = 0; i < fileInputs.length; i++) {
                fileInputs[i].addEventListener("change", function () {
                    if (this.files.length > maxFiles) {
                        notify_script(
                            "Error",
                            "You can upload a maximum of " +
                                maxFiles +
                                " files.",
                            "error",
                            true
                        );
                        this.value = ""; // Clear the selected files
                    }
                    for (let i = 0; i < this.files.length; i++) {
                        if (!allowedMimes.includes(this.files[i].type)) {
                            notify_script(
                                "Error",
                                "Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.",
                                "error",
                                true
                            );
                            this.value = "";
                            return;
                        }

                        if (this.files[i].size > maxSizeInBytes) {
                            notify_script(
                                "Error",
                                "File size exceeds the maximum limit of " +
                                    maxSizeInMB +
                                    " MB.",
                                "error",
                                true
                            );
                            this.value = "";
                            return;
                        }
                    }
                });
            }

            let formData = new FormData();
            for (let i = 0; i < files_msg.length; i++) {
                formData.append("files[]", files_msg[i]);
            }
            formData.append("message_id", msg_id);
            formData.append("message_type", msg_type);
            formData.append("senter_id", senter_id);
            formData.append("message", message);

            let url = window.replay_message;

            if (url) {
                axios
                    .post(url, formData)
                    .then((response) => {
                        console.log(response);
                        if (response.data) {
                            $(".inbox_message" + msg_id).append(
                                response.data.view
                            );

                            var count = $(".container").length;
                            if (count >= "3") {
                                $(".inbox_message" + msg_id).addClass(
                                    "group_content_scroll"
                                );
                            }
                            var $groupContent = $(".inbox_message" + msg_id);
                            $groupContent.scrollTop(
                                $groupContent[0].scrollHeight
                            );

                            let editor =
                                CKEDITOR.instances[
                                    "gmail_individual_message" + msg_id
                                ];
                            editor.setData("");
                            $(".viewreplayimg" + msg_id + "div").empty();
                            $("#replay_message_img" + msg_id).val("");
                        } else {
                            console.error("Invalid response data:", response);
                            // Handle the case where viewfile is not present in the response data
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                        // Handle AJAX error gracefully, e.g., display an error message to the user
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        });

        $(".recipients_group").on("change", function () {
            let id = $(this).val();
            let url = window.receptiants + "?id=" + id;

            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.receptiants) {
                            let receptiants = response.data.receptiants;
                            let data_id = $(this).attr("data-id");
                            $("#" + data_id)
                                .empty()
                                .prepend()
                                .select2({
                                    allowClear: true,
                                    placeholder: "",
                                    data: receptiants,
                                });

                            $("#" + data_id)
                                .val(null)
                                .trigger("change");
                        } else {
                            console.error("Invalid response data:", response);
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        });

        $(document).on("click", ".inbox_star", function () {
            console.log("Star icon clicked");
            let starred_id = $(this).attr("data-id");
            let star = $(this).hasClass("inbox_starred") ? "0" : "1";
            let url =
                window.replay_message +
                "?starred_id=" +
                starred_id +
                "&star=" +
                star;

            if (url) {
                axios
                    .post(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data.star_status) {
                            let status = response.data.star_status;
                            if (status == "added") {
                                $(this).addClass(
                                    "bxs-star text-warning inbox_starred"
                                );
                                $(this).removeClass("bx-star");
                                window.location.reload();
                            } else {
                                $(this).addClass("bx-star");
                                $(this).removeClass(
                                    "bxs-star text-warning inbox_starred"
                                );
                                window.location.reload();
                            }
                        } else {
                            console.error("Invalid response data:", response);
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        });

        $(document).on("change", ".inbox_check", function () {
            if ($(".inbox_check:checked").length > 0) {
                $(".delete_inbox").show();
            } else {
                $(".delete_inbox").hide();
            }
        });

        $(document).on("change", ".sent_check", function () {
            if ($(".sent_check:checked").length > 0) {
                $(".delete_sent").show();
            } else {
                $(".delete_sent").hide();
            }
        });

        $(document).on("change", ".starred_check", function () {
            if ($(".starred_check:checked").length > 0) {
                $(".delete_starred").show();
            } else {
                $(".delete_starred").hide();
            }
        });

        $(document).on("change", ".draft_check", function () {
            if ($(".draft_check:checked").length > 0) {
                $(".delete_draft").show();
            } else {
                $(".delete_draft").hide();
            }
        });

        $(document).on("change", ".bin_check", function () {
            if ($(".bin_check:checked").length > 0) {
                $(".delete_bin").show();
                $(".restore_bin").show();
            } else {
                $(".delete_bin").hide();
                $(".restore_bin").hide();
            }
        });

        $(document).on("click", ".delete_message", function () {
            console.log("delete");
            let msg_type = $(this).val();
            let check_ids = [];
            if (msg_type == "inbox") {
                $(".inbox_check:checked").each(function () {
                    check_ids.push($(this).val());
                });
            } else if (msg_type == "sent") {
                $(".sent_check:checked").each(function () {
                    check_ids.push($(this).val());
                });
            } else if (msg_type == "starred") {
                $(".starred_check:checked").each(function () {
                    check_ids.push($(this).val());
                });
            } else if (msg_type == "draft") {
                $(".draft_check:checked").each(function () {
                    check_ids.push($(this).val());
                });
            } else if (msg_type == "restore") {
                $(".bin_check:checked").each(function () {
                    check_ids.push($(this).val());
                });
            } else {
                $(".bin_check:checked").each(function () {
                    check_ids.push($(this).val());
                });
            }

            console.log(check_ids);
            let url =
                window.deletemessage +
                "?check_ids=" +
                check_ids +
                "&msg_type=" +
                msg_type;
            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response);
                        if (response.data) {
                            if (msg_type == "restore") {
                                notify_script(
                                    "Success",
                                    "Messages Restored Successfully",
                                    "success",
                                    true
                                );
                            } else {
                                notify_script(
                                    "Success",
                                    "Messages Deleted Successfully",
                                    "success",
                                    true
                                );
                            }

                            setTimeout(function () {
                                location.reload();
                            }, 600);
                        } else {
                            console.error("Invalid response data:", response);
                            // Handle the case where viewfile is not present in the response data
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                        // Handle AJAX error gracefully, e.g., display an error message to the user
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        });

        $(document).on("click", ".delete_icon", function () {
            console.log("delete");
            let msg_type = $(this).attr("data-msg_type");
            let check_ids = $(this).attr("id");

            console.log(check_ids);
            let url =
                window.deletemessage +
                "?check_ids=" +
                check_ids +
                "&msg_type=" +
                msg_type;
            if (url) {
                axios
                    .get(url)
                    .then((response) => {
                        console.log(response, msg_type);
                        if (response.data) {
                            if (msg_type == "restore") {
                                notify_script(
                                    "Success",
                                    "Messages Restored Successfully",
                                    "success",
                                    true
                                );
                            } else {
                                sessionStorage.setItem("active_tab", msg_type);

                                notify_script(
                                    "Success",
                                    "Messages Deleted Successfully",
                                    "success",
                                    true
                                );
                            }
                            setTimeout(function () {
                                location.reload();
                            }, 800);
                        } else {
                            console.error("Invalid response data:", response);
                            // Handle the case where viewfile is not present in the response data
                        }
                    })
                    .catch((error) => {
                        console.error("Error fetching student report:", error);
                        // Handle AJAX error gracefully, e.g., display an error message to the user
                    });
            } else {
                console.error("Invalid URL:", url);
            }
        });

        document
            .getElementById("message_img")
            .addEventListener("change", function () {
                const maxFiles = 3;
                const maxSizeInMB = 2; // Maximum file size in MB
                const maxSizeInBytes = maxSizeInMB * 1024 * 1024; // Convert MB to bytes
                const allowedMimes = [
                    "image/jpeg",
                    "image/png",
                    "application/pdf",
                ];
                if (this.files.length > maxFiles) {
                    notify_script(
                        "Error",
                        "You can upload a maximum of " + maxFiles + " files.",
                        "error",
                        true
                    );
                    this.value = "";
                    return;
                }

                for (let i = 0; i < this.files.length; i++) {
                    if (!allowedMimes.includes(this.files[i].type)) {
                        notify_script(
                            "Error",
                            "Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.",
                            "error",
                            true
                        );
                        this.value = "";
                        return;
                    }
                    console.log(this.files[i].size, maxSizeInBytes);
                    if (this.files[i].size > maxSizeInBytes) {
                        console.log("its enter");
                        notify_script(
                            "Error",
                            "File size exceeds the maximum limit of " +
                                maxSizeInMB +
                                " MB.",
                            "error",
                            true
                        );
                        this.value = "";
                        return;
                    }
                }
            });

        $(".replay_message_img").on("change", function () {
            console.log("its enter in class");
            const maxFiles = 3;
            const maxSizeInMB = 2; // Maximum file size in MB
            const maxSizeInBytes = maxSizeInMB * 1024 * 1024; // Convert MB to bytes
            const allowedMimes = ["image/jpeg", "image/png", "application/pdf"];
            if (this.files.length > maxFiles) {
                notify_script(
                    "Error",
                    "You can upload a maximum of " + maxFiles + " files.",
                    "error",
                    true
                );
                this.value = "";
                return;
            }

            for (let i = 0; i < this.files.length; i++) {
                if (!allowedMimes.includes(this.files[i].type)) {
                    notify_script(
                        "Error",
                        "Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.",
                        "error",
                        true
                    );
                    this.value = "";
                    return;
                }
                console.log(this.files[i].size, maxSizeInBytes);
                if (this.files[i].size > maxSizeInBytes) {
                    console.log("its enter");
                    notify_script(
                        "Error",
                        "File size exceeds the maximum limit of " +
                            maxSizeInMB +
                            " MB.",
                        "error",
                        true
                    );
                    this.value = "";
                    return;
                }
            }
        });

        $(".message_img").on("change", function () {
            console.log("its enter in class");
            const maxFiles = 3;
            const maxSizeInMB = 2; // Maximum file size in MB
            const maxSizeInBytes = maxSizeInMB * 1024 * 1024; // Convert MB to bytes
            const allowedMimes = ["image/jpeg", "image/png", "application/pdf"];
            if (this.files.length > maxFiles) {
                notify_script(
                    "Error",
                    "You can upload a maximum of " + maxFiles + " files.",
                    "error",
                    true
                );
                this.value = "";
                return;
            }

            for (let i = 0; i < this.files.length; i++) {
                if (!allowedMimes.includes(this.files[i].type)) {
                    notify_script(
                        "Error",
                        "Invalid file type. Only JPG, JPEG, PNG, and PDF files are allowed.",
                        "error",
                        true
                    );
                    this.value = "";
                    return;
                }
                console.log(this.files[i].size, maxSizeInBytes);
                if (this.files[i].size > maxSizeInBytes) {
                    console.log("its enter");
                    notify_script(
                        "Error",
                        "File size exceeds the maximum limit of " +
                            maxSizeInMB +
                            " MB.",
                        "error",
                        true
                    );
                    this.value = "";
                    return;
                }
            }
        });
    }
    static GroupMessage(id) {
        function scrollToBottom(id) {
            var $groupContent = $(".group_content" + id);
            $groupContent.scrollTop($groupContent[0].scrollHeight);
        }
        let group_id = $("#group_id").val();
        let editor = CKEDITOR.instances["gmail_group_message" + id];
        let message = editor.getData();
        let input = $("#message_img" + id);
        let files_msg = input[0].files ?? 0;
        console.log("message");
        console.log(message);

        let formData = new FormData();
        for (let i = 0; i < files_msg.length; i++) {
            formData.append("files[]", files_msg[i]);
        }
        formData.append("group_id", id);
        formData.append("message", message);

        let url = window.gmail_message;

        if (url) {
            axios
                .post(url, formData)
                .then((response) => {
                    console.log(response);
                    if (response.data.view) {
                        $(".group_content" + id).append(response.data.view);

                        var count = $(".container").length;
                        if (count >= "3") {
                            $(".group_content" + id).addClass(
                                "group_content_scroll"
                            );
                        }
                        scrollToBottom(id);
                        let editor =
                            CKEDITOR.instances["gmail_group_message" + id];
                        editor.setData("");
                        $(".viewimg" + id + "div").empty();
                        $("#message_img" + id).empty();
                    } else {
                        console.error("Invalid response data:", response);
                        // Handle the case where viewfile is not present in the response data
                    }
                })
                .catch((error) => {
                    console.error("Error fetching student report:", error);
                    // Handle AJAX error gracefully, e.g., display an error message to the user
                });
        } else {
            console.error("Invalid URL:", url);
        }
    }

    // static GroupMessage(id) {
    //     function scrollToBottom(id) {
    //         var $groupContent = $('.group_content' + id);
    //         $groupContent.scrollTop($groupContent[0].scrollHeight);
    //     }

    //     let group_id = $('#group_id').val();
    //     let message = $('#gmail_group_message' + id).val();
    //     let input = document.getElementById('message_img'+id);
    //     let files_msg = input.files;
    //     console.log(files_msg);

    //     // Check if files exist
    //     if (files_msg.length > 0) {
    //         let filenames = [];
    //         for (let i = 0; i < files_msg.length; i++) {
    //             filenames.push(files_msg[i].name);
    //         }
    //         console.log(filenames);

    //         let url = window.gmail_message + "?group_id=" + id + "&message=" + message + "&files=" + filenames.join(',');

    //         if (url) {
    //             axios
    //                 .get(url)
    //                 .then((response) => {
    //                     console.log(response);
    //                     if (response.data.view) {
    //                         $(".group_content" + id).append(response.data.view);
    //                         var count = $(".container").length;
    //                         if (count >= 3) {
    //                             $(".group_content" + id).addClass("group_content_scroll");
    //                         }
    //                         scrollToBottom(id);
    //                         $('#gmail_group_message' + id).val("");
    //                     } else {
    //                         console.error("Invalid response data:", response);
    //                         // Handle the case where viewfile is not present in the response data
    //                     }
    //                 })
    //                 .catch((error) => {
    //                     console.error("Error fetching student report:", error);
    //                     // Handle AJAX error gracefully, e.g., display an error message to the user
    //                 });
    //         } else {
    //             console.error("Invalid URL:", url);
    //         }
    //     } else {
    //         console.error("No files selected.");
    //     }
    // }
}
