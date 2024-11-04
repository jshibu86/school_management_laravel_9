<div class="row">
    @foreach($gallery as $event)
    {{-- @php
      $dateString = $event->event_date;
      $dateTime = new DateTime($dateString);

      $formattedDate = $dateTime->format('F j, Y');
    @endphp --}}
    <div class="col-lg-4 mb-30 col-md-6">
        <div class="gallery-item">
            <div class="gallery-img">
                <img src="{{asset($event->image)}}" class="img-thumbnail" style="height:300px !important" alt="">
            </div>
            <div class="title mt-3 mb-3">
                {{$event->title}}
            </div>
        </div>
    </div> 
    @endforeach                     
</div>

<div class="row">
    <div class="col-lg-12 text-center py-5">
        {{-- custom-navigation --}}
      <div class="">
        @if($gallery->links() !== null)
        <div class="pagination-info row">
            <div class="col-6 my-3">
                Showing {{ $gallery->firstItem() }}-{{ $gallery->lastItem() }} of {{ $gallery->total() }}
            </div>
            <div class="col-6 my-3">
                {{$gallery->links() }}
            </div>
        </div>
    @endif
    </div>
  </div>
</div>
