@extends('layout::admin.master')

@section('title','leavereport')
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
        
        /* .map-class{
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .map-class li{
            list-style-type:none;
        } */

    </style>
@endsection
@section('body')
{{ Form::open(array('role' => 'form', 'route'=>array('hostelreport'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form','novalidate' => 'novalidate')) }}
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">Leave Report</h4>
            {{-- @if(CGate::allows('create-transportroute'))
            <a class="btn btn-primary" href="{{route('transportroute.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif --}}
          
        </div>
        <hr/>

        <div class="row" style="width: 60%;margin:auto">
    
            <div class="col-12 col-lg-3">
                <div class="card radius-15 bg-primary-blue">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="mb-0 text-white">{{sizeof(@$leave)}} <i class="bx bxs-down-arrow-alt font-14 text-white"></i> </h2>
                            </div>
                            <div class="ms-auto font-35 text-white"><i class='bx bx-user' ></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Total Leave</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card radius-15 bg-rose">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="mb-0 text-white">{{@$leave ? @$leave->where("application_status","-1")->count() : 0}} <i class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                            </div>
                            <div class="ms-auto font-35 text-white"><i class='bx bx-male'></i></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Rejected</p>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card radius-15 bg-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="mb-0 text-white">{{@$leave ? @$leave->where("application_status","1")->count() : 0}} <i class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                            </div>
                            <div class="ms-auto font-35 text-white"><i class='bx bx-female' ></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Approved</p>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-3">
                <div class="card radius-15 bg-sunset">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div>
                                <h2 class="mb-0 text-white">{{@$leave ? @$leave->where("application_status","2")->count() : 0}} <i class="bx bxs-up-arrow-alt font-14 text-white"></i> </h2>
                            </div>
                            <div class="ms-auto font-35 text-white"><i class='bx bx-female' ></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div>
                                <p class="mb-0 text-white">Pending</p>
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h1 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                   Get Leave Report 
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
                                          <input type="hidden" name="class_id" value="{{ @$data->class_id }}"/>
                                          <input type="hidden" name="section_id" value="{{ @$data->section_id }}"/>
                                          <input type="hidden" name="term_id" value="{{ @$data->term_id }}"/>
                                              
                                          @endif
                                          {{ Form::select('academic_year',@$academicyears,Configurations::getCurrentAcademicyear() ,
                                          array('id'=>'timetableacyear','class' => @$layout =="edit" ? " form-control" : 
                                          "single-select form-control termacademicyear",'required' => 'required','placeholder'=>"Select Academic year",@$layout =="edit"? "disabled" : "")) }}
                                      </div>
                                </div>
                                     
                              </div>

                              <div class="col-md-3 mb-4">
                                    <div class="item form-group">
                                        <label for="exam_term" class="mb-2">Exam Term <span>*</span></label>


                                        {{ Form::select('academic_term', @$examterms, @$data->term_id ? @$data->term_id : @$current_academic_term, [
                                            'id' => 'examterm',
                                            'class' => 'single-select form-control',
                                            'required' => 'required',
                                            'placeholder' => 'Select Exam Term',
                                            @$layout == 'edit' ? 'disabled' : '',
                                        ]) }}
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
                              <div class="col-xs-12 col-sm-4 col-md-2">
                                  <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Class <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                                            array('id'=>'class_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "" )) }}
                                        </div>
                                  </div>
                                       
                              </div>
                              <div class="col-xs-12 col-sm-4 col-md-2">
                                  <div class="item form-group">
                                   <label class="control-label margin__bottom" for="status">Section <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('section_id',@$sections,@$data->section_id ,
                                            array('id'=>'section_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control','required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "" )) }}
                                        </div>
                                  </div>
                                       
                              </div>

                              
                            <div class="col-md-2 ">
                                <button type="button" id="leavestudent__report" class="btn btn-primary  add_btn att_btn w-100" name="daily"> <i class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get Report</button>
                            </div>
                              
                        </div>
                    </div>
                </div>
            </div>
           
           
        </div>
        <div class="get_students_stop_assign mt-4">
         <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Academic year</th>
                        <th>Student Name</th>
                        <th>Type</th>
                        <th>From / To</th>
                        <th>Status</th>
                       
                      
                       
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
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
     window.classurl ='{{ route('schooltype.index') }}'
    window.leavereport ='{{ route('leavereport') }}'
      AcademicYearConfig.AcademicyearInit();
     AttendanceConfig.AttendanceInit(notify_script);
     ReportConfig.ReportInit(notify_script,'leave');
    
    
</script>
@endsection
@section('script')
    <script>
     window.statuschange='{{route('transportroute_action_from_admin')}}';
       
    </script>

@endsection
