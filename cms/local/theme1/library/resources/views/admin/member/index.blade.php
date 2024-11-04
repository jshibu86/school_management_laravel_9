@extends('layout::admin.master')

@section('title','library')
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
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Members</h4>
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('member.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Member</a>
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Member Id</th>
                        <th>Username</th>
                        <th>Member Type</th>
                        <th>Email</th>
                        
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
        <div class="modal-dialog modal-lg modal-dialog-centered">
           
            <div class="modal-content" >
               
                <div class="modal-body assigen_parent_body">
    
                    <div class="homework_details">
                       some
               
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                   
                </div>
            </div>
    
        
        </div>
    </div>
</div>


  

@endsection
@section('script')
    <script>
     window.statuschange='{{route('member_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_library_member_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'username', name: 'library_member.member_username', width: '15%' },
                { data: 'userinfo', name: 'username', width: '15%' ,searchable: false,sortable: false},
                { data: 'membertype', name: 'library_member.member_type', width: '15%' },
                
               
                { data: 'useremail', name: 'useremail', width: '15%' ,searchable: false,sortable: false},
             
                 { data: 'status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                        
                            return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                      </label>`;
                        
                        
                    }
                  },
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('library_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('library_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('library_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('library.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
