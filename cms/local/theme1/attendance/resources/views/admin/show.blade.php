@extends('layout::admin.master')

@section('title','profile')
@section('style')
@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{asset('assets/backend/css/profile.css')}}">
<style type="text/css" media="print">
	@page {
		size: auto;   /* auto is the initial value */
		margin: 0;  /* this affects the margin in the printer settings */
	}
	</style>

    <style>
    .attendance_table{
        width: 100%;
        margin-bottom: 25px;
        margin-top: 25px;
        }
    .attendance_table tr,th{
        border: 1px solid #c3c3c3 !important;
    }
    .attendance_table th{
        padding: 10px;
        font-size: 11px;
    }
    .attendance_table tr td {
        text-align: center;
        border: 0.1px solid #ddd;
        padding: 4px 0px;
       
    }
    .academic_yearinfo{
    box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px;
    padding: 20px;
    margin-bottom: 20px;
    background-color: #2a3f54;
    color: white;
    }
    .attendance_year{
    
    padding: 10px;
    margin-bottom: 14px;
    background-color: #2a3f54;
    color: white;
    }
    .ini-bg-secondary{
        background-color: rgb(61 109 157);
        color: #c3c3c3
    }
    .absent{
        background-color: red;
        color: white;
        
    }
    .present{
        background-color: #73b70b;
        color: white
    }
    .weekend{
        background-color: #2a3f54;
        color: white
    }
    .hrattendance{
    max-height: 700px;
    overflow-y: scroll;
    }
    .table_scroll{
        overflow-x: scroll;
    }
    </style>

@endsection

@section('body')
<div class="x_content">
    <div class="box-header with-border mar-bottom20">
       
       
		{{-- <button class="btn btn-primary btn-sm m-1  px-3 print" type="button"><i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;Print</button> --}}

            <a class="btn btn-info btn-sm m-1 px-3" href="{{ @$attendance_type == "hourly" ? route('attendance.hourlyindex') :route('attendance.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

        @include("layout::admin.breadcrump",['route'=> cms\students\models\StudentsModel::studentname($data->id)."-Attendance Info"])
    </div>
<!-- section begin -->




{{-- Printable section end --}}
	
<div class="card radius-15">
	<div class="card-body">
		<div class="card-title">
			<h4 class="mb-0">{{ cms\students\models\StudentsModel::studentname($data->id) }} Information</h4>
		</div>
		<hr/>
		
		{{-- //section start --}}
		<section class="pro_section">
			<div class="container_">
				<div class="row">
		
					<div class="col-lg-3 col-md-12 col-sm-12">
		
						<div class="stu_box">
							<div class="stu_bg"></div>
							<img class="stu_img" src="{{@$data->user->images ?@$data->user->images :asset('assets/images/staff.jpg')   }}" alt="Image">
		
							<div class="stu_box_inner">
								<div class="box_value">
									<h5 class="pro_heading">Student Name</h5>
									<h6 class="pro_value">{{ @$data->first_name }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Registration Code</h5>
									<h6 class="pro_value">{{ @$data->reg_no }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Class</h5>
									<h6 class="pro_value">{{ @$data->class->name }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Section</h5>
									<h6 class="pro_value">{{ @$data->section->name }}</h6>
								</div>
								
								
								<div class="box_value">
									<h5 class="pro_heading">Date of Birth</h5>
									<h6 class="pro_value">{{ @$data->dob }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Gender</h5>
									<h6 class="pro_value">{{ ucfirst(@$data->gender) }}</h6>
								</div>
								
		
								
		
								
							</div>
		
						</div>
		
					</div>
		
					
					
					<div class="col-lg-9 col-md-12 col-sm-12">
                        <div class="loader" style="display: none">
                            <img src="{{ asset("assets/images/loader.gif") }}" width="50px"/>
                            <br/>
                            <small>Loading ...</small>
                        </div>
                        <div class="daily_attendance"></div>
                        @if (@$attendance_type == "hourly")
                        <div class="hourly_attendance" >
                            <div class="row mb-4">
                                <input type="hidden" name="student_id" id="student_id" value="{{ @$data->id }}"/>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="name">Academic year<span class="required">*</span>
                                        </label>
                                        <div>
                                            {{Form::text('ac_year',@$academicyears[$data->academic_year],['class'=>'form-control single-select','id'=>"ac_year","placeholder"=>"Select Academic year","required","readonly"])}}
                                        </div>
                                        <input type="hidden" id="start_date" value="{{@$academic_year->start_date}}">
                                        <input type="hidden" id="end_date" value="{{@$academic_year->end_date}}">
                                        <input type="hidden" name="academic_year" id="academic_year" value="{{@$data->academic_year}}">
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="name">Select Subject<span class="required">*</span>
                                        </label>
                                        <div>
                                            {{Form::select('subject_id',@$subject_lists,null,['class'=>'form-control single-select',"placeholder"=>"Select Subject","required"])}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="name">Select Month<span class="required">*</span>
                                        </label>
                                        <div>
                                            {{Form::text('month',null,array('id'=>"hrmonth",'class'=>"month-picker-attend form-control col-md-7 col-xs-12" ,
                                            'placeholder'=>"Select Month ",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        
                                        <div class="mt-4">
                                           <button class="btn btn-primary gethourlyattendance">Get Attendance</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row hourlyattendancereport"></div>
                        </div>
                        @endif
                       
                       
						
					 {{-- calender table --}}
					</div>
					{{-- //end --}}
				</div>
			</div>
		
		</section>	
		<!-- section end -->
	</div>
</div>

</div>
@endsection
@section("scripts")

<script type="module">
    var student_id={{ @$data->id }}
    var atntype='{{ @$attendance_type }}'
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
     window.dailyattendanceurl='{{route('getdailyattendance')}}';
     window.hourlyattendanceurl='{{route('gethourlyattendance')}}';
     AttendanceConfig.AttendanceInit(notify_script);
     if(atntype != "hourly")
    AttendanceConfig.getDailyattendance(student_id)
    
    
</script>
@endsection

