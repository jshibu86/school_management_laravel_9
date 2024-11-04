@extends('layout::admin.master')

@section('title','transportstop')
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
            <h4 class="mb-0">View Transport Stop</h4>
            @if(CGate::allows('create-transportstop'))
            <a class="btn btn-primary" href="{{route('transportstop.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Stop Name</th>
                        <th>Pickup time</th>
                        <th>Drop Time</th>
                        <th>Fare</th>
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
     window.statuschange='{{route('transportstop_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_transportstop_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'stop_name', name: 'stop_name', width: '15%' },
                { data: 'pickup_time', name: 'pickup_time' , className: 'textcenter' },
                { data: 'drop_time', name: 'drop_time' , className: 'textcenter' },
                { data: 'fare_amount', name: 'fare_amount' , className: 'textcenter' },
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
                        url : "{{route('transport_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('transport_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('transport_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('transport.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
