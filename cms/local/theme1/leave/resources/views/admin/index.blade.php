@extends('layout::admin.master')

@section('title','leave')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .appname{
            align-items: center;
            justify-content: flex-start;
            gap: 3px;
        }
        .form form{
            width: 100%;
        }
        .find{
            margin-top: 28px;
        }
       
    .table-responsive{
        overflow: unset !important;
    }
   
    </style>
@endsection
@section('body')
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Leave</h4>

            @if (Session::get("ACTIVE_GROUP") == "Student" || Session::get("ACTIVE_GROUP") == "Teacher")

            @if(CGate::allows("create-leave"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('leave.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Leave</a>
            @endif
            @endif
            
          
        </div>
        <hr/>

        @if (Session::get("ACTIVE_GROUP") == "Super Admin" )
        {{ Form::open(array('role' => 'form', 'route'=>array('leave.index'), 'method' => 'get', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'leave-form','novalidate' => 'novalidate')) }}
        <div class="row">
            <input type="hidden" name="fillter" value="fillter"/>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="role">Select Role <span class="required">*</span>
                      </label>
                      <div class="feild">
                          {{ Form::select('role',@$roles,@$role,
                          array('id'=>'role','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select role" )) }}
                      </div>
                </div>
                     
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="item form-group">
                 <label class="control-label margin__bottom" for="status">Status<span class="required">*</span>
                      </label>
                      <div class="feild">
                          {{ Form::select('leavestatus',@$status,@$status_,
                          array('id'=>'status','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select status" )) }}
                      </div>
                </div>
                     
            </div>
            <div class="col-xs-12 col-sm-4 col-md-3">
                <div class="item form-group">
                
                      <div class="feild">
                         <button class="btn btn-primary find" type="submit">Find</button>
                      </div>
                </div>
                     
            </div>
       
        </div>
        {{ Form::close() }}
        <hr/>

        @endif


        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Applicant Username</th>
                        <th>Type</th>
                        <th>From / To</th>
                        <th>Status</th>
                        
                       
                        <th class="noExport">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
    </div>

    <div class="modal fade" id="view__homeworks"  aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered form">
            {{ Form::open(array('role' => 'form', 'route'=>array('leave_action_from_admin'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => '', 'id' => 'leave-form','novalidate' => 'novalidate')) }}
            <div class="modal-content" >
                
                <div class="modal-body assigen_parent_body">
    
                    <div class="homework_details">
                       some
                
                </div>
                <div class="modal-footer">
                    @if (Session::get("ACTIVE_GROUP") == "Super Admin")
                    {{-- <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button> --}}
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                   
                </div>
            </div>

            {{ Form::close() }}
    
        
        </div>
    </div>
</div>


  

@endsection
@section('script')


    <script>
       
        var element;
        var url_;
        function ajaxData(url_)
        {
            $('document').ready(function(){

             element = $("#datatable-buttons1");
            var url =  url_;
            var column = [
        
            {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
            { data: 'applicantname', name: 'applicantname', width: '15%',searchable: false, sortable: false, },
            { data: 'type', name: 'leave_types.leave_type' , className: 'textcenter' },
            { data: 'fromto', name: 'leave.from_date' , className: 'textcenter' },
            { data: 'status', name: 'status' , className: 'textcenter', searchable: false, sortable: false, },
            
            { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
            //order : [ [ 6, "desc" ] ],
            lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
            button : [
                {
                    name : "Publish" ,
                    url : "{{route('leave_action_from_admin',1)}}"
                },
                {
                    name : "Un Publish",
                    url : "{{route('leave_action_from_admin',0)}}"
                },
                {
                    name : "Trash",
                    url : "{{route('leave_action_from_admin',-1)}}"
                },
                {
                    name : "Delete",
                    url : "{{route('leave.destroy',1)}}",
                    method : "DELETE"
                }
            ],

            }


            dataTable(element,url,column,csrf,options);

            });
        }

        
       
        @if(@$role || @$status_)
            {
                var status_=<?php echo json_encode($status_); ?>;
                var role=<?php echo json_encode($role); ?>;
                url_='{{route('get_leave_data_from_admin')}}'+"?role="+role+"&leavestatus="+status_;
                ajaxData(url_);
            }
        
        
        @else{
            console.log("status",status);
        
            url_ =  '{{route('get_leave_data_from_admin')}}';
            ajaxData(url_);
        }
          
        @endif
        
        // $("#role").on("change",function(){
        //     var value=$(this).val();

        //     let newurl='{{route('get_leave_data_from_admin')}}'+"?role="+value;

        //     $("datatable-buttons1").dataTable().fnDestroy();

        //    //$("#datatable-buttons1").destroy();

        //     ajaxData(newurl);

        //     console.log(value,"from change");
        // });
      
    </script>

    <script>
         
    </script>

@endsection

@section('scripts')

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
    window.sectionurl="{{ route('section.index') }}";
    window.subjecturl="{{ route('subject.index') }}";
    window.viewleaveurl="{{ route('leave.index') }}";

    $(".viewroute").on("click",function(){

        var class_id=$(this).attr("id");
       
        console.log(class_id,"from home");
        // AcademicConfig.Viewhomework( class_id,section_id,subject_id,notify_script);
       
    });


   
</script>
@endsection
