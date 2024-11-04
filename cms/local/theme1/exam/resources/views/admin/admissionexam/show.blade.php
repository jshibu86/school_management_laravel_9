@extends('layout::admin.master2')

@section('title','profile')
@section('style')
@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{asset('assets/backend/css/onlineexam.css')}}">
@endsection


@section('body')
<section>
    {{ Form::open(array('role' => 'form', 'route'=>array('admissionexam.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'exam-form','novalidate' => 'novalidate')) }}
	<div class="container py-5">
    
		<form>
            <div class="alertmessage alert-danger" role="alert">
                <div>
                    <h6 >Warning</h6>
                    <small>Do Not Press Back/refresh Button</small>
                    <br/>
                    <small>Exam Duration : {{ @$exam->timeline }}</small>
                    <br/>
                    <small>If Timer Completed Exam Automatically Submitted</small>
                    <br/>
                    <small>Don't Close the Current Window</small>
                </div>
                @if(empty(@$error))
                    <div style="font-size: 30px;" class="mt-3">
                        <p id="onlineexamtimer"></p>                  
                    </div>
                @endif
                </div>
              
                <div class="text-center exam__information  mt-2 mb-2">
                    <div class="row">
                        <div class="col-3" style="text-align: left">
                            @if(isset(Configurations::getConfig('site')->imagec))
                                <img src="{{ Configurations::getConfig('site')->imagec }} " width="80" alt="nologo"/>
                            @endif
                        </div>
                        <div class="col-6 company-details">
                            <h2 class="name">
                            <a target="_blank" href="javascript:;">
                                {{isset(Configurations::getConfig('site')->school_name) ?Configurations::getConfig('site')->school_name : 'School'}}
                            </a>
                            </h2>
                            <div>{{ Configurations::getConfig('site')->place }}, {{ Configurations::getConfig('site')->city }},{{ Configurations::getConfig('site')->pin_code }},{{ Configurations::getConfig('site')->country }}</div>
                            
                            <div><span>Contact : {{ Configurations::getConfig('site')->school_phone }}</span></div>
                            <div><span>Email : {{ Configurations::getConfig('site')->school_email }}</span></div>
                            <div class="mt-2"><span>Class : <b>{{ @$exam->class->name }}</b></span>,<span>Section :  <b>{{ @$exam->section->name }}</b></span>,<span>Subject :  <b>{{ @$exam->subject->name }}</b></span></div>
                        </div>
                        
                        <div class="col-3" style="text-align: right">
                            <img class="" src="{{@$data->user->images ?@$data->user->images :asset('assets/images/staff.jpg')   }}"  width="80" alt="nologo">
                        </div>
                    </div>
                </div>
                
			<div class="row take_exam_tab">
                <input type="hidden" name="admission_id" value="{{ @$student_data->id }}"/>
                <input type="hidden" name="exam_id" value="{{ @$exam->id }}"/>
                
				<div class="col-md-7">
                    
					<div class="tab_box" id="TabList">

						<div class="empty_sp">
                            @if(@$error)
                                <div class="alertmessage alert-danger" role="alert">
                                    <strong> {{ @$error }}</strong>
                                </div>
                            @else
                        </div>

                        
                            <div class="tab-content">
                                <!-- Tab Start -->
                                @forelse (@$questions as $question)
                                <div class="tab-pane {{ $loop->index+1 == 1 ? "active" :"" }}" id="tab-{{ $loop->index+1 }}" data-id="{{ $question->id }}">
                                    <div>
                                        
                                        <h3 class="noquscls">Question {{ $loop->index+1 }} of {{ sizeof(@$questions) }}</h3>
                                
                                        @if ($question->question_type == "fillintheblanks")
                                        <div class="d-flex justify-content-between mt-4">
                                            <h4 class="qus_txt">{{ @$question->question }}</h4>
                                            <h4 class="mark_txt">{{ @$question->mark }} Mark</h4>
                                        </div>
                                        <div class="row my-3">
                                            <input type="text" name="questions[{{ @$question->id }}][answer]" class="form-control fill_blanks_text{{ $question->id }}" required/>
                                        </div> 
                                        @endif

                                        @if ($question->question_type == "yesorno")
                                        <div class="d-flex justify-content-between mt-4">
                                            <h4 class="qus_txt">{{ @$question->question }}</h4>
                                            <h4 class="mark_txt">{{ @$question->mark }} Mark</h4>
                                        </div>

                                        @if ($question->attachment)
                                        <div class="row my-3">
                                            <img src="{{ asset($question->attachment)}}" class="img-fluid quspaper_type py-1"  alt="Type Questions Image" /></div>                                        
                                        @endif
                                        <div class="row my-3">
                                            @foreach (explode(",",$question->options) as $yesornokey=> $answer)
                                            <div class="col-md-6 d-flex align-items-center">
                                                <span class="ans_span">
                                                    <input type="radio" name="questions[{{ @$question->id }}][answer]" id="qus1ans1" value="{{ $yesornokey }}" class=" take_exam_radio take_exam_radio{{ $question->id }}" />
                                                    <label class="mb-0" for="qus1ans1">{{@$answer }}</label>
                                                </span>
                                            </div>
                                        
                                            @endforeach
                                            
                                            
                                        </div>
                                        
                                        @endif

                                        @if ($question->question_type == "choosebest")
                                        <div class="d-flex justify-content-between mt-4">
                                            <h4 class="qus_txt">{{ @$question->question }}</h4>
                                            <h4 class="mark_txt">{{ @$question->mark }} Mark</h4>
                                        </div>
                                        @if ($question->attachment)
                                        <div class="row my-3">
                                            <img src="{{ asset($question->attachment)}}" class="img-fluid quspaper_type py-1" alt="Type Questions Image"  /></div>                                        
                                        @endif
                                        <div class="row my-3">
                                        @foreach (explode(",",$question->options) as $choosekey=> $answer)
                                            <div class="col-md-6 d-flex align-items-center">
                                                <span class="ans_span">
                                                    <input type="radio" name="questions[{{ @$question->id }}][answer]" id="qus1ans1" value="{{ $choosekey }}" class="take_exam_radio take_exam_radio_choose{{ $question->id }}" />
                                                    <label class="mb-0" for="qus1ans1">{{@$answer }}</label>
                                                </span>
                                            </div>
                                        
                                        @endforeach
                                        </div>
                                        @endif
                                        
                                    </div>
                                </div>
                                @empty
                                    
                                @endforelse
                                
                                <!-- Tab End -->
                        
                                <div class="take_exam_btns mt-5 pt-4">
                                    <button type="button" class="btn bg_blue btnPrevious"><i class="fa fa-angle-left mr-2"></i>Previous</button>

                                    {{-- <button type="button" class="btn bg_gray"> Mark For Review & Next</button> --}}

                                    <button type="button" class="btn bg_blue btnNext next_question">Next<i class="fa fa-angle-right ml-2"></i></button>

                                    <button type="button" class="btn bg_gray clear_answer">Clear Answer</button>

                                    <button type="submit" class="btn bg_red">Finish</button>
                                </div>

                            </div>
                       
					</div>

				</div>

				<div class="col-md-5 qus_sidebar py-3">

					<h4 class="siderbar_head mb-5">Computer Overhauls</h4>

					<ul class="nav nav-tabs">

                        @forelse (@$questions as $question )
                            
						<li class="nav-item">
							<a id="tabright{{ $question->id }}" href="#tab-{{ $loop->index+1 }}" data-bs-toggle="tab" class="onlineexamnav nav-link {{ $loop->index+1 == 1 ? "active" :""}}">{{ $loop->index+1 }}</a>
						</li>
                        @empty
                            
                        @endforelse						
											
					</ul>

					{{-- <div>
						<h4 class="siderbar_head my-4">Summary</h4>
						<div class="row">
							<div class="col-md-6 d-flex align-items-center">
								<h2 class="sum_box sum_box_answered bg_blue">0</h2>
								<h6 class="sum_txt">Answered</h6>
							</div>
							
							<div class="col-md-6 d-flex align-items-center">
								<h2 class=" sum_box sum_box_not_answered bg_red">0</h2>
								<h6 class="sum_txt">Not Answered</h6>
							</div>
							<div class="col-md-6 d-flex align-items-center">
								<h2 class=" sum_box sum_box_not_visited bg_gray">{{ sizeof(@$questions) }}</h2>
								<h6 class="sum_txt">Not Visited</h6>
							</div>
						</div>
					</div> --}}
				</div>

			</div>
		</form>
        
	</div>

    {{ Form::close() }}
    @endif
</section>
@endsection


@section("scripts")

<script type="module">
     @if(isset($error))
        // If there is an error, assign a default value (e.g., 0) or handle as needed
        var totalMinutes = 0;
    @else
        // No error, assign the value from the server-side variable
        var totalMinutes = @json($totalMinutes);
    @endif
   
    function notify_script(title,text,type,hide) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            hide: hide,
            styling: 'bootstrap3'
        })
    }
   
    ExamConfig.onlineexaminit(notify_script,totalMinutes);


</script>
<script>

	$(document).ready(function(){
		$("ul.nav-tabs li a").click(function() {
			$(this).addClass('qusactive');
		});
	});

</script>
<script>
	function bootstrapTabControl(){

		var i, items = $('.onlineexamnav'), pane = $('.tab-pane');


        console.log(items,"from boot");

		// $('.btnNext').on('click',function(){
			
		// });

		$('.btnPrevious').on('click',function(){
			for(i = 0; i < items.length; i++){
				if($(items[i]).hasClass('active') ==true){
					break;
				}
			}

			if(i != 0){
				$(items[i]).removeClass('active');
				$(items[i-1]).addClass('active');
				$(pane[i]).removeClass('show active');
				$(pane[i-1]).addClass('show active');
			}
		});

	}

	bootstrapTabControl();

</script>
<script>

    window.onload=function(){
     $("#invoice").show();
	$(".sidebar-wrapper").hide();
	$(".top-header").hide();
	$(".footer").hide();
	$(".box-header").hide();
	$(".radius-15").hide();

	$('.page-content-wrapper').css('margin-left','0px');
	$('.page-content-wrapper').css('margin-top','0px');
	$(".page-wrapper").css("margin-top","0px");
    };
</script>
@endsection