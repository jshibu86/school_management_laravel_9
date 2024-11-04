@extends('layout::admin.master')

@section('title','submit')
@section('style')
@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{ asset('assets/backend/css/attendance.css') }}">
<style>
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
        width: 200px;
        height:200px;
        background: radial-gradient(#111 50%, #000 100%);
        overflow: hidden;
        cursor: pointer;
        
    }

    img {
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

</style>
@endsection
@section('body')
<div class="x_content">

    <div class="box-header with-border mar-bottom20">
      <a class="btn btn-info" href="{{route('homeworkdata.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
    </div>
         <div class="card">
            <div class="card-body">
                <h5 class="card-title">Evaluate Homework</h5>
                <hr/>
                 <input type="hidden" name="student_id" value="{{$student_id}}">
                <div class="homework__data exam_information">
                    <h4>{{ @$homework->exam_title }}</h4>
                    <p>{{ @$homework->class->name }} / {{ @$homework->section->name }} </p>

                    <span>Homework Allocated Date : {{ @$homework->exam_date }}</span>
                </div>
                
                <div class="spacer mb-4"></div>
                 <h4 class="">Teacher Section :</h4>
                 <div class="card border">
                    <div class="card-body scrollable_teacher">
                        <div class="row">
                            @foreach($info as $data)
                         
                               
                                    <div class="attachment">
                                           
                                        <div class="container_attachment container my-2 d-block">
                                            @if(isset($data->attachment))
                                                <p class="fw-bold">Attachment :</p>
                                                <div class="card_attachment">
                                                    @php
                                                    $file_extension = pathinfo($data->attachment, PATHINFO_EXTENSION);
                                                    @endphp
                                                    @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                    <img src="{{ asset($data->attachment) }}" alt="Animated Card Hover Effect Html & CSS">
                                                    @elseif ($file_extension == 'mp4' || $file_extension == 'avi' || $file_extension == 'mov')  
                                                    <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS">
                                                    @elseif($file_extension == 'mp3') 
                                                    <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" alt="Animated Card Hover Effect Html & CSS">
                                                    @else
                                                    <img src="{{ asset('assets/sample/file.jpg') }}" alt="Animated Card Hover Effect Html & CSS">
                                                    @endif
                                                    <div class="overlay">
                                                        <a href="{{$data->attachment}}" class="link-b btn bg-white text-dark nav-link"target="_blank"><i class="fa fa-eye"></i></a>
                                                        <a href="{{ @$data->attachment }}" class="link-b btn bg-white text-dark" download="{{ @$data->attachment }}"><i class="fa fa-download"></i></a>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="homework__description mt-3">
                                                <p class=""><span class="fw-bold">Teacher Description: </span> {{ @$data->question }}</p>
                                                </div>
                                        
                                        </div>
                                    
                                        {{-- <a href="{{ @$data->attachment }}" download="{{ @$data->attachment }}">Download 2</a> --}}
                                    </div>
                              
                            @endforeach
                        </div>
                    </div>
                 </div>
               
               
                <div class="spacer"></div>
                <h4>Student Section :</h4>
                 <div class="card border">
                    @if(isset($answer) && isset($answer->attachment))
                    <div class="card-body scrollable_student">
                    @else 
                    <div class="card-body">
                    @endif       
                        <div class="submit__homework">
                            <input type="hidden" name="homework_id" value="{{ @$homework->id }}"/>
                            <input type="hidden" name="subject_id" value="{{ @$homework->subject_id }}"/>
                            @if(isset($answer))
                                <div class="">
                                    @if(isset($answer->attachment))
                                    <p class="fw-bold">Attachment :</p>
                                        <div class="card_attachment">
                                        
                                      
                                            @php
                                        
                                            $attachment_extension = pathinfo($answer->attachment, PATHINFO_EXTENSION);
                                            
                                            @endphp
                                            
                                            @if($attachment_extension == "jpg" || $attachment_extension == "png" || $attachment_extension == "gif")
                                            <img src="{{ asset($answer->attachment) }}" alt="Animated Card Hover Effect Html & CSS">
                                            @elseif ($attachment_extension == 'mp4' || $attachment_extension == 'avi' || $attachment_extension == 'mov')  
                                            <img src="{{ asset('assets/sample/images.png') }}" alt="Animated Card Hover Effect Html & CSS">
                                            @elseif($attachment_extension == 'mp3') 
                                            <img src="{{ asset('assets/sample/istockphoto-1244097573-612x612.jpg') }}" alt="Animated Card Hover Effect Html & CSS">
                                            @else
                                            <img src="{{ asset('assets/sample/file.jpg') }}" alt="Animated Card Hover Effect Html & CSS">
                                            @endif
                                            <div class="overlay">
                                                <a href="{{$answer->attachment}}" class="link-b btn bg-white text-dark nav-link"target="_blank"><i class="fa fa-eye"></i></a>
                                                <a href="{{ @$answer->attachment }}" class="link-b btn bg-white text-dark" download="{{ @$answer->attachment }}"><i class="fa fa-download"></i></a>
                                            </div>
                                         
                                        </div>
                                        @endif 
                                </div>
                                @if(isset($answer->remark))
                                    <div class="mt-3">
                                        <p><span class="fw-bold">Student Description: </span> {{$answer->remark}}</p>
                                    </div>
                                @endif
                            @else
                            <span class="badge bg-secondary text-center">Still ,Student didn't Sumbit the Homework</span>                      
                            @endif
                          
                        </div>
                    </div>
                 </div>
                
             
            </div>
        </div>

    
   
</div>
@endsection
@section('scripts')

<script type="module">
    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
    window.sectionurl="{{ route('section.index') }}";
    window.subjecturl="{{ route('subject.index') }}";

   
   

   
</script>
@endsection

@section("script_link")

    <!-- validator -->

    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}
   
   
   
@endsection