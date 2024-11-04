@extends('layout::admin.master')

@section('title','exam type')
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
            <h4 class="mb-0">View Online Exam</h4>  
        </div>
        <hr/>
   
       
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Academic Year</th>
                        <th>Exam Type</th>
                        <th>Class/Section</th>
                        <th>Subject</th>
                        <th>Exam Date/Time</th>
                        @if (Session::get("ACTIVE_GROUP") == "Super Admin")
                        <th class="noExport">Action</th>
                        @endif
                    
                        @if (Session::get("ACTIVE_GROUP") == "Student")
                        <th class="noExport">Take Exam</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
        </div>
    </div>
</div>

<style>
    @media print {
    .action-column {
        display: flex;
        align-items: center;
    }

    .action-column button,
    .action-column i,
    .action-column a {
        font-size: 18px !important;
    }
}
</style>
  

@endsection
@section('script')
    <script>
     window.statuschange='{{route('exam_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_onlineexam_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'year', name: 'academicyear.year', width: '15%' },
                { data: 'type_name', name: 'exam_type.exam_type_name' , className: 'textcenter' },
                { data: 'class_section', name: 'class_section' , className: 'textcenter' ,searchable: false,sortable: false},
                { data: 'subject_name', name: 'subject.name' , className: 'textcenter' },
                { data: 'examdatetime', name: 'examdatetime' , className: 'textcenter' ,searchable: false,sortable: false},
                @if (Session::get("ACTIVE_GROUP") == "Super Admin")
                { data: 'duplicateexam', name: 'id', searchable: false, sortable: false, className: 'textcenter action-column'}
                @endif
                @if (Session::get("ACTIVE_GROUP") == "Student")
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter action-column'}
                @endif
                 
               
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('exam_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('exam_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('exam_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('exam.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
