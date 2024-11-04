@extends('layout::admin.master')

@section('title','classtimetable')
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
            <h4 class="mb-0">View Class Timetable</h4>
            {{-- <input type="text" id="demo" value="#336699" /> --}}

            @if(CGate::allows('create-classtimetable'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('classtimetable.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif
          
        </div>
        <hr/>
        <input type="hidden" id="activegrp" value="{{Session::get("ACTIVE_GROUP")}}">
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Academic Year</th>
                        <th>Class</th>
                        <th>Section</th>
                        <th>No of Periods</th>
                        @if(Session::get("ACTIVE_GROUP") !== "Student")
                          <th class="noExport">Status</th>
                        @endif
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
     window.statuschange='{{route('classtimetable_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_classtimetable_data_from_admin')}}';
            if($("#activegrp").val() != "Student"){
                var column = [
              
                    {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                    { data: 'academic_year', name: 'academic_year', width: '15%' },
                    { data: 'class', name: 'lclass.name', width: '15%' },
                    { data: 'section', name: 'section.name' , className: 'textcenter' },
                    { data: 'no_of_periods', name: 'no_of_periods' , className: 'textcenter' },             
                    { data: 'status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
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
                    

                    { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
                ];
            }
            else{
                var column = [
              
              {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
              { data: 'academic_year', name: 'academic_year', width: '15%' },
              { data: 'class', name: 'lclass.name', width: '15%' },
              { data: 'section', name: 'section.name' , className: 'textcenter' },
              { data: 'no_of_periods', name: 'no_of_periods' , className: 'textcenter' },             
              { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
          ];
            }
         
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('classtimetable_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('classtimetable_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('classtimetable_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('classtimetable.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
