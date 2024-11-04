@extends('layout::admin.master')

@section('title','classteacher')
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
@include("layout::admin.breadcrump",['route'=> "View Classteacher"])

<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Classteacher</h4>
            @if (CGate::allows("create-classteacher"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('classteacher.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Classteacher</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Academic Year</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>Department</th>
                        <th>Class Teacher</th>

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
     window.statuschange='{{route('classteacher_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_classteacher_data_from_admin')}}';
            var column = [
               
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'academic_year', name: 'academicyear.year', width: '15%' },
                { data: 'class', name: 'lclass.name' , className: 'textcenter' },
                { data: 'section', name: 'section.name' , className: 'textcenter' },
                { data: 'department', name: 'section.department_id' , className: 'textcenter' },

                { data: 'teacher_name', name: 'teacher.teacher_name' , className: 'textcenter' },
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
                        name : "Trash",
                        url : "{{route('classteacher_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('classteacher.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection