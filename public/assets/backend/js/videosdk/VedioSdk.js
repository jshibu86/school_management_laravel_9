import axios from "axios";

export default class VedioSdk {
    static TimerThing = null;
    static GracePeriodGiven = false;
    static VedioSdkInit() {
        // Getting Elements from DOM
        VedioSdk.dragElement(document.getElementById("mydiv"));
        const joinButton = document.getElementById("joinBtn");
        const leaveButton = document.getElementById("leaveBtn");
        const rejoinButton = document.getElementById("rejoinBtn");
        const endButton = document.getElementById("endBtn");
        const toggleMicButton = document.getElementById("toggleMicBtn");
        const toggleWebCamButton = document.getElementById("toggleWebCamBtn");
        const createButton = document.getElementById("createMeetingBtn");
        const videoContainer = document.getElementById("videoContainer");
        const textDiv = document.getElementById("textDiv");
        const TOKEN =
            "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhcGlrZXkiOiI0ODVlNDc1MC0xOTJjLTRmNzgtYjk2My01M2RlMGZiNzhlZjEiLCJwZXJtaXNzaW9ucyI6WyJhbGxvd19qb2luIl0sImlhdCI6MTcxMzMyODg4NSwiZXhwIjoxODcxMTE2ODg1fQ.O5ayhAgtY5XwroUFiKeQoUWJZGn9CVZt7geB_LoNVE8";

        // Declare Variables
        let meeting = null;
        let meetingId = "";
        let isMicOn = false;
        let isWebCamOn = false;
        let doctorName = $("#meeting_doctor_name").val();
        let doctorSpecialist = $("#meeting_doctor_specialist").val();
        let doctorDepartment = $("#meeting_doctor_department").val();

        // Initialize meeting
        function initializeMeeting() {
            window.VideoSDK.config(TOKEN);

            meeting = window.VideoSDK.initMeeting({
                meetingId: meetingId, // required
                name:
                    "Dr." +
                    doctorName +
                    " , " +
                    doctorSpecialist +
                    " , " +
                    doctorDepartment, // required
                micEnabled: true, // optional, default: true
                webcamEnabled: true, // optional, default: true
            });

            meeting.join();

            // Creating local participant
            createLocalParticipant();

            // Setting local participant stream
            meeting.localParticipant.on("stream-enabled", (stream) => {
                setTrack(stream, null, meeting.localParticipant, true);
            });

            // meeting joined event
            meeting.on("meeting-joined", () => {
                textDiv.style.display = "none";
                document.getElementById("grid-screen").style.display = "block";
                document.getElementById(
                    "meetingIdHeading"
                ).textContent = `Meeting Id: ${meetingId}`;
            });

            // meeting left event
            meeting.on("meeting-left", () => {
                videoContainer.innerHTML = "";
                console.log("left user");
                EndMeeting();

                $("#leaveBtn").hide();
                $("#rejoinBtn").show();

                const timerElementTimer =
                    document.getElementById("meeting_Timer");
                timerElementTimer.textContent = "00:00:00";
                clearInterval(VedioSdk.TimerThing);
            });

            // Remote participants Event
            // participant joined
            meeting.on("participant-joined", (participant) => {
                let videoElement = createVideoElement(
                    participant.id,
                    participant.displayName
                );
                IsPatientJoined(null);
                let audioElement = createAudioElement(participant.id);
                // stream-enabled
                participant.on("stream-enabled", (stream) => {
                    setTrack(stream, audioElement, participant, false);
                });
                videoContainer.appendChild(videoElement);
                videoContainer.appendChild(audioElement);
            });

            // participant left
            meeting.on("participant-left", (participant) => {
                let vElement = document.getElementById(`f-${participant.id}`);
                vElement.remove(vElement);

                let aElement = document.getElementById(`a-${participant.id}`);
                aElement.remove(aElement);
            });
        }

        async function EndMeeting() {
            const url = `https://api.videosdk.live/v2/sessions/end`;
            const options = {
                method: "POST",
                headers: {
                    Authorization: TOKEN,
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    roomId: meetingId,
                }),
            };

            try {
                const response = await fetch(url, options);
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }

                const data = await response.json();
                console.log(data);
            } catch (error) {
                console.log("Error", error.message);
            }
        }

        function createLocalParticipant() {
            let localParticipant = createVideoElement(
                meeting.localParticipant.id,
                meeting.localParticipant.displayName
            );
            videoContainer.appendChild(localParticipant);
        }

        function createVideoElement(pId, name) {
            let videoFrame = document.createElement("div");
            videoFrame.setAttribute("id", `f-${pId}`);

            //create video
            let videoElement = document.createElement("video");
            videoElement.classList.add("video-frame");
            videoElement.setAttribute("id", `v-${pId}`);
            videoElement.setAttribute("playsinline", true);
            videoElement.setAttribute("width", "250");
            videoElement.setAttribute("height", "250");
            videoElement.style.objectFit = "cover";
            videoFrame.appendChild(videoElement);

            let displayName = document.createElement("div");
            displayName.style.marginTop = "10px";
            displayName.style.marginBottom = "10px";
            displayName.innerHTML = `Name : ${name}`;

            videoFrame.appendChild(displayName);
            return videoFrame;
        }

        // creating audio element
        function createAudioElement(pId) {
            let audioElement = document.createElement("audio");
            audioElement.setAttribute("autoPlay", "false");
            audioElement.setAttribute("playsInline", "true");
            audioElement.setAttribute("controls", "false");
            audioElement.setAttribute("id", `a-${pId}`);
            audioElement.style.display = "none";
            return audioElement;
        }

        // setting media track
        function setTrack(stream, audioElement, participant, isLocal) {
            if (stream.kind == "video") {
                isWebCamOn = true;
                const mediaStream = new MediaStream();
                mediaStream.addTrack(stream.track);
                let videoElm = document.getElementById(`v-${participant.id}`);
                videoElm.srcObject = mediaStream;
                videoElm
                    .play()
                    .catch((error) =>
                        console.error("videoElem.current.play() failed", error)
                    );
            }
            if (stream.kind == "audio") {
                if (isLocal) {
                    isMicOn = true;
                } else {
                    const mediaStream = new MediaStream();
                    mediaStream.addTrack(stream.track);
                    audioElement.srcObject = mediaStream;
                    audioElement
                        .play()
                        .catch((error) =>
                            console.error("audioElem.play() failed", error)
                        );
                }
            }
        }

        // Join Meeting Button Event Listener
        joinButton.addEventListener("click", async () => {
            document.getElementById("join-screen").style.display = "none";
            textDiv.textContent = "Joining the meeting...";

            roomId = document.getElementById("meetingIdTxt").value;
            meetingId = roomId;

            initializeMeeting();
        });

        // Create Meeting Button Event Listener
        createButton.addEventListener("click", async () => {
            document.getElementById("join-screen").style.display = "none";
            textDiv.textContent = "Please wait, we are joining the meeting";

            // API call to create meeting
            const url = `https://api.videosdk.live/v2/rooms`;
            const options = {
                method: "POST",
                headers: {
                    Authorization: TOKEN,
                    "Content-Type": "application/json",
                },
            };

            const { roomId } = await fetch(url, options)
                .then((response) => response.json())
                .catch((error) => alert("error", error));

            JoinNowNotification(roomId);
            VedioSdk.TimerStart(null);
        });

        rejoinButton.addEventListener("click", async () => {
            let type = "doctor";
            let appointment_id = $("#meeting_appointment_id").val();
            let baseUrl = document
                .querySelector('meta[name="base-url"]')
                .getAttribute("content");
            let geturl =
                baseUrl +
                "/api/appointment/" +
                type +
                "/rejoin/" +
                appointment_id;
            axios
                .get(geturl)
                .then((response) => {
                    meetingId = response?.data?.data?.meeting_id;
                    initializeMeeting();
                    $("#leaveBtn").show();
                    $("#rejoinBtn").hide();
                    VedioSdk.TimerStart(null);
                })
                .catch((error) => {
                    console.log(error);
                });
        });
        endButton.addEventListener("click", async () => {
            EndMeeting();
            videoContainer.innerHTML = "";
            $("#leaveBtn").hide();
            $("#rejoinBtn").show();
            const timerElementTimer = document.getElementById("meeting_Timer");
            timerElementTimer.textContent = "00:00:00";
            clearInterval(VedioSdk.TimerThing);
        });

        leaveButton.addEventListener("click", async () => {
            if (meeting) meeting.leave();
            $("#leaveBtn").show();
            $("#rejoinBtn").hide();
            clearInterval(VedioSdk.TimerThing);
            document.getElementById("grid-screen").style.display = "none";
            document.getElementById("join-screen").style.display = "block";
        });

        function JoinNowNotification(roomId) {
            let type = "webdoctor";
            let appointment_id = $("#meeting_appointment_id").val();
            let baseUrl = document
                .querySelector('meta[name="base-url"]')
                .getAttribute("content");
            let geturl =
                baseUrl +
                "/api/appointment/" +
                type +
                "/joinnow/" +
                appointment_id +
                "?meeting_id=" +
                roomId;
            axios
                .get(geturl)
                .then((response) => {
                    meetingId = response?.data?.data?.meeting_id;

                    initializeMeeting();
                    toastr.success(response?.data?.message);
                })
                .catch((error) => {
                    console.log(error);
                });
        }

        function IsPatientJoined(roomId) {
            let type = "webdoctor";
            let appointment_id = $("#meeting_appointment_id").val();
            let baseUrl = document
                .querySelector('meta[name="base-url"]')
                .getAttribute("content");
            let geturl = baseUrl + "/api/is_patient_joined/" + appointment_id;
            axios
                .post(geturl, { status: 1 })
                .then((response) => {
                    console.log("patient joined");
                })
                .catch((error) => {
                    console.log(error);
                });
        }
    }

    static TimerStart = (durationLimit = null) => {
        const duration =
            durationLimit != null
                ? durationLimit
                : $("#meeting_appointment_time").val(); // "00:25:00"

        //const appointDate = $("#meeting_appointment_datetime").val(); // "2024-04-23 18:25:00"

        const appointDate = moment().format("YYYY-MM-DD HH:mm:ss");

        const timerElement = document.getElementById("meeting_Timer");

        VedioSdk.TimerThing = setInterval(() => {
            try {
                console.log("timer running");
                const appointmentDateTime =
                    VedioSdk.parseAppointmentDate(appointDate);

                const [hours, minutes, seconds] = duration
                    .split(":")
                    .map(Number);

                const remainingMilliseconds =
                    appointmentDateTime.getTime() +
                    hours * 3600000 +
                    minutes * 60000 +
                    seconds * 1000 -
                    Date.now();

                const remainingHours = Math.floor(
                    (remainingMilliseconds / (1000 * 60 * 60)) % 24
                );
                const remainingMinutes = Math.floor(
                    (remainingMilliseconds / (1000 * 60)) % 60
                );
                const remainingSeconds = Math.floor(
                    (remainingMilliseconds / 1000) % 60
                );

                const formattedTime = `${remainingHours
                    .toString()
                    .padStart(2, "0")}:${remainingMinutes
                    .toString()
                    .padStart(2, "0")}:${remainingSeconds
                    .toString()
                    .padStart(2, "0")}`;

                timerElement.textContent = formattedTime;

                if (timerElement.textContent == "00:02:00") {
                    timerElement.style.backgroundColor = "red";
                    toastr.warning("Meeting will end in 2 minutes");
                }

                if (remainingMilliseconds <= 0) {
                    clearInterval(VedioSdk.TimerThing);
                    timerElement.textContent = "00:00:00";
                    if (VedioSdk.GracePeriodGiven) {
                        $("#leaveBtn").click();
                        toastr.error("Timer Reached");
                    }
                    if (!VedioSdk.GracePeriodGiven) {
                        swal({
                            title: "Alert",
                            text: "You have reached your time duration. If you want an extra 5 more minutes",
                            icon: "warning",
                            buttons: {
                                confirm: "Yes",
                                cancel: "No",
                            },
                        }).then(function (result) {
                            if (result) {
                                VedioSdk.TimerStart("00:05:00");
                                timerElement.style.backgroundColor = "green";
                                VedioSdk.GracePeriodGiven = true;
                            } else {
                                $("#leaveBtn").click();
                                toastr.error("Timer Reached");
                            }
                        });
                    }

                    // Add your logic for when the timer reaches 00:00:00
                }
            } catch (error) {
                console.error(error);
            }
        }, 1000);
    };

    static dragElement(elmnt) {
        var pos1 = 0,
            pos2 = 0,
            pos3 = 0,
            pos4 = 0;

        if (elmnt) {
            if (elmnt && document.getElementById(elmnt.id)) {
                /* if present, the header is where you move the DIV from:*/
                document.getElementById(elmnt.id).onmousedown = dragMouseDown;
            } else {
                /* otherwise, move the DIV from anywhere inside the DIV:*/
                elmnt.onmousedown = dragMouseDown;
            }
            function dragMouseDown(e) {
                e = e || window.event;
                e.preventDefault();
                // get the mouse cursor position at startup:
                pos3 = e.clientX;
                pos4 = e.clientY;
                document.onmouseup = closeDragElement;
                // call a function whenever the cursor moves:
                document.onmousemove = elementDrag;
            }

            function elementDrag(e) {
                e = e || window.event;
                e.preventDefault();
                // calculate the new cursor position:
                pos1 = pos3 - e.clientX;
                pos2 = pos4 - e.clientY;
                pos3 = e.clientX;
                pos4 = e.clientY;
                // set the element's new position:
                elmnt.style.top = elmnt.offsetTop - pos2 + "px";
                elmnt.style.left = elmnt.offsetLeft - pos1 + "px";
            }
            function closeDragElement() {
                /* stop moving when mouse button is released:*/
                document.onmouseup = null;
                document.onmousemove = null;
            }
        }
    }

    static parseAppointmentDate = (appointDate) => {
        // Regular expression to match date components
        const regex = /(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/;
        const match = appointDate.match(regex);

        if (!match) {
            throw new Error("Invalid appointment date format");
        }

        // Extract date components
        const [, year, month, day, hour, minute, second] = match;

        // Parse appointment date and time
        return new Date(year, month - 1, day, hour, minute, second);
    };
}
