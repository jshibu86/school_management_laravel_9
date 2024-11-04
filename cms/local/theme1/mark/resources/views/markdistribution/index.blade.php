@extends('layout::admin.master')

@section('title','mark')
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
            <h4 class="mb-0">View Mark Distribution</h4>
          
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>School Type</th>
                        <th>Mark distribution</th>
                        <th>Is Department Applicable</th>                                           
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
     window.statuschange='{{route('mdistribute_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_markdistribution_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'school_type', name: 'school_type', width: '15%' },
                { data: 'distribution', name: 'distribution' , className: 'textcenter'  },
                { data: 'is_Department', name: 'is_Department' , className: 'textcenter' },               
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('mark_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('mark_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('mark_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('mark.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
