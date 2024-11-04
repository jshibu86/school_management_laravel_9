@extends('layout::admin.master')

@section('title','visitorbook')
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
            <h4 class="mb-0">View visitorbook</h4>
            @if(CGate::allows('create-visitorbook'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('visitorbook.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Visitor Name</th>
                        <th>Visitor Email</th>
                        <th>Visitor Phone</th>
                        <th>Description</th>
                        <th>Status</th>
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
     window.statuschange='{{route('visitorbook_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_visitorbook_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'visitor_name', name: 'visitor_name', width: '15%' },
                { data: 'visitor_email', name: 'visitor_email', width: '15%' },
                { data: 'visitor_phone', name: 'visitor_phone', width: '15%' },
                { data: 'reason', name: 'reason' , className: 'textcenter' },
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
                        url : "{{route('visitorbook_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('visitorbook_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('visitorbook_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('visitorbook.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
