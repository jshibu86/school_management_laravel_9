@extends('layout::admin.master')

@section('title','fees type')
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
            <h4 class="mb-0">View Fee Setup</h4>
            @if(CGate::allows('create-fees'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('feesetup.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Academic Year</th>
                        <th>Class Name</th>
                        <th>School Type</th>
                        <th>Department</th>
                        <th>Total Amount ₦</th>
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
     window.statuschange='{{route('feesetup_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_feessetup_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'acyear', name: 'academicyear.year', width: '15%' },
                { data: 'classname', name: 'lclass.name' , className: 'textcenter' },
                { data: 'schooltype', name: 'school_type.school_type' , className: 'textcenter' },
                { data: 'deptname', name: 'department.dept_name' , className: 'textcenter',searchable: false, sortable: false, },
                { data: 'total_amount', name: 'total_amount' , className: 'textcenter' },
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
                        url : "{{route('fees_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('fees_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('fees_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('fees.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
