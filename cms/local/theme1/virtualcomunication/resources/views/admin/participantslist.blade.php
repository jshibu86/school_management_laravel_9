@foreach($participants as $index => $participant)
@php
$image = ($participant->user->images !== null) ? $participant->user->images : 'assets/images/default.jpg';
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
@endforeach