@extends('layout::admin.master')

@section('title','attendance')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .atn_width{
        width: 65%;
        display: block;
        text-align: center;
        }
        .not_taken{
         background-color: black;
        color: white;
        padding: 10px;
        opacity: 1;
        font-weight: bold;
        border-radius: 7px;
        }
        .atn_late{
            background-color: blue;
            color: white;
            padding: 10px;
            opacity: 1;
            border-radius: 7px;
            font-weight: bold;
        }
        .atn_present{
           background-color: green;
            color: white;
            padding: 10px;
            opacity: 1;
            border-radius: 7px;
            font-weight: bold;
        }
        .atn_absent{
            background-color: red;
            color: white;
            padding: 10px;
            opacity: 1;
            border-radius: 7px;
            font-weight: bold;
        }
        .students_info{
        padding: 20px;
        background-color: #efefef;
        text-align: center
        }
        .students_info p{
        font-size: 30px;
        font-weight: 800;
        }
        .students_info div{
            border-left: 3px solid gray;
        }
        .students_info div:nth-child(1)
        {
            border-left: none !important
        }
           
        
    </style>
@endsection
@section('body')
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View {{ @$type == "daily" ? "Daily" : "Hourly" }} attendance</h4>
            @if(CGate::allows('create-attendance'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('attendance.create',$type)}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Add Attendance</a>
            @endif
          
        </div>
        <hr/>
        <div class="row">
            @if (@$type=="daily")

            <div class="col-md-7">
            <div class="row">
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="status">Academic Year <span class="required">*</span>
                      </label>
                      <div class="feild">
                          {{ Form::select('academic_year',@$academicyears,@$data->academic_year?@$data->academic_year :@$current_academic_year ,
                          array('id'=>'academic_year','class' =>@$layout =="edit" ? ' form-control termacademicyear': 'single-select form-control termacademicyear' ,'required' => 'required','placeholder'=>"Select Academic Year",@$layout =="edit"? "disabled" : "" )) }}
                      </div>
                </div>
                     
            </div>
             <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="status">Academic Term <span class="required">*</span>
                      </label>
                      <div class="feild">
                          {{ Form::select('academic_term',@$examterms,@$data->academic_term?@$data->academic_term : @$current_academic_term ,
                          array('id'=>'academic_term','class' =>@$layout =="edit" ? ' form-control ': 'single-select form-control ' ,'required' => 'required','placeholder'=>"Select Academic Term",@$layout =="edit"? "disabled" : "" )) }}
                      </div>
                </div>
                     
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="status">Class <span class="required">*</span>
                      </label>
                      <div class="feild">
                          {{ Form::select('class_id',@$class_lists,@$data->class_id ,
                          array('id'=>'class_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select Class",@$layout =="edit"? "disabled" : "" )) }}
                      </div>
                </div>
                     
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="status">Section <span class="required">*</span>
                      </label>
                      <div class="feild">
                          {{ Form::select('section_id',@$sections,@$data->section_id ,
                          array('id'=>'section_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "" )) }}
                      </div>
                </div>
                     
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="status"> <span class="required"></span>
                      </label>
                      <div class="feild">
                         <button class="btn btn-dark mt-2 getstudents">Get Students</button>
                      </div>
                </div>
                     
            </div>
            </div>
            </div>
            <div class="col-md-5">
                <div class="row students_info">
                    <div class="col-md-4">Total Students <p class="total_students" style="color: blue">{{ @$total_students }}</p></div>
                    <div class="col-md-4">No of Present @if (@$attendance_info)
                        <p style="color:green" class="total_present">{{ sizeof(@$attendance_info->where("attendance",1)) }}</p>
                        @else
                        <p class="total_present">0</p>
                    @endif</div>
                    <div class="col-md-4">No of Absent @if (@$attendance_info)
                        <p style="color:red" class="total_absent">{{ sizeof(@$attendance_info->where("attendance",0)) }}</p>
                        @else
                        <p class="total_absent">0</p>
                    @endif</div>
                </div>
            </div>

           
            @else

            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="status">Academic Year <span class="required">*</span>
                      </label>
                     <div class="feild">
                          {{ Form::select('academic_year',@$academicyears,@$data->academic_year?@$data->academic_year :@$current_academic_year ,
                          array('id'=>'academic_year','class' =>@$layout =="edit" ? ' form-control termacademicyear': 'single-select form-control termacademicyear' ,'required' => 'required','placeholder'=>"Select Academic Year",@$layout =="edit"? "disabled" : "" )) }}
                      </div>
                </div>
                     
            </div>
            <div class="col-xs-12 col-sm-4 col-md-4">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="status">Academic Term <span class="required">*</span>
                      </label>
                      <div class="feild">
                          {{ Form::select('academic_term',@$examterms,@$data->academic_term?@$data->academic_term : @$current_academic_term ,
                          array('id'=>'academic_term','class' =>@$layout =="edit" ? ' form-control ': 'single-select form-control ' ,'required' => 'required','placeholder'=>"Select Academic Term",@$layout =="edit"? "disabled" : "" )) }}
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
                          array('id'=>'section_id','class' =>@$layout =="edit" ? ' form-control': 'single-select form-control' ,'required' => 'required','placeholder'=>"Select Section",@$layout =="edit"? "disabled" : "" )) }}
                      </div>
                </div>
                     
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="status"> <span class="required"></span>
                      </label>
                      <div class="feild">
                         <button class="btn btn-dark mt-2 getstudents">Get Students</button>
                      </div>
                </div>
                     
            </div>

                
            @endif
           
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th class="noExport">Student Image</th>
                        <th>Registration Number</th>
                        <th>Student Name</th>
                        @if (@$type=="daily")
                            <th>Attendance Status</th>
                        @endif
                        
                        
                        <th class="noExport">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
    </div>
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
     window.attendancedailycount='{{route('attendancedailycount')}}';
    
    AttendanceConfig.AttendanceInit(notify_script,"daily")
    AcademicYearConfig.AcademicyearInit();
    
    
</script>
@endsection
@section('script')
    <script>
    var type ='{{ @$type }}'
     window.statuschange='{{route('attendance_action_from_admin')}}';
     var class_id= 0;
     var section_id= 0;
     var academic_year=0;

        $('document').ready(function(){

            getstudentsdata();
            $(".getstudents").on("click",function(){
                Pace.start();
                 class_id=$("#class_id").val() || 0;
                 section_id=$("#section_id").val() || 0;
                 academic_year=$("#academic_year").val() || 0;
                 if(type=="daily")
                 {
                     AttendanceConfig.attendancedailycount(class_id,section_id,academic_year,type);
                 }
               
                $('#datatable-buttons1').DataTable().clear().destroy();
                getstudentsdata();
                Pace.stop();
               
            })

        });

        function getstudentsdata()
        {
            var element = $("#datatable-buttons1");
            var url =  '{{route('get_attendance_data_from_admin')}}'+"?class_id="+class_id+"&section_id="+section_id+"&acyear="+academic_year+"&type="+type;
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'pimage', name: 'pimage' , className: 'textcenter' },
                
                { data: 'reg_no', name: 'reg_no' , className: 'textcenter' },
                { data: 'first_name', name: 'first_name' , className: 'textcenter' },
               @if (@$type=="daily")
                    { data: 'attendance', name: 'attendance' , className: 'textcenter' },
               @endif
               
               
                 
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('attendance_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('attendance_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('attendance_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('attendance.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);
        }
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
    window.getstudentperformanceinfo = "{{ route('studentperformance') }}";
    StudentPerformance.StudentPerformanceInit(notify_script);
    </script>
@endsection
