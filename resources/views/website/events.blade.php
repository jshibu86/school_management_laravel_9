<!-- resources/views/home.blade.php -->
@extends('website.layout')

@section('title', 'Home Page')

@section('content')
  
<div class="hero overlay inner-page" style="background-image: url({{@$menu["banner_image"]}}); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <!-- <img src="images/blob.svg" alt="" class="img-fluid blob"> -->
    <div class="container">
      <div class="row align-items-center justify-content-center text-center pt-5">
        <div class="col-lg-6">
          <h1 class="heading text-white mb-3" data-aos="fade-up" >{{@$menu["banner_title"]}}</h1>
          <p class="text-white mb-4" data-aos="fade-up" data-aos-delay="100">{{@$menu["banner_description"]}}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="section sec-news">
    <div class="container" id="events_container" data-container="#starred-messages-container" data-type="starred">
      @include('website.maping.events_maping', ['events' => $events,])
    </div>   
  </div>

@endsection

@section('scripts')
<script>
  $(document).on('click', '.pagination a', function(e) {
    e.preventDefault(); 
    var url = $(this).attr('href');
    var row = $(this).closest('.row');
    fetchMessages(url, type);
  });


    $(document).on('click', '.nav-link', function(e) {
        e.preventDefault(); 
        var url = $(this).attr('href');
        var container = $($(this).attr('data-container')); 
      
        var pageUrl = url.includes('?') ? url + '&page=1' : url + '?page=1';
        fetchContent(pageUrl, container); 
    });



    function fetchMessages(url, type) {
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
              
                    $('#events_container').html(data);
                
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }


    function fetchContent(url, container) {
        $.ajax({
            url: url,
            type: 'GET', // Specify the request type as 'GET'
            success: function(data) {
                container.html(data);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });
    }



    
  
    $('.nav-link').on('click',function(){
        $('.nav-link').removeClass('active');
        $(this).addClass('active');
    });
</script>
@endsection
