@extends('layout::admin.master')

@section('title','StudentPerformance')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
      
    </style>
@endsection
@section('body')
     {{ Form::open(['role' => 'form', 'route' => ['studentperformance_store'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form', 'novalidate' => 'novalidate']) }}
     
        <div class="card">
            <div class="card-body">
                <div class="card-title btn_style">
                    <h4 class="mb-0">Student Performance</h4>

                </div>
                <hr />

                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        
                        <div id="collapseOne" class=" atnaccodrdian accordion-collapse collapse show"
                            aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
                            <div class="accordion-body">
                                <div class="row">

                                    <div class="col-xs-12 col-sm-4 col-xl-2">
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

                                    <div class="col-xs-12 col-sm-4 col-xl-2">
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
                                    <div class="col-xs-12 col-sm-4 col-xl-2">
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
                                    <div class="col-xs-12 col-sm-4 col-xl-2">
                                        <div class="item form-group">
                                            <label for="class_id" class="mb-2">Class <span>*</span></label>
                                            {{ Form::select('class_id', @$class_lists, @$data->class_id, [
                                                'id' => 'class_id_grade',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select CLass',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}

                                        </div>

                                    </div>

                                    <div class="col-xs-12 col-sm-4 col-xl-2">
                                        <div class="item form-group">
                                            <label for="sec_dep" class="mb-2">Sec/Dep <span>*</span></label>
                                            {{ Form::select('sec_dep', @$section_lists, @$data->class_id, [
                                                'id' => 'sec_dep',
                                                'class' => @$layout == 'edit' ? ' form-control' : 'single-select form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Select CLass',
                                                @$layout == 'edit' ? 'disabled' : '',
                                            ]) }}

                                        </div>

                                    </div>
                                     
                                    
                                        <div class="col-xs-12 col-sm-4 col-xl-2">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="status">Period<span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    <div class="feild">
                                                        {{ Form::select('period_type',Configurations::PERIODTYPE,@$type ,
                                                        array('id'=>'period_type','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select period type" )) }}
                                                    </div>
                                                </div>
                                            </div>
                                               
                                        </div>
                            
                                        <div class="col-xs-12 col-sm-4 col-xl-2 weekly" style="display: none;">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="status">Start Date <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    <div class="feild">
                                                        {{ Form::text('start_date',@$start_date ,
                                                        array('id'=>'start_date','class' => ' form-control weekdate startdate',"placeholder"=>"select start date" )) }}
                                                    </div>
                                                </div>
                                            </div>
                                               
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-xl-2 weekly" style="display: none;">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="status">End Date <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    <div class="feild">
                                                        {{ Form::text('end_date',@$end_date ,
                                                        array('id'=>'end_date','class' => ' form-control weekdate enddate',"placeholder"=>"select end date" )) }}
                                                    </div>
                                                </div>
                                            </div>
                                               
                                        </div>
                            
                                       
                            
                                        <div class="col-xs-12 col-sm-4 col-xl-2 monthly" style="display: none;">
                                            <div class="item form-group">
                                                <label class="control-label margin__bottom" for="status">Select Month <span class="required">*</span>
                                                </label>
                                                <div class="feild">
                                                    <div class="feild">
                                                        {{ Form::text('month',@$month ,
                                                        array('id'=>'month','class' => ' form-control month-picker month',"placeholder"=>"select month" )) }}
                                                    </div>
                                                </div>
                                            </div>
                                               
                                        </div>
                                       
                                            <div class="col-md-3">
                                           
                                                <button type="button" class="btn btn-primary students_performance form-control" style="margin-top: 30px;"> <i
                                                        class="fa fa-plus" name="daily"></i>&nbsp;&nbsp;Check Performance</button>
                                            </div>
                                        
                                           
                                </div>
                       
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body">
                <div class="get_students_stop_assign mt-4">
                    <div class="table-responsive ">
                        <table id="students_performance_table" class="table bg-white" style="width:100%">
                         
                        </table>
                    </div>
                </div>
            </div>
            <div class="row justify-content-end btn_submit" style="display:none;">
                <div class="col-md-3 mb-3 me-2">
                    <button type="submit" class="btn btn-success" style="float:inline-end" id="send_save">
                        Send/Save Report
                    </button>
                </div>
            </div>
           
           
        </div>
     {{ Form::close() }}
  
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
        window.getstudentperformanceinfo = "{{ route('studentperformance') }}";
       
       
        AttendanceConfig.AttendanceInit(notify_script);
        AcademicConfig.Leaveinit(notify_script);
        //grade -- Class,Section List
        PromotionConfig.PromotionInit(notify_script);
        // ReportConfig.ReportInit(notify_script);
        StudentPerformance.StudentPerformanceInit(notify_script);
        //grade chart
        Account.AccountInit();
       
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

