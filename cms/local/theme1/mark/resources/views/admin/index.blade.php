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
            <h4 class="mb-0">View Mark Entry</h4>
            @if(CGate::allows('create-mark'))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('mark.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Academic year</th>
                        <th>Academic term</th>
                        <th>Class/Section</th>
                        <th>Subject</th>                  
                        <th>Entry Date</th>
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
       window.statuschange='{{route('mark_action_from_admin')}}';
       $('document').ready(function(){

           var element = $("#datatable-buttons1");
           var url =  '{{route('get_mark_data_from_admin')}}';
           var column = [
             
               {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
               { data: 'year', name: 'academicyear.year', width: '15%' },
               { data: 'term_name', name: 'exam_term.exam_term_name' , className: 'textcenter acyear' },
               { data: 'class_section', name: 'class_section' , className: 'textcenter' },
               { data: 'subject', name: 'subject.name' , className: 'textcenter' },            
               { data: 'entry_date', name: 'mark_entry.entry_date' , className: 'textcenter'},
                
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
