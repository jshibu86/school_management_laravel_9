@extends('layout::admin.master')

@section('title', 'exam type')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }

        .create-btn {
            text-align: right;
            margin-bottom: 10px;
        }
     
    </style>
@endsection
@section('body')

    <div class="card radius-15">
        <div class="card-body">
            <div class="card-title">
                <h4 class="mb-0">View Exam</h4>
            </div>
            <hr>
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation"> <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                        href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Offline Exam</a>
                </li>
                <li class="nav-item" role="presentation"> <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                        href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Online
                        Exam</a>
                </li>
                <li class="nav-item" role="presentation"> <a class="nav-link" id="pills-homework-tab" data-bs-toggle="pill"
                        href="#pills-homework" role="tab" aria-controls="pills-homework" aria-selected="false">
                        Homework</a>
                </li>
                <li class="nav-item" role="presentation"> <a class="nav-link" id="pills-admissionexam-tab" data-bs-toggle="pill"
                        href="#pills-admissionexam" role="tab" aria-controls="pills-admissionexam" aria-selected="false">
                        Admission Exam</a>
                </li>

                {{-- <li class="nav-item" role="presentation"> <a class="nav-link" id="pills-quiz-tab" data-bs-toggle="pill"
                    href="#pills-quiz" role="tab" aria-controls="pills-quiz" aria-selected="false">
                    Quiz</a>
                </li> --}}

            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade active show" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    @if (CGate::allows('create-exam'))
                        <div class="create-btn">
                            <a class="btn btn-primary btn-sm m-1  px-3 " href="{{ route('exam.create') }}"><i
                                    class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Academic Year</th>
                                    <th>Academic Term</th>
                                    <th>Exam Title</th>
                                    <th>Exam Type</th>
                                    <th>Class/Section</th>
                                    <th>Subject</th>
                                    <th>Exam Date</th>
                                    <th class="noExport">Status</th>
                                    <th class="noExport">Action</th>
                                    {{-- <th class="noExport">Mark</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    @if (CGate::allows('create-exam'))
                        <div class="create-btn">
                            <a class="btn btn-primary btn-sm m-1  px-3 " href="{{ route('exam.create') }}"><i
                                    class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="datatable-buttons2" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Academic Year</th>
                                    <th>Exam Type</th>
                                    <th>Class/Section</th>
                                    <th>Subject</th>
                                    <th>Exam Date/Time</th>  
                                    <th>Submission Date/Time</th>                                  
                                    @if (Session::get('ACTIVE_GROUP') == 'Super Admin')
                                        <th class="noExport">Action</th>
                                    @endif

                                    @if (Session::get('ACTIVE_GROUP') == 'Student')
                                        <th class="noExport">Tak Exam</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>
                </div>
                <div class="tab-pane fade " id="pills-homework" role="tabpanel" aria-labelledby="pills-homework-tab">
                    @if (CGate::allows('create-exam'))
                        <div class="create-btn">
                            <a class="btn btn-primary btn-sm m-1  px-3 " href="{{ route('exam.create') }}"><i
                                    class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="datatable-buttons3" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Academic Year</th>
                                    <th>Exam Title</th>
                                    <th>Exam Type</th>
                                    <th>Class/Section</th>
                                    <th>Subject</th>
                                    <th>Exam Date</th> 
                                    <th>Submission Date/Time</th>                             
                                    <th class="noExport">Status</th>
                                    <th class="noExport">Action</th>
                                    {{-- <th class="noExport">Mark</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                </div>
                <div class="tab-pane fade " id="pills-admissionexam" role="tabpanel" aria-labelledby="pills-admissionexam-tab">
                    @if (CGate::allows('create-exam'))
                        <div class="create-btn">
                            <a class="btn btn-primary btn-sm m-1  px-3 " href="{{ route('exam.create') }}"><i
                                    class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="datatable-buttons4" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Academic Year</th>
                                    <th>Exam Title</th>
                                    <th>Exam Type</th>
                                    <th>Class/Section</th>
                                    <th>Subject</th>
                                    <th>Exam Date</th> 
                                    <!-- <th>Submission Date/Time</th>                              -->
                                    <th class="noExport">Status</th>
                                    <th class="noExport">Action</th>
                                    {{-- <th class="noExport">Mark</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                </div>

                {{-- <div class="tab-pane fade " id="pills-quiz" role="tabpanel" aria-labelledby="pills-homework-tab">
                  
                    <div class="table-responsive">
                        <table id="datatable-buttons5" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Academic Year</th>
                                    <th>Exam Title</th>
                                    <th>Exam Type</th>
                                    <th>Class/Section</th>
                                    <th>Subject</th>
                                    <th>Exam Date</th> 
                              
                                    <th class="noExport">Status</th>
                                    <th class="noExport">Action</th>
                    
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>
                    </div>

                </div> --}}

            </div>
        </div>
    </div>

@endsection

@section('script')
 <!-- script to get offline exam data -->
    <script>
        window.statuschange = '{{ route('exam_action_from_admin') }}';
        $('document').ready(function() {

            var element = $("#datatable-buttons1");
            var url = '{{ route('get_exam_data_from_admin') }}'+'?type=Offline';
            var column = [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'acyear',
                    name: 'academicyear.year',
                    width: '15%'
                },
                {
                    data: 'term_name',
                    name: 'exam_term.exam_term_name',
                    width: '15%'
                },
                {
                    data: 'exam_title',
                    name: 'exam_title',
                    width: '15%'
                },
                {
                    data: 'exam_type_column',
                    name: 'exam_type.exam_type_name',
                    className: 'textcenter'
                },
                {
                    data: 'class_section',
                    name: 'class_section',
                    className: 'textcenter'
                },
                {
                    data: 'subject_name',
                    name: 'subject.name',
                    className: 'textcenter'
                },
                {
                    data: 'examdate',
                    name: 'examdate',
                    className: 'textcenter'
                },
               
                {
                    data: 'status',
                    name: 'id',
                    searchable: false,
                    sortable: false,
                    className: 'textcenter',
                    render: function(data, type, row, meta) {

                        return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                      </label>`;

                    }
                },
                {
                    data: 'action',
                    name: 'id',
                    searchable: false,
                    sortable: false,
                    className: 'textcenter'
                },

                // { data: 'entrymark', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [
                    [15, 25, 50, 100, 250, 500, -1],
                    [15, 25, 50, 100, 250, 500, "ALL"]
                ],
                button: [{
                        name: "Publish",
                        url: "{{ route('exam_action_from_admin', 1) }}"
                    },
                    {
                        name: "Un Publish",
                        url: "{{ route('exam_action_from_admin', 0) }}"
                    },
                    {
                        name: "Trash",
                        url: "{{ route('exam_action_from_admin', -1) }}"
                    },
                    {
                        name: "Delete",
                        url: "{{ route('exam.destroy', 1) }}",
                        method: "DELETE"
                    }
                ],

            }


            dataTable(element, url, column, csrf, options);

        });
    
    
    </script>
   
   <!-- script to get homework exam data -->
   <script>
        window.statuschange = '{{ route('exam_action_from_admin') }}';
        $('document').ready(function() {

            var element = $("#datatable-buttons3");
            var url = '{{ route('get_exam_data_from_admin') }}'+'?type=Homework';
            var column = [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'acyear',
                    name: 'academicyear.year',
                    width: '15%'
                },
                
                {
                    data: 'exam_title',
                    name: 'exam_title',
                    width: '15%'
                },
                {
                    data: 'exam_type_column',
                    name: 'exam_type.exam_type_name',
                    className: 'textcenter'
                },
                {
                    data: 'class_section',
                    name: 'class_section',
                    className: 'textcenter'
                },
                {
                    data: 'subject_name',
                    name: 'subject.name',
                    className: 'textcenter'
                },
                {
                    data: 'examdate',
                    name: 'examdate',
                    className: 'textcenter'
                },
                { data:'examsubmissiondatetime', name:'examsubmissiondatetime', className:'textcenter', width: '15%' }, 
                {
                    data: 'status',
                    name: 'id',
                    searchable: false,
                    sortable: false,
                    className: 'textcenter',
                    render: function(data, type, row, meta) {

                        return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                        </label>`;

                    }
                },
                {
                    data: 'action',
                    name: 'id',
                    searchable: false,
                    sortable: false,
                    className: 'textcenter'
                },

                // { data: 'entrymark', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [
                    [15, 25, 50, 100, 250, 500, -1],
                    [15, 25, 50, 100, 250, 500, "ALL"]
                ],
                button: [{
                        name: "Publish",
                        url: "{{ route('exam_action_from_admin', 1) }}"
                    },
                    {
                        name: "Un Publish",
                        url: "{{ route('exam_action_from_admin', 0) }}"
                    },
                    {
                        name: "Trash",
                        url: "{{ route('exam_action_from_admin', -1) }}"
                    },
                    {
                        name: "Delete",
                        url: "{{ route('exam.destroy', 1) }}",
                        method: "DELETE"
                    }
                ],

            }


            dataTable(element, url, column, csrf, options);

        });
   </script>

   <!-- script for online exam -->
   <script>
        window.statuschange='{{route('exam_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons2");
            var url =  '{{route('get_onlineexam_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'year', name: 'academicyear.year', width: '15%' },
                { data: 'type_name', name: 'exam_type.exam_type_name' , className: 'textcenter' },
                { data: 'class_section', name: 'class_section' , className: 'textcenter' },
                { data: 'subject_name', name: 'subject.name' , className: 'textcenter' },
                { data: 'examdatetime', name: 'examdatetime' , className: 'textcenter' },
                { data: 'examsubmissiondatetime', name: 'examsubmissiondatetime' , className: 'textcenter' },
                @if (Session::get("ACTIVE_GROUP") == "Super Admin")
                { data: 'duplicateexam', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
                @endif
                @if (Session::get("ACTIVE_GROUP") == "Student")
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
                @endif
                 
               
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('exam_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('exam_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('exam_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('exam.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
   </script>

   <!-- script for admissionexam    -->
    <script>
        window.statuschange = '{{ route('exam_action_from_admin') }}';
        $('document').ready(function() {

            var element = $("#datatable-buttons4");
            var url = '{{ route('get_admissionexam_data_from_admin') }}'+'?type=Admission';
            var column = [

                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'acyear',
                    name: 'academicyear.year',
                    width: '15%'
                },
                
                {
                    data: 'exam_title',
                    name: 'exam_title',
                    width: '15%'
                },
                {
                    data: 'exam_type_column',
                    name: 'exam_type.exam_type_name',
                    className: 'textcenter'
                },
                {
                    data: 'class_section',
                    name: 'class_section',
                    className: 'textcenter'
                },
                {
                    data: 'subject_name',
                    name: 'subject.name',
                    className: 'textcenter'
                },
                {
                    data: 'exam_date',
                    name: 'exam_date',
                    className: 'textcenter'
                },
                // { data:'exam_submission_date', name:'examsubmissiondatetime', className:'textcenter', width: '15%' }, 
                {
                    data: 'status',
                    name: 'id',
                    searchable: false,
                    sortable: false,
                    className: 'textcenter',
                    render: function(data, type, row, meta) {

                        return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                        </label>`;

                    }
                },
                {
                    data: 'action',
                    name: 'id',
                    searchable: false,
                    sortable: false,
                    className: 'textcenter'
                },

                // { data: 'entrymark', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [
                    [15, 25, 50, 100, 250, 500, -1],
                    [15, 25, 50, 100, 250, 500, "ALL"]
                ],
                button: [{
                        name: "Publish",
                        url: "{{ route('exam_action_from_admin', 1) }}"
                    },
                    {
                        name: "Un Publish",
                        url: "{{ route('exam_action_from_admin', 0) }}"
                    },
                    {
                        name: "Trash",
                        url: "{{ route('exam_action_from_admin', -1) }}"
                    },
                    {
                        name: "Delete",
                        url: "{{ route('exam.destroy', 1) }}",
                        method: "DELETE"
                    }
                ],

            }


            dataTable(element, url, column, csrf, options);

        });
    </script>

      <!-- script to get quiz exam data -->
   {{-- <script>
    window.statuschange = '{{ route('exam_action_from_admin') }}';
    $('document').ready(function() {

        var element = $("#datatable-buttons5");
        var url = '{{ route('get_exam_data_from_admin') }}'+'?type=Quiz';
        var column = [

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex',
                searchable: false,
                sortable: false
            },
            {
                data: 'acyear',
                name: 'acyear',
                width: '15%'
            },
            
            {
                data: 'exam_title',
                name: 'exam_title',
                width: '15%'
            },
            {
                data: 'exam_type_column',
                name: 'exam_type_column',
                className: 'textcenter'
            },
            {
                data: 'class_section',
                name: 'class_section',
                className: 'textcenter'
            },
            {
                data: 'subject',
                name: 'subject',
                className: 'textcenter'
            },
            {
                data: 'examdate',
                name: 'examdate',
                className: 'textcenter'
            },
            // { data:'examsubmissiondatetime', name:'examsubmissiondatetime', className:'textcenter', width: '15%' }, 
            {
                data: 'status',
                name: 'id',
                searchable: false,
                sortable: false,
                className: 'textcenter',
                render: function(data, type, row, meta) {

                    return `<label class="switch">
                    <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                    <span class="slider round"></span>
                    </label>`;

                }
            },
            {
                data: 'action',
                name: 'id',
                searchable: false,
                sortable: false,
                className: 'textcenter'
            },

            // { data: 'entrymark', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
        ];
        var csrf = '{{ csrf_token() }}';

        var options = {
            //order : [ [ 6, "desc" ] ],
            lengthMenu: [
                [15, 25, 50, 100, 250, 500, -1],
                [15, 25, 50, 100, 250, 500, "ALL"]
            ],
            button: [{
                    name: "Publish",
                    url: "{{ route('exam_action_from_admin', 1) }}"
                },
                {
                    name: "Un Publish",
                    url: "{{ route('exam_action_from_admin', 0) }}"
                },
                {
                    name: "Trash",
                    url: "{{ route('exam_action_from_admin', -1) }}"
                },
                {
                    name: "Delete",
                    url: "{{ route('exam.destroy', 1) }}",
                    method: "DELETE"
                }
            ],

        }


        dataTable(element, url, column, csrf, options);

    });
</script> --}}
@endsection
