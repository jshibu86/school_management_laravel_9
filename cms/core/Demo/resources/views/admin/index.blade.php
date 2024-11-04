@extends('layout::admin.master')

@section('title','Demo')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection
@section('body')  
<div class="container-fluid"> 
    <div class="row" >
        <div class="card-title btn_style">  
            <h4 class="mb-0">Admin Management - Demo</h4>    
            @if (CGate::allows("admin-user"))
                <a href="{{route('Demo.create')}}" class="btn btn-primary btn-sm m-1 px-3"><i class='fa fa-plus'></i> Create Demo</a>           
            @endif                             
        </div>  
        <!-- Modal Structure -->
        <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="scheduleModalLabel">Schedule Demo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>                        
                    <div class="modal-body">                            
                        <div class="card-body">   
                            <div class="form-check">
                                <label class="form-check-label p-2">
                                    <span class="form-check-label text-primary">Select Date: </span>
                                </label>
                                <input type="date" id="inputdate" class= "form-control rounded-pill form-control col-md-4" name="inputdate" required>  					                                     
                            </div>
                            <div class="form-check">
                                <label class="form-check-label p-2">
                                    <span class="form-check-label text-primary">Select Time: </span>
                                </label>
                                <input type="time" class= "form-control rounded-pill form-control col-md-4" id="inputtime" name="inputtime" required>
                            </div>                           
                            <div class="form-check">  
                                <label class="form-check-label p-2">                                        
                                </label>                                 
                                <textarea class= "form-control col-md-4" id="inputtext" name="inputtext" required>{{@$data}}</textarea>
                            </div>                                     
                                <input type="hidden" id="demoId" value="">                                                                                                       
                            </div>
                            <br/>
                            <br/>
                            <br/>
                            <br/>
                            <br/>
                            <div class="box-header with-border mar-bottom20"> 
                                <button class="btn btn-primary btn-sm m-1 px-3" data-bs-dismiss="modal" aria-label="Close">Close</button>                                    
                                <button class="btn btn-primary btn-sm m-1 px-3" id="modalSubmit" onclick="saveScheduleDemo()" type="submit">Send</button>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>  

            <div class="modal fade" id="attendanceModal" tabindex="-1" aria-labelledby="attendanceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="attendanceModalLabel">Attendance</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>                        
                    <div class="modal-body">                            
                        <div class="card-body">   
                            <div class="form-check">
                                <label class="form-check-label p-2">
                                    <h5 class="modal-title" id="attendanceModalLabel"><b>Are you sure you want to mark the Attendance</b></h5>
                                </label>                               
                            </div>
                                                                                                                                   
                            </div>
                            <br/>
                            <br/>                            
                            <br/>
                            <div class="box-header with-border mar-bottom20"> 
                                <button class="btn btn-primary btn-sm m-1 px-3" data-bs-dismiss="modal" aria-label="Close">Close</button>                                    
                                <button class="btn btn-primary btn-sm m-1 px-3" id="modalSubmit" onclick="saveAttendance()" type="submit">Confirm</button>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>  
            
        @if (Session::get("ACTIVE_GROUP") == "Super Admin")   
        
            @php 
            $cards = [ 
                ['title'=>'Total Request Demo', 'value'=>$shedule_request, 'icon'=> "bx bx-mail-send text-warning","bg_color"=>"alert-warning"],
                        ['title'=>'Total Schedule Demo', 'value'=>$sheduled, 'icon'=> "bx bx-calendar text-primary","bg_color"=>"alert-primary"],
                        ['title'=>'Total Attendant', 'value'=>$attend, 'icon'=> "bx bx-calendar-check text-success","bg_color"=>"alert-success"], 
                        ];
            @endphp

            @foreach($cards as $card)
                <div class="col-10 col-lg-3">
                    <div class="card radius-15 overflow-hidden">
                        <div class="card-body">         
                            <div class="d-flex align-items-center"> 
                                <div class="ms-2 font-25"> 
                                    <span class="rounded-circle p-2 d-inline-block {{ $card['bg_color'] }}">
                                        <i class="{{ $card['icon']}} text-center" alt="logo"  style="width: 30px; height: 30px; font-size:20px;"></i>
                                    </span>
                                </div>           
                                <div class="ms-2 font-18">    
                                    <p class="feild mb-0 font-weight-bold text-info text-truncate">{{ $card['title'] }}</p>
                                    <h5 class="mb-0">{{ $card['value'] }}</h5>  
                                <div>     
                            </div>                          
                        </div>  
                    </div>                           
                </div>
            </div>
            </div>    
            @endforeach  
        @endif    
    </div> <!--  end div for row -->

    <div class="card radius-15">
        <div class="card-body">
            <div class="row">
                <div class="card-title">
                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">Demo Request</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"  href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">Schedule Demo</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#pills-contact" role="tab"
                                aria-controls="pills-contact" aria-selected="false">Attendant</a>						 
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-setting-tab" data-bs-toggle="pill" href="#pills-setting" role="tab"
                                aria-controls="pills-setting" aria-selected="false">Setting</a>						 
                            </li>
                        </ul>                    
                    </div>
                </div>

                <hr style="border: 1px solid black;">

                <div class="tab-content" id="pills-tabContent">
                        
                    <!-- To display demo request index details(Tab-1) -->
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">												  
                            <div class="table-responsive">
                                <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>School Name</th>
                                            <th>Contact Person</th>                                        
                                            <th>Email</th>
                                            <th>Mobile</th>
                                            <th>Role</th>
                                            <th>Request Joined</th>                                                                             
                                            <th class="noExport">Status</th>
                                            <th class="noExport">Schedule Request</th>
                                            <th class="noExport">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>                        
                                </table>
                            </div>   
                        </div>             

                    <!--  To display schedule demo details(Tab-2) -->
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <div class="table-responsive">
                                <table id="datatable-buttons2" class="table table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>School Name</th>
                                        <th>Contact Person</th>                                        
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Role</th>
                                        <th>Date Request</th>                                                                             
                                        <th>Date Demo</th>                                                                             
                                        <th>Time Demo</th>  
                                        <th class="noExport">Status</th>
                                        <th class="noExport">Attendant</th>
                                        <th class="noExport">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>                            
                                </table>
                            </div>
                        </div>
                                            
                    <!--  To display Attendant Index details (Tab-3) -->
                        <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">                                                                                
                            <!-- <div class="card-body">
                                {{ Form::open(array('role' => 'form', 'route'=>array('save_roles_from_admin'), 'method' => 'post', 'class' => 'form-horizontal form-label-left', 'id' => 'role-form')) }}
                                <div class="card-title btn_style">                                
                                    {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => '' , 'value' => 'role_save' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}                                
                                </div>
                                <hr/>
                            </div> -->                          
                            <div class="table-responsive">
                                <table id="datatable-buttons3" class="table table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>School Name</th>
                                        <th>Contact Person</th>                                        
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Role</th>
                                        <th>Date Request</th>                                                                             
                                        <th>Date Demo</th>                                                                             
                                        <th>Time Demo</th>                                          
                                        <th class="noExport">Status</th>                                        
                                        <th class="noExport">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>                            
                                </table>                          
                            </div>
                        </div>

                    <!--  To setting of demo schedule message (Tab-4) -->
                        <div class="tab-pane fade" id="pills-setting" role="tabpanel" aria-labelledby="pills-setting-tab">                                                                                
                        <div class = "col-3">                        
                            <h6 class="mb-0">Demo - Schedule Message</h6>                              
                        </div>
                            <div class = "col-9">
                            {{ Form::textarea('schedule_message',  @$data, array(
                            'id'=>"schedule_message",'rows'=>'3','class'=>"form-control col-md-7 col-xs-12" , 'Placeholder'=>'text', ))   }}
                            <br/>
                            <br/>
                            <div class="box-header with-border mar-bottom20">                                                                
                                <button class="btn btn-primary btn-sm m-1 px-3" id="settingMessage" onclick="saveSettingMessage()" type="submit">Save</button>
                            </div>
                            </div>                                                       
                        </div>                        
                </div>                    
            </div>            
        </div>
    </div>     
</div>  
@endsection
@section('script')
<script>
$(document).ready(function() {

    // Display the table on page load if the first tab is active
    if ($('#pills-home').hasClass('active')) {    
        displayIndex('#pills-home');
    }

    // Listen for click events on the tab links
    $('a[data-bs-toggle="pill"]').on('click', function (e) {
        var target = $(this).attr("href");    
        displayIndex(target);
    
    });

    // to change tab config
    function displayIndex(target) {
        var config = getColumnConfig(target);    
        if (config) {
            initializeDataTable(config);
        }
    }

    // to intialize values to data table
    function initializeDataTable(config) {
        var csrf = '{{ csrf_token() }}';
        var options = {
            button: [
                {
                    name: "Trash",
                    url: "{{route('user_action_from_admin', -1)}}"
                },
                {
                    name: "Delete",
                    url: "{{route('user.destroy', 1)}}",
                    method: "DELETE"
                }
            ],
            // Uncomment and modify if needed
            // order: [[6, "desc"]],
            // lengthMenu: [[100, 250, 500], [100, 250, 500]]
        };
        
        // Destroy any existing DataTable instance
        if ($.fn.DataTable.isDataTable(config.element)) {
                config.element.DataTable().destroy();
            }

        dataTable(config.element, config.url, config.column, csrf, options);
    }
    
    // to show the data tables on web page
    function getColumnConfig(target) {

        if (target === "#pills-home") 
        {        
            return {
                element: $("#datatable-buttons1"),
                url: '{{route('get_Demo_data_from_admin')}}',
                column: [
                        { data: 'demo_id', name: 'demo_id', width: '15%' },
                        { data: 'school_name', name: 'school_name', width: '15%' },
                        { data: 'contact_name', name: 'contact_name', width: '15%' },
                        { data: 'email', name: 'email', width: '15%' },
                        { data: 'phoneno', name: 'phoneno', width: '15%' },                
                        { data: 'role', name: 'role' , className: 'textcenter' },
                        { data: 'joindate', name: 'joindate', width: '15%' },                        
                        { data: 'status', name: 'status', sortable: false , className: 'textcenter' },            
                        { data: 'schedule', name: 'schedule', searchable: false, sortable: false, className: 'text-center',
                            render: function(data, type, row, meta) {                        
                                return `<button id="${row['id']}" class="btn btn-primary btn-sm m-1 px-3" style="border-radius: 8px;" onclick="scheduleDemo(${row.id})" > Schedule&nbsp;<i class="fa fa-angle-right"></i></button>`;
                            }
                        },            
                        { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
                ]
            };
        }
        else if (target === "#pills-profile") 
        {
            return {
                element: $("#datatable-buttons2"),
                url: '{{route('get_Demo_schedule_data_from_admin')}}',
                column: [
                        { data: 'demo_id', name: 'demo_id', width: '15%' },
                        { data: 'school_name', name: 'school_name', width: '15%' },
                        { data: 'contact_name', name: 'contact_name', width: '15%' },
                        { data: 'email', name: 'email', width: '15%' },
                        { data: 'phoneno', name: 'phoneno', width: '15%' },                
                        { data: 'role', name: 'role' , className: 'textcenter' },
                        { data: 'joindate', name: 'joindate', width: '15%' },
                        { data: 'demo_date', name: 'demo_date', width: '15%' },
                        { data: 'demo_time', name: 'demo_time', width: '15%' },
                        { data: 'status', name: 'status', sortable: false , className: 'textcenter' },            
                        { data: 'status', name: 'schedule', searchable: false, sortable: false, className: 'text-center',
                            render: function(data, type, row, meta) {  
                                console.log("Row Status"+row.status); 
                                if (row.status.includes("Expired") ) {
                                    return `<button id="${row['id']}" class="btn btn-primary btn-sm m-1 px-3" style="border-radius: 8px;" onclick="approvescheduleDemo(${row.id}, 'reschedule')"> Re-Schedule&nbsp;<i class="fa fa-angle-right"></i></button>`;
                                }
                                else{
                                    return `<button id="${row['id']}" class="btn btn-primary btn-sm m-1 px-3" style="border-radius: 8px;" onclick="approvescheduleDemo(${row.id}, 'attendant')" > Attendant&nbsp;<i class="fa fa-angle-right"></i></button>`;
                                }             
                                
                            }
                        },
                            
                        { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
                ]
            };
        }
        else if (target === "#pills-contact") 
        {        
            return {
                element: $("#datatable-buttons3"),
                url: '{{route('get_Demo_attendant_data_from_admin')}}',
                column: [
                        { data: 'demo_id', name: 'demo_id', width: '15%' },
                        { data: 'school_name', name: 'school_name', width: '15%' },
                        { data: 'contact_name', name: 'contact_name', width: '15%' },
                        { data: 'email', name: 'email', width: '15%' },
                        { data: 'phoneno', name: 'phoneno', width: '15%' },                
                        { data: 'role', name: 'role' , className: 'textcenter' },
                        { data: 'joindate', name: 'joindate', width: '15%' },
                        { data: 'demo_date', name: 'demo_date', width: '15%' },
                        { data: 'demo_time', name: 'demo_time', width: '15%' },
                        { data: 'status', name: 'status', sortable: false , className: 'textcenter' },                                                        
                        { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
                ]
            };
        
        }           
        return null;
    }

});

// function to show modal window 
function scheduleDemo(demoId) {                         
        $("#demoId").val(demoId);
        $("#scheduleModal").modal("show");
}

// function to save the schedule date&time and send email to school
function saveScheduleDemo() {      
    var demoId = $("#demoId").val();            
    var demoDate = $("#inputdate").val();
    var demoTime = $("#inputtime").val();
    var demoText = $("#inputtext").val();            
    console.log('schedule demo called'+demoId +demoDate + demoTime);
    var inputData = new FormData();
    inputData.append('demoId', demoId);
    inputData.append('demoDate', demoDate);
    inputData.append('demoTime', demoTime);
    inputData.append('demoText', demoText);

    axios.post('{{ route('demo.scheduleDemo') }}', inputData)
        .then(response => {
            if (response.data.success) {                      
                console.log("Sucess");    
                window.location.href = response.data.redirect;                 
            } else {
                console.log("Unexpected response format");
            }
        })
        .catch(error => {                    
            console.error('There was an error!', error.response);                    
        });
}

// function to save whether user has attended demo or user is expired

function approvescheduleDemo(demoId, action) {       
    var demoaction = action;          
         if(action.includes("reschedule")){            
            scheduleDemo(demoId);                        
         }else{
            $("#demoId").val(demoId); 
            $("#attendanceModal").modal("show");            
         }             
}

function saveAttendance(){
    var demoId = $("#demoId").val();    
    console.log('Save attendance'+demoId);
    var inputData = new FormData();
    inputData.append('demoId', demoId);    

    axios.post('{{ route('demo.saveAttendance') }}', inputData)
        .then(response => {
            if (response.data.success) {                      
                console.log("Sucess");    
                window.location.href = response.data.redirect;                 
            } else {
                console.log("Unexpected response format");
            }
        })
        .catch(error => {                    
            console.error('There was an error!', error.response);                    
        });
} 

function saveSettingMessage(){
    
    var demoSettingMsg = $("#schedule_message").val();        
    console.log('saveSettingMessage'+demoSettingMsg);
    var inputData = new FormData();
    inputData.append('demoSettingMsg', demoSettingMsg);    

    axios.post('{{ route('demo.saveSettingMessage') }}', inputData)
        .then(response => {
            if (response.data.success) {                      
                console.log("Sucess");    
                window.location.href = response.data.redirect;                 
            } else {
                console.log("Unexpected response format");
            }
        })
        .catch(error => {                    
            console.error('There was an error!', error.response);                    
        });
}

</script>

@endsection

