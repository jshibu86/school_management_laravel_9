@extends('layout::admin.master')

@section('title','leave types')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .types{
            box-shadow: rgb(149 157 165 / 20%) 0px 8px 24px;
             padding: 20px;
             height: 225px;
        }
    </style>
@endsection
@section('body')


<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Leave Types</h4>
            @if(CGate::allows("create-leave"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('leavetype.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Leave Type</a>
            @endif
        </div>
        <hr/>
        <div class="row">
           
        
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="table-responsive">
                    <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Type</th>
                                
                                <th class="noExport">Action</th>
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


  

@endsection
@section('script')
    <script>
     window.statuschange='{{route('get_leave_type_data_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_leave_type_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'leave_type', name: 'leave_type', width: '35%' },
             
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
    </script>

@endsection
