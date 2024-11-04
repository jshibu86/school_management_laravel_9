@extends('layout::admin.master')

@section('title', 'schoolmanagement')
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
    <div class="card radius-15">
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                    <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>School Name</th>
                                <th>Tenant Id</th>
                                <th>Database Name</th>
                                <th>Domain</th>
                                <th class="noExport">Status</th>
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
@endsection
@section('script')
<script>
     window.statuschange = '{{ route('status_change_from_admin') }}';
     $('document').ready(function(){

         var element = $("#datatable-buttons1");
         var url =  '{{route('get_school_tenant_data_from_admin')}}';
         var column = [
           
             {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
             {
                data: 'school_name',
                name: 'school_name',
                width: '10%'
             },
             {
                data: 'tenant',
                name: 'school_profile.tenant_id',
                width: '5%'
            },
            {
                data: 'database_name',
                name: 'tenants.tenancy_db_name',
                width: '5%',searchable: false,sortable: false
            },
            {
                data: 'domain',
                name: 'domains.domain',
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
             
             { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
         ];
         var csrf = '{{ csrf_token() }}';

         var options  = {
             //order : [ [ 6, "desc" ] ],
             lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
             button : [
                 {
                     name : "Publish" ,
                     url : "{{route('event_action_from_admin',1)}}"
                 },
                 {
                     name : "Un Publish",
                     url : "{{route('event_action_from_admin',0)}}"
                 },
                 {
                     name : "Trash",
                     url : "{{route('event_action_from_admin',-1)}}"
                 },
                 {
                     name : "Delete",
                     url : "{{route('event.destroy',1)}}",
                     method : "DELETE"
                 }
             ],

         }


         dataTable(element,url,column,csrf,options);

     });
 </script>
@endsection