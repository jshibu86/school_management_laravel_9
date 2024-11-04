@extends('layout::admin.master')

@section('title','exam')
@section('style')
<link rel="stylesheet" href="{{asset('assets/backend/css/multistepquestion.css')}}">
<style>
 .type_content{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
 }
 .types{
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
}
.hr-time-picker{
    background: #fff !important;
    max-width: 100% !important
}
.error{

    color: red;
}
.prevbtn{
    position: absolute;
    top: -201px;
    right: -95px;
    background-color: #294dfe;
    color: #fff;
    padding: 4px 0px;
    width: 100px;
}

</style>
@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('exam.store'),  'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'exam-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('exam.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'exam-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_exam' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn_', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}



            @endif

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('exam.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit exam" : "Create Exam"])

           <div class="card">
            <div class="card-body">
                <div class="container py-5">

                    <form class="msfform pt-5">
                        <input type="hidden" name="hiddenpreview" id="hidden-preview" value="normal"/>
                        <div class="msfdiv py-5">

                            <ul id="progressbar" class="examprogressbar">
                                <li class="active" id="step1">Exam Configuration</li>
                                <li id="step2">Timing & Notification</li>
                                <li id="step3">Student</li>
                                <li id="step4">Question</li>
                            </ul>

                            <div class="clearfix"></div>

                            <div class="row justify-content-center">
                                <div class="col-md-9 mt-5">

                                    <fieldset>

                                        <div class="form-card step1">

                                            <div class="row">
                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                    <label for="acc_yr">Academic Year <span>*</span></label>

                                                    {{ Form::select('academic_year',@$academic_years,@$data->academic_year ,
                                                    array('id'=>'acyear','class' => 'single-select  form-control','required' => 'required',"placeholder"=>"Select Academic Year" )) }}
                                                    </div>

                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                    <label for="exam_type">Exam Type <span>*</span></label>


                                                    {{ Form::select('exam_type',@$exam_types,@$data->exam_type ,
                                                    array('id'=>'examtype','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Exam Type" )) }}
                                                    </div>
                                                </div>


                                            </div>

                                            <div class="row">


                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                    <label for="selc_cls">Class <span>*</span></label>

                                                        {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                                                         array('id'=>'class_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Class" )) }}
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group ">
                                                    <label for="exam_type">Section <span>*</span></label>

                                                        {{ Form::select('section_id',@$section_lists,@$data->section_id ,
                                                         array('id'=>'section_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Section" )) }}
                                                        </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                    <label for="exam_type">Subject <span>*</span></label>

                                                        {{ Form::select('subject_id',@$subject_lists,@$data->subject_id ,
                                                         array('id'=>'subject_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Subject" )) }}
                                                        </div>
                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <div class="item form-group">
                                                    <label for="selc_cls">Department <span>*</span></label>

                                                        {{ Form::select('department_id',@$department,@$data->department_id ,
                                                         array('id'=>'department_id','class' => 'single-select form-control','required' => 'required',"placeholder"=>"Select Department" )) }}
                                                        </div>
                                                </div>
                                            </div>

                                            <div class="row">


                                                <div class="col-md-6 mb-4">
                                                    <label for="maxmark">Max Mark <span>*</span></label>

                                                    <div class="item form-group ">
                                                        <input type="text" required value="{{ @$data->max_mark }}" id="maxmark" name="max_mark" class="form-control ques_ip" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" placeholder="Max Mark" />
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-4">
                                                    <label for="minmark">Min Mark <span>*</span></label>
                                                    <div class="item form-group">
                                                        <input type="text" required value="{{ @$data->min_mark }}" id="minmark"  name="min_mark" class="form-control ques_ip" placeholder="Min Mark" />
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="row">


                                                <div class="col-md-6 mb-4">
                                                    <label for="exam_type">Promotional Exam <span>*</span></label>
                                                    <select class="form-control proexam" name="promotion" id="exam_type">
                                                        <option>Yes </option>
                                                        <option>No </option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                        <input type="button" name="next" class="next action-button btn" value="Next" />
                                    </fieldset>

                                    <fieldset>

                                        <div class="form-card step2">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="quote_txt mb-4">
                                                        <h6 class="quote_head">Exam Timing</h6>
                                                        <p class="quote_para">Enter the exam date and time, as well as the exam timeline</p>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="examdate">Date <span>*</span></label>

                                                        <div class="item form-group  ">
                                                            <input type="date" required id="exdate" class="form-control  ques_ip" name="exam_date" value="{{ @$data->exam_date }}" />
                                                        </div>

                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="examtime">Time <span>*</span></label>
                                                        <div class="item form-group  ">
                                                            <input type="time" required id="extime" class="form-control  ques_ip" name="exam_time" value="{{ @$data->exam_time }}" />
                                                        </div>
                                                    </div>
                                                    <div class="mb-4">

                                                        <label for="examtime">Time Line <span>*</span></label>
                                                        <div class="item form-group  ">
                                                            <div class="hr-time-picker">
                                                                <div class="picked-time-wrapper">
                                                                    <input type="text" class="picked-time" name="timeline" value="{{ @$data->timeline }}">
                                                                </div>
                                                                <div class="pick-time-now">
                                                                    <div class="hours hr-timer">
                                                                        <div class="movable-area">
                                                                            <ul></ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="minutes hr-timer">
                                                                        <ul></ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="quote_txt mb-4">
                                                        <h6 class="quote_head">Exam Notification</h6>
                                                        <p class="quote_para">Provide the exam notice in order to notify the student and parent as soon as the exam date approaches.</p>
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="notifydate">Date <span>*</span></label>
                                                        <input type="date" id="notifydate" class="form-control ques_ip" value="{{ @$data->notification->notify_date }}" name="notify_date" />
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="notifytime">Time <span>*</span></label>
                                                        <input type="time" id="notifytime" class="form-control ques_ip" value="{{ @$data->notification->notify_time }}" name="notify_time" />
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="notifymsg">Message <span>*</span></label>
                                                        <textarea rows="6" id="notifymsg" class="form-control ques_ip" name="notify_message">{{ @$data->notification->notify_message }}</textarea>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <input type="button" name="previous" class="previous action-button-previous btn" value="Back" />
                                        <input type="button" name="next" class="next action-button btn" value="Next" />
                                    </fieldset>

                                    <fieldset>

                                        <div class="form-card step3">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="quote_txt">
                                                        <h6 class="quote_head">Student Exclusive</h6>
                                                        <p class="quote_para">You may exclude some students from a specific class for academic reasons.</p>
                                                    </div>


                                                    @if (@$layout == "edit")
                                                    <div class="like_switch my-4">
                                                        <label class="switch" for="stu_excludecb">
                                                              <input type="checkbox" id="stu_excludecb">
                                                              <span class="slider"></span>
                                                        </label>
                                                        <span class="quote_para ml-2">Enable if you will like to exclude student</span>
                                                    </div>
                                                    <div id="stu_exclude">

                                                        @foreach(explode(",",@$data->exclude_students) as $id )
                                                        <div class="mb-4 position-relative stu_box">
                                                            <select class="form-control exc_stu" id="exam_type" name="exclude_students[]">
                                                                @foreach ($exclude_students as $key=> $student )

                                                                    <option value="{{ $key }}" {{ $id == $key ? "selected" :"" }}>{{ $student }}</option>
                                                                @endforeach
                                                            </select>
                                                            <button type="button" class="btn remove_stu">&times;</button>
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" id="exclude_stu" class="btn addnew"> Add New</button>
                                                    @else
                                                    <div class="like_switch my-4">
                                                        <label class="switch" for="stu_excludecb">
                                                              <input type="checkbox" id="stu_excludecb">
                                                              <span class="slider"></span>
                                                        </label>
                                                        <span class="quote_para ml-2">Enable if you will like to exclude student</span>
                                                    </div>

                                                    <div id="stu_exclude"></div>
                                                    <button type="button" id="exclude_stu" class="btn addnew"> Add New</button>
                                                    @endif


                                                </div>

                                                <div class="col-md-6">
                                                    <div class="quote_txt">
                                                        <h6 class="quote_head">Student Inclusive</h6>
                                                        <p class="quote_para">You may add students from the same class or from other classes for academic purposes.</p>
                                                    </div>
                                                    <div class="like_switch my-4">
                                                        <label class="switch" for="stu_includecb">
                                                              <input type="checkbox" id="stu_includecb">
                                                              <span class="slider"></span>
                                                        </label>
                                                        <span class="quote_para ml-2">Enable if you will like to include student</span>
                                                    </div>
                                                    <div id="stu_include">

                                                    </div>
                                                    <button type="button" id="include_stu" class="btn addnew"> Add New</button>
                                                </div>
                                            </div>

                                        </div>

                                        <input type="button" name="previous" class="previous action-button-previous btn" value="Back" />
                                        <input type="button" name="next" class="next action-button btn" value="Next" />
                                    </fieldset>

                                    <fieldset>

                                        <div class="form-card step4">

                                            <div id="secdiv" class="pt-3">

                                            </div>

                                            <div class="row justify-content-end align-items-end">
                                                <div class="col-md-9">
                                                    <button type="button" id="addsec" class="btn"> Add Section</button>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="totalmark">Total Mark</label>
                                                    <input type="text" id="totalmark" readonly class="form-control ques_ip" onkeyup="totalvalidate();" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^0[^.]/, '0');" />
                                                </div>
                                                <div class="col-md-1">
                                                </div>
                                            </div>


                                        </div>

                                        <input type="button" name="previous" class="previous action-button-previous btn" value="Back" />


                                        @if (@$layout =="create")

                                        {{ Form::button('<i class="fa fa-eye"></i>&nbsp;Preview', array('type' => 'button', 'id' => 'submit_btn_', 'name' => 'preview' , 'value' => 'preview' , 'class' => 'btn btn-dark  prevbtn action-button')) }}
                                        @endif

                                        {{-- <input type="submit" name="next" class="next action-button btn" value="Submit" /> --}}
                                    </fieldset>

                                </div>

                            </div>

                        </div>

                    </form>
                </div>
            </div>
           </div>
    </div>
    {{Form::close()}}
    @endsection

    @section("scripts")



    <script type="module">

        @if (@$layout =="edit")
        $(".remove_stu").on("click", function() {
            $(this).closest('.stu_box').remove();
        });
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
        window.subjecturl="{{ route('subject.index') }}";
        window.sectionurl="{{ route('section.index') }}";
        window.fetchstudents="{{ route('exam.index') }}"
        window.deletequestion="{{ route('exam.deletequestion') }}"

        ExamConfig.examinit(notify_script);


</script>
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
    <script>

        $(document).ready(function(){

            var current_fs, next_fs, previous_fs;
            var opacity;
            var current = 1;
            var steps = $("fieldset").length;

            setProgressBar(current);

            $(".next").click(function(){

                var form =$("#exam-form");

                form.validate();

                if(form.valid() === true)
                {

                current_fs = $(this).closest('fieldset');
                next_fs = $(this).closest('fieldset').next();

                $("#progressbar li").eq( $("fieldset").index(next_fs) ).addClass("active");

                next_fs.show();
                current_fs.animate({opacity: 0}, {
                    step: function(now) {
                        opacity = 1 - now;

                        $("#progressbar li").eq( $("fieldset").index(current_fs) ).addClass("finish");
                        current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                        });
                        next_fs.css({'opacity': opacity});

                    },
                    duration: 500
                });
                setProgressBar(++current);

            }

            });

            $(".previous").click(function(){

                current_fs = $(this).parent();
                previous_fs = $(this).parent().prev();

                $("#progressbar li").eq( $("fieldset").index(current_fs) ).removeClass("active");

                previous_fs.show();

                current_fs.animate({opacity: 0}, {
                    step: function(now) {
                        opacity = 1 - now;

                        $("#progressbar li").eq( $("fieldset").index(previous_fs) ).removeClass("finish");
                        current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                        });
                        previous_fs.css({'opacity': opacity});
                    },
                    duration: 500
                });
                setProgressBar(--current);
            });

            function setProgressBar(curStep){
                var percent = parseFloat(100 / steps) * curStep;
                percent = percent.toFixed();
                $(".progress-bar").css("width", percent+"%")
            }

            $('input[type="submit"]').click(function(){
                return false;
            })

        });
    </script>


    <script>
        $(document).ready(function() {
            $(".hr-time-picker").hrTimePicker({
            disableColor: "#989c9c", // red, green, #000
            enableColor: "#ff5722", // red, green, #000
            arrowTopSymbol: "&#9650;", // ▲ -- Enter html entity code
            arrowBottomSymbol: "&#9660;" // ▼ -- Enter html entity code
        });
            $('.selectacc_yr').select2({
                placeholder: 'Select Academic Year',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect : true,
                selectOnClose: false,
            });

            $('.selectacc_term').select2({
                placeholder: 'Select Academic Term',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect : true,
                selectOnClose: false,
            });

            $('.selectexam_type').select2({
                placeholder: 'Select Exam Type',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect : true,
                selectOnClose: false,
            });

            $('.select_cls').select2({
                placeholder: 'Select Class',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect : true,
                selectOnClose: false,
            });

            $('.selectsec').select2({
                placeholder: 'Select Section',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect : true,
                selectOnClose: false,
            });

            $('.selectdep').select2({
                placeholder: 'Select Department',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect : true,
                selectOnClose: false,
            });

            $('.selectsub').select2({
                placeholder: 'Select Subject',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect : true,
                selectOnClose: false,
            });

            $('.proexam').select2({
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: true,
                closeOnSelect : true,
                selectOnClose: false,
            });

        });

    </script>

    <script>

        $(document).ready(function() {



            $(document).on("click", "#exclude_stu", function() {
                // @if (@$layout == "edit")
                // window.students_exclude={!! json_encode(@$exclude_students) !!};
                // window.students_include={!! json_encode(@$include_students) !!};
                // @endif



                if(typeof window.students_exclude === "undefined")
                {


                    var arr_=Object.entries({!! json_encode(@$exclude_students) !!}).map((data,i)=>
                    `<option value="${data[0]}">${data[1]}</option>`

                ).join("");

                }else{
                    var arr_= window.students_exclude.map((data)=>
                    `<option value="${data?.id}">${data?.text}</option>`

                ).join("");
                }


                $("#stu_exclude").append(`<div class="mb-4 position-relative stu_box">
                                            <select class="form-control exc_stu" id="exam_type" name="exclude_students[]">
                                                ${arr_}
                                            </select>
                                            <button type="button" class="btn remove_stu">&times;</button>
                                        </div>`);

                $(".remove_stu").on("click", function() {
                    $(this).closest('.stu_box').remove();
                });

                $('.exc_stu').select2({
                    placeholder: 'Student',
                    width: '100%',
                    allowHtml: true,
                    allowClear: false,
                    tags: false,
                    closeOnSelect : true,
                    selectOnClose: false,

                });
                $('#stu_exclude b[role="presentation"]').hide();

            });

            $("#stu_excludecb").click(function() {
                if($(this).is(":checked")) {
                    $("#exclude_stu").show();
                    $("#stu_exclude").show();
                } else {
                    $("#exclude_stu").hide();
                    $("#stu_exclude").hide();
                    $("#stu_exclude div").remove();
                }
            });

        });
    </script>

    <script>

        $(document).ready(function() {

            $(document).on("click", "#include_stu", function() {


                if(typeof window.students_include === "undefined")
                {


                    var arr=Object.entries({!! json_encode(@$include_students) !!}).map((data,i)=>
                    `<option value="${data[0]}">${data[1]}</option>`

                ).join("");

                }else{
                    var arr= window.students_include.map((data)=>
                    `<option value="${data?.id}">${data?.text}</option>`

                ).join("");
                }


                console.log( window.students_exclude, window.students_include);


                $("#stu_include").append(`<div class="mb-4 position-relative stu_box">
                                            <select class="form-control inclu_stu" id="exam_type" name="include_students[]">

                                                ${arr}
                                            </select>
                                            <button type="button" class="btn remove_stu">&times;</button>
                                        </div>`);

                $(".remove_stu").on("click", function() {
                    $(this).closest('.stu_box').remove();
                });

                $('.inclu_stu').select2({
                    placeholder: 'Student',
                    width: '100%',
                    allowHtml: true,
                    allowClear: false,
                    tags: false,
                    closeOnSelect : true,
                    selectOnClose: false,

                });
                $('#stu_include b[role="presentation"]').hide();

            });

            $("#stu_includecb").click(function() {
                if($(this).is(":checked")) {
                    $("#include_stu").show();
                    $("#stu_include").show();
                } else {
                    $("#include_stu").hide();
                    $("#stu_include").hide();
                    $("#stu_include div").remove();
                }
            });

        });
    </script>

    <script>
        $(document).ready(function(){
            $("#examdate").datepicker({
                numberOfMonths: 1,
                minDate: 0
            });
            $('#examtime').timepicker({
                timeFormat: 'h:mm p',
                dynamic: false,
                dropdown: true,
                interval : 60,
            });
            $('.examtimeline').select2({
                placeholder: 'Time Line',
                width: '100%',
                allowHtml: true,
                allowClear: false,
                tags: false,
                closeOnSelect : true,
                selectOnClose: false,
            });
            $("#notifydate").datepicker({
                numberOfMonths: 1,
                minDate: 0
            });
            $('#notifytime').timepicker({
                timeFormat: 'h:mm p',
                dynamic: false,
                dropdown: true,
                interval : 60,
            });
        });

    </script>


<!-- Questions script -->

<script>
	function totalvalidate(){
       var total = parseInt(document.getElementById("totalmark").value);
       var max = parseInt(document.getElementById("maxmark").value);
       if(total > max) {
          alert("Total Mark must be lesser than or Equal to Maximum Mark.");
       }
 	}

	function findTotal(){
		$(document).on('keyup', ".mark_cls", function() {
			var arr = document.getElementsByClassName('mark_cls');
			var tot=0;
			for(var i=0;i<arr.length;i++){
				if(parseFloat(arr[i].value))
				tot += parseFloat(arr[i].value);
			}

			var secarr = $(this).closest('.secrow').find('.mark_cls');
			var sectot=0;
			for(var i=0;i<secarr.length;i++){
				if(parseFloat(secarr[i].value))
				sectot += parseFloat(secarr[i].value);
			}

			document.getElementById('totalmark').value = tot;
			$(this).closest('.secrow').find('#sectotalmark').val(sectot);
			totalvalidate();
		});
	}



</script>

<script>

	$(document).ready(function() {

        var ordercount=0;

		$(document).on("click", "#addsec", function() {

			var secmainid=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var secid=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
			var qusdivid=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);


			$("#secdiv").append(`<div class="row secrow py-4" id="${secmainid}"><div class="col-md-12"><div class="remove_section"><i class="fa fa-times"></i></div><div class="d-flex align-items-end"><div class="col-10 pl-0 d-flex align-items-center"><label class="qus_label mb-0 mr-3" for="${secid}">Section Name </label><input type="text" id="${secid}" placeholder="Section Name" class="form-control" name="section[section${secmainid}][]" /></div><div class="col-2 pr-0"><label for="totalmark" class="text-nowrap secmark_label">Section Total Mark</label><input type="text" id="sectotalmark" class="form-control ques_ip" name="section[section${secmainid}][totalmark]" readonly onkeyup="totalvalidate();" /></div></div><div class="d-flex justify-content-between mt-4"><button type="button" data-id="${secid}" class="btn qus-btns" id="fillintheblanks">Fill in the Blanks</button><button type="button"  data-id="${secid}" class="btn qus-btns" id="choosethebestans">Choose the Best Answer</button><button type="button" class="btn qus-btns"  data-id="${secid}" id="yesornoqus">Yes/No Questions</button><button type="button" class="btn qus-btns"  data-id="${secid}" id="typequs">Define Type Questions</button><button type="button" class="btn qus-btns"  data-id="${secid}" id="shortqus">Short Questions</button><button type="button" class="btn qus-btns"  data-id="${secid}" id="longqus">Long Questions</button></div><div id="qusdiv" class="pt-4 ${qusdivid}"></div></div></div>`);

			function findTotal2(){
				var arr2 = document.getElementsByClassName('mark_cls');
				var tot2=0;
				for(var i=0;i<arr2.length;i++){
					if(parseFloat(arr2[i].value))
						tot2 += parseFloat(arr2[i].value);
				}

				var secarr2 = $('#' + secmainid + ' .mark_cls').closest('.secrow').find('.mark_cls');
				var sectot2=0;
				for(var i=0;i<secarr2.length;i++){
					if(parseFloat(secarr2[i].value))
					sectot2 += parseFloat(secarr2[i].value);
				}

				document.getElementById('totalmark').value = tot2;
				$('#' + secmainid + ' .mark_cls').closest('.secrow').find('#sectotalmark').val(sectot2);
				totalvalidate();
			}


			// Qus 1 script
			$(document).on("click", '#' + secmainid + ' #fillintheblanks', function() {

                ordercount++;



				var idqus=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idans=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idmark=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

				updateIndex = function() {
					$('#' + secmainid + ' .inc_span').each(function(i) {
						$(this).html(i + 1);
					});
				};


				$(this).closest('.secrow').find("#qusdiv")
                .append(`<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-6"><label for="${idqus}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><input type="text" name="section[section${secmainid}][questions][fillblanks][${ordercount}][]" id="${idqus}" class="form-control ques_ip" /></div><div class="col-md-3"><label for="${idans}">Answer</label><input type="text" name="section[section${secmainid}][questions][fillblanks][${ordercount}][answer][]" id="${idans}" class="form-control ques_ip" /></div><div class="col-md-2"><label for="${idmark}">Mark</label><input type="text" name="section[section${secmainid}][questions][fillblanks][${ordercount}][mark][]" id="${idmark}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
				updateIndex();
				findTotal();

				$(".remove_ques").on("click", function() {
					updateIndex = function() {
						$('#' + secmainid + ' .inc_span').each(function(i) {
							$(this).html(i + 1);
						});
					};
					$(this).closest('.tab_row').remove();
					updateIndex();
					findTotal2();
				});

			});


			// Qus 2 script
			$(document).on("click", '#' + secmainid + ' #choosethebestans', function() {

                ordercount++;

				var idqus2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idoptionradioname=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idoptionradio1=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idoptionradio2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idoptionradio3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idoptionradio4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idoption1=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idoption2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idoption3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idoption4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idmark2=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var choosediv=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

				updateIndex = function() {
					$('#' + secmainid + ' .inc_span').each(function(i) {
						$(this).html(i + 1);
					});
				};

				$(this).closest('.secrow').find("#qusdiv").append(`<div class="tab_row mb-3" id="${choosediv}"><div class="row align-items-end"><div class="col-md-9"><div class="row"><div class="col-md-12"><label for="${idqus2}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><input type="text" name="section[section${secmainid}][questions][choose_best][${ordercount}][]" id="${idqus2}" class="form-control ques_ip" /></div></div><div class="row mt-3" id="optionrow"><div class="col-md-6 pr-0 d-flex"><input  type="radio" value="0" checked name="section[section${secmainid}][questions][choose_best][${ordercount}][answer][]" id="${idoptionradio1}" class="radio_cbox" name="section[section${secmainid}][questions][choose_best][${ordercount}][answer][]" /><label><input type="text" name="section[section${secmainid}][questions][choose_best][${ordercount}][options][]" value="a. Option 1" id="${idoption1}" class="form-control radio_txt" /></label></div><div class="col-md-6 pr-0 d-flex"><input type="radio" value="1"  name="section[section${secmainid}][questions][choose_best][${ordercount}][answer][]" id="${idoptionradio2}" class="radio_cbox" /><label><input type="text" name="section[section${secmainid}][questions][choose_best][${ordercount}][options][]" value="b. Option 2" id="${idoption2}" class="form-control radio_txt" /></label></div><div class="col-md-6 pr-0 d-flex"><input type="radio" value="2"  name="section[section${secmainid}][questions][choose_best][${ordercount}][answer][]" id="${idoptionradio3}" class="radio_cbox" /><label><input type="text" name="section[section${secmainid}][questions][choose_best][${ordercount}][options][]" value="c. Option 3" id="${idoption3}" class="form-control radio_txt" /></label></div><div class="col-md-6 d-flex"><input type="radio" value="3" data-last="3"  name="section[section${secmainid}][questions][choose_best][${ordercount}][answer][]" id="${idoptionradio4}" class="radio_cbox" /><label><input type="text" name="section[section${secmainid}][questions][choose_best][${ordercount}][options][]" value="d. Option 4" id="${idoption4}" class="form-control radio_txt" /></label></div></div><div class="row mt-3">
													<div class="col-md-12">
														<button type="button" data-order="${ordercount}" data-id="${secmainid}" data-last="3" id="addoption" class="btn"> Add Option</button>
													</div>
												</div></div><div class="col-md-2"><label for="${idmark2}">Mark</label><input type="text" name="section[section${secmainid}][questions][choose_best][${ordercount}][mark][]" id="${idmark2}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
				updateIndex();
				findTotal();

				$(document).on("click", '#' + secmainid + ' #addoption', function() {

                    var  sectionNum=$(this).attr("data-id");
                    var  order=$(this).attr("data-order");
                    var  last=$(this).attr("data-last");

					var idoptionradionew=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
					var idoptionnew=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

					$(this).closest('#' + choosediv + '.tab_row').find("#optionrow").append(`<div class="col-md-6 d-flex">

														<input type="radio" value="${Number(last) + 1}" name="section[section${sectionNum}][questions][choose_best][${order}][answer][]" id="${idoptionradionew}" class="radio_cbox" />
														<label>
															<input type="text" name=section[section${sectionNum}][questions][choose_best][${order}][options][]" value="New Option" id="${idoptionnew}" class="form-control radio_txt" />
														</label>
													</div>`);

                                                    $(this).attr("data-last",+last+1);

				});

				$(".remove_ques").on("click", function() {
					updateIndex = function() {
						$('#' + secmainid + ' .inc_span').each(function(i) {
							$(this).html(i + 1);
						});
					};
					$(this).closest('.tab_row').remove();
					updateIndex();
					findTotal2();
				});

			});

			// Qus 3 script
			$(document).on("click", '#' + secmainid + ' #yesornoqus', function() {
                ordercount++;

				var idqus6=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idmark6=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var radioname=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

				updateIndex = function() {
					$('#' + secmainid + ' .inc_span').each(function(i) {
						$(this).html(i + 1);
					});
				};

				$(this).closest('.secrow').find("#qusdiv").append(`<div class="tab_row mb-3"><div class="row"><div class="col-md-6"><label for="${idqus6}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><input type="text" name="section[section${secmainid}][questions][yesorno][${ordercount}][]" id="${idqus6}" class="form-control" /></div><div class="col-md-3"><label class="mr-3">Answer</label><div class="d-flex"><input type="radio" value="0" name="section[section${secmainid}][questions][yesorno][${ordercount}][answer][]" id="yesans" class="radio_cbox" /><label><input type="text" name="section[section${secmainid}][questions][yesorno][${ordercount}][options][]" value="Yes" id="yestxtyes" class="form-control radio_txt" /></label></div><div class="d-flex"><input type="radio" name="section[section${secmainid}][questions][yesorno][${ordercount}][answer][]" value="1" id="noans" class="radio_cbox" /><label><input type="text" name="section[section${secmainid}][questions][yesorno][${ordercount}][options][]" value="No" id="yestxtno" class="form-control radio_txt" /></label></div></div><div class="col-md-2"><label for="${idmark6}">Mark</label><input type="text" name="section[section${secmainid}][questions][yesorno][${ordercount}][mark][]" id="${idmark6}" class="form-control mark_cls" /></div><div class="col-md-1 mt_31"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
				updateIndex();
				findTotal();

				$(".remove_ques").on("click", function() {
					updateIndex = function() {
						$('#' + secmainid + ' .inc_span').each(function(i) {
							$(this).html(i + 1);
						});
					};
					$(this).closest('.tab_row').remove();
					updateIndex();
					findTotal2();
				});

			});

			// Qus 4 script
			$(document).on("click", '#' + secmainid + " #typequs", function() {

                ordercount++;

				var idqus3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idans3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var fileid=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idmark3=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

				updateIndex = function() {
					$('#' + secmainid + ' .inc_span').each(function(i) {
						$(this).html(i + 1);
					});
				};

				$(this).closest('.secrow').find("#qusdiv").append(`<div class="tab_row py-3"><div class="row"><div class="col-md-6"><div class="row"><div class="col-md-12"><p class="mb-2"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Upload Image</p><input type="file" name="section[section${secmainid}][questions][typequs][${ordercount}][]" id="${fileid}" placeholder="Choose the file" class="form-control file_ip imag" accept=".xlsx,.docx,.pdf,.txt,.jpeg,.gif,.jpg,.png,.mp4" /><span class="ch_span">Choose the file... Ex:jpeg, gif, png, pdf</span><label for="${fileid}" class="upload_cls btn mt-3">Upload</label></div><div class="col-md-12 mt-3"><label for="${idqus3}">Add Question</label><input type="text" name="section[section${secmainid}][questions][typequs][${ordercount}][question][]" id="${idqus3}" class="form-control" /></div><div class="col-md-12 mt-3"><label for="${idans3}">Answer</label><textarea rows="5" name="section[section${secmainid}][questions][typequs][${ordercount}][answer]" id="${idans3}" class="form-control"></textarea></div></div></div><div class="col-md-5"><label for="preview_3">Image Preview</label><div class="img_box"><img id="ImgPreview" src="" class="preview1" style="display: none;"/></div><div class="mt-3 col-5 float-right pr-0 pl-4"><label for="${idmark3}">Mark</label><input type="text" name="section[section${secmainid}][questions][typequs][${ordercount}][mark][]" id="${idmark3}" class="form-control mark_cls" /></div></div><div class="col-md-1 align-self-center"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
				afterClick();
				updateIndex();
				findTotal();

				$(".remove_ques").on("click", function() {
					updateIndex = function() {
						$('#' + secmainid + ' .inc_span').each(function(i) {
							$(this).html(i + 1);
						});
					};
					$(this).closest('.tab_row').remove();
					updateIndex();
					findTotal2();
				});

			});

			// Qus 5 script
			$(document).on("click", '#' + secmainid + " #shortqus", function() {
                ordercount++;

				var idqus4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idans4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idmark4=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

				updateIndex = function() {
					$('#' + secmainid + ' .inc_span').each(function(i) {
						$(this).html(i + 1);
					});
				};

				$(this).closest('.secrow').find("#qusdiv").append(`<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus4}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><input type="text" required name="section[section${secmainid}][questions][shortques][${ordercount}][]" id="${idqus4}" class="form-control ques_ip" /></div><div class="col-md-9 mt-3"><label for="${idans4}">Answer</label><textarea rows="5" name="section[section${secmainid}][questions][shortques][${ordercount}][]" id="${idans4}" required class="form-control ques_ip"></textarea></div><div class="col-md-2"><label for="${idmark4}">Mark</label><input type="text" name="section[section${secmainid}][questions][shortques][${ordercount}][mark][]" id="${idmark4}" required class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
				updateIndex();
				findTotal();

				$(".remove_ques").on("click", function() {
					updateIndex = function() {
						$('#' + secmainid + ' .inc_span').each(function(i) {
							$(this).html(i + 1);
						});
					};
					$(this).closest('.tab_row').remove();
					updateIndex();
					findTotal2();
				});

			});

			// Qus 6 script
			$(document).on("click", '#' + secmainid + " #longqus", function() {
                ordercount++;

				var idqus5=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idans5=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);
				var idmark5=Math.random().toString(36).replace(/[^a-z]+/g, '').substr(2, 10);

				updateIndex = function() {
					$('#' + secmainid + ' .inc_span').each(function(i) {
						$(this).html(i + 1);
					});
				};

				$(this).closest('.secrow').find("#qusdiv").append(`<div class="tab_row mb-3"><div class="row align-items-end"><div class="col-md-9"><label for="${idqus5}"><span><span class="inc_span"></span>&nbsp;.&nbsp;</span>Question </label><input type="text" required name="section[section${secmainid}][questions][longques][${ordercount}][]" id="${idqus5}" class="form-control ques_ip" /></div><div class="col-md-9 mt-3"><label for="${idans5}">Answer</label><textarea rows="10" name="section[section${secmainid}][questions][longques][${ordercount}][]"  id="${idans5}" class="form-control ques_ip"></textarea></div><div class="col-md-2"><label for="${idmark5}">Mark</label><input type="text" name="section[section${secmainid}][questions][longques][${ordercount}][mark][]" id="${idmark5}" class="form-control ques_ip mark_cls" /></div><div class="col-md-1"><button type="button" class="remove_ques btn btn-danger"><i class="fa fa-trash"></i></button></div></div></div>`);
				updateIndex();
				findTotal();

				$(".remove_ques").on("click", function() {
					updateIndex = function() {
						$('#' + secmainid + ' .inc_span').each(function(i) {
							$(this).html(i + 1);
						});
					};
					$(this).closest('.tab_row').remove();
					updateIndex();
					findTotal2();
				});

			});


			// section remove
			$(".remove_section").on("click", function() {
				$(this).closest('.secrow').remove();
				findTotal2();
			});

		});

	});

</script>

<script>

	function afterClick()
	{
		function readIMG(input, imgControlName) {
			if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$(imgControlName).attr('src', e.target.result);
			}
			reader.readAsDataURL(input.files[0]);
			}
		}

		$('.imag').change(function() {

			$(this).closest('.tab_row').find('.file_ip').css('color','#7b7b7b');
			$(this).closest('.tab_row').find('.ch_span').hide();

			var ext = $(this).closest('.tab_row').find(".imag").val().split('.').pop();

			if(ext == 'mp4'){

				$(this).closest('.tab_row').find(".img_box").html(`<video controls autoplay src="" class="vids"></video>`);

				var imgControlName = $(this).closest('.tab_row').find('.vids');
				readIMG(this, imgControlName);
				$(this).closest('.tab_row').find('.vidssrc').show();

			}
			else if (ext =="pdf" || ext =="xlsx" || ext =="docx") {

				$(this).closest('.tab_row').find(".img_box").html(`<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`);

				$(this).closest('.tab_row').find('#ImgPreview').show();
				var image = $(this).closest('.tab_row').find('.preview1');

				switch (ext) {
					case 'pdf':
						image[0].src = "{{ URL::to('/') }}/assets/docs/pdf.png";
						break;
					case 'xlsx':
						image[0].src = "{{ URL::to('/') }}/assets/docs/xlsx.png";
						break;
					case 'docx':
						image[0].src = "{{ URL::to('/') }}/assets/docs/docimage.png";
						break;
				}

			}
			else{

				$(this).closest('.tab_row').find(".img_box").html(`<img id="ImgPreview" src="" class="preview1" style="display: none;" type="application/pdf"/>`);

				var imgControlName = $(this).closest('.tab_row').find('#ImgPreview');
				readIMG(this, imgControlName);
				$(this).closest('.tab_row').find('#ImgPreview').show();
			}
		});
	}
</script>


    @endsection



    @section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
