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

        .exam_information {
            background-color: #D9D9D9;
            width: 30%;
            margin: auto;
            padding: 10px;
        }

        .tdclass {
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .optionsmenu {
            cursor: pointer;
        }

        .DTFC_LeftHeadWrapper,.DTFC_RightHeadWrapper{
            height:40px !important;
        }
       
        .DTFC_LeftBodyLiner,.DTFC_RightBodyLiner{
           margin-top:3px;
        } 
      
        .DTFC_LeftBodyLiner table{
            margin:0px !important;
         
        }
        .DTFC_LeftHeadWrapper{
            overflow:unset !important;
        }
        .DTFC_LeftHeadWrapper table{
            margin-bottom:0px !important;
        }
        .DTFC_RightBodyLiner table{
            margin:0px !important;
         
        }
       
        div .dataTables_scrollBody table tbody tr:first-child td{
            border-bottom: 1px solid #dee2e6 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            padding: 0px !important;
        }
        .table.table-bordered.dataTable td {  
            border-bottom: 1px solid #dee2e6 !important;
        }

        .DTFC_Cloned thead tr{
            height:1px !important;
        }
        div.DTFC_LeftWrapper table.dataTable.no-footer{
            border-right: 1px solid #dee2e6 !important;
        }
        div.DTFC_RightWrapper table.dataTable.no-footer {
            border-left: 1px solid #dee2e6 !important;
        }
        .table td{
            text-align: center;
        }  
        #subjectbarchart{
            width:100% !important;
            overflow-x: scroll;
            overflow-y: hidden;
        }

    </style>
@endsection
@section('body')
    {{ Form::open(['role' => 'form', 'route' => ['hostelreport'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form', 'novalidate' => 'novalidate']) }}
    <div class="card">
        <div class="card-body">
            <div class="card-title btn_style">
                <h4 class="mb-0">Exam Report</h4>
                <a href="{{ route('exam.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>

            </div>
            <hr />



        </div>



        {{-- <li class="tab tab-active" id="tab1" onclick="openCity('Over')" >Over All</li>
            <li class="tab" id ="tab2" onclick="openCity('Subject')" ><a href="{{ route('gradereportsubject')}}">Subject</a></li> --}}
        <div class="card-body">
            <div class="exam_information text-center">
                <h4>{{ @$data->exam_title }}</h4>
                <p>{{ @$data->subject->name }}</p>
                <p>{{ @$data->class->name }}-{{ @$data->section->name }}</p>
            </div>

            <div class="col-md-12">
                <div class="row">

                    <div class="col-md-9">
                        <div class="card-body">
                            <div class="subjectreport" style="border: 1px solid grey;padding:20px;border-radius: 13px;">
                                <div id="subjectbarchart" class="">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="card-body">
                            <div class="subjectreport" style="border: 1px solid grey;padding:20px;border-radius: 13px;">
                                <div id="subjectpiechart" class="" style="display: flex;justify-content:center;"></div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="get_students_stop_assign mt-4">
                <div class="table-responsive">
                    <table id="datatable-buttons2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Reg ID</th>
                                <th>Name</th>
                                @foreach (@$questions as $question)
                                    <th>Q{{ $loop->index + 1 }}({{ $question->mark }} Mark)</th>
                                @endforeach
                                <th>Total Score</th>
                                <th>Position</th>
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$online_exam_submission as $key => $submission)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $submission->student->username }}</td>
                                    <td>{{ $submission->student->first_name }}</td>
                                    @foreach (@$questions as $question)
                                        @foreach (@$submission->examsubmision as $quessubmission)
                                            @if ($question->id == $quessubmission->question_id)
                                                @php
                                                    $correct = $quessubmission->is_correct ? 'green' : 'red';
                                                    $correctBool = $quessubmission->is_correct ? true : false;
                                                @endphp
                                                @if ($question->question_type == 'fillintheblanks')
                                                    <td style="color: {{ $correct }}">
                                                         <div style="display:flex;align-items: center;justify-content:space-between">
                                                        {{ $quessubmission->your_answer }} @if ($correctBool)
                                                            <i class="fa fa-check"></i>
                                                        @else
                                                            <i class="fa fa-close"></i>
                                                        @endif <i class='fa fa-ellipsis-v optionsmenu'
                                                            id="{{ $question->id }}"
                                                            data-submission="{{ $quessubmission->id }}"
                                                            data-onlineexam="{{ $submission->id }}"
                                                            onclick="ExamConfig.getQuestionsinfo(this,this.id)"></i></div></td>
                                                @elseif ($question->question_type == 'choosebest')
                                                    @php
                                                        $chooseoptions = explode(',', $question->options);
                                                    @endphp
                                                   
                                                        <td style="color: {{ $correct }}">
                                                             <div style="display:flex;align-items: center;justify-content:space-between">
                                                            {{ isset($chooseoptions[$quessubmission->your_answer]) ? $chooseoptions[$quessubmission->your_answer] : 'N/A' }}
                                                            @if ($correctBool)
                                                                <i class="fa fa-check"></i>
                                                            @else
                                                                <i class="fa fa-close"></i>
                                                            @endif <i
                                                                class='fa fa-ellipsis-v optionsmenu'
                                                                id="{{ $question->id }}"
                                                                data-submission="{{ $quessubmission->id }}"
                                                                data-onlineexam="{{ $submission->id }}"
                                                                onclick="ExamConfig.getQuestionsinfo(this,this.id)"></i>
                                                             </div>
                                                        </td>
                                                  
                                                @elseif ($question->question_type == 'yesorno')
                                                    @php
                                                        $chooseoptionsYes = explode(',', $question->options);
                                                    @endphp
                                                  
                                                        <td style="color: {{ $correct }}">
                                                             <div style="display:flex;align-items: center;justify-content:space-between">
                                                            {{ isset($chooseoptionsYes[$quessubmission->your_answer]) ? $chooseoptionsYes[$quessubmission->your_answer] : 'N/A' }}
                                                            @if ($correctBool)
                                                                <i class="fa fa-check"></i>
                                                            @else
                                                                <i class="fa fa-close"></i>
                                                            @endif <i
                                                                class='fa fa-ellipsis-v optionsmenu'
                                                                id="{{ $question->id }}"
                                                                data-submission="{{ $quessubmission->id }}"
                                                                data-onlineexam="{{ $submission->id }}"
                                                                onclick="ExamConfig.getQuestionsinfo(this,this.id)"></i>
                                                             </div>
                                                        </td>
                                                    
                                                @endif
                                            @endif
                                        @endforeach
                                    @endforeach

                                    <td>{{ @$submission->total_marks }}</td>
                                  
                                    <td>{{ Configurations::ordinal($submission->position) }}</td>
                                    <td>
                                        @if (@$submission->examsubmision->where('is_correct', 1)->sum('mark') >= $data->min_mark)
                                            <span class="text-success">Pass</span>
                                        @else
                                            <span class="text-danger">Fail</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
            </div>







        </div>


    </div>
    </div>
    {{ Form::close() }}

    <div class="modal fade" id="view__report" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered form">

            <div class="modal-content">

                <div class="modal-body assigen_parent_body">

                        <div class="homework_details position-relative">
                            some

                        </div>
                        <div class="modal-footer position-absolute top-0 end-0">
                            @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
                                {{-- <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button> --}}
                            @endif
                            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> --}}
                            <i class="fa fa-times-circle fs-2" style="color: red" data-bs-dismiss="modal"
                                aria-hidden="true"></i>


                        </div>
                </div>




            </div>
        </div>
    
    @endsection
    @section('script')

        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <!-- DataTables CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<!-- FixedColumns CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.3/css/fixedColumns.dataTables.min.css">


<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<!-- FixedColumns JS -->
<script src="https://cdn.datatables.net/fixedcolumns/3.3.3/js/dataTables.fixedColumns.min.js"></script>

    @endsection

    @section('scripts')


        <script>
            $(document).ready(function() {
            
            $('#datatable-buttons2').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'pdf', 'print'
                ],
                scrollX: true, // Enable horizontal scrolling if needed
                fixedColumns: {
                leftColumns: 3, // Number of columns to fix on the left side
                rightColumns: 3 // Number of columns to fix on the right side
            }
            });
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
            window.classurl = '{{ route('schooltype.index') }}'
            window.getgradeinfo = "{{ route('gradereportoverall') }}"
            window.questioninfo = "{{ route('getQuestionsinfo') }}"
            AttendanceConfig.AttendanceInit(notify_script);
            AcademicConfig.Leaveinit(notify_script);
            //grade -- Class,Section List
            PromotionConfig.PromotionInit(notify_script);
            ReportConfig.ReportInit(notify_script);

            var questions = @json(@$barquestions);

            var percenatge = @json(@$barquestionspercentage);

            var pass = @json($pass);
            var fail = @json($fail);
            //grade chart
            Account.AccountInit();
            Account.GradeBarChart2("Subject Performance", questions, percenatge);
            Account.GradePieChart2(['pass', "failed"],pass,fail);
            Account.GradeBarChart3();
            Account.GradePieChart3();
        </script>

        <script>
            window.onload = function() {
                // Code to be executed when the entire page has finished loading
                $(".subject_div").hide();
            };
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
