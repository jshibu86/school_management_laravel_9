@extends('layout::admin.master')
@section('title', 'Grade Report')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{ asset('assets/backend/css/attendance.css') }}">
    <style>
        .table-div table {
            width: 100% !important;
        }

        .error {
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


        /* Basic styling for tabs */
        .tabs {
            display: flex;
            list-style: none;
            padding: 0;
        }

        .tab {
            margin: 0;
            padding: 10px 20px;
            cursor: pointer;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 5px 5px 0 0;
            margin-right: 5px;
            position: relative;
            border-right: none;
            border-left: none;
            border-top: none;
        }

        /* Hover effect */
        .tab:hover {
            color: #BD02FF;
            border-bottom-color: #BD02FF;
            /* font-weight: 800; */
            /* font-size: 17px; */
        }

        .tab-active {
            color: #BD02FF;
            border-bottom-color: #BD02FF;
            font-weight: 800;
            font-size: 17px;
        }

        /* */

        .all {
            display: none;
        }

        .subject {
            display: none;
        }

    </style>
@endsection
@section('body')
   
    <div class="card">
        <div class="card-body">
            <div class="card-title btn_style">
                <h4 class="mb-0">Grade Report</h4>

            </div>
            <hr />

            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    
                    <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse show"
                        aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                        <div class="accordion-body">
                            <div class="row">

                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">Academic Year <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">
                                            {{ Form::select('academic_year_grade', @$academicyears, Configurations::getCurrentAcademicyear(), [
                                                'id' => 'academic',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select Academic year',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-2 mb-4">
                                    <div class="item form-group">
                                        <label for="exam_term" class="mb-2">Academic Term <span>*</span></label>


                                        {{ Form::select('academic_term', @$examterms, @$data->term_id ? @$data->term_id : @$current_academic_term, [
                                            'id' => 'examterm',
                                            'class' => 'single-select form-control',
                                            'required' => 'required',
                                            'placeholder' => 'Select Exam Term',
                                            @$layout == 'edit' ? 'disabled' : '',
                                        ]) }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="item form-group">
                                        <label class="control-label margin__bottom" for="status">School Type <span
                                                class="required">*</span>
                                        </label>
                                        <div class="feild">

                                            {{ Form::select(
                                                'school_type',
                                                @$school_type_info,
                                                @$data->school_type_info ? @$data->school_type_info : @$school_type_infos,
                                                [
                                                    'id' => 'school_type_grade',
                                                    'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                    'required' => 'required',
                                                    'placeholder' => 'Select School Type',
                                                    @$layout == 'edit' ? 'disabled' : '',
                                                ],
                                            ) }}
                                        </div>
                                    </div>

                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-2">
                                    <div class="item form-group">
                                        <label for="exam_term" class="mb-2">Class <span>*</span></label>
                                        {{ Form::select('class_id', @$class_lists, @$data->class_id, [
                                            'id' => 'class_id_grade',
                                            'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                            'required' => 'required',
                                            'placeholder' => 'Select CLass',
                                            @$layout == 'edit' ? 'disabled' : '',
                                        ]) }}

                                    </div>

                                </div>

                                <div class="col-xs-12 col-sm-4 col-md-3">
                                    <div class="item form-group">
                                        <label for="exam_term" class="mb-2">Section <span>*</span></label>


                                        {{ Form::select('section_id', @$section_lists, @$data->section_id, [
                                            'id' => 'section_id',
                                            'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                            'required' => 'required',
                                            'placeholder' => 'Select Section',
                                            @$layout == 'edit' ? 'disabled' : '',
                                        ]) }}

                                    </div>
                                </div>


                                <div class="col-xs-12 col-sm-4 col-md-3 subject_div">
                                    <div class="item form-group">
                                        <label for="exam_term" class="mb-2">Subject <span>*</span></label>


                                        {{ Form::select('subject_id_grade', @$subjects, @$data->subject_id, [
                                            'id' => 'subject_id',
                                            'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                            'required' => 'required',
                                            'placeholder' => 'Select Section',
                                            @$layout == 'edit' ? 'disabled' : '',
                                        ]) }}

                                    </div>
                                </div>


                            </div>

                            <div class="col-md-2 ">
                                <button type="button" class="btn btn-primary gradereport  add_btn att_btn w-100" name="daily"> <i
                                        class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Get Report</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>



        {{-- <li class="tab tab-active" id="tab1" onclick="openCity('Over')" >Over All</li>
            <li class="tab" id ="tab2" onclick="openCity('Subject')" ><a href="{{ route('gradereportsubject')}}">Subject</a></li> --}}
     <div class="card-body grade_report_body">

       
        <ul class="tabs">
            <li class="tab tab-active" id="tab1">Over All</li>
            <li class="tab" id ="tab2">Subject</li>

            </li>
        </ul>



        <div class="all" id="tab1">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">

                            <div class="col-md-9">
                                <div class="card-body border">
                                    <div class="subjectreport">
                                        <div id="subjectbarchart" class="">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="card-body border">
                                    <div class="subjectreport">
                                        <div id="subjectpiechart" class=""></div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="get_students_stop_assign mt-4">
              
                <div class="table-responsive">
                    <table id="datatable_buttons2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Image</th>
                                <th>Reg ID</th>
                                <th>Name</th>
                                <th>Avg Score</th>
                                <th>Position</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>                      
                    </table>
                </div>

            </div>

        </div>


        <div class="subject_con" id="tab2">

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">

                            <div class="col-md-9">
                                <div class="card-body border">
                                    <div class="subjectreport">
                                        <div id="subjectbarchart1" class="">
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-3">
                                <div class="card-body border">
                                    <div class="subjectreport">
                                        <div id="subjectpiechart1" class=""></div>

                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <div class="get_students_stop_assign mt-4">
                <div class="table-responsive ">
                    <table id="datatable_buttons1" class="table table-striped table-bordered" style="width:100%">
                     
                    </table>
                </div>
            </div>
        </div>
         </div>


    </div>
    </div>
   
    <div class="modal fade" id="view_report" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered form">

            <div class="modal-content">

                <div class="modal-body assigen_parent_body">

                        <div class="student_report_details position-relative">
                            some

                        </div>
                        <div class="modal-footer position-absolute top-0 end-0">
                           
                            <i class="fa fa-times-circle fs-2" style="color: red" data-bs-dismiss="modal"
                                aria-hidden="true"></i>


                        </div>
                </div>




            </div>
        </div>
    </div>
@endsection
@section('script')

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- FixedColumns CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.3/css/fixedColumns.dataTables.min.css">
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <!-- FixedColumns JS -->
    <script src="https://cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js"></script>
   
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
        window.classurl = '{{ route('schooltype.index') }}';
        window.getgradeinfo = "{{ route('gradereportoverall') }}";
        window.getgradesubjectinfo = "{{  route('gradereportsubject') }}";
        AttendanceConfig.AttendanceInit(notify_script);
        AcademicConfig.Leaveinit(notify_script);
        //grade -- Class,Section List
        PromotionConfig.PromotionInit(notify_script);
        ReportConfig.ReportInit(notify_script);
       
        //grade chart
        Account.AccountInit();
        Account.GradeBarChart();
        Account.GradePieChart();
        Account.GradeBarChart1();
        Account.GradePieChart1();
        // window.student_overall_report_info = "{{ route('grade_student_report_view') }}"
        // ReportConfig.getStudentsMarkinfo(id,academic_year,position,term);
    </script>

    <script>
        window.onload = function() {
            // Code to be executed when the entire page has finished loading
            $(".subject_div").hide();
        };
    </script>
      <script>
          $(".grade_report_body").hide();
      </script>



@endsection
@section('script')
    <script>
        //  window.statuschange = '{{ route('transportroute_action_from_admin') }}';    
        window.onload = function() {
            // Code to be executed when the entire page has finished loading
            $(".subject_div").hide();
          
        };
    </script>


@endsection
