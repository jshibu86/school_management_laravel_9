<ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">                             
    @foreach($bin_messages as $message)                                   
     
        <li class="nav-item nav_li w-100 row border-bottom" role="presentation">
            <div class="d-md-flex col-md-1 align-items-center text-dark py-3 ">
                <input type="checkbox" class="form-check_input bin_check" name="check[]" value="{{$message->id}}">                                             
            </div>
            <div class="bins_link col-md-9">  
                <div class="d-md-flex gap-3 align-items-center text-dark  px-3 py-3 ">
                    <div class="d-flex align-items-center ">                                  
                        <p class="mb-0"><b>{{$message->senter->name}}</b></p>
                    </div>
                    <div class=" d-flex gap-3" style="max-width: 600px;">
                        {{-- <p class="p-2 indication_radius mb-0" style="background-color:{{@$background}};font-size:16px; color:{{@$color}};">{{@$post->usergroup->group}}</p> --}}
                        <p style="font-size:16px;" class="p-2 mb-0 text_overflow" title="{{$message->subject}}">{{$message->subject}}</p>
                    </div>
                   
                </div>
            </div>
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
                <p class="mb-0 email-time text-end">{{ $formattedDate}}</p>  
                <i class="fa fa-trash delete_icon ms-3 text-danger fa-1x" data-msg_type="bin" aria-hidden="true" style="display:none;" id="{{$message->id}}"></i>
                <i class="fa fa-refresh delete_icon ms-2 text-secondary fa-1x" data-msg_type="restore" aria-hidden="true" style="display:none;" id="{{$message->id}}"></i>
            </div>
        </li>
    @endforeach
    <div class="w-100 container">
        <button type="button" class="btn btn-danger delete_bin delete_message mt-2" value="bin" style="display:none; float:right">Delete</button>
        <button type="button" class="btn btn-secondary delete_bin delete_message mt-2 me-2" value="restore" style="display:none; float:right">Restore</button>
    </div>
    
</ul>

@if($bin_messages->links() !== null)
    <div class="pagination-info row" data-group="bin">
        <div class="col-6 my-3">
            Showing {{ $bin_messages->firstItem() }}-{{ $bin_messages->lastItem() }} of {{ $bin_messages->total() }}
        </div>
        <div class="col-6 my-3">
            {{$bin_messages->links() }}
        </div>
    </div>
@endif