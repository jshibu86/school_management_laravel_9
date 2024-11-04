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
            <h4 class="mb-0">View User Group</h4>
            @if (CGate::allows("create-user"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('usergroup.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Group</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Group Name</th>
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
     window.statuschange='{{route('user_group_action_from_admin')}}';
    $('document').ready(function(){

        var element = $("#datatable-buttons1");
        var url =  '{{route('get_user_group_data_from_admin')}}';
        var column = [
            
            {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
            { data: 'group', name: 'group', width: '35%' },
           { data: 'usergroup.status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
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
            { data: 'action', name: 'usergroup.id', searchable: false, sortable: false, className: 'textcenter'}
        ];
        var csrf = '{{ csrf_token() }}';

        var options  = {
            //order : [ [ 6, "desc" ] ],
            //lengthMenu: [[100, 250, 500], [100, 250, 500]]
            button : [
               
                {
                    name : "Trash",
                    url : "{{route('user_group_action_from_admin',-1)}}"
                },
                {
                    name : "Delete",
                    url : "{{route('usergroup.destroy',1)}}",
                    method : "DELETE"
                }
            ],

        }


        dataTable(element,url,column,csrf,options);

    });
    </script>

@endsection