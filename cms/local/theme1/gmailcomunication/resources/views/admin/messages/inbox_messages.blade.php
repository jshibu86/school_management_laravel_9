<ul class="nav nav-pills mb-3 inbox_messages" id="pills-tab" role="tablist">                             
    @foreach($inbox_messages as $message)                                   
        @php
        
        $post = $senter_roles->where('user_id',$message->from_id)->first();
        // dd( $post);
        if($post->usergroup && $post->usergroup->group == "Teacher"){
                $background = "#ccf0eb";
                $color = "#32ab13";                                           
            }
        elseif($post->usergroup &&$post->usergroup->group == "Student"){
                $background = "#f7e2ff";
                $color = "#D456FD";                                        
            }
            elseif($post->usergroup &&$post->usergroup->group == "Parent"){
                $background = "#ffede0";
                $color = "rgba(var(--bs-warning-rgb), var(--bs-text-opacity)) !important;";                                      
            }
            elseif($post->usergroup &&$post->usergroup->group == "Super Admin"){
                $background = "#e1d5f5";
                $color = "#673ab7 !important;";                                    
            }
            else{
                $background = "";
                $color = "";                                        
            }
        @endphp 
        <li class="nav-item w-100 nav_li row border-bottom" role="presentation">
            <div class="d-md-flex col-md-1 align-items-center text-dark py-3 ">
                <input type="checkbox" class="form-check_input mt-0 inbox_check" name="check[]" value="{{$message->id}}">
                @php
                  $starred = $starred_ids->toArray();
                @endphp
                @if(in_array($message->id,$starred))
                  <i class="bx bxs-star text-warning font-20 mx-2 email-star inbox_star inbox_starred" data-id="{{$message->id}}"></i>
                @else
                  <i class="bx bx-star font-20 mx-2 email-star inbox_star" data-id="{{$message->id}}"></i>
                @endif
            </div>
            <a class="nav-link inbox_link col-md-9"id="pills-inbox-tab{{$message->id}}" data-bs-toggle="pill"
              href="#pills-inbox{{$message->id}}" role="tab" aria-controls="pills-inbox{{$message->id}}" aria-selected="true">  
                <div class="d-md-flex gap-3 align-items-center text-dark  px-3 py-3 ">
                    <div class="d-flex align-items-center ">                                  
                        <p class="mb-0"><b>{{$message->senter->name}}</b></p>
                    </div>
                    <div class=" d-flex gap-3" style="max-width: 600px;">
                        <p class="p-2 indication_radius mb-0" style="background-color:{{@$background}};font-size:16px; color:{{@$color}};">{{@$post->usergroup->group}}</p>
                        <p style="font-size:16px;" class="p-2 mb-0 text_overflow"  title="{{$message->subject}}">{{$message->subject}}</p>
                    </div>
                   
                </div>
            </a>
            <div class="d-md-flex col-md-2 align-items-center text-dark px-3  py-3 ">
                @php
                    $date = $message->created_at;
                    $dateTime = new DateTime($date);
                    $year = $dateTime->format('Y');
                    $currentYear = date('Y');
                    $isCurrentDay = $dateTime->format('Y-m-d') === date('Y-m-d');
                    if($isCurrentDay){
                        $formattedDate = $message->time;
                    }
                    else if($year == $currentYear){
                        $formattedDate = $dateTime->format('F d');
                    }                  
                    else{
                        $formattedDate = $dateTime->format('F d, Y');
                    }
                @endphp
                <p class="mb-0 email-time text-end">{{$formattedDate}}</p>
                <i class="fa fa-trash delete_icon ms-3 text-danger fa-1x" data-msg_type="inbox" aria-hidden="true" style="display:none;" id="{{$message->id}}"></i>
            </div>
        </li>
    @endforeach
    <div class="w-100 container">
        <button type="button" class="btn btn-danger delete_inbox delete_message mt-2" value="inbox" style="display:none; float:right;">Delete</button>
    </div>
 
</ul>
@if($inbox_messages->links() !== null)
    <div class="pagination-info row" data-group="inbox">
        <div class="col-6 my-3">
            Showing {{ $inbox_messages->firstItem() }}-{{ $inbox_messages->lastItem() }} of {{ $inbox_messages->total() }}
        </div>
        <div class="col-6 my-3">
            {{$inbox_messages->links() }}
        </div>
    </div>
@endif