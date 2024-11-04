@extends('layout::admin.master')

@section('title','transportroute')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">
    <style>
        .table-div table {
            width: 100% !important;
        }
        .error{
            display: none;
        }
    </style>
@endsection
@section('body')
{{ Form::open(array('role' => 'form', 'route'=>array('StudentIdcard'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form','novalidate' => 'novalidate')) }}
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">Student ID Card Bulk Print</h4>
            {{-- @if(CGate::allows('create-transportroute'))
            <a class="btn btn-primary" href="{{route('transportroute.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif --}}
          
        </div>
        <hr/>

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h1 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                   Get Students 
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
                                         
                                          {{ Form::select('academic_year',@$academicyears,Configurations::getCurrentAcademicyear() ,
                                          array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control" : 
                                          "single-select form-control",'required' => 'required','placeholder'=>"Select Academic year",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                              </div>
                               <div class="col-xs-12 col-sm-4 col-md-3">
                                  <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">School Type <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('school_type',@$school_type_info,@$school_type ,
                                            array('id'=>'school_type_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select School Type",@$layout =="edit"? "disabled" : "" )) }}
                                        </div>
                                  </div>
                                       
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-3">
                                  <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Class <span class="required"></span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                                            array('id'=>'class_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "" )) }}
                                        </div>
                                  </div>
                                       
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-3">
                                  <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Section <span class="required"></span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('section_id',@$sections,@$data->section_id ,
                                            array('id'=>'section_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control','required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "" )) }}
                                        </div>
                                  </div>
                                       
                              </div>
                               <div class="col-xs-12 col-sm-4 col-md-3">
                              <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Students<span class="required"></span>
                                      </label>
                                      <div class="feild">
                                         
                                          {{ Form::select('student_id[]',@$students,@$students ,
                                          array('id'=>'studentd_id','class' => @$layout =="edit" ? " form-control" : 
                                          "single-select form-control",'required' => 'required','placeholder'=>"Select Student ",@$layout =="edit"? "disabled" : "","multiple")) }}
                                      </div>
                                </div>
                               </div>
                                                             

                            <div class="col-md-3 ">
                                <button type="submit" id="addatt" class="btn btn-primary  add_btn att_btn w-100" name="daily"> <i class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get ID Card</button>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
           
           
        </div>
       
        </div>
    </div>
</div>
{{ Form::close() }}


  

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
     window.transportstudent='{{ route('transportstudent.index') }}';
     window.getvehicle='{{ route('getstopvehicle') }}';
     window.termurl='{{ route('examterm.index') }}';
     window.classurl ='{{ route('schooltype.index') }}'
     window.transportreport ='{{ route('transportreport') }}'
     window.studentsurl='{{route('students.index')}}'
   
    AttendanceConfig.AttendanceInit(notify_script,"idcard");
    ReportConfig.ReportInit(notify_script);
    
    
</script>
@endsection
@section('script')
    <script>
     window.statuschange='{{route('transportroute_action_from_admin')}}';
       
    </script>

@endsection
