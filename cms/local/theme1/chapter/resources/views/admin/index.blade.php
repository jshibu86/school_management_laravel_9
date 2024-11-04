@extends('layout::admin.master')

@section('title','chapter')
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
@include("layout::admin.breadcrump",['route'=> "View Syllabus"])

<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Syllabus</h4>
            @if (CGate::allows("create-chapter"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('chapter.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Syllabus</a>
            @endif
           
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Chapter Name</th>
                        <th>Class - Section</th>
                        <th>Subject</th>
                        <th>Total Topics</th>
                        @if (CGate::allows("edit-chapter"))
                        <th>Add Topics</th>
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
     window.statuschange='{{route('chapter_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_chapter_data_from_admin')}}';
            var column = [
                
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'chapter_name', name: 'chapter_name', width: '15%' },
                { data: 'class_section', name: 'class_section' , className: 'textcenter' },
                { data: 'subjectname', name: 'subject.name' , className: 'textcenter' },
                { data: 'count', name: 'count' , className: 'textcenter' },
                @if (CGate::allows("edit-chapter"))
                { data: 'add-topics', name: 'count' , className: 'textcenter',searchable: false,sortable: false },
                @endif
                 
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('chapter_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('chapter_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('chapter_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('chapter.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
