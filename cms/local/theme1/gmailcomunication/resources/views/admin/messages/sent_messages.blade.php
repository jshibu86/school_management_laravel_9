<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist"> 
    @foreach($sent_messages as $message)
        <li class="nav-item nav_li w-100 row border-bottom" role="presentation">
            <div class="d-md-flex col-md-1 align-items-center text-dark py-3 ">
                <input type="checkbox" class="form-check_input sent_check" name="check[]" value="{{$message->id}}">
            </div>
            <a class="nav-link sent_link col-md-9" id="pills-sent-tab{{$message->id}}" data-bs-toggle="pill"
             href="#pills-sent{{$message->id}}" role="tab" aria-controls="pills-sent{{$message->id}}" aria-selected="true">                                              
               <div class="d-md-flex gap-3 align-items-center text-dark  px-3 py-3 ">
                   <div class="d-flex align-items-center "></div>
                   <div class="d-flex gap-3" style="max-width: 600px;">
                      <p style="font-size:16px;" class="p-2 mb-0 text_overflow" title="{{$message->subject}}">{{$message->subject}}</p>
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
                    if( $isCurrentDay){
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
                <i class="fa fa-trash delete_icon ms-3 text-danger fa-1x" data-msg_type="sent" aria-hidden="true" style="display:none;" id="{{$message->id}}"></i>
            </div>
        </li>
    @endforeach
    <div class="w-100 container">
       <button type="button" class="btn btn-danger delete_sent delete_message mt-2" value="sent" style="display:none; float:right">Delete</button>
    </div>
</ul> 

@if($sent_messages->links() !== null)
    <div class="pagination-info row" data-group="sent">
        <div class="col-6 my-3">
            Showing {{ $sent_messages->firstItem() }}-{{ $sent_messages->lastItem() }} of {{ $sent_messages->total() }}
        </div>
        <div class="col-6 my-3">
            {{$sent_messages->links() }}
        </div>
    </div>
@endif
