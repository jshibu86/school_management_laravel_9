
<style>
    .badge_btn {
        display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        vertical-align: middle;
        border: 1px solid transparent;
        padding: .375rem .75rem;
        font-size: 1rem;
        border-radius: .25rem;
        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .badge_i{
        vertical-align: middle;
        font-size: 1.3rem;
        margin-top: -1em;
        margin-bottom: -1em;
    }
</style>    
@if($type)
 @if($type == "expired")
   <span class="badge_btn bg-danger text-center py-2 text-white" style="width:160px;font-size:1rem">Expired <i class='badge_i bx bxs-video'></i></span>
 @elseif($type == "join")
 <a href="{{ route('join_meeting', ['id' => $data->id]) }}" class="btn btn-success text-center" style="width:160px;" id="meetingJoinButton">Join <i class='bx bxs-video'></i></a>
 @else
   <span class="badge_btn bg-secondary text-center py-2 text-white"style="width:160px;font-size:1rem">Pending <i class='badge_i bx bxs-video'></i></span>
 @endif
@endif