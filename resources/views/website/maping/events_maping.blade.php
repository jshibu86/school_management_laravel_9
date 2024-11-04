<style>
  .scroll_body{
    height:250px;
    overflow-x: hidden;
    overflow-y: scroll;
  }
  .scroll_temp:hover::-webkit-scrollbar-thumb {
      display: block;
  }
  .scroll_temp::-webkit-scrollbar-track {
      /* -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); */
      border-radius: 10px;
      background-color: #fff;
  }
  .scroll_temp::-webkit-scrollbar {
      width: 4px !important;
      height: 5px;
      background-color: #fff;
  }
  .scroll_temp::-webkit-scrollbar-thumb {
      display: none;
      border-radius: 10px;
      -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
      background-color: #c9caca;
  }

</style>
<div class="row events" id="pills-tab">
    @foreach($events as $event)
      @php
        $dateString = $event->event_date;
        $dateTime = new DateTime($dateString);

        $formattedDate = $dateTime->format('F j, Y');
      @endphp
    <div class="col-md-6 col-lg-4 mb-4" data-aos="fade-up" data-aos-delay="0">
      <div class="card post-entry" style="height:600px">
        <img src="{{asset($event->event_image)}}" class="card-img-top" alt="Image" style="height:250px !important;">
        <div class="card-body">
          <div><span class="text-uppercase font-weight-bold date">{{$formattedDate}}</span></div>
          <h5 class="card-title">{{$event->event_name}}</h5>
          <div class="scroll_body scroll_temp">
            <p>{{$event->description}}</p>
          </div>
          {{-- <p class="mt-5 mb-0"><a href="#">Read more</a></p> --}}
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <div class="row">
    <div class="col-lg-12 text-center py-5">
        {{-- custom-navigation --}}
      <div class="">
        @if($events->links() !== null)
        <div class="pagination-info row">
            <div class="col-6 my-3">
                Showing {{ $events->firstItem() }}-{{ $events->lastItem() }} of {{ $events->total() }}
            </div>
            <div class="col-6 my-3">
                {{$events->links() }}
            </div>
        </div>
    @endif
    </div>
  </div>
</div>