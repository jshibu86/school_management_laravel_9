
{{-- <style>
    .exam_information {
        background-color: #D9D9D9;
        width: 47%;
        margin: auto;
        padding: 10px;
    }
    .homework__data{
        text-align: center
    }
    .attachment a:hover{
        color: white
    }
    .container_attachment {
        display: flex;      
        flex-wrap: wrap;
        float:left;
        padding-left: unset !important;
    }

    .card_attachment {
        position: relative;
        width: 150px;
        height:100px;
        background: radial-gradient(#111 50%, #000 100%);
        overflow: hidden;
        cursor: pointer;
        
    }

    .img {
        max-width: 100%;
        height:100%;
        display: block;
    }

    .card_attachment img {
        transform: scale(1.3);
        transition: 0.3s ease-out;
    }

    .card_attachment:hover img {
        transform: scale(1.1) ;
        opacity: 0.3;
    }

    .overlay {
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 100%;

        top:30px;
        text-align: center;
        color: #fff;
    }



    .link-a {
        display: inline-block;
        border: solid 2px white;
        color: #fff;
        margin-top: 30px;
        padding: 5px 5px;
        border-radius: 5px;
        transform: translateY(30px);
        opacity: 0;
        transition: all .3s ease-out 0.4s;
    }
    .link-b {
        display: inline-block;
        border: solid 2px white;
        color: #fff;
        margin-top: 30px;
        padding: 5px 5px;
        border-radius: 5px;
        transform: translateY(30px);
        opacity: 0;
        transition: all .3s ease-out 0.4s;
    }

    .overlay .link-a:hover {
        background: #fff;
        color:#000;
    }
    .overlay .link-b:hover {
        background: #fff;
        color:#000;
    }
    .card_attachment:hover .overlay .link-a {
        opacity: 1;
        transform: translateY(0);
    }
    .card_attachment:hover .overlay .link-b {
        opacity: 1;
        transform: translateY(0);
       
    }
    .scrollable_teacher {
            height: 400px;
            overflow-y: auto; 
            overflow-x:hidden;
            border: 1px solid #ccc; 
            padding: 10px; 
    }
   .scrollable_student {
            height: 280px;
            overflow-y: auto; 
            overflow-x:hidden;
            border: 1px solid #ccc; 
            padding: 10px; 
    }    

</style> --}}
<div class="container">
    <div class="row mt-5">
    <div class="col-2"></div>
        <div class="col-9 p-3" style="background-color: #F5F5F5;border-radius:16px;">
            <p>{!!$message!!}</p>
            @if($file_paths !==null)
            <div class="row">
                @foreach($file_paths as $key => $path)
                  <div class="col-md-3">
                    <div class="card_attachment">
                        @php
                        $file_extension = pathinfo($path, PATHINFO_EXTENSION);
                        @endphp
                        @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                        <img src="{{ asset($path) }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                        @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                        <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS" class="img">
                        @elseif($file_extension == 'mp3') 
                        <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                        @else
                        <img src="{{ asset('assets/sample/file.jpg') }}" class="img" alt="Animated Card Hover Effect Html & CSS">
                        @endif
                        <div class="overlay">
                            <a href="{{$path}}" class="link-b btn bg-white text-dark nav-link"target="_blank"><i class="fa fa-eye"></i></a>
                            <a href="{{ @$path }}" class="link-b btn bg-white text-dark" download="{{ @$path }}"><i class="fa fa-download"></i></a>
                        </div>
                    </div>
                  </div>
                @endforeach
            </div>
             
            @endif 
            <div class="row mt-5">
                <div class="col-6">
                    <p><span>{{$time}} </span><button type="button" class="info_btn border-none" style="background-color: #F5F5F5;"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button></p>
                </div>
                <div class="col-6">
                    <p class="fw-bold" style="float:right;">{{$user->name}}</p>
                </div>
            </div>
        </div>
        <div class="col-1 align-self-end" style="float:right;">
            @if($user->images == null)
              <img class="user-img" src="http://127.0.0.1:8000/assets/images/default.jpg" alt="" name="user_image">
            @else
              <img class="user-img" src="{{asset($user->images)}}" alt="" name="user_image">
            @endif
        </div>
        
    </div>
</div>                         