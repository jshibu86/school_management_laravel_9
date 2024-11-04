@extends('layout::admin.master')

@section('title','attendance')
@section('style')
@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
   <style>
    .info_Section{
        padding: 11px;
    background-color: white;
    margin-bottom: 10px;
    }
   </style>
   

@endsection
@section('body')
    <div class="x_content">

       
        <div class="box-header with-border mar-bottom20">
           
            {{-- {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_attendance' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }} --}}

            {{-- @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif --}}
           

           
         
             <a class="btn btn-info btn-sm m-1 px-3" href="{{@$attendance_type == "daily" ? route('attendance.index') : route('attendance.hourlyindex')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{-- {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }} --}}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Attendance" : "Create Attendance"])

           {{-- <div class="col-12 info_Section">
            <span class="text-danger">To Change Attendance Type Click here <a href="{{ route("admin_site_configuration") }}">site settings</a></span>
           </div> --}}

           <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h1 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    {{ @$attendance_type == "hourly" ? "Hourly" : "Daily" }} Attendance 
                    </button>
                 </h1>
                <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                    <div class="accordion-body">
                        <div class="row">
                           
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Academic Year <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          @if (@$layout == "edit")
                                          <input type="hidden" name="academic_year" value="{{ @$data->academic_year }}"/>
                                          <input type="hidden" name="academic_term" value="{{ @$data->academic_term }}"/>
                                          <input type="hidden" name="class_id" value="{{ @$data->class_id }}"/>
                                          <input type="hidden" name="section_id" value="{{ @$data->section_id }}"/>
                                          <input type="hidden" name="term_id" value="{{ @$data->term_id }}"/>
                                              
                                          @endif
                                          {{ Form::select('academic_year',@$academicyears,@$data->academic_year ?@$data->academic_year : @$info['current_academic_year'],
                                          array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control" : 
                                          'single-select form-control termacademicyear','required' => 'required','placeholder'=>"Select Academic year",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                              </div>

                               <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Academic Term <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('academic_term',@$info['examterms'],@$data->academic_term ?@$data->academic_term : @$info['current_academic_term'],
                                          array('id'=>'timetableacyear1','class' => @$layout =="edit" ? " form-control" : 
                                          'single-select form-control ','required' => 'required','placeholder'=>"Select Academic Term",@$layout =="edit"? "disabled" : "")) }}
                                          
                                      </div>
                                </div>
                                     
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-3">
                                  <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Class <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                                            array('id'=>'class_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "" )) }}
                                        </div>
                                  </div>
                                       
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-3">
                                  <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Section <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('section_id',@$sections,@$data->section_id ,
                                            array('id'=>'section_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control','required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "" )) }}
                                        </div>
                                  </div>
                                       
                              </div>
                              @if (@$attendance_type == "daily")
                              <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Current Date <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                        {{Form::text('attendance_date',$date,array('id'=>"nodays",'class'=>"form-control col-md-7 col-xs-12" ,
                                        'placeholder'=>"date",'required'=>"required","readonly"))}}
                                      </div>
                                </div>
                                     
                            </div>
                              @endif
                              @if (@$attendance_type == "hourly")
                              <div class="col-md-3">
                                <button type="button" id="addatt" class="btn add_btn att_btn w-100 attendancebtn" name="hourly"> <i class="fa fa-plus" name="hourly"></i>&nbsp;&nbsp;Continue to enter attendance</button>
                            </div>
                            @else

                            <div class="col-md-3">
                                <button type="button" id="addatt" class="btn add_btn att_btn w-100 attendancebtn" name="daily"> <i class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get Students</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
           
           
        </div>

        <div class="attendanceinfo"></div>

        
       
       

      
    </div>

@endsection

@section("scripts")
<script type="module">
    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }

       
     window.sectionurl='{{route('section.index')}}';
     window.attendanceurl='{{route('attendance.index')}}';
    AttendanceConfig.AttendanceInit(notify_script)
    window.fetchstudents = "{{ route('exam.index') }}";
  
</script>
@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
