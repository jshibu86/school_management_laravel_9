@extends('layout::admin.master')

@section('title', 'attendance')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/attendance.css') }}">
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
    {{ Form::open(['role' => 'form', 'route' => ['attendancereport'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form', 'novalidate' => 'novalidate']) }}
    <div class="card">
        <div class="card-body">
            <div class="card-title btn_style">
                <h4 class="mb-0">Attendance report</h4>
                {{-- @if (CGate::allows('create-transportroute'))
            <a class="btn btn-primary" href="{{route('transportroute.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif --}}

            </div>
            <hr />
            

            <div class="row" style="width: 60%; margin: auto;">
                <div class="col-12 col-lg-4">
                    <div class="card radius-15 bg-primary-blue">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                   
                                    <h2 class="mb-0 text-white"><span id="total_count">0 </span><i class="bx bxs-down-arrow-alt font-14 text-white"></i></h2>
                                                                   
                                </div>
                                <div class="ms-auto font-35 text-white"><i class="bx bxs-user"></i></div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Total User</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card radius-15 bg-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="mb-0 text-white"><span id="present_percentage">
                                   0                                 
                                    
                                    </span> <i
                                            class="bx bxs-down-arrow-alt font-14 text-white"></i></h2>
                                </div>
                                <div class="ms-auto font-35 text-white"><i class="bx bxs-user"></i></div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Total Percentage of Present</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="card radius-15 bg-sunset">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h2 class="mb-0 text-white"><span id="absent_percentage">0</span> <i
                                            class="bx bxs-up-arrow-alt font-14 text-white"></i></h2>
                                </div>
                                <div class="ms-auto font-35 text-white"><i class="bx bx-user"></i></div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-white">Total Percentage of Absent</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h1 class="accordion-header" id="headingOne">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Get Report
                        </button>
                    </h1>
                    <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse show"
                        aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                            <div class="row">

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Academic Year <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">

                                            {{ Form::select('academic_year', @$academicyears, Configurations::getCurrentAcademicyear(), [
                                                'id' => 'timetableacyear',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Academic year',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">School Type <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('school_type', @$school_type_info, @$school_type, [
                                                'id' => 'school_type_id',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select School Type',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Class <span
                                                class="required"></span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('class_id', @$class_lists, @$data->class_id, [
                                                'id' => 'class_id',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Class',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Section <span
                                                class="required"></span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('section_id', @$sections, @$data->section_id, [
                                                'id' => 'section_id',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Section',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">


                          
                                        <label class="control-label margin__bottom" for="status">Students <span
                                                class="required"></span>
                                        </label>
                                       
                                        <div class="feild">
                                                                                
                                                 {{ Form::select('student_id[]', @$students, @$students, [
                                                'id' => 'studentd_id',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Student ',
                                                @$layout == 'edit' ? 'disabled' : '',
                                                'multiple',
                                            ]) }}
                                       </div>
                                                          
                                    </div>
                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Select Month <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::text('month', @$monthyear, [
                                                'id' => 'month_year',
                                                'class' => 'form-control  month-picker col-md-7 col-xs-12',
                                                'placeholder' => 'Select Month',
                                                'required' => 'required',
                                            ]) }}
                                        </div>
                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3" >
                                    <button type="button" id="getattendancereport" class="btn btn-primary  add_btn att_btn w-100"
                                        name="daily" value="Report"> <i class="fa fa-plus" name="daily" ></i>&nbsp;&nbsp;Get
                                        Report</button>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-3" >
                                    <button type="submit" id="exportexcel"    class="btn btn-primary add_btn"
                                        name="type" value="excel"> <i class="fa fa-download"></i> Export Excel</button>
                                         <button type="submit" id="exportprint"    class="btn btn-primary add_btn"
                                        name="type" value="print"> <i class="fa fa-print"></i> Print Attendance</button>
                                </div>
                                <div class="col-md-2">
                                   
                                </div>


                                {{-- <div class="col-md-1">
                                    <button type="submit" id="exportpdf" class="btn btn-primary add_btn "
                                         name="type" value="pdf">PDF</button>
                                </div> --}}

                            </div>
                        </div>
                    </div>
                </div>


            </div>

            <div class="displayattendancereport"></div>

        </div>
    </div>
    </div>
    {{ Form::close() }}




@endsection

@section('scripts')
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
        window.transportstudent = '{{ route('transportstudent.index') }}';
        window.getvehicle = '{{ route('getstopvehicle') }}';
        window.termurl = '{{ route('examterm.index') }}';
        window.classurl = '{{ route('schooltype.index') }}'
        window.transportreport = '{{ route('transportreport') }}'
        window.studentsurl = '{{ route('students.index') }}'
         window.getAttendanceReportUrl = '{{ route('attendancereport') }}'

        AttendanceConfig.AttendanceInit(notify_script, "idcard");
        ReportConfig.ReportInit(notify_script);
    </script>
@endsection
@section('script')
    <script>
        window.statuschange = '{{ route('transportroute_action_from_admin') }}';
    </script>

@endsection
