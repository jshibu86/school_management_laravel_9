@extends('layout::admin.master')

@section('title','subject')
@section('style')
<style>
     .select2-selection__choice__remove:empty {
            display: none;
     }
     .participants_field .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered{
        height:60px !important;
        border-radius:22.41px !important;
     }
     .participants_field .select2-container--bootstrap4 .select2-selection{
        border-radius:22.41px !important;
     }
     .profile-container {
        display: flex;
        align-items: center;
    }

    .profile-image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid white;
        margin-left: -10px;
        z-index: 1;
        object-fit: cover;
    }

    .profile-container img:first-child {
        margin-left: 0;
    }

    .profile-more {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid white;
        margin-left: -10px;
        background-color: #e0e7ff;
        color: #4a4a4a;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        z-index: 0;
    }

    .btn_link{
            background-color:#e1d5f5 !important;
            color:#673ab7 !important;
    }
  
    .nav_div .nav-pills .nav-item .nav-link.active,  .nav_div .nav-pills .show > .nav-link ,{
            background-color:#673ab7 !important;
            color:#fff !important;
    }
    .accordion-button:not(.collapsed){
        background-color: unset !important;
    }
    .accordion-button:not(.collapsed)::after {
        background-image: url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2016%2016'%20fill='%23000000'%3E%3Cpath%20fill-rule='evenodd'%20d='M1.646%204.646a.5.5%200%200%201%20.708%200L8%2010.293l5.646-5.647a.5.5%200%200%201%20.708.708l-6%206a.5.5%200%200%201-.708%200l-6-6a.5.5%200%200%201%200-.708z'/%3E%3C/svg%3E");
        transform: rotate(-180deg);
    }
    .scroll-container {
        overflow-x: auto; 
        overflow-y: hidden;
        white-space: nowrap; 
        width: 100%; 
        padding-bottom: 10px; 
    }

 
    .overlay_member_row {
        display: flex;
        flex-wrap: nowrap; 
    }

 
    .overlay_member_row .col-md-3 {
        flex: 0 0 auto;
        max-width: 25%; 
    }
    .scroll_body{
        height:550px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
   
    .scroll_body:hover::-webkit-scrollbar-thumb, .scroll-container:hover::-webkit-scrollbar-thumb{
           
           display:block;
       
    }
    .scroll_body::-webkit-scrollbar-track,.scroll-container::-webkit-scrollbar-track
    {
           
        /* -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); */
        border-radius: 10px;
        background-color: #FFF;
    }
    .scroll_body::-webkit-scrollbar,.scroll-container::-webkit-scrollbar
    { 
            
        width: 5px !important;
        height: 6px; 
        background-color: #FFF;
    }
    .scroll_body::-webkit-scrollbar-thumb, .scroll-container::-webkit-scrollbar-thumb
    {
        display:none;
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
        background-color: #673ab7;;
    }
    .input-container {
        display: flex;
        align-items: center;
        background-color: #fff;
        border-radius: 30px;
        padding: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .save-btn {
        background-color: transparent;
        border: none;
        cursor: pointer;
        outline: none;
        padding: 0 10px;
    }

    .input-field {
        flex: 1;
        border: none;
        outline: none;
        font-size: 16px;
        padding: 10px;
    }

    .input-field::placeholder {
        color: #aaa;
    }

    .save-btn {
        background-color: #007bff;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .save-btn .icon-send {
        fill: #fff;
        width: 20px;
        height: 20px;
    }

    .video-container {
        position: relative;
        width: 100%; /* Adjust the width as needed */
        height: 500px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .img-container {
        position: relative;
        overflow: hidden;
        height:170px;
    }
    .member_video{
        border-radius: 10px;
    }
    .video-image {
        width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px;
        box-sizing: border-box;
    }
    .overlay_member{
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height:100%;
        /* display: flex; */
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        padding-bottom:30px;
        box-sizing: border-box;
    }
    .rise_hand{
        font-size: 20px !important;
    }
    .raise{
        float:right;
        color:#FFF;
    }
    .timer {
        align-self: flex-start;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
    }
  
    .name-label {
       
        background-color:#AAABAD !important;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        margin-top: 20px;
       
    }

    .btn {
        align-self: flex-end;
        margin-top: 10px;
    }

    .end-call-btn {
        background-color: red;
        color: white;
    }

    .mute-btn {
        float:right !important;
        align-self: flex-end;
        color: white;
        background-color:#AAABAD !important;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        padding: 3px;
        border:none;
    }
    .member_mute_btn{
        float:right !important;
        align-self: flex-end;
      
        border-radius: 50%;
        padding: 3px;
    }

    .fullscreen-btn {
        background-color:#AAABAD !important;
        color: white;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        border: 1px solid #e5e5e5;
        padding: 3px;
    }
   
    .popover{
        border-radius: 22px;
        border:none !important;
    }
    #pop_div ul li a{
        border-radius:0.65em !important;
    }

    @media (max-width: 600px) {
        .video-container {
            width: 100%;
        }
    }

    #whiteboard-container {
        width: 100%;
        height: auto;
        border: 1px solid #ccc;
        margin-bottom: 10px;
    }
    .toolbar {
        display:none;
        margin-bottom: 10px;
    }
    .toolbar button {
        margin-right: 5px;
    }
    .overlay-image {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 10; /* Ensures the overlay image is above the video */
        /* Adjust width and height as needed */
        width: 150px;
        height: 150px;
    }
    .member_mute_btn{
        cursor:context-menu !important;
    }
</style>

@endsection
@section('body')
    <div class="x_content">
        <div class="box-header with-border mar-bottom20">

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('virtualcomunication.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
          
        </div>

        <div class="card">
            <div class="card-header ">
                <div class="row ">
                    <div class="col-md-1 border-end">
                        <i class='fa fa-video-camera text-primary pb-3 px-4' style="font-size:58px;"></i>
                    </div>
                    <div class="col-md-4 py-2">
                        <p class="fs-bold h5">{{$data->title}}</p>
                        @php
                       
                            $date = $data->meeting_date;
                            $time = $data->time;
                            
                            // Convert the date and time to a DateTime object
                            $dateTime = DateTime::createFromFormat('m/d/Y H:i', "$date $time");
                            
                            // Check if the date was correctly parsed
                            if ($dateTime) {
                                // Format the date to "June 11th, 2024"
                                $formattedDate = $dateTime->format('F jS, Y');
                                
                                // Format the time to "5:16 PM"
                                $formattedTime = $dateTime->format('g:i A');
                            
                                // Combine date and time
                                $formattedDateTime = "$formattedDate | $formattedTime";
                            
                            
                            } else {
                                $formattedDateTime = "";
                            }
                        
                        @endphp
                        <span class="opacity-50">{{@$formattedDateTime}}</span>
                    </div>
                    <div class="col-md-4 py-2">
                        <div class="d-flex gap-3">
                            <div class="profile-container">
                                @php                            
                                    $maxImagesToShow = 4;                               
                                    $totalParticipants = count($participants);                             
                                    $remainingParticipants = $totalParticipants - $maxImagesToShow;
                                @endphp
                            
                                @foreach($participants as $index => $participant)
                                    @if($index < $maxImagesToShow)
                                        @php
                                            $image = ($participant->user->images !== null) ? $participant->user->images : 'assets/images/default.jpg';
                                        @endphp
                                        <img src="{{ asset($image) }}" alt="Profile {{$index + 1}}" class="profile-image user_image">
                                    @endif
                                @endforeach
                                
                                @if($remainingParticipants > 0)
                                    <div class="profile-more">+{{ $remainingParticipants }}</div>
                                @endif
                            
                            </div>
                            <div>
                                <a href="#" class="btn rounded-pill btn_link"><i class='bx bx-link-alt'></i>|{{@$data->meeting_token}}</a>
                            </div>
                        </div>                      
                    </div>
                    <div class="col-md-3">
                        <button type="button" class="btn rounded-pill btn-light d-flex gap-5 " style="width:90%">
                            <div class="d-flex gap-3">
                                @php
                                  $user_image = ($user_info->images !== null) ? $user_info->images : 'assets/images/default.jpg';
                                @endphp
                                <img class="user-img" src="{{asset($user_image)}}" alt="user_image" name="user_image">
                                <div>
                                    <span class="fs-bold">{{$user_info->name}}</span><br>
                                    @if($data->moderator == $user_info->id)
                                     <span class="opacity-50">Moderator</span>
                                    @else
                                     <span class="opacity-50">Participant</span>
                                    @endif
                                </div>
                            </div>
                            <div>
                                <i class='bx bx-dots-vertical-rounded' style="margin-top:unset !important"></i>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body" id="joinPage">
                <div class="d-flex gap-3 my-3">
                    <div class="card p-3 w-100">
                        <div class="video-container">
                            <video class="video bg-dark" id="joinCam" style="
                            position: absolute;
                            width: 100%;
                            height: 100%;
                            border-radius: 10px;
                            transform: rotate('90');
                            object-fit: cover;
                            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.19),
                              0 6px 6px rgba(0, 0, 0, 0.23);
                          "></video>
                            @php
                              $user_image = ($user_info->images !== null) ? $user_info->images : 'assets/images/default.jpg';
                            @endphp
                          <img src="{{asset($user_image)}}" id="overlay_image"class="overlay-image main_image rounded-circle"  alt="Overlay Image">
                          <input type="hidden" id="user_image" value="{{asset($user_image)}}">
                        </div>
                        <div class="d-flex gap-3 video-content justify-content-center">
                            <button class="btn btn-danger rounded-circle p-3" id="camButton" onclick="toggleWebCam()"
                            >
                              <i class="bx bxs-video" style="color: black; font-size: 21px; display: none" id="onCamera"></i>
                              <i class="bx bxs-video-off" style="color: black; font-size: 21px; " id="offCamera"></i>
                            </button>
                            <button id="micButton" class="btn btn-danger rounded-circle p-3" onclick="toggleMic()">
                              <i class="bx bx-microphone" style="color: black; font-size: 21px;" id="muteMic"></i>
                              <i class="bx bx-microphone-off" style="color: black; font-size: 21px; display: none" id="unmuteMic"></i>
                            </button>
                          </div>
                          <input type="hidden"  id="joinMeetingId" value="{{@$data->meeting_token}}" />
                          <input type="hidden"  id="joinUserName" value="{{@$user_info->name}}" />
                          <input type="hidden"  id="joinUserId" value="{{@$user_info->id}}" />
                          <div class="input-group-append align-self-center my-4">
                            <button class="btn btn-primary " style="width:160px;
                                
                                " id="meetingJoinButton" onclick="joinMeeting(false)">
                              Join Meeting
                            </button>
                          </div>
                    </div>
                   
                </div>        
            </div>
            <div class="card-body" id="gridPpage" style="display: none;"> 
                <input type="hidden" style="background-color: #212032" class="form-control navbar-brand" id="meetingid"
                readonly />  
                <div class="d-flex gap-3 my-3 w-100">
                    <div class="card p-3" style="width:70%">  
                        {{-- <div id="contentRaiseHand" class="alert alert-info col-2" style="
                        left: 10;
                        bottom: 0;
                        position: absolute;
                        height: 60px;
                        display: none;
                      " role="alert"></div> --}}
                            
                            <div class="video-container">
                                <div class="toolbar" id="toolbar">
                                    <button id="pen-tool">Pen</button>
                                    <button id="eraser-tool">Eraser</button>
                                </div>
                                <div id="whiteboard-container" ></div>
                                
                                <div class="row" id="videoContainer" data-poster-url="{{ asset('assets/images/default.jpg') }}">
                                </div>
                              
                                <div class="overlay">
                                    <div class="alert alert-success" style="display: none;" id="contentRaiseHand"></div>
                                    <div class="timer d-none">24:01:45</div>
                                    <button type="button"class="btn  fullscreen-bt"><i class='bx bx-fullscreen d-none' ></i></button>
                                    <div class="d-flex w-100" style="gap:80%;">
                                        <div style="padding-top: 25px;" id="local_name_div" ><span class="name-label p-2" id="name-label"></span></div>
                                       
                                      
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="participant-wrapper" id="participants">
                                <div class="participant-wrapper-header text-light">
                                  <span class="closebtn" id="ParticipantsCloseBtn" onclick="closeParticipantWrapper()">&times;</span>
                                  <h5 id="totalParticipants"></h5>
                                </div>
                                <div class="scroll-container">
                                    <div class="row" id="participantsList">
                                        
                                    </div>
                                </div>
                              </div>
                            
                            <div class="row">
                                <div class="col-md-9">
                                    <div class="d-flex gap-3  justify-content-center">
                                        <button type="button" class="btn btn-danger user-img" id="main-pg-mute-mic"style="display:none;" ><i class='bx bxs-microphone-off'></i></button>
                                        <button type="button" class="btn btn-primary user-img" id="main-pg-unmute-mic" ><i class='bx bxs-microphone'></i></button>
                                        <button type="button" class="btn btn-primary user-img" id="main-pg-cam-on"><i class='bx bxs-video'></i></button>
                                        <button type="button" class="btn btn-danger user-img" id="main-pg-cam-off" style="display: none"><i class='bx bxs-video-off'></i></button>
                                        <button type="button" class="btn btn_link user-img" id="btnScreenShare"><i class='bx bx-upload'></i></button>
                                        <button type="button" class="btn btn-primary user-img" id="btnStartRecording"><i class='bx bx-radio-circle-marked'></i></button>
                                        <button type="button" class="btn btn-danger user-img"  style="display: none" id="btnStopRecording"><i class='bx bx-radio-circle-marked'></i></button>
                                        <button type="button" class="btn btn_link user-img d-none"  id="popoverButton"data-bs-toggle="popover" data-bs-placement="top">
                                            <i class='bx bx-dots-horizontal-rounded'></i>
                                        </button>
                                        <button type="button" class="btn border-0 user-img" id="btnRaiseHand"><i class="fa fa-hand-paper-o" aria-hidden="true"></i></button>
                                        <button type="button" class="btn border-0 user-img" style="display:none" id="btnUnRaiseHand"><i class="fa fa-hand-paper-o text-warning"></i></button>
                                    </div>
                                    
                                </div>
                                <div class="col-md-3">
                                    @php
                                      if($user_info->id == $data->moderator){
                                         $call_type = "endCall";
                                         $text = "End Call";  
                                      }
                                      else{
                                        $call_type = "leaveCall";
                                        $text = "Leave"; 
                                      }
                                    @endphp
                                    <button type="button" class="btn btn-danger w-75 rounded-pill" id="{{@$call_type}}">{{@$text}}</button>
                                </div> 
                            </div>
                          
                            <div id="pop_div" class="rounded" style="display: none;">
                                <ul class="ps-0">
                                    <li class="d-flex gap-3 mb-2 btn" id="Whiteboard">
                                    <i class='bx bxs-spreadsheet text-warning alert-warning my-0 px-1'></i> 
                                        <p class="mb-0">Whiteboard</p>
                                    </li>
                                    <li class="d-flex gap-3 mb-2">
                                        <a href="#" class="btn btn_link"><i class='bx bx-layout'></i></a>
                                        <p class="mt-3 mb-0">Change Layout</p>
                                    </li>
                                    {{-- <li class="d-flex gap-3 mb-2">
                                        <a href="#" class="btn alert-danger"><i class='bx bx-fullscreen' ></i></a>
                                        <p class="mt-3 mb-0">Full Screen</p>
                                    </li> --}}
                                    <li class="d-flex gap-3 mb-2">
                                        <a href="#" class="btn alert-success"><i class='bx bx-paste'></i></a>
                                        <p class="mt-3 mb-0">Open Picture in Picture</p>
                                    </li>
                                    <li class="d-flex gap-3 mb-2">
                                        <a href="#" class="btn alert-success"><i class='bx bx-cog'></i></a>
                                        <p class="mt-3 mb-0">Settings</p>
                                    </li>
                                </ul>
                            </div>                      
                    </div>
                    <div class="card p-3" style="width:30%">
                        <div class="card-body p-0">
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item w-100 ">
                                  <h6 class="accordion-header " id="headingOne">
                                    <button class="accordion-button d-flex gap-4 text-dark" id="list_accordion" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        <span style="font-size:18px !important;">Participants</span>
                                        <span type="button" class="btn btn_link rounded-pill" data-bs-toggle="modal" data-bs-target="#exampleModal" style="font-size:14px !important;">Add Participants <i class='bx bx-user-plus' ></i></span>
                                    </button>
                                  </h6>
                                  <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body scroll_body" style="background-color:#f5f3f9">
                                       <ul class="members p-0" id="participants_list_ul">
                                        @foreach($participants as $index => $participant)
                                            @if($participant->user)
                                                @php
                                                   $image = ($participant->user && $participant->user->images !== null) ? $participant->user->images : 'assets/images/default.jpg';
                                                @endphp
                                                <li class="row p-2 mb-2 border-bottom rounded-pill bg-light">
                                                        <div class="col-md-12 d-flex gap-2">
                                                            <img class="user-img" src="{{asset($image)}}" alt="user_image" name="user_image">
                                                            <h6 style="margin-top: 1em !important;">{{@$participant->user->name}}</h6>
                                                        </div>
                                                        {{-- <div class="col-md-3 d-flex gap-2 mt-2">
                                                            <button id="member_mute_btn" class="btn btn-primary member_mute_btn" onclick="toggleMemberMic()">
                                                            <i class='bx bx-microphone text-primary' style="font-size:20px;"></i>
                                                            </button>
                                                            <i class='bx bx-video-off text-danger' style="font-size:20px;"></i>
                                                        </div>                                            --}}
                                                </li>
                                            @endif  
                                        @endforeach
                                       </ul>
                                    </div>
                                  </div>
                                </div>                             
                            </div>                         
                        </div>
                    </div>
                </div>
            </div> 
        </div>
          
        <div class="modal fade add_participants_model" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Add Participant</h5>
                  <button type="button" class="btn-close btn-danger rounded-circle p-2 close_btn" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                    <div class="modal-body row">
                        <input type="hidden" id="meeting_id" value="{{$data->id}}">
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="participants">Participant Group  <span class="required">*</span>
                                    </label>
                                    <div class="feild ">
                                        {{ Form::select('participants_model_group',@$groups,@$data->group,
                                        array('id'=>'participants_group','class' => ' form-control form-select-lg single-select','placeholder'=>"select group",'required' => 'required' )) }}
                                    </div>
                                </div>
                        </div>
                     
                        <div class="col-xs-12 col-sm-12 col-md-12 mb-4">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="participants">Add Participant  <span class="required">*</span>
                                    </label>
                                    <div class="feild add_participant_model">
                                        {{ Form::select('participants[]',[],@$data->school_type ,
                                        array('id'=>'participants','class' => ' form-control form-select-lg multiple-select','style'=>'padding: 15px !important;','required' => 'required','multiple'=>'multiple' )) }}
                                    </div>
                                </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-light text-primary close_btn" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary add_participant" id="add_participant_btn" data-id ="{{$data->id}}">Add</button>
                    </div>
              </div>
            </div>
        </div>
       
    </div>

        
    

@endsection
@section('script')
   
    <script type="module">
        function notify_script(title, text, type, hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
        window.getparticipantssections = "{{route('participants_sections')}}";
        window.getparticipants = "{{ route('get_participants') }}";
        window.addparticipants = "{{ route('add_particiapants') }}";
        VirtualCommunicationConfig.VirtualCommunicationInit(notify_script);
       
    </script>
     <script src="{{asset('assets/backend/js/videosdk/index.js')}}"></script>   
     <script src="https://sdk.videosdk.live/js-sdk/0.0.82/videosdk.js"></script>
     <script src="{{asset('assets/backend/js/videosdk/config.js')}}"></script>
     <script>
       
        document.addEventListener('DOMContentLoaded', function () {
            var popoverButton = document.getElementById('popoverButton');
            var popoverContent = document.getElementById('pop_div').innerHTML;
            console.log(popoverContent);
            var popover = new bootstrap.Popover(popoverButton, {
                container: 'body',
                content: popoverContent,
                html: true,
                trigger: 'click'
            });
            console.log(popover);
           
            const onCamera = document.getElementById("onCamera");
            const onPage = document.getElementById("gridPpage");
            const overlayImage = document.querySelector(".overlay-image");
            console.log(onCamera.style.display,onPage.style.display);
            if (onCamera.style.display === "none" && onPage.style.display === "none") {
                    overlayImage.style.display = "none";
            } else {
                    overlayImage.style.display = "none";
            }
       
          
        });
       $('.multiple-select').select2({
        
            dropdownParent: $('.add_participant_model')
        });
        $('.single-select').select2({
          
            dropdownParent: $('.add_participants_model')
        });
    
        window.addEventListener("beforeunload", function (event) {
            console.log("reload");
            event.preventDefault();
            event.returnValue = "";  // Required for Chrome
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "F5" || (event.ctrlKey && event.key === "r")) {
                event.preventDefault();
                alert("Refreshing is disabled.");
            }
        });
      
    </script>
@endsection
@section('script_link')

    <!-- validator -->
    {{-- {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!} --}}

@endsection