@extends('layout::admin.master')

@section('title','users')
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
@include("layout::admin.breadcrump",['route'=> "View user"])

<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Users</h4>
            @if (CGate::allows("create-user"))
            <a href="{{route('user.create')}}" class="btn btn-primary btn-sm m-1  px-3"><i class='fa fa-plus'></i> Create User</a>
           
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>User Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Group</th>
                        <th class="noExport">Status</th>
                        <th class="noExport">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
    </div>
</div>

    
    
@endsection
@section('script')
<script>
    window.statuschange='{{route('user_action_from_admin')}}';
    $('document').ready(function(){

        var element = $("#example");
        var url =  '{{route('get_user_data_from_admin')}}';
        var column = [
          
            {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
            { data: 'name', name: 'name', width: '15%' },
            { data: 'username', name: 'username', width: '20%' },
            { data: 'email', name: 'email', width: '10%', className: 'textcenter' },
            { data: 'mobile', name: 'mobile' , className: 'textcenter' },
            { data: 'group', name: 'user_groups.group' , className: 'textcenter', sortable: false, },
            { data: 'users.status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                        if(row['id']!=1)
                        {
                            return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                      </label>`;
                        }else{
                            return "";
                        }
                        
                    }
                  },

            { data: 'action', name: 'users.id', searchable: false, sortable: false, className: 'textcenter'}
        ];
        var csrf = '{{ csrf_token() }}';

        var options  = {
            //order : [ [ 6, "desc" ] ],
            //lengthMenu: [[100, 250, 500], [100, 250, 500]]
            button : [
               
                {
                    name : "Trash",
                    url : "{{route('user_action_from_admin',-1)}}"
                },
                {
                    name : "Delete",
                    url : "{{route('user.destroy',1)}}",
                    method : "DELETE"
                }
            ],

        }


        dataTable(element,url,column,csrf,options);

    });
    </script>
    <script>
        $(function(){
            $("input[data-bootstrap-switch]").each(function(){
              $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        });
        </script>
         

@endsection