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
            width: 47%;
            margin: auto;
            padding: 10px;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button{
            padding: 0px !important;
        }
        .custom_margin{
            margin-top:unset !important;
            margin-bottom:unset !important;
        }
        .dt-buttons{
            margin-left:20px;
        }
    </style>
@endsection
@section('body')
    {{ Form::open(['role' => 'form', 'route' => ['SubmitofflineExamMark'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'trasnportreport-form', 'novalidate' => 'novalidate']) }}
    <div class="card">
        <div class="card-body">
            <div class="card-title btn_style">
                @if($data->type_of_exam == "Offline" || $data->type_of_exam == "offline")
                    <h4 class="mb-0">Offline Exam Report</h4>
                
                @else
                   <h4 class="mb-0">Homework Report</h4>
                @endif
                <a href="{{ route('exam.index') }}" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Back</a>
                 
            </div>
            <hr />



        </div>



        {{-- <li class="tab tab-active" id="tab1" onclick="openCity('Over')" >Over All</li>
            <li class="tab" id ="tab2" onclick="openCity('Subject')" ><a href="{{ route('gradereportsubject')}}">Subject</a></li> --}}
        <div class="card-body">
            <div class="exam_information text-center">
                <input type="hidden" name="exam_id" id="exam_id" value="{{@$data->id}}"/>
                <h4>{{ @$data->exam_title }}</h4>
                <p>{{ @$data->subject->name }}</p>
                <p>{{ @$data->class->name }}-{{ @$data->section->name }}</p>
            </div>

            <div class="col-md-12 mt-3" >
                <div class="row" style="width: 50%;margin:auto">
                    
                    <div class="col-md-6">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="card radius-15 bg-primary-blue">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <h2 class="mb-0 text-white">{{@$questions_count}}<i
                                                        class="bx bxs-down-arrow-alt font-14 text-white"></i> </h2>
                                            </div>
                                            <div class="ms-auto font-35 text-white"><i class="fa fa-graduation-cap"></i>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <div>
                                                <p class="mb-0 text-white">Total Questions</p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
                            <div class="subjectreport" style="border: 1px solid grey">
                                <div id="subjectpiechart" class="" style="display: flex;justify-content:center;"></div>

                            </div>
                        </div>

                    </div>
                    
                </div>
            </div>
           
            <div class="get_students_stop_assign mt-4">
                <div class="table-responsive">
                    <table id="datatable-buttons" class="table table-striped table-bordered" style="width:100%">
                        <input type="hidden" name = "exam_type" id = "exam_type" value={{$data->type_of_exam}}>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Reg ID</th>
                                <th>Name</th>
                                <th style="width: 15%" class="noExport">Add Score</th>
                                <th>Total Score</th>
                                <th>Position</th>
                                @if(isset($data->type_of_exam) && $data->type_of_exam == "Homework")
                                <th class="noExport">Evaluate</th>
                                @endif                             
                                <th>Status</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach (@$students as $student)
                            @php
                                $mark=$marks->where("student_id",$student->id)->first();
                            @endphp
                               <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$student->reg_no}}</td>
                                    <td>{{$student->first_name}} {{$student->last_name}}</td>
                                    <td><input type="number" value="{{$mark ? $mark->score ?? "" : ""}}" name="marks[{{$student->id}}][]" class="form-control exam_score"/></td>
                                    <td>{{$mark ? $mark->score ?? 0 : 0}} / {{@$data->max_mark}}</td>
                                    <td>
                                        @php
                                        if($mark !== null && $mark->position !== null) {
                                       
                                            $position_last= $mark->position; 
                                            
                                         
                                      
                                        }
                                        else {
                                             $position_last= "NA";
                                          }  
                                       @endphp 
                                       @if($mark !== null && $mark->position !== null)
                                       @if($mark->score <= $data->min_mark)
                                         <span>---</span> 
                                       @else
                                       {{  Configurations::ordinal($position_last)}}
                                       @endif
                                       @else
                                       {{  Configurations::ordinal($position_last)}}
                                       @endif
                                    </td>
                                    @if(isset($data->type_of_exam) && $data->type_of_exam == "Homework")
                                    <td>
                                      
                                        <button type="button" class="btn-primary btn evaluate_btn" id="{{$student->id}}">View</a>
                                         
                                    </td>
                                    @endif
                                    <td>
                                    @if($mark !== null)
                                    @if ($mark->position !== "null")
                                    <p class="custom_margin {{$mark->score <= $data->min_mark ? "text-danger" : "text-success"}}">{{$mark->score <= $data->min_mark ? "Failed" : "Pass"}}</p>
                                    @else
                                    <p></p>                                 
                                    @endif                                   
                                    @endif
                                </td>
                               </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
            <div class="footer_offline" style="text-align: right">
                 <button type="submit" class="btn btn-primary">Submit Entry</button>
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
    </div>    


    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<!-- FixedColumns CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedcolumns/3.3.3/css/fixedColumns.dataTables.min.css">


<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>



@endsection

@section('scripts')
<script>
    $(document).ready(function(){
        $('#datatable-buttons').DataTable({
            dom: '<"top"lBf>rt<"bottom"ip><"clear">',
            // dom: 'Bfrtip',
            buttons: [
            {
                extend: "excel",
                className: "btn-sm",
                exportOptions: {
                    columns: "thead th:not(.noExport)",
                },
            },
            {
                extend: "pdf",
                className: "btn-sm",
                exportOptions: {
                    columns: "thead th:not(.noExport)",
                   
                },
            },
            {
                extend: "print",
                className: "btn-sm",
                exportOptions: {
                    columns: "thead th:not(.noExport)",
                },
            },
        ],
            lengthMenu: [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
            pageLength: 10, 
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
        window.classurl = '{{ route('schooltype.index') }}';
        window.getgradeinfo = "{{ route('gradereportoverall') }}";
        window.homeworkevaluate = "{{route('evaluate_homework')}}";
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
        Account.GradeBarChart2("Overall Performance", questions, percenatge);
        Account.GradePieChart2(['pass', "failed"],pass,fail);
        Account.GradeBarChart3();
        Account.GradePieChart3();
    </script>

    <script>
        window.onload = function() {
            // Code to be executed when the entire page has finished loading
            $(".subject_div").hide();
        };
    
        $(".evaluate_btn").on("click",function(){         
            let id = $(this).attr('id');
            let exam_id = $('#exam_id').val();
            let url = window.homeworkevaluate + "?id=" + id + "&exam_id=" + exam_id;
            if(url){
                axios
                    .get(url)
                    .then((response)=>{
                    console.log(response);
                    if(response.data.view){
                        $(".homework_details").empty();
                        $(".homework_details").html(response.data.view);
                        $("#view__report").modal("show");
                    }
                    else {
                        console.error("Invalid response data:", response);
                        // Handle the case where viewfile is not present in the response data
                    }
                  })
                  .catch((error) => {
                    console.error("Error fetching student report:", error);
                    // Handle AJAX error gracefully, e.g., display an error message to the user
                });
            }
            else{
                console.error("Invalid URL:", url);
            }
        });
      
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
