@extends('layout::admin.master')

@section('title','Exam Time Table')
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
            <h4 class="mb-0">Exam Time Table</h4>
           
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('examtimetable_create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
          
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Academic Year</th>
                        <th>Term Name</th>
                        <th>Class/Section</th>
                        <th>No of periods</th>
                        <th>Start Date</th>
                        <th>End Date</th>
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
     window.statuschange='{{route('ExamTimetable_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_ExamTimetable_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'year', name: 'academicyear.year', width: '15%' },
                { data: 'academic_term', name: 'exam_term.exam_term_name' , className: 'textcenter' },
                { data: 'class_sec', name: 'class_sec' , className: 'textcenter' },
                { data: 'no_of_period', name: 'no_of_period' , className: 'textcenter' },
                { data: 'start_date', name: 'start_date' , className: 'textcenter' },
                { data: 'end_date', name: 'end_date' , className: 'textcenter' },
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
