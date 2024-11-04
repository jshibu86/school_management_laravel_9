@extends('layout::admin.master')

@section('title', 'schoolmanagement')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')

    <style>
        .three-dots-horizontal {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 32px; /* Adjust width as needed */
            height: 12px; /* Adjust height as needed */
        }

        .three-dots-horizontal span {
            width: 4px;
            height: 4px;
            background-color: black;
            border-radius: 50%;
            margin: 0 3px; /* Space between dots */
        }
        .table-div table {
            width: 100% !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection

@section('body')
    <div class="container-fluid">
        <div class="row">
            <div class="card-title btn_style">
                <h4 class="mb-0">School Management</h4>
                @if (CGate::allows('admin-user'))
                    <a href="{{ route('schoolmanagement.create') }}" class="btn btn-primary btn-sm m-1  px-3"><i
                            class='fa fa-plus'></i> Create Account</a>
                @endif

            </div>
            @if (Session::get('ACTIVE_GROUP') == 'Super Admin')

                @php
                    $cards = [
                        [
                            'title' => 'Total School',
                            'value' => @$schoolcount,
                            'image' => asset('assets/images/revenue_icon.png'),
                        ],
                        [
                            'title' => 'Total Active School',
                            'value' => @$activecount,
                            'image' => asset('assets/images/school_icon.png'),
                        ],
                        [
                            'title' => 'Total Inactive School',
                            'value' => @$inactivecount,
                            'image' => asset('assets/images/active_sub.png'),
                        ],
                    ];
                @endphp
                @foreach ($cards as $card)
                    <div class="col-10 col-lg-3">
                        <div class="card radius-15 overflow-hidden">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2 font-25">
                                        <span class="rounded-circle p-2 d-inline-block">
                                            <img src="{{ $card['image'] }}" alt="logo"
                                                style="max-width: 30px; max-height: 30px;">
                                        </span>
                                    </div>
                                    <div class="ms-2 font-18">
                                        <p class="feild mb-0 font-weight-bold text-info text-truncate">{{ $card['title'] }}
                                        </p>
                                        <h5 class="mb-0">{{ $card['value'] }}</h5>
                                        <div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- section for filter options -->
                {{ Form::open(['role' => 'form', 'route' => ['schoolmanagement.index'], 'method' => 'get', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'schoolmanagement-form', 'novalidate' => 'novalidate']) }}
                <input type="hidden" name="filter" value="filterenable" />
                <div class="row d-flex align-items-start">
                    <div class="col-6 d-flex flex-fill">
                        <div class="p-2">
                            {{ Form::select('all_status', ['0' => 'Inactive', '1' => 'Active'], @$actvstatus, [
                                'id' => 'all_status',
                                'class' => 'form-select form-select-sm single-select',
                                'placeholder' => 'select status',
                            ]) }}
                        </div>
                        <div class="p-2 ">
                            {{ Form::select('subscription_status', ['0' => 'Subscribed', '1' => 'Expired'], @$subscstatus, [
                                'id' => 'subsc_status',
                                'class' => 'form-select form-select-sm single-select',
                                'placeholder' => 'select status',
                            ]) }}
                        </div>
                        <div class="p-2">
                            {{ Form::select('approval_status', ['1' => 'Approved', '0' => 'Pending', '-1' => 'Denied'], @$apprstatus, [
                                'id' => 'approval_status',
                                'class' => 'form-select form-select-sm single-select',
                                'placeholder' => 'select status',
                            ]) }}
                        </div>
                        <div class="p-2 ">
                            {!! Form::date('fromdate', '@$fromdate', [
                                'class' => 'form-control form-select-sm',
                                'style' => 'width: 150px;',
                                'placeholder' => 'Select Date',
                                'id' => 'fromdate',
                            ]) !!}
                        </div>
                        <div class="p-2 ">
                            {!! Form::date('todate', '@$todate', [
                                'class' => 'form-control form-select-sm',
                                'style' => 'width: 150px;',
                                'placeholder' => 'Select Date',
                                'id' => 'todate',
                            ]) !!}
                        </div>
                        <div class="feild">
                            <button class="btn btn-primary find" type="submit">Find</button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            @endif
            <hr />


            <!-- Modal Structure -->
            <div class="modal fade" id="approvalModal" tabindex="-1" aria-labelledby="approvalModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="approvalModalLabel">Approval Process</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>                        
                        <div class="modal-body">
                            <div class="card border-secondary m-3">
                                <div class="card-body">                                    
                                    <input type="hidden" id="schoolId" value="">                                                                    
                                    <div class="d-flex flex-column" id="information-approvel"></div>   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>  
    <div class="card radius-15">
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>School Name</th>
                                <th>Phone No</th>
                                <th>Plan Name</th>
                                <th>Billing Cycle</th>
                                <th>Student Count</th>
                                <th>Join Date</th>
                                <th class="noExport">Status</th>
                                <th class="noExport">Subscribe Status</th>
                                <th class="noExport">Approval Process</th>
                                <th class="noExport">Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
@endsection

@section('script')
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
    <script>
        // get the approval access for allowing button enable/disable
        var hasApprovalAccess = {{ json_encode($hasApprovalAccess) }};
        window.statuschange = '{{ route('status_change_from_admin') }}';

        function approveSchool(schoolId) {                      
            var inputData = new FormData();
            inputData.append('schoolId', schoolId);
            axios.post('{{ route('schoolmanagement.approveSchool') }}', inputData)
                .then(response => {
                    if (response.data.success) {
                        $("#information-approvel").html("");
                        $("#information-approvel").html(response?.data?.view );                       
                        $("#approvalModal").modal("show");
                        return false;                        
                    } else {
                        console.log("Unexpected response format");
                    }
                })
                .catch(error => {                    
                    console.error('There was an error!', error.response);                    
                });
        }
      
        function approveSection(userSelectedaction, schoolId) {
            // check for null data
            if(!userSelectedaction || !schoolId){
                console.log("Unexpected response");
                return;
            }                        
            var inputData = new FormData();
            inputData.append('schoolId', schoolId);
            inputData.append('action', userSelectedaction);

            axios.post('{{ route('schoolmanagement.onboardapproval') }}', inputData)
                .then(response => {
                    if (response.data.success) {
                        window.location.href = response.data.redirect; // Redirect to the specified URL
                    } else {
                        console.log("Unexpected response format");
                    }

                }).catch(function(error) {                    
                    console.error('There was an error!', error.response.data.message);   
                });
        }

        function ajaxRequest(urlpath) {
            console.log("Inside ajaxRequest Method: " + urlpath);
            $('document').ready(function() {
                var element = $("#example");
                var url = urlpath;
                var column = [{
                        data: 'reg_no',
                        name: 'reg_no',
                        searchable: false,
                        sortable: false,
                        width: '5%'
                    },
                    {
                        data: 'pimage',
                        name: 'pimage',
                        width: '2%'
                    },
                    {
                        data: 'school_name',
                        name: 'school_name',
                        width: '10%'
                    },
                    {
                        data: 'phoneno',
                        name: 'phoneno',
                        width: '10%'
                    },
                    {
                        data: 'plan_name',
                        name: 'subscription_plan.plan_name',
                        width: '5%'
                    },
                    {
                        data: 'billing_id',
                        name: 'billing_id',
                        width: '5%'
                    },
                    {
                        data: 'student_count',
                        name: 'student_count',
                        width: '5%'
                    },
                    {
                        data: 'join_date',
                        name: 'join_date',
                        width: '5%'
                    },
                   
                    {
                        data: 'status',
                        name: 'id',
                        searchable: false,
                        sortable: false,
                        className: 'textcenter',
                        render: function(data, type, row, meta) {
                            return `<label class="switch">
                                    <input type="checkbox" id=${row['id']} ${row['status']=="1" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                                    <span class="slider round"></span>
                                    </label>`;
                        }
                    },
                    {
                        data: 'subscribe_status',
                        name: 'subscribe_status',
                        width: '5%'
                    },
                    {
                        data: 'approval_status',
                        name: 'approval_status',
                        width: '5%',
                        render: function(data, type, row) {
                            let buttonLabel = '';
                            let buttonClass = ''; // To store the appropriate button class

                        if (row.approval_status == 0) {
                            buttonLabel = 'Pending';
                            buttonClass = 'btn-warning';
                        } else if (row.approval_status == 1) {
                            buttonLabel = 'Approved';
                            buttonClass = 'btn-success';
                        } else if (row.approval_status == -1) {
                            buttonLabel = 'Denied';
                            buttonClass = 'btn-danger';
                        }
                        let isDisabled = hasApprovalAccess == 1 ? 'enabled' : 'disabled'; // Use
                            return `<button id="btn_approve" class="btn ${buttonClass}" style="border-radius: 10px;" onclick="approveSchool(${row.id}, 'approve')" ${isDisabled}>${buttonLabel}&nbsp;&nbsp;&nbsp;<i class="fa fa-angle-right"></i></button>`;                                                      
                        }
                    },
                    {
                        data: 'action',
                        name: 'id',
                        width: '10%',
                        searchable: false,
                        sortable: false,
                        className: 'textcenter'
                    }
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
                            url: "{{ route('get_school_data_from_admin', 1) }}"
                        },
                        {
                            name: "Un Publish",
                            url: "{{ route('get_school_data_from_admin', 0) }}"
                        },
                        {
                            name: "Trash",
                            url: "{{ route('get_school_data_from_admin', -1) }}"
                        },
                        {
                            name: "Delete",
                            url: "{{ route('schoolmanagement.destroy', 1) }}",
                            method: "DELETE"
                        }
                    ],
                }
                dataTable(element, url, column, csrf, options);
            });
        }

        @if (
            (isset($actvstatus) && in_array($actvstatus, ['0', '1'])) ||
                (isset($subscstatus) && in_array($subscstatus, ['subscribed', 'expired'])) ||
                (isset($apprstatus) && in_array($apprstatus, ['approved', 'pending', 'denied'])) ||
                !empty($fromdate) ||
                !empty($todate))
            {
                //  call during the filter button pressed
                console.log("Inside if block");
                var actvstatus = <?php echo json_encode($actvstatus); ?>;
                var subscstatus = <?php echo json_encode($subscstatus); ?>;
                var apprstatus = <?php echo json_encode($apprstatus); ?>;
                var fromdate = <?php echo json_encode($fromdate); ?>;
                var todate = <?php echo json_encode($todate); ?>;
                console.log("Query Filter Inputs : " + actvstatus);
                url = '{{ route('get_school_data_from_admin') }}' + "?actvstatus=" + actvstatus + "&subscstatus=" +
                    subscstatus + "&apprstatus=" + apprstatus + "&fromdate=" + fromdate + "&todate=" + todate;
                ajaxRequest(url);
            }

            // call during onload the document for first time
        @else
            {
                console.log("else");
                url = '{{ route('get_school_data_from_admin') }}';
                ajaxRequest(url);
            }
        @endif
    </script>

    <script>
        $(function() {
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        });
    </script>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd', // Set the format you want
                autoclose: true, // Close the picker automatically after selecting a date
                todayHighlight: true // Highlight today's date
            });
        });
    </script>
@endsection
