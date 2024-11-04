@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{ asset('assets/backend/css/attendance.css') }}">
<style>
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
        background: radial-gradient(#111 50%, #000 100%);
        overflow: hidden;
        cursor: pointer;
        
    }

    img {
        max-width: 100%;
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
    .reminderdate{
        background-color: #fff !important;
    }
    

</style>
<div class="x_content">

    {{-- @if($layout == "create") --}}
        {{ Form::open(array('role' => 'form', 'route'=>array('confirm_fees_reminder'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'homework-form','novalidate' => 'novalidate')) }}
    {{-- @elseif($layout == "edit")
        {{ Form::open(array('role' => 'form', 'route'=>array('homework_evaluate',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
    @endif --}}
   
         @foreach($unpaid_students as $student)
           <input type="hidden" name="student[]" value="{{$student->id}}">
             @php
                      
                $exist = collect($data1)->where('student_id', $student->id)->first();

                if($exist){
                    $paid_amount = $exist->sum('paid_amount');
                    $amount = $total_amount - $paid_amount;
                }
                else{
                    $amount = $total_amount;                       
                }
              
             @endphp 
             <input type="hidden" name="unpaid_amount[]" value="{{$unpaidAmounts[$student->id]}}">    
         @endforeach
           <input type="hidden" name="academic_year" value="{{$academic_year}}">
           <input type="hidden" name="class_id" value="{{$class_id}}">
           <input type="hidden" name="section_id" value="{{$section_id}}">
           <input type="hidden" name="school_type" value="{{$school_type}}">
         <div class="card">
            <div class="card-body">
                <h4 class="mb-0">Fee Reminder</h4>
                <hr/>
                <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation"> <a class="nav-link active" id="pills-instant-tab" data-bs-toggle="pill"
                            href="#pills-instant" role="tab" aria-controls="pills-instant" aria-selected="true">Instant Reminder</a>
                    </li>
                    <li class="nav-item" role="presentation"> <a class="nav-link" id="pills-schedule-tab" data-bs-toggle="pill"
                            href="#pills-schedule" role="tab" aria-controls="pills-schedule" aria-selected="false">Schedule Reminder</a>
                    </li>
        
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active show" id="pills-instant" role="tabpanel" aria-labelledby="pills-instant-tab">
                        <div class="form-group">
                            <label for="reminder_text" class="form-label">Fees reminder Message:</label><br>
                            @include('layout::widget.ckeditor',['name'=>'reminder_text','id'=>'reminder_text','class'=>'w-50','content'=>@$reminder_text ?@$reminder_text: old("homework_description") ])
                        </div>
                      

                        <div class="box-header with-border mar-bottom20 mt-2">

                            {{ Form::button('&nbsp;&nbsp;&nbsp;Confirm Reminder', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit' , 'value' => 'instant' , 'class' => 'btn btn-success')) }}
                        </div>
                    </div>
                    <div class="tab-pane fade " id="pills-schedule" role="tabpanel" aria-labelledby="pills-schedule-tab">
                        
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="schedule_date">Date <span class="required">*</span>
                                    </label>                                 
                                        <div class="feild">
                                            {{ Form::text('schedule_date',@$date ,
                                            array('id'=>'schedule_date','class' => ' form-control reminderdate startdate shedule_input',"placeholder"=>"select date","required"=>"required" ,"readonly" )) }}
                                        </div> 
                                        @if ($errors->has('schedule_date'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('schedule_date') }}</div>
                                        @endif
                                </div>
                                   
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <label for="schedule_time" class="control-label margin__bottom">Time <span>*</span></label>
                                <div class="item form-group  ">
                                    <input type="time" required
                                        class="form-control shedule_input" name="schedule_time"
                                        id="schedule_time"
                                        value="{{ date('H:i', strtotime(@$data1->exam_time)) }}" required/>

                                        @if ($errors->has('schedule_time'))
                                            <div class="invalid-feedback d-block">{{ $errors->first('schedule_time') }}</div>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="reminder_text" class="form-label">Fees reminder Message <span>*</span></label><br>
                            @include('layout::widget.ckeditor',['name'=>'shedule_reminder_text','id'=>'shedule_reminder_text','class'=>'w-50 shedule_input','content'=>@$reminder_text ?@$reminder_text : old("homework_description") ])
                            @if ($errors->has('shedule_reminder_text'))
                                <div class="invalid-feedback d-block">{{ $errors->first('shedule_reminder_text') }}</div>
                            @endif
                        </div>
                       
                        <div class="box-header with-border mar-bottom20 mt-2">

                            {{ Form::button('&nbsp;&nbsp;&nbsp;Confirm Reminder', array('type' => 'submit', 'id' => 'submit_btn1', 'name' => 'submit' , 'value' => 'shedule' , 'class' => 'btn btn-success' , "required"=>"required")) }}
                        </div>
                    </div>  
                   
                </div>        
                 
              
              
            </div>
        </div>

    
   
       

    {{Form::close()}}
</div>
<script>
    $(document).ready(function(){
       
        $("#submit_btn1").on("click",function(){
                var date = $("#schedule_date").val();
                var time = $("#schedule_time").val();
                var text = $("#shedule_reminder_text").val();

            
                $(".alert").remove();

            
                if (!date || !time || !text) {
                
                    var alertDiv = '<div class="alert alert-danger mt-2">Please Fill All The Required Fields.</div>';
                    $(this).closest('.box-header').before(alertDiv);  
                
                    return false;
                }
               

            
                $("#homework-form").submit();
        });
    });

    $(".shedule_input").on("input",function(){
        var date = $("#schedule_date").val();
        var time = $("#schedule_time").val();
        var text = $("#shedule_reminder_text").val();

        if (date && time && text) {
            $(".alert").remove();
        }
    });

    document.getElementById('schedule_date').addEventListener('copy', function(e) {
        e.preventDefault();
        alert('Copying is disabled!');
    });

    document.getElementById('schedule_date').addEventListener('paste', function(e) {
        e.preventDefault();
        alert('Pasting is disabled!');
    });

    document.getElementById('schedule_date').addEventListener('cut', function(e) {
        e.preventDefault();
        alert('Cutting is disabled!');
    });
   
 </script>
<script type="module">
    function notify_script(title, text, type, hide) {
        new PNotify({
            title: title,
            text: text,
            type: type,
            hide: hide,
            styling: 'fontawesome'
        })
    }


    window.sectionurl = '{{ route('section.index') }}';
    window.classurl = '{{ route('schooltype.index') }}';
    window.getstudentperformanceinfo = "{{ route('studentperformance') }}";
   
   
    AttendanceConfig.AttendanceInit(notify_script);
    AcademicConfig.Leaveinit(notify_script);
    //grade -- Class,Section List
    PromotionConfig.PromotionInit(notify_script);
    // ReportConfig.ReportInit(notify_script);
    StudentPerformance.StudentPerformanceInit(notify_script);
    //grade chart
    Account.AccountInit();

    GeneralConfig.generalinit(notify_script);
   
    // window.student_overall_report_info = "{{ route('grade_student_report_view') }}"
    // ReportConfig.getStudentsMarkinfo(id,academic_year,position,term);

  
</script>
