// Constants
const API_BASE_URL = "https://api.videosdk.live";

// Declaring variables
let videoContainer = document.getElementById("videoContainer");
let micButton = document.getElementById("micButton");
let camButton = document.getElementById("camButton");
let copy_meeting_id = document.getElementById("meetingid");
public / assets / backend / js / videosdk / index.js;
let contentRaiseHand = document.getElementById("contentRaiseHand");
let btnScreenShare = document.getElementById("btnScreenShare");
let videoScreenShare = document.getElementById("videoScreenShare");
let btnRaiseHand = document.getElementById("btnRaiseHand");
// let btnStopPresenting = document.getElementById("btnStopPresenting");
let btnSend = document.getElementById("btnSend");
let participantsList = document.getElementById("participantsList");
let localparticipantsList = document.getElementById("local_name_div");
let videoCamOff = document.getElementById("main-pg-cam-off");
let videoCamOn = document.getElementById("main-pg-cam-on");

let micOn = document.getElementById("main-pg-unmute-mic");
let micOff = document.getElementById("main-pg-mute-mic");

//recording
let btnStartRecording = document.getElementById("btnStartRecording");
let btnStopRecording = document.getElementById("btnStopRecording");

//videoPlayback DIV
let videoPlayback = document.getElementById("videoPlayback");

// For PreCall
const cameraDeviceDropDown = "Permission needed";
const microphoneDeviceDropDown = "Permission needed";
const playBackDeviceDropDown = "Permission needed";

let meeting = "";
// Local participants
let localParticipant;
let localParticipantAudio;
let createMeetingFlag = 0;
let joinMeetingFlag = 0;
let token = "";
let micEnable = false;
let webCamEnable = false;
let totalParticipants = 0;
let remoteParticipantId = "";
let remoteParticipantName = "";
// dd($mediaItem);
let participants = [];
// join page
let joinPageWebcam = document.getElementById("joinCam");
let meetingCode = "";
let screenShareOn = false;
let joinPageVideoStream = null;
let cameraPermissionAllowed = true;
let microphonePermissionAllowed = true;
let deviceChangeEventListener;

window.addEventListener("load", async function () {
    /*

  const audioPermission = await window.VideoSDK.requestPermission(
    window.VideoSDK.Constants.permission.AUDIO,
  );

  console.log(
    "request Audio Permissions",
    audioPermission.get(window.VideoSDK.Constants.permission.AUDIO)
  );


  const videoPermission = await window.VideoSDK.requestPermission(
    window.VideoSDK.Constants.permission.VIDEO,
  );

  console.log(
    "request Video Permissions",
    videoPermission.get(window.VideoSDK.Constants.permission.VIDEO)
  );

  const audiovideoPermission = await window.VideoSDK.requestPermission(
    window.VideoSDK.Constants.permission.AUDIO_AND_VIDEO,
  );

  console.log(
    "request Audio and Video Permissions",
    audiovideoPermission.get(window.VideoSDK.Constants.permission.AUDIO),
    audiovideoPermission.get(window.VideoSDK.Constants.permission.VIDEO)
  );

  */

    /*

  try {
    const checkAudioPermission = await window.VideoSDK.checkPermissions(
      window.VideoSDK.Constants.permission.AUDIO,
    );
    console.log(
      "Check Audio Permissions",
      checkAudioPermission.get(window.VideoSDK.Constants.permission.AUDIO)
    );
  } catch (e) {
    console.error(e.message);
  }

  try {
    const checkVideoPermission = await window.VideoSDK.checkPermissions(
      window.VideoSDK.Constants.permission.VIDEO,
    );
    console.log(
      "Check Video Permissions",
      checkVideoPermission.get(window.VideoSDK.Constants.permission.VIDEO)
    );
  } catch (e) {
    console.error(e.message);
  }

  try {
    const checkAudioVideoPermission = await window.VideoSDK.checkPermissions(
      window.VideoSDK.Constants.permission.AUDIO_AND_VIDEO,
    );
    console.log(
      "Check Audio Video Permissions",
      checkAudioVideoPermission.get(window.VideoSDK.Constants.permission.VIDEO),
      checkAudioVideoPermission.get(window.VideoSDK.Constants.permission.AUDIO)
    );
  } catch (e) {
    console.error(e.message);
  }

  */

    const requestPermission = await window.VideoSDK.requestPermission(
        window.VideoSDK.Constants.permission.AUDIO_AND_VIDEO
    );

    console.log(
        "request Audio and Video Permissions",
        requestPermission.get(window.VideoSDK.Constants.permission.AUDIO),
        requestPermission.get(window.VideoSDK.Constants.permission.VIDEO)
    );

    // await updateDevices();
    await enableCam();
    await enableMic();

    await window.VideoSDK.getNetworkStats({ timeoutDuration: 120000 })
        .then((result) => {
            console.log("Network Stats : ", result);
        })
        .catch((error) => {
            console.log("Error in Network Stats : ", error);
        });

    deviceChangeEventListener = async (devices) => {
        //
        // await updateDevices();
        await enableCam();
    };
    window.VideoSDK.on("device-changed", deviceChangeEventListener);
});

// async function updateDevices() {
//   try {
//     const checkAudioVideoPermission = await window.VideoSDK.checkPermissions();

//     cameraPermissionAllowed = checkAudioVideoPermission.get(window.VideoSDK.Constants.permission.VIDEO);
//     microphonePermissionAllowed = checkAudioVideoPermission.get(window.VideoSDK.Constants.permission.AUDIO);

//     if (cameraPermissionAllowed) {
//       const cameras = await window.VideoSDK.getCameras();
//       cameraDeviceDropDown.innerHTML = "";
//       cameras.forEach(item => {
//         const option = document.createElement('option');
//         option.value = item.deviceId;
//         option.text = item.label;
//         cameraDeviceDropDown.appendChild(option);
//       });

//     } else {
//       const option = document.createElement('option');
//       option.value = "Permission needed";
//       option.text = "Permission needed";
//       cameraDeviceDropDown.appendChild(option);

//       cameraDeviceDropDown.disabled = true;
//       cameraDeviceDropDown.setAttribute("style", "cursor:not-allowed")
//     }

//     if (microphonePermissionAllowed) {
//       const microphones = await window.VideoSDK.getMicrophones();
//       const playBackDevices = await window.VideoSDK.getPlaybackDevices();
//       microphoneDeviceDropDown.innerHTML = "";
//       playBackDeviceDropDown.innerHTML = "";

//       microphones.forEach(item => {
//         const option = document.createElement('option');
//         option.value = item.deviceId;
//         option.text = item.label;
//         microphoneDeviceDropDown.appendChild(option);
//       });

//       playBackDevices.forEach(item => {
//         const option = document.createElement('option');
//         option.value = item.deviceId;
//         option.text = item.label;
//         playBackDeviceDropDown.appendChild(option);
//       });

//     } else {
//       const microphoneDeviceOption = document.createElement('option');
//       microphoneDeviceOption.value = "Permission needed";
//       microphoneDeviceOption.text = "Permission needed";
//       microphoneDeviceDropDown.appendChild(microphoneDeviceOption);

//       const playBackDeviceOption = document.createElement('option');
//       playBackDeviceOption.value = "Permission needed";
//       playBackDeviceOption.text = "Permission needed";
//       playBackDeviceDropDown.appendChild(playBackDeviceOption);

//       microphoneDeviceDropDown.disabled = true;
//       playBackDeviceDropDown.disabled = true;
//       microphoneDeviceDropDown.setAttribute("style", "cursor:not-allowed")
//       playBackDeviceDropDown.setAttribute("style", "cursor:not-allowed")
//     }
//   } catch (Ex) {
//     console.log("Error in check permission" + Ex);
//   }
// }

const setAudioOutputDevice = (deviceId) => {
    const audioTags = document.getElementsByTagName("audio");
    for (let i = 0; i < audioTags.length; i++) {
        audioTags.item(i).setSinkId(deviceId);
    }
};

async function tokenGeneration() {
    if (TOKEN != "") {
        token = TOKEN;
        console.log(token);
    } else if (AUTH_URL != "") {
        token = await window
            .fetch(AUTH_URL + "/generateJWTToken")
            .then(async (response) => {
                const { token } = await response.json();
                console.log(token);
                return token;
            })
            .catch(async (e) => {
                console.log(await e);
                return;
            });
    } else if (AUTH_URL == "" && TOKEN == "") {
        alert("Set Your configuration details first ");
        window.location.href = "/";
        // window.location.reload();
    } else {
        alert("Check Your configuration once ");
        window.location.href = "/";
        // window.location.reload();
    }
}

async function validateMeeting(meetingId, joinMeetingName, joinMeetingUser) {
    if (token != "") {
        const url = `${API_BASE_URL}/v2/rooms/validate/${meetingId}`;

        const options = {
            method: "GET",
            headers: { Authorization: token },
        };

        const result = await fetch(url, options)
            .then((response) => response.json()) //result will have meeting id
            .catch((error) => {
                console.error("error", error);
                alert("Invalid Meeting Id");
                window.location.href = "/";
                return;
            });
        if (result.roomId === meetingId) {
            document.getElementById("meetingid").value = meetingId;
            document.getElementById("joinPage").style.display = "none";
            document.getElementById("gridPpage").style.display = "flex";
            toggleControls();
            startMeeting(token, meetingId, joinMeetingName, joinMeetingUser);
        }
    } else {
        await fetch(AUTH_URL + "/validatemeeting/" + meetingId, {
            method: "POST",
            headers: {
                Authorization: token,
                "Content-Type": "application/json",
            },
        })
            .then(async (result) => {
                const { meetingId } = await result.json();
                console.log(meetingId);
                if (meetingId == undefined) {
                    return alert("Invalid Meeting ID ");
                } else {
                    document.getElementById("meetingid").value = meetingId;
                    document.getElementById("joinPage").style.display = "none";
                    document.getElementById("gridPpage").style.display = "flex";
                    toggleControls();
                    startMeeting(
                        token,
                        meetingId,
                        joinMeetingName,
                        joinMeetingUser
                    );
                }
            })
            .catch(async (e) => {
                alert("Meeting ID Invalid", await e);
                window.location.href = "/";
                return;
            });
    }
}

function addParticipantToList({ id, displayName }) {
    let participantTemplate = document.getElementById("name-label");

    //icon
    // let colIcon = document.createElement("div");
    // colIcon.className = "col-2";
    // colIcon.innerHTML = "Icon";
    // participantTemplate.appendChild(colIcon);

    //name
    let content = `${displayName}`;

    participantTemplate.innerHTML = content;
    // participants.push({ id, displayName });

    console.log(participants);
    if (displayName == "You") {
        localparticipantsList.appendChild(participantTemplate);
    } else {
        participantsList.appendChild(participantTemplate);
        participantsList.appendChild(document.createElement("br"));
    }
}

function createLocalParticipant() {
    totalParticipants++;
    let type = "local";
    let name = "you";
    let id = null;
    let mic_state = meeting.localParticipant.micOn;
    localParticipant = createVideoElement(
        meeting.localParticipant.id,
        type,
        name,
        id,
        mic_state
    );
    let audio = document.getElementById(`a-${meeting.localParticipant.id}`);
    if (!audio) {
        localParticipantAudio = createAudioElement(meeting.localParticipant.id);
    }

    // console.log("localPartcipant.id : ", localParticipant.className);
    // console.log("meeting.localPartcipant.id : ", meeting.localParticipant.id);
    videoContainer.appendChild(localParticipant);
}

async function startMeeting(token, meetingId, name, joinMeetingUser) {
    if (joinPageVideoStream !== null) {
        const tracks = joinPageVideoStream.getTracks();
        tracks.forEach((track) => {
            track.stop();
        });
        joinPageVideoStream = null;
        joinPageWebcam.srcObject = null;
    }

    window.VideoSDK.off("device-changed", deviceChangeEventListener);

    // Meeting config
    window.VideoSDK.config(token);
    let customVideoTrack, customAudioTrack;

    if (webCamEnable) {
        customVideoTrack = await window.VideoSDK.createCameraVideoTrack({
            cameraId: cameraDeviceDropDown.value
                ? cameraDeviceDropDown.value
                : undefined,
            optimizationMode: "motion",
            multiStream: false,
        });
    }

    if (micEnable) {
        customAudioTrack = await window.VideoSDK.createMicrophoneAudioTrack({
            microphoneId: microphoneDeviceDropDown.value
                ? microphoneDeviceDropDown.value
                : undefined,
            encoderConfig: "high_quality",
            noiseConfig: {
                noiseSuppresion: true,
                echoCancellation: true,
                autoGainControl: true,
            },
        });
    }
    console.log("id" + joinMeetingUser);
    // Meeting Init
    let participant_Id = document.getElementById("joinUserId").value || "";
    meeting = window.VideoSDK.initMeeting({
        meetingId: meetingId, // required
        name: name, // required
        participantId: "JD" + participant_Id,
        micEnabled: micEnable, // optional, default: true
        webcamEnabled: webCamEnable, // optional, default: true
        maxResolution: "hd", // optional, default: "hd"
        customCameraVideoTrack: customVideoTrack,
        customMicrophoneAudioTrack: customAudioTrack,
        // moreOptionsEnabled:true,
        // whiteboardEnabled: true,
        // permissions: {
        //     drawOnWhiteboard: true,
        //     toggleWhiteboard: true,
        // }
    });

    participants = meeting.participants;
    console.log("meeting obj : ", meeting, meeting.participantId);
    console.log("meeting obj : ", participants);
    // Meeting Join
    meeting.join();

    //create Local Participant
    createLocalParticipant();

    //add yourself in participant list
    if (totalParticipants != 0)
        addParticipantToList({
            id: meeting.localParticipant.id,
            displayName: "You",
        });

    // Setting local participant stream
    meeting.localParticipant.on("stream-enabled", (stream) => {
        setTrack(
            stream,
            localParticipantAudio,
            meeting.localParticipant,
            (isLocal = true),
            (share = false),
            (speaker = false)
        );
        console.log("webcam used : ", meeting.selectedCameraDevice);
        console.log("microphone used : ", meeting.selectedMicrophoneDevice);
    });

    meeting.localParticipant.on("stream-disabled", (stream) => {
        console.log("stream disabled:", stream);
        if (stream.kind == "video") {
            console.log("video stream disabled");
            videoCamOn.style.display = "none";
            videoCamOff.style.display = "inline-block";
        }
        if (stream.kind == "audio") {
            console.log("audio stream disabled");
            micOn.style.display = "none";
            micOff.style.display = "inline-block";
            let message = "audio";
            meeting.pubSub
                .publish("CHAT", message, { persist: true })
                .then((res) => console.log(`response of publish : ${res}`))
                .catch((err) => console.log(`error of publish : ${err}`));
            meeting.sendChatMessage(JSON.stringify({ type: "chat", message }));
        }
        console.log("webcam used : ", meeting.selectedCameraDevice);
        console.log("microphone used : ", meeting.selectedMicrophoneDevice);
    });

    meeting.on("meeting-joined", () => {
        meeting.pubSub.subscribe("CHAT", (data) => {
            let { message, senderId, senderName, timestamp } = data;
            console.log("chat enter");
            const chatTemplate = `
          <div style="margin-bottom: 10px; ${
              meeting.localParticipant.id == senderId && "text-align : right"
          }">
            <span style="font-size:12px;">${senderName}</span>
            <div style="margin-top:5px">
              <span style="background:${
                  meeting.localParticipant.id == senderId ? "grey" : "crimson"
              };color:white;padding:5px;border-radius:8px">${message}<span>
            </div>
          </div>
          `;
            if (
                meeting.localParticipant.id !== senderId &&
                message !== "handoff" &&
                message !== "audio"
            ) {
                contentRaiseHand.style.display = "inline-block";
                contentRaiseHand.innerHTML = "";
                contentRaiseHand.insertAdjacentHTML("beforeend", chatTemplate);
                // let participant_m = meeting.participants.get(`${senderId}`);
                // console.log("meeti_msg:",participant_m,participant_m.micOn);
                let hand = document.getElementById(`h-${senderId}`);
                hand.style.display = "block";
                hand.innerHTML = "<i class='fa fa-hand-paper-o raise'></i>";
            }
            if (message == "handoff") {
                let hand = document.getElementById(`h-${senderId}`);
                hand.style.display = "none";
            }
            if (
                meeting.localParticipant.id !== senderId &&
                message == "audio"
            ) {
                const elements = document.getElementsByClassName(senderId);
                for (let i = 0; i < elements.length; i++) {
                    elements[i].classList.add("btn-danger");
                    elements[i].classList.remove("btn-primary");
                    elements[i].innerHTML = "";
                    elements[i].innerHTML =
                        "<i class='bx bx-microphone-off'></i>";
                }
            }
            $("#contentRaiseHand").show();
            setTimeout(function () {
                $("#contentRaiseHand").hide();
            }, 3000);
        });
    });

    meeting.on("meeting-left", () => {
        window.location.reload();
        document.getElementById("join-page").style.display = "flex";
    });
    let mics = {};

    // Other participants
    meeting.on("participant-joined", (participant) => {
        totalParticipants++;
        let type = "other";
        console.log("parti");
        console.log(
            "parti" + participant,
            participant.displayName,
            participant.whiteboardEnabled
        );
        console.log("Participant ID:", participant.id);
        let name = participant.displayName;
        let mic_state = participant.micOn;

        mics[participant.id] = mic_state;
        console.log("mic_state:", mic_state);
        let videoElement = createVideoElement(
            participant.id,
            type,
            name,
            mic_state
        );

        console.log("Video Element Created");
        let resizeObserver = new ResizeObserver(() => {
            participant.setViewPort(
                videoElement.offsetWidth,
                videoElement.offsetHeight
            );
        });
        resizeObserver.observe(videoElement);
        // let audio = document.getElementById(`a-${meeting.localParticipant.id}`);
        let audioElement = createAudioElement(meeting.localParticipant.id);

        remoteParticipantId = participant.id;
        remoteParticipantName = participant.displayName;

        let parent_div = document.getElementById("participantsList");
        parent_div.appendChild(videoElement);
        console.log("Video Element Appended");
        console.log("participant.stream", participant.id, participant.stream);

        participant.on("stream-enabled", (stream) => {
            setTrack(
                stream,
                audioElement,
                participant,
                (isLocal = false),
                (share = false),
                (speaker = false)
            );
        });
        videoContainer.appendChild(audioElement);

        // addParticipantToList(participant);
        setAudioOutputDevice(playBackDeviceDropDown.value);
    });
    console.log("mics:", mics);
    // participants left
    meeting.on("participant-left", (participant) => {
        totalParticipants--;
        let vElements = document.getElementsByClassName(`v-${participant.id}`);
        console.log(vElements);
        if (vElements.length > 0) {
            let vElement = vElements[0];
            vElement.parentNode.removeChild(vElement);
        } else {
            console.error(`No elements found with class v-${participant.id}`);
        }

        let aElement = document.getElementById(`a-${participant.id}`);
        aElement.parentNode.removeChild(aElement);
        //remove it from participant list participantId;
        document.getElementById(`p-${participant.id}`).remove();
    });

    //recording events
    meeting.on("recording-started", () => {
        console.log("RECORDING STARTED EVENT");
        btnStartRecording.style.display = "none";
        btnStopRecording.style.display = "inline-block";
    });
    meeting.on("recording-stopped", () => {
        console.log("RECORDING STOPPED EVENT");
        btnStartRecording.style.display = "inline-block";
        btnStopRecording.style.display = "none";
    });

    meeting.on("presenter-changed", (presenterId) => {
        // let videoElm = document.getElementById(`v-${participant.id}`);

        if (presenterId) {
            console.log(presenterId);
            console.log("presenter changes if");
            //videoScreenShare.style.display = "inline-block";
        } else {
            console.log("presenter changes else");

            btnScreenShare.style.color = "white";
            screenShareOn = false;

            console.log(localParticipantAudio, meeting.localParticipant);
            const streams = meeting.localParticipant.streams;

            // Check if the streams object has any streams (modify according to your actual streams structure)
            if (streams && streams.size > 0) {
                // Iterate over the streams Map
                for (let [key, stream] of streams.entries()) {
                    if (stream) {
                        console.log(
                            `Stream with key ${key} is enabled:`,
                            stream
                        );

                        // Trigger setTrack with the stream
                        console.log(
                            stream,
                            localParticipantAudio,
                            meeting.localParticipant
                        );
                        setTrack(
                            stream,
                            localParticipantAudio,
                            meeting.localParticipant,
                            (isLocal = true),
                            (share = true),
                            (speaker = false)
                        );
                    } else {
                        console.log(`Stream with key ${key} is not found.`);
                    }
                }
            } else {
                console.log("No streams found for the local participant.");
            }

            console.log(`screen share on : ${screenShareOn}`);
        }
    });

    meeting.on("speaker-changed", (activeSpeakerId) => {
        // let videoElm = document.getElementById(`v-${participant.id}`);

        if (!activeSpeakerId) {
            console.log(activeSpeakerId);
            console.log("active changes if");
            console.log(
                localParticipantAudio,
                meeting.localParticipant.streams
            );
            //videoScreenShare.style.display = "inline-block";
        } else {
            console.log("presenter changes else");

            btnScreenShare.style.color = "white";
            screenShareOn = false;

            console.log(localParticipantAudio, meeting.activeSpeakerId);
            const streams = meeting.localParticipant.streams;

            // Check if the streams object has any streams (modify according to your actual streams structure)
            if (streams && streams.size > 0) {
                // Iterate over the streams Map
                for (let [key, stream] of streams.entries()) {
                    if (stream) {
                        console.log(
                            `Stream with key ${key} is enabled:`,
                            stream
                        );

                        // Trigger setTrack with the stream
                        console.log(
                            stream,
                            localParticipantAudio,
                            meeting.localParticipant
                        );
                        if (activeSpeakerId == meeting.localParticipant.id) {
                            console.log("is local");
                            setTrack(
                                stream,
                                localParticipantAudio,
                                meeting.localParticipant,
                                (isLocal = true),
                                (share = false),
                                (speaker = true)
                            );
                        } else {
                            console.log("is not local");
                            var participant = participants.get(activeSpeakerId);
                            console.log("participant:", participant);
                            setTrack(
                                stream,
                                localParticipantAudio,
                                participant,
                                (isLocal = false),
                                (share = false),
                                (speaker = true)
                            );
                        }
                    } else {
                        console.log(`Stream with key ${key} is not found.`);
                    }
                }
            } else {
                console.log("No streams found for the local participant.");
            }
        }
    });

    //add DOM Events
    addDomEvents();
}

// joinMeeting();
async function joinMeeting(newMeeting) {
    // get Token
    tokenGeneration();

    let joinMeetingName = document.getElementById("joinUserName").value || "";
    let joinMeetingUser = document.getElementById("joinUserId").value || "";
    let meetingId = document.getElementById("joinMeetingId").value || "";
    if (!meetingId && !newMeeting) {
        return alert("Please Provide a meetingId");
    }

    if (!newMeeting) {
        validateMeeting(meetingId, joinMeetingName, joinMeetingUser);
    }

    //create New Meeting
    //get new meeting if new meeting requested;
    if (newMeeting && TOKEN != "") {
        const url = `${API_BASE_URL}/v2/rooms`;
        const options = {
            method: "POST",
            headers: {
                Authorization: token,
                "Content-Type": "application/json",
            },
        };

        const { roomId } = await fetch(url, options)
            .then((response) => response.json())
            .catch((error) => alert("error", error));

        if (roomId) {
            document.getElementById("meetingid").value = roomId;
            document.getElementById("joinPage").style.display = "none";
            document.getElementById("gridPpage").style.display = "flex";
            toggleControls();
            startMeeting(token, roomId, joinMeetingName, joinMeetingUser);
        }
    } else if (newMeeting && TOKEN == "") {
        const options = {
            method: "POST",
            headers: {
                Authorization: token,
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ token }),
        };

        meetingId = await fetch(AUTH_URL + "/createMeeting", options).then(
            async (result) => {
                console.log("result of create meeting : ", result);
                const { meetingId } = await result.json();
                console.log("NEW MEETING meetingId", meetingId);
                return meetingId;
            }
        );
        if (meetingId) {
            document.getElementById("meetingid").value = meetingId;
            document.getElementById("joinPage").style.display = "none";
            document.getElementById("gridPpage").style.display = "flex";

            toggleControls();
            startMeeting(token, meetingId, joinMeetingName, joinMeetingUser);
        }
    }
}
document.getElementById("Whiteboard").addEventListener("click", function () {
    console.log("Whiteboard");
    enableWhiteboard();
});
function enableWhiteboard() {
    if (meeting && meeting.whiteboardEnabled) {
        try {
            const whiteboard = meeting.whiteboard; // Assuming 'whiteboard' is the correct way to access it.
            if (whiteboard) {
                console.log("Whiteboard instance:", whiteboard);
                attachWhiteboardToDOM(whiteboard);
                setupDrawingTools(whiteboard);
            } else {
                console.error("Failed to get whiteboard instance");
            }
        } catch (error) {
            console.error("Error enabling whiteboard:", error);
        }
    } else {
        console.error(
            "Meeting object does not support whiteboard functionality"
        );
    }
}

function attachWhiteboardToDOM(whiteboard) {
    const container = document.getElementById("whiteboard-container");
    document.getElementById("toolbar").style.display = "block";
    whiteboard.attach(container);
}

function setupDrawingTools(whiteboard) {
    const penTool = document.getElementById("pen-tool");
    const eraserTool = document.getElementById("eraser-tool");

    penTool.addEventListener("click", () => {
        whiteboard.setTool("pen");
    });

    eraserTool.addEventListener("click", () => {
        whiteboard.setTool("eraser");
    });
}

// creating video element
function createVideoElement(pId, type, name, mic) {
    let division;

    console.log(type);
    if (type == "local") {
        division = document.createElement("div");
        division.setAttribute("id", "video-frame-container");
        division.className = `v-${pId}`;
        division.setAttribute("data_id", `${pId}`);
        division.classList.add("col-md-12");
        division.classList.add("h-100");
        let videoElement = document.createElement("video");
        videoElement.classList.add("video-frame");
        videoElement.classList.add("bg-dark");
        videoElement.classList.add("main_frame");
        videoElement.setAttribute("id", `v-${pId}`);
        videoElement.setAttribute("playsinline", true);
        videoElement.setAttribute("height", "100%");
        videoElement.setAttribute("width", "100%");
        division.appendChild(videoElement);
    } else {
        let userid = document.getElementById("joinUserId").value || "";
        let exist = document.getElementById("div" + userid);
        console.log(userid, exist);

        let videoElements = document.getElementsByClassName("video" + userid);

        // Convert the HTMLCollection to an array and remove each element
        // Array.from(videoElements).forEach(element => element.remove());

        division = document.createElement("div");
        division.setAttribute("id", "video-frame-container");
        division.className = `v-${pId}`;
        division.classList.add("col-md-3");
        division.classList.add("img-container");
        division.classList.add("video" + userid);
        let videoElement = document.createElement("video");
        videoElement.classList.add("video-frame");
        videoElement.classList.add("member_video");
        videoElement.setAttribute("id", `v-${pId}`);
        videoElement.setAttribute("playsinline", true);
        videoElement.setAttribute("height", "100%");
        videoElement.setAttribute("width", "100%");
        videoElement.setAttribute("data-id", `${pId}`);
        let overlayMember = document.createElement("div");
        overlayMember.setAttribute("id", "div" + userid);
        overlayMember.className = "overlay_member";

        // Create row div
        let handCol = document.createElement("div");
        handCol.setAttribute("id", `h-${pId}`);
        handCol.setAttribute("style", "display:none;margin-top:10%;");
        handCol.classList.add("rise_hand");
        // handCol.classList.add();

        let rowDiv = document.createElement("div");
        rowDiv.className = "row";
        rowDiv.setAttribute("style", "margin-top:35%;");

        // Create column for name label
        let nameCol = document.createElement("div");
        nameCol.className = "col-md-9";
        nameCol.style.paddingTop = "15px";

        // Create name label
        let nameLabel = document.createElement("span");
        nameLabel.className = "name-label";
        nameLabel.classList.add(`name-${pId}`);
        nameLabel.textContent = name;

        // Append name label to name column
        nameCol.appendChild(nameLabel);

        // Create column for mute button
        let buttonCol = document.createElement("div");
        buttonCol.className = "col-md-3 ps-2 align-self-end";

        // Create mute button
        let muteButton = document.createElement("button");
        muteButton.setAttribute("id", "member_mute_btn");
        muteButton.setAttribute("data-member-id", `${pId}`);
        // muteButton.className = "btn member_mute_btn";
        muteButton.className = "btn member_mute_btn";
        muteButton.classList.add(`${pId}`);

        console.log("meeting:", meeting.participants, pId);
        let member = meeting.participants.get(`${pId}`);
        console.log("member:", member, member.micOn, mic, micEnable);
        if (mic) {
            muteButton.classList.add("btn-primary");
            muteButton.innerHTML = "<i class='bx bx-microphone'></i>";
        } else {
            muteButton.classList.add("btn-danger");
            muteButton.innerHTML = "<i class='bx bx-microphone-off'></i>";
        }

        // muteButton.setAttribute("onclick","toggleMemberMic()");

        // Append mute button to button column
        buttonCol.appendChild(muteButton);

        // Append columns to row div
        rowDiv.appendChild(nameCol);
        rowDiv.appendChild(buttonCol);

        // Append row div to overlay member div
        overlayMember.appendChild(handCol);
        overlayMember.appendChild(rowDiv);

        division.appendChild(videoElement);
        division.appendChild(overlayMember);
    }

    return division;
}
// $('.member_video').on("click",function(){

// });
// creating audio element
function createAudioElement(pId) {
    let audio = document.getElementById(`a-${pId}`);
    // let member1 = meeting.participants.get(`${pId}`);
    // if(member1){
    //   console.log("member_mic:",member1.micOn);
    // }

    // let member_mic = member1.micOn;
    // console.log("member_mic:",member1);
    if (!audio) {
        console.log("audio_element " + `${pId}` + "not exist");
        let audioElement = document.createElement("audio");
        audioElement.setAttribute("autoPlay", "false");
        audioElement.setAttribute("playsInline", "true");
        audioElement.setAttribute("controls", "false");
        audioElement.setAttribute("id", `a-${pId}`);
        return audioElement;
    } else {
        console.log("audio_element " + `${pId}` + "exist");
    }
}

//setting up tracks

function setTrack(stream, audioElement, participant, isLocal, share, speaker) {
    console.log("its on setTracck:", share, stream.kind);
    console.log(" speaker:", speaker);
    if (
        stream.kind !== "audio" &&
        stream.kind == "video" &&
        speaker !== "true" &&
        speaker !== true
    ) {
        console.log("setTrack called...");
        if (isLocal) {
            videoCamOff.style.display = "none";
            videoCamOn.style.display = "inline-block";
        }
        console.log(stream);
        const mediaStream = new MediaStream();
        console.log("before", mediaStream.active, mediaStream);
        if (stream == false) {
            const videoTracks = meeting.localParticipant.videoTracks;
            videoTracks.forEach((track) => {
                mediaStream.addTrack(track.mediaStreamTrack);
            });
        } else {
            mediaStream.addTrack(stream.track);
        }
        console.log("after", mediaStream.active, mediaStream);
        console.log("share:" + share);
        if (!isLocal) {
            console.log("after", mediaStream.active, mediaStream);
            if (!mediaStream) {
                console.log("after", mediaStream.active, mediaStream);
                document
                    .getElementById(`v-${participant.id}`)
                    .classList.add("bg-dark");
            } else {
                document
                    .getElementById(`v-${participant.id}`)
                    .classList.remove("bg-dark");
            }
        }

        if (share == "true" || share == true) {
            const videostream = document.getElementById(`v-${participant.id}`);
            const mainFrame = document.querySelector(".main_frame");
            const mainNameLabel = document.getElementById("name-label");
            const participant_id = mainNameLabel.getAttribute("data-swap-id");
            const participant_name =
                mainNameLabel.getAttribute("data-swap-name");
            console.log("videostream:", videostream);
            // console.log("participantNameElement:", participantNameElement);
            console.log("mainFrame:", mainFrame);
            console.log("mainNameLabel:", mainNameLabel);
            if (!videostream) {
                console.error(
                    "Video stream element not found. ID used:",
                    `v-${participant.id}`
                );
                return;
            }

            if (!mainFrame) {
                console.error(
                    "Main frame video element not found. Selector used:",
                    ".main_frame video"
                );
                return;
            }
            if (!mainNameLabel) {
                console.error(
                    "Main name label element not found. ID used:",
                    "name-label"
                );
                return;
            }
            const currentMainStream = videostream.srcObject;
            const currentMainName = mainNameLabel.innerText;
            const participant_stream = meeting.participants.get(participant_id);
            // Switch streams
            // console.log("streams:",currentMainStream,mediaStream);
            console.log("local:", isLocal);
            mainFrame.srcObject = currentMainStream;
            videostream.srcObject = mediaStream;

            console.log(currentMainName);
            // Switch names
            console.log(participant_id, participant.id);
            if (participant_id !== null) {
                const participantNameElement = document.querySelector(
                    ".name-" + participant_id
                );
                console.log(currentMainName);
                participantNameElement.textContent = participant_name;
                mainNameLabel.innerText = "You";
            }

            mainFrame
                .play()
                .catch((error) =>
                    console.error("videoElem.current.play() failed", error)
                );
            videostream
                .play()
                .catch((error) =>
                    console.error("videoElem.current.play() failed", error)
                );
        }
        if (share == "false" || share == false) {
            console.log("else:" + share);
            let videoElm = document.getElementById(`v-${participant.id}`);
            videoElm.srcObject = mediaStream;
            videoElm
                .play()
                .catch((error) =>
                    console.error("videoElem.current.play() failed", error)
                );
            participant.setViewPort(
                videoElm.offsetWidth,
                videoElm.offsetHeight
            );
        }

        btnScreenShare.classList.add("btn_link");
        btnScreenShare.classList.remove("alert-secondary");
    }
    if (stream.kind == "audio" && speaker !== "true" && speaker !== true) {
        console.log("strem kind audio");

        if (isLocal) {
            console.log("strem kind audio if");
            micOff.style.display = "none";
            micOn.style.display = "inline-block";
            return;
        }
        const elements = document.getElementsByClassName(remoteParticipantId);
        for (let i = 0; i < elements.length; i++) {
            elements[i].classList.remove("btn-danger");
            elements[i].classList.add("btn-primary");
            elements[i].innerHTML = "";
            elements[i].innerHTML = "<i class='bx bx-microphone'></i>";
        }
        console.log("strem kind audio other", stream.track, isLocal);

        const mediaStream = new MediaStream();
        mediaStream.addTrack(stream.track);
        if (!stream.track) {
            console.log("audio is empty");
        }
        audioElement.srcObject = mediaStream;
        audioElement
            .play()
            .catch((error) => console.error("audioElem.play() failed", error));
    }
    if (speaker == "true" || (speaker == true && stream.kind !== "share")) {
        console.log("speaker speaker");
        if (!isLocal) {
            if (participant.id == meeting.localParticipant.id) {
                console.log("local speak");
                const mediaStream = new MediaStream();
                mediaStream.addTrack(stream.track);
                let videostreme = document.getElementById(
                    `v-${meeting.localParticipant.id}`
                );
                console.log(
                    "local_part:" + `v-${meeting.localParticipant.id}`,
                    `v-${participant.id}`,
                    videostreme
                );
                videostreme.srcObject = mediaStream;
                videostreme
                    .play()
                    .catch((error) =>
                        console.error("videoElem.current.play() failed", error)
                    );
                // videoScreenShare.style.display = "inline-block";
                btnScreenShare.classList.remove("btn_link");
                btnScreenShare.classList.add("alert-secondary");
            } else {
                console.log("!local speak");
                const mediaStream = new MediaStream();
                mediaStream.addTrack(stream.track);
                const videostream = document.getElementById(
                    `v-${participant.id}`
                );
                const participantNameElement = document.querySelector(
                    `.name-${participant.id}`
                );
                const mainFrame = document.querySelector(".main_frame");
                const mainNameLabel = document.getElementById("name-label");
                console.log("speakvideostream:", videostream);
                console.log(
                    "speakparticipantNameElement:",
                    participantNameElement
                );
                console.log("speakmainFrame:", mainFrame);
                console.log("speakmainNameLabel:", mainNameLabel);
                mainNameLabel.setAttribute("data-swap-id", `${participant.id}`);
                if (!videostream) {
                    console.error(
                        "speak Video stream element not found. ID used:",
                        `v-${participant.id}`
                    );
                    return;
                }
                if (!participantNameElement) {
                    console.error(
                        "speak Participant name element not found. Class used:",
                        `.name-${participant.id}`
                    );
                    return;
                }
                if (!mainFrame) {
                    console.error(
                        "speak Main frame video element not found. Selector used:",
                        ".main_frame video"
                    );
                    return;
                }
                if (!mainNameLabel) {
                    console.error(
                        "speak Main name label element not found. ID used:",
                        "name-label"
                    );
                    return;
                }
                const currentMainStream = mainFrame.srcObject;
                const currentMainName = mainNameLabel.innerText;

                // Switch streams
                videostream.srcObject = currentMainStream;
                mainFrame.srcObject = mediaStream;

                // Switch names
                mainNameLabel.innerText = participantNameElement.textContent;
                mainNameLabel.setAttribute(
                    "data-swap-name",
                    participantNameElement.textContent
                );
                participantNameElement.textContent = "You";
                mainFrame
                    .play()
                    .catch((error) =>
                        console.error("videoElem.current.play() failed", error)
                    );
                videostream
                    .play()
                    .catch((error) =>
                        console.error("videoElem.current.play() failed", error)
                    );
                // videoScreenShare.style.display = "inline-block";
                btnScreenShare.style.color = "grey";
            }
        }
        if (isLocal) {
            console.log("local speak");
            const mediaStream = new MediaStream();
            mediaStream.addTrack(stream.track);
            let videostreme = document.getElementById(
                `v-${meeting.localParticipant.id}`
            );
            console.log(
                "local_part:" + `v-${meeting.localParticipant.id}`,
                `v-${participant.id}`,
                videostreme
            );
            videostreme.srcObject = mediaStream;
            videostreme
                .play()
                .catch((error) =>
                    console.error("videoElem.current.play() failed", error)
                );
            // videoScreenShare.style.display = "inline-block";
            btnScreenShare.classList.remove("btn_link");
            btnScreenShare.classList.add("alert-secondary");
        }
    }
    if (stream.kind == "share" && !isLocal) {
        screenShareOn = true;
        const mediaStream = new MediaStream();
        mediaStream.addTrack(stream.track);
        const videostream = document.getElementById(`v-${participant.id}`);
        const participantNameElement = document.querySelector(
            `.name-${participant.id}`
        );
        const mainFrame = document.querySelector(".main_frame");
        const mainNameLabel = document.getElementById("name-label");
        console.log("videostream:", videostream);
        console.log("participantNameElement:", participantNameElement);
        console.log("mainFrame:", mainFrame);
        console.log("mainNameLabel:", mainNameLabel);
        mainNameLabel.setAttribute("data-swap-id", `${participant.id}`);
        if (!videostream) {
            console.error(
                "Video stream element not found. ID used:",
                `v-${participant.id}`
            );
            return;
        }
        if (!participantNameElement) {
            console.error(
                "Participant name element not found. Class used:",
                `.name-${participant.id}`
            );
            return;
        }
        if (!mainFrame) {
            console.error(
                "Main frame video element not found. Selector used:",
                ".main_frame video"
            );
            return;
        }
        if (!mainNameLabel) {
            console.error(
                "Main name label element not found. ID used:",
                "name-label"
            );
            return;
        }
        const currentMainStream = mainFrame.srcObject;
        const currentMainName = mainNameLabel.innerText;

        // Switch streams
        videostream.srcObject = currentMainStream;
        mainFrame.srcObject = mediaStream;

        // Switch names
        mainNameLabel.innerText = participantNameElement.textContent;
        mainNameLabel.setAttribute(
            "data-swap-name",
            participantNameElement.textContent
        );
        participantNameElement.textContent = "You";
        mainFrame
            .play()
            .catch((error) =>
                console.error("videoElem.current.play() failed", error)
            );
        videostream
            .play()
            .catch((error) =>
                console.error("videoElem.current.play() failed", error)
            );
        // videoScreenShare.style.display = "inline-block";
        btnScreenShare.style.color = "grey";
    }
    if (stream.kind == "share" && isLocal) {
        screenShareOn = true;
        const mediaStream = new MediaStream();
        mediaStream.addTrack(stream.track);
        let videostreme = document.getElementById(
            `v-${meeting.localParticipant.id}`
        );
        console.log(
            "local_part:" + `v-${meeting.localParticipant.id}`,
            `v-${participant.id}`,
            videostreme
        );
        videostreme.srcObject = mediaStream;
        videostreme
            .play()
            .catch((error) =>
                console.error("videoElem.current.play() failed", error)
            );
        // videoScreenShare.style.display = "inline-block";
        btnScreenShare.classList.remove("btn_link");
        btnScreenShare.classList.add("alert-secondary");
    }
}

//add button events once meeting is created
function addDomEvents() {
    // mic button event listener
    micOn.addEventListener("click", () => {
        console.log("Mic-on pressed");
        // $("#main-pg-cam-off").click();
        meeting.muteMic();
    });

    micOff.addEventListener("click", async () => {
        console.log("Mic-f pressed");
        if (microphonePermissionAllowed) {
            console.log("audio permission allowed");
            meeting.enableWebcam();
            meeting.unmuteMic();
        } else {
            console.log("Audio : Permission not granted");
        }
    });

    videoCamOn.addEventListener("click", async () => {
        meeting.disableWebcam();
    });

    videoCamOff.addEventListener("click", async () => {
        console.log("its on event");
        if (cameraPermissionAllowed) {
            console.log("Camera : Permission granted");
            meeting.enableWebcam();
        } else {
            console.log("Camera : Permission not granted");
        }
    });
    function triggerVideoCamOffClick() {
        const videoCamOff = document.getElementById("videoCamOff");
        if (videoCamOff) {
            videoCamOff.click();
        } else {
            console.log("Button element not found.");
        }
    }
    // screen share button event listener
    btnScreenShare.addEventListener("click", async () => {
        if (btnScreenShare.style.color == "grey") {
            meeting.disableScreenShare();
        } else {
            meeting.enableScreenShare();
        }
    });

    //raise hand event
    $("#btnRaiseHand").click(function () {
        let participantId = localParticipant.getAttribute("data_id");
        console.log(participantId, meeting.localParticipant.id);
        if (participantId == meeting.localParticipant.id) {
            contentRaiseHand.style.display = "inline-block";
            contentRaiseHand.innerHTML = "You Have Raised Your Hand";
            $("#btnRaiseHand").hide();
            $("#btnUnRaiseHand").show();

            // console.log(participant);
            let message = `${meeting.localParticipant.displayName} was Raised Hand`;
            meeting.pubSub
                .publish("CHAT", message, { persist: true })
                .then((res) => console.log(`response of publish : ${res}`))
                .catch((err) => console.log(`error of publish : ${err}`));
            meeting.sendChatMessage(JSON.stringify({ type: "chat", message }));
        } else {
            console.log(remoteParticipantName);
            let hand = document.getElementById(`h-${participantId}`);
            hand.innerHTML = "<i class='bx bxs-hand text-warning'></i>";
            contentRaiseHand.innerHTML = `<b>${remoteParticipantId}</b> Have Raised Their Hand`;
        }

        $("#contentRaiseHand").show();
        setTimeout(function () {
            $("#contentRaiseHand").hide();
        }, 2000);
    });
    $("#btnUnRaiseHand").click(function () {
        let participantId = localParticipant.getAttribute("data_id");
        console.log(participantId, meeting.localParticipant.id);
        if (participantId == meeting.localParticipant.id) {
            let message = "handoff";
            meeting.pubSub
                .publish("CHAT", message, { persist: true })
                .then((res) => console.log(`response of publish : ${res}`))
                .catch((err) => console.log(`error of publish : ${err}`));
            meeting.sendChatMessage(JSON.stringify({ type: "chat", message }));
            $("#btnUnRaiseHand").hide();
            $("#btnRaiseHand").show();
        } else {
            console.log(remoteParticipantName);
            let hand = document.getElementById(`h-${participantId}`);
            hand.innerHTML = "";
        }
    });

    //send chat message button
    // btnSend.addEventListener("click", async () => {
    //   const message = document.getElementById("txtChat").value;
    //   console.log("publish : ", message);
    //   document.getElementById("txtChat").value = "";
    //   meeting.pubSub
    //     .publish("CHAT", message, { persist: true })
    //     .then((res) => console.log(`response of publish : ${res}`))
    //     .catch((err) => console.log(`error of publish : ${err}`));
    //   // meeting.sendChatMessage(JSON.stringify({ type: "chat", message }));
    // });

    // //leave Meeting Button
    $("#leaveCall").click(async () => {
        participants = new Map(meeting.participants);
        meeting.leave();
    });

    //end meeting button
    $("#endCall").click(async () => {
        // Call the meeting.end() function
        meeting.end();

        // Redirect the user using JavaScript
        window.location.href = "{{ route('virtualcontroller.index') }}";
    });

    // //startRecording
    btnStartRecording.addEventListener("click", async () => {
        console.log("btnRecording is clicked");
        meeting.startRecording();
    });
    // //Stop Recording
    btnStopRecording.addEventListener("click", async () => {
        meeting.stopRecording();
    });
}

async function toggleMic() {
    console.log("micEnable", micEnable);
    if (micEnable) {
        document.getElementById("micButton").style.backgroundColor = "red";
        document.getElementById("muteMic").style.display = "inline-block";
        document.getElementById("unmuteMic").style.display = "none";
        micEnable = false;
        meeting.localParticipant.micOn = false;
    } else {
        enableMic();
        meeting.localParticipant.micOn = true;
    }
}
// async function toggleMemberMic() {
//   console.log("Toggle member microphone state");

//   let memberMuteBtn = document.getElementById("member_mute_btn");
//   let muteIcon = memberMuteBtn.querySelector('i');
//   let participantIdToMute = memberMuteBtn.getAttribute("data-member-id");

//   const participant = participants.get(participantIdToMute);
//   console.log("participant"+participant.id);
//   if (!participant) {
//     console.error(`Participant with ID ${participantIdToMute} not found.`);
//     return;
//   }

//   try {
//     // Assuming 'isMicEnabled' is a property indicating mic state (true/false)
//     const micEnabled = participant.micOn;
//       console.log("state:"+micEnabled)
//     if (micEnabled == true) {
//       // Disable mic
//       memberMuteBtn.classList.remove("btn-primary");
//       memberMuteBtn.classList.add("btn-danger");
//       muteIcon.classList.remove("bx-microphone");
//       muteIcon.classList.add("bx-microphone-off");
//       participant.micOn = false; // Assuming 'disableMic' is a method to disable mic
//       console.log(`Participant ${participantIdToMute} microphone disabled.`);
//     } else {
//       // Enable mic
//       memberMuteBtn.classList.add("btn-primary");
//       memberMuteBtn.classList.remove("btn-danger");
//       muteIcon.classList.add("bx-microphone");
//       muteIcon.classList.remove("bx-microphone-off");
//       participant.micOn = true; // Assuming 'enableMic' is a method to enable mic
//       console.log(`Participant ${participantIdToMute} microphone enabled.`);
//     }
//   } catch (error) {
//     console.error(`Error toggling microphone for participant ${participantIdToMute}:`, error);
//     // Handle error appropriately (e.g., show error message to user)
//   }
// }

async function toggleWebCam() {
    console.log("joinPageVideoStream", joinPageVideoStream);
    if (joinPageVideoStream) {
        joinPageWebcam.style.backgroundColor = "black";
        joinPageWebcam.srcObject = null;
        document.getElementById("camButton").style.backgroundColor = "red";
        document.getElementById("offCamera").style.display = "inline-block";
        document.getElementById("onCamera").style.display = "none";
        document.getElementById("overlay_image").style.display = "block";
        webCamEnable = false;
        const tracks = joinPageVideoStream.getTracks();
        tracks.forEach((track) => {
            track.stop();
        });
        joinPageVideoStream = null;
    } else {
        document.getElementById("overlay_image").style.display = "none";
        enableCam();
    }
}

async function enableCam() {
    if (joinPageVideoStream !== null) {
        const tracks = joinPageVideoStream.getTracks();
        tracks.forEach((track) => {
            track.stop();
        });
        joinPageVideoStream = null;
        joinPageWebcam.srcObject = null;
    }

    if (cameraPermissionAllowed) {
        let mediaStream;
        try {
            mediaStream = await window.VideoSDK.createCameraVideoTrack({
                cameraId: cameraDeviceDropDown.value
                    ? cameraDeviceDropDown.value
                    : undefined,
                optimizationMode: "motion",
                multiStream: false,
            });
        } catch (ex) {
            console.log("Exception in enableCam", ex);
        }

        if (mediaStream) {
            joinPageVideoStream = mediaStream;
            joinPageWebcam.srcObject = mediaStream;
            joinPageWebcam
                .play()
                .catch((error) =>
                    console.log("videoElem.current.play() failed", error)
                );
            document.getElementById("camButton").style.backgroundColor =
                "#DCDCDC";
            document.getElementById("offCamera").style.display = "none";
            document.getElementById("onCamera").style.display = "inline-block";
            webCamEnable = true;
        }
    }
}

async function enableMic() {
    if (microphonePermissionAllowed) {
        micEnable = true;
        document.getElementById("micButton").style.backgroundColor = "#DCDCDC";
        document.getElementById("muteMic").style.display = "none";
        document.getElementById("unmuteMic").style.display = "inline-block";
        // document.
    }
}

function copyMeetingCode() {
    copy_meeting_id.select();
    document.execCommand("copy");
}

//open participant wrapper
function openParticipantWrapper() {
    document.getElementById("participants").style.width = "350px";
    document.getElementById("gridPpage").style.marginRight = "350px";
    document.getElementById("ParticipantsCloseBtn").style.visibility =
        "visible";
    document.getElementById("totalParticipants").style.visibility = "visible";
    document.getElementById(
        "totalParticipants"
    ).innerHTML = `Participants (${totalParticipants})`;
}

function closeParticipantWrapper() {
    document.getElementById("participants").style.width = "0";
    document.getElementById("gridPpage").style.marginRight = "0";
    document.getElementById("ParticipantsCloseBtn").style.visibility = "hidden";
    document.getElementById("totalParticipants").style.visibility = "hidden";
}

function openChatWrapper() {
    document.getElementById("chatModule").style.width = "350px";
    document.getElementById("gridPpage").style.marginRight = "350px";
    document.getElementById("chatCloseBtn").style.visibility = "visible";
    document.getElementById("chatHeading").style.visibility = "visible";
    document.getElementById("btnSend").style.display = "inline-block";
}

function closeChatWrapper() {
    document.getElementById("chatModule").style.width = "0";
    document.getElementById("gridPpage").style.marginRight = "0";
    document.getElementById("chatCloseBtn").style.visibility = "hidden";
    document.getElementById("btnSend").style.display = "none";
}

function toggleControls() {
    console.log("from toggleControls");
    if (micEnable) {
        console.log("micEnable True");
        micOn.style.display = "inline-block";
        micOff.style.display = "none";
    } else {
        console.log("micEnable False");
        micOn.style.display = "none";
        micOff.style.display = "inline-block";
    }

    if (webCamEnable) {
        console.log("webCamEnable True");
        videoCamOn.style.display = "inline-block";
        videoCamOff.style.display = "none";
    } else {
        console.log("webCamEnable False");
        videoCamOn.style.display = "none";
        videoCamOff.style.display = "inline-block";
    }
}
