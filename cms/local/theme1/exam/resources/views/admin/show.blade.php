@extends('layout::admin.master')

@section('title','exam type')
@section('style')
    <!-- Datatables -->
    <style type="text/css" media="print">
        @page {
            size: auto;   /* auto is the initial value */
            margin: auto;  /* this affects the margin in the printer settings */
        }
        </style>
    <style>
        .table-div table {
            width: 100% !important;
        }
        .exam_information{
            text-align: center;
        }
            /*-------------Question paper styling-------------*/
    .quspaper_head{
        text-transform: uppercase;
        font-size: 22px;
        font-weight: 700;
    }
    .quspaper_type{
        max-width: 100px;
    }
    .section_name{
        text-align: center;
    font-weight: bold;
    }
    .school_config{
        display: flex;
    align-items: center;
    justify-content: space-between;
    }
    .previewimg{
        width: 50%;
    }
    .info_section{
        text-align: right;
    }
    .options{
        display: flex;
        flex-direction: column;
        margin-left: 10px;
    }
    .options_div{
        display: flex;
        justify-content: space-between
    }
    .info_section p{
        margin: 0
    }
        
    </style>
@endsection
@section('body')
<div class="card infobody">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">Show Exam with Questions</h4>
            <div>
                @if (@$data->uploaded_file == null)
                <button class="btn btn-primary btn-sm m-1  px-3 print"  ><i class='fa fa-file-pdf-o print'></i>&nbsp;&nbsp;PRINT</button>
                @endif
                <a class="btn btn-info btn-sm m-1  px-3" href="{{route('exam.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
            </div>
         

           


            
           
          
        </div>
        <hr/>

        {{-- <div class="row">
            <div class="exam_information">
                
                <p>Academic Year : {{ @$data->academyyear->year }}</p>
                <p>Class-Section : {{ @$data->class->name  }}-{{ @$data->section->name }}</p>
                <p>Subject : {{ @$data->subject->name }}</p>
            </div>
        </div> --}}
       
    </div>
</div>

<div class="card questioninfo">
    <div class="card-body">
        {{-- <div class="card-title  text-center title">
            <h4 class="mb-0">Questions</h4>
            <small>Instruction : {{ @$data->examistruction }}</small>
        </div>
        <hr/> --}}

        @if (@$data->uploaded_file !=null)
        <div class="uploadquestion text-center">
            <a href="{{ asset(@$data->uploaded_file) }}" target="_blank" class="uploadqus upload_cls btn mt-3 btn btn-primary" >View Document <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
        </div>
        
        @else
        <section class="container print_section py-5">
            <div class="row py-5">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="w-75 mx-auto">
                        <div class="school_config">
                            <div class="image_section">
                                <img src="{{ asset(@$config->imagec) }}" class="previewimg"/>
                            </div>

                            <div class="info_section">
                                <p>{{ @$config->school_name }}</p>
                                <p>{{ @$config->place }},{{ @$config->city }},{{ @$config->country }}</p>
                                <p>{{ @$config->school_phone }}</p>
                                <p>{{ @$config->school_email }}</p>
                            </div>
                        </div>
                        <h4 style="text-align: center;margin-top: 1.5rem;">{{@$data->exam_title}}</h4>
                        <h5 class="quspaper_head mt-4">Instructions:</h5>
                        <p>{{ @$data->examistruction }}</p>
                        {{-- <h3 class="quspaper_head text-center">Session {{ @$data->academyyear->year  }}</h3>
                        <h3 class="quspaper_head text-center" >Subject : {{ @$data->subject->name }}</h3> --}}
                        <div class="d-flex justify-content-between mt-5">
                            <h6><b>Time - {{ @$data->timeline }}</b></h6>
                            <h6><b>Full Mark - {{ @$data->max_mark }}</b></h6>
                            
                        </div>
                        {{-- <h6 class="my-4 text-center"><i>Read the instructions carefully before attempting questions from each group.</i></h6> --}}


                        @foreach (@$examquestions->sections as $sections )
                            @php
                            $count=0;
                            @endphp
                            <div class="section_name">

                                <p>{{ucfirst( @$sections->section_name) }}</p>
                            </div>
                            @foreach ($sections->questions as $question )

                                @php
                                $count++;
                                @endphp
                        
                                @if($question->question_type == "fillintheblanks")
                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ $question->question }}</span>
                                            </div>
                                            
                                            <span><b>[{{ @$question->mark }}]</b></span>
                                        </h6>
                                        {{-- <h6 class="px-5">Answer</h6> --}}
                                    </div>
                                @endif

                                @if($question->question_type == "choosebest")
                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ $question->question }}</span>
                                            </div>
                                        
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        <div class=" options_div text-left px-5">
                                            <div class="options">
                                                @foreach (explode(",",$question->options) as $answer)
                                                <span class=" py-1">{{ chr($loop->index +65) }}. {{ @$answer }}</span>
                                            
                                                @endforeach
                                            </div>

                                            <div class="image_">
                                                @if (@$question->attachment)
                                                <div class="px-5">
                                                    <img src="{{ asset($question->attachment)}}" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />
                                                </div>
                                                @endif
                                            </div>
                                        
                                        </div>
                                    
                                    
                                    </div>
                                @endif

                                @if($question->question_type == "definequestion")

                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ $question->question }}</span>
                                            </div>
                                            
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        <div class="px-5">
                                            <img src="{{ asset($question->attachment)}}" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />
                                        </div>
                                    </div>
                                @endif

                                @if($question->question_type == "shortquestion")
                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ $question->question }}</span>
                                            </div>
                                            
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        {{-- <h6 class="px-5">Answer</h6> --}}
                                    </div>
                                @endif

                                @if($question->question_type == "longquestion")
                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ucfirst($question->question) }}</span>
                                            </div>
                                            
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        {{-- <h6 class="px-5">Answer</h6> --}}
                                    </div>
                                @endif

                                
                                @if($question->question_type == "yesorno")
                                    <div class="qus_box mt-5">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ucfirst($question->question) }}</span>
                                            </div>
                                        
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        <div class="options_div text-left px-5">
                                            <div class="options">
                                                @foreach (explode(",",$question->options) as $answer)
                                                <span class=" py-1">{{ chr($loop->index +65) }}. {{ @$answer }}</span>
                                            
                                                @endforeach
                                            </div>

                                            <div class="image_">
                                                @if (@$question->attachment)
                                                <div class="px-5">
                                                    <img src="{{ asset($question->attachment)}}" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />
                                                </div>
                                                @endif
                                            </div>
                                        
                                        </div>

                                    
                                        {{-- <h6 class="px-5">Answer</h6> --}}
                                    </div>
                                @endif

                                @if($question->question_type == "subquestion")
                                
                                    @foreach ($question->subquestion as $order => $ques)

                                    
                                        <div class="qus_box mt-3">

                                        

                                            <h6 class="d-flex justify-content-between">

                                                @if ($loop->first)

                                                <div class="d-flex">
                                                    <span>{{ @$count }}.{{ chr($loop->index +65) }}.&nbsp;</span>
                                                    <span> {{ $ques->question }}</span>
                                                </div>
                                            
                                                <span><b>[{{ $ques->mark  }}]</b></span>

                                                @else

                                                <div class="d-flex">
                                                    <span>&nbsp;&nbsp;&nbsp;{{ chr($loop->index +65) }}.</span>
                                                    <span> &nbsp;{{$ques->question}}</span>
                                                </div>
                                                
                                                <span><b>[{{  $ques->mark }}]</b></span>
                                                @endif
                                            
                                            </h6>
                                                
                                        
                                            
                                            {{-- <h6 class="px-5">Answer</h6> --}}
                                        </div>
                                    @endforeach
                                @endif
                            
                                @if($question->question_type == "homework")
                                    <div class="qus_box mt-5 row">
                                    
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ucfirst($question->question) }}</span>
                                            </div>
                                        
                                        
                                        </h6>
                                    
                                        <div class=" text-left px-5">
                                            @php
                                            $file_extension = pathinfo($question->attachment, PATHINFO_EXTENSION);
                                            @endphp
                                            
                                            <div class="">
                                                @if($file_extension == "jpg" || $file_extension == "png" || $file_extension == "gif")
                                                    <div class="">
                                                        <img src="{{ asset($question->attachment)}}" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />
                                                    </div>
                                                @else
                                                    <div class="">
                                                    <img src="{{ asset('assets/sample/file.jpg') }}" class="img-fluid quspaper_type py-1" alt="Animated Card Hover Effect Html & CSS">
                                                    </div>
                                            
                                                @endif
                                            </div>
                                        
                                        </div>

                                    
                                        {{-- <h6 class="px-5">Answer</h6> --}}
                                    </div>
                                @endif
                                
                            @endforeach

                        

                        

                            

                            
                            @php
                            $count=0;
                            @endphp
                            
                        @endforeach
    
                     
    
    
                       
    
                       
    
                        
    
                        {{-- <h6 class="mt-5 text-center">page <b>1</b> of <b>2</b></h6> --}}
    
                    </div>
                </div>
            </div>
        </section>
        @endif

       
    

        {{-- <div class="questions__section">
           
            @foreach (@$examquestions->questions as  $question)
               @if($question->question_type == "fillintheblanks")
              <div class="exam_question_fill">
                <div class="exam_question">
                    <p><small>{{ @$loop->index+1 }} : </small>{{ @$question->question }} ?</p>
                </div>
               
              </div>
               @endif
               @if($question->question_type == "choosebest")
               
               @endif
               @if($question->question_type == "definequestion")
              
               @endif
               @if($question->question_type == "shortquestion")
               
               @endif
               @if($question->question_type == "longquestion")
               
               @endif
            @endforeach
        </div> --}}
    </div>
</div>

<div class="print-body" style="display:none">
    <section class=" print_section py-5">
       <div class="row py-5">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="w-75 mx-auto">
                        <div class="school_config">
                            <div class="image_section">
                                <img src="{{ asset(@$config->imagec) }}" class="previewimg"/>
                            </div>

                            <div class="info_section">
                                <p>{{ @$config->school_name }}</p>
                                <p>{{ @$config->place }},{{ @$config->city }},{{ @$config->country }}</p>
                                <p>{{ @$config->school_phone }}</p>
                                <p>{{ @$config->school_email }}</p>
                            </div>
                        </div>
                        <h4 style="text-align: center;margin-top: 1.5rem;">{{@$data->exam_title}}</h4>
                        <h5 class="quspaper_head mt-4">Instructions:</h5>
                        <p>{{ @$data->examistruction }}</p>
                        {{-- <h3 class="quspaper_head text-center">Session {{ @$data->academyyear->year  }}</h3>
                        <h3 class="quspaper_head text-center" >Subject : {{ @$data->subject->name }}</h3> --}}
                        <div class="d-flex justify-content-between mt-5">
                            <h6><b>Time - {{ @$data->timeline }}</b></h6>
                            <h6><b>Full Mark - {{ @$data->max_mark }}</b></h6>
                            
                        </div>
                        {{-- <h6 class="my-4 text-center"><i>Read the instructions carefully before attempting questions from each group.</i></h6> --}}


                        @foreach (@$examquestions->sections as $sections )
                            @php
                            $count=0;
                            @endphp
                            <div class="section_name">

                                <p>{{ucfirst( @$sections->section_name) }}</p>
                            </div>
                            @foreach ($sections->questions as $question )

                                @php
                                $count++;
                                @endphp
                            
                                @if($question->question_type == "fillintheblanks")
                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ $question->question }}</span>
                                            </div>
                                            
                                            <span><b>[{{ @$question->mark }}]</b></span>
                                        </h6>
                                        {{-- <h6 class="px-5">Answer</h6> --}}
                                    </div>
                                @endif

                                @if($question->question_type == "choosebest")
                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ $question->question }}</span>
                                            </div>
                                        
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        <div class=" options_div text-left px-5">
                                            <div class="options">
                                                @foreach (explode(",",$question->options) as $answer)
                                                <span class=" py-1">{{ chr($loop->index +65) }}. {{ @$answer }}</span>
                                            
                                                @endforeach
                                            </div>

                                            <div class="image_">
                                                @if (@$question->attachment)
                                                <div class="px-5">
                                                    <img src="{{ asset($question->attachment)}}" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />
                                                </div>
                                                @endif
                                            </div>
                                        
                                        </div>
                                    
                                    
                                    </div>
                                @endif

                                @if($question->question_type == "definequestion")

                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ $question->question }}</span>
                                            </div>
                                            
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        <div class="px-5">
                                            <img src="{{ asset($question->attachment)}}" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />
                                        </div>
                                    </div>
                                @endif

                                @if($question->question_type == "shortquestion")
                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ $question->question }}</span>
                                            </div>
                                            
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        {{-- <h6 class="px-5">Answer</h6> --}}
                                    </div>
                                @endif

                                @if($question->question_type == "longquestion")
                                    <div class="qus_box mt-3">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ucfirst($question->question) }}</span>
                                            </div>
                                            
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        {{-- <h6 class="px-5">Answer</h6> --}}
                                    </div>
                                @endif

                                
                                @if($question->question_type == "yesorno")
                                    <div class="qus_box mt-5">
                                        <h6 class="d-flex justify-content-between">
                                            <div class="d-flex">
                                                <span>{{ @$count }}.&nbsp;</span>
                                                <span> {{ucfirst($question->question) }}</span>
                                            </div>
                                        
                                            <span><b>[{{ $question->mark }}]</b></span>
                                        </h6>
                                        <div class="options_div text-left px-5">
                                            <div class="options">
                                                @foreach (explode(",",$question->options) as $answer)
                                                <span class=" py-1">{{ chr($loop->index +65) }}. {{ @$answer }}</span>
                                            
                                                @endforeach
                                            </div>

                                            <div class="image_">
                                                @if (@$question->attachment)
                                                <div class="px-5">
                                                    <img src="{{ asset($question->attachment)}}" class="img-fluid quspaper_type py-1" alt="Type Questions Image" />
                                                </div>
                                                @endif
                                            </div>
                                        
                                        </div>

                                    
                                        {{-- <h6 class="px-5">Answer</h6> --}}
                                    </div>
                                @endif

                                @if($question->question_type == "subquestion")
                                
                                    @foreach ($question->subquestion as $order => $ques)

                                    
                                        <div class="qus_box mt-3">

                                        

                                            <h6 class="d-flex justify-content-between">

                                                @if ($loop->first)

                                                <div class="d-flex">
                                                    <span>{{ @$count }}.{{ chr($loop->index +65) }}.&nbsp;</span>
                                                    <span> {{ $ques->question }}</span>
                                                </div>
                                            
                                                <span><b>[{{ $ques->mark  }}]</b></span>

                                                @else

                                                <div class="d-flex">
                                                    <span>&nbsp;&nbsp;&nbsp;{{ chr($loop->index +65) }}.</span>
                                                    <span> &nbsp;{{$ques->question}}</span>
                                                </div>
                                                
                                                <span><b>[{{  $ques->mark }}]</b></span>
                                                @endif
                                            
                                            </h6>
                                                
                                        
                                            
                                            {{-- <h6 class="px-5">Answer</h6> --}}
                                        </div>
                                    @endforeach
                                @endif
                            
                                
                            @endforeach

                        

                        

                            

                            
                            @php
                            $count=0;
                            @endphp
                            
                        @endforeach
    
                     
    
    
                       
    
                       
    
                        
    
                        {{-- <h6 class="mt-5 text-center">page <b>1</b> of <b>2</b></h6> --}}
    
                    </div>
                </div>
            </div>
    </section>
</div>


  

@endsection
@section('scripts')
    <script>
        console.log("hee");
        var button=document.querySelector(".print");

button.addEventListener("click",function(){
  
  
	$(".print-body").show();
	$(".sidebar-wrapper").hide();
	$(".infobody").hide();
	$(".questioninfo").hide();
	$(".top-header").hide();
	$(".footer").hide();
	$(".box-header").hide();
	$(".radius-15").hide();

	$('.page-content-wrapper').css('margin-left','0px');
	$('.page-content-wrapper').css('margin-top','0px');
	$(".page-wrapper").css("margin-top","0px");
	window.print();
    $(".print-body").hide();
	$(".infobody").show();
	$(".questioninfo").show()
	$(".sidebar-wrapper").show();
	$(".top-header").show();
	$(".footer").show();
	$(".box-header").show();
	$(".radius-15").show();

	$('.page-content-wrapper').css('margin-left','260px');
	$('.page-content-wrapper').css('margin-top','70px');
	$(".page-wrapper").css("margin-top","70px");
});
    </script>
@endsection
