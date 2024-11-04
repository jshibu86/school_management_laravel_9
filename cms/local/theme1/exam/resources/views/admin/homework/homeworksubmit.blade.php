@extends('layout::admin.master')

@section('title','submit')
@section('style')

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
            height: 350px;
            overflow-y: auto; 
            overflow-x:hidden;
            border: 1px solid #ccc; 
            padding: 10px; 
    }
    

</style>
@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('homework_submit'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'homework-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('homework_submit',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
            

            

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Submit', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_homework' , 'class' => 'btn btn-success')) }}

            <a class="btn btn-info" href="{{route('homeworkdata.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger']) }}

        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Submit  homework" : "Submit  homework"])

             <div class="card container-fluid">
                <div class="card-body">
                    <h5 class="card-title">Submit Homework</h5>
                    <hr/>

                    <div class="homework__data exam_information">
                        <h4>{{ @$homework->exam_title }}</h4>
                        <p>{{ @$homework->class->name }} / {{ @$homework->section->name }} </p>

                        <span>Homework Allocated Date : {{ @$homework->exam_date }}</span>
                        <div class="mt-2">
                            <span class="text-end h6">Total Mark: {{@$homework->max_mark}}</span>
                        </div>
                     
                    </div>
                   
                    <div class="my-2">
                        <h4>Exam Instructions:</h4>
                        <p>{{@$homework->examistruction}}</p>
                    </div>
                    <h2 class="card-title">Teacher Section:</h2>
                   
                    <div class="card border">
                       
                        <div class="card-body scrollable_teacher">
                            <div class="row">
                                @foreach($info as $data)                                                              
                                        <div class="attachment">
                                           <p class="fw-bold">Attachment:</p>
                                            <div class="container_attachment container my-2 d-block">
                                              
                                                <div class="card_attachment">
                                                    @php
                                                    $file_extension = pathinfo($data->attachment, PATHINFO_EXTENSION);
                                                    @endphp
                                                    @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif" || $file_extension == "jpeg")
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
                                                <div class="homework__description mt-3">
                                                    <p><span class="fw-bold">Teacher Description:</span> {{ @$data->question }}</p>
                                                    </div>
                                            
                                            </div>
                                        
                                            {{-- <a href="{{ @$data->attachment }}" download="{{ @$data->attachment }}">Download 2</a> --}}
                                        </div>
                                    
                                
                                @endforeach
                            </div>
                        </div>
                       
                    </div>
                    <h2>Student Section :</h2>
                    <div class="card border">
                        <div class="card-body">
                          
                            <div class="submit__homework">
                                <input type="hidden" name="homework_id" value="{{ @$homework->id }}"/>
                                <input type="hidden" name="subject_id" value="{{ @$homework->subject_id }}"/>
                                <div class="col-6">
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label fw-bold">Attach File</label>
                                        <input class="form-control home_img" type="file" id="formFile" name="attachment" accept=".pdf,.jpg,.jpeg,.png">
                                        <p class="text-danger error_msg"></p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label for="notes" class="form-label  fw-bold">Remarks / Notes</label>
                                    <textarea class="form-control" id="notes" name="remark" placeholder="type..." rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                    <div class="spacer"></div>
                     
                 
                    
                </div>
            </div>

        
       
       

        {{Form::close()}}
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
