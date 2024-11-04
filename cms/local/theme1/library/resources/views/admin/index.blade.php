@extends('layout::admin.master')

@section('title','library')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .form form{
            width: 100%;
        }
        .table-responsive{
        overflow: unset !important;
    }
    </style>
@endsection
@section('body')
<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Books</h4>

            @if(CGate::allows("create-library"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('library.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Book</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Book No</th>
                        <th>Title</th>
                       
                        <th>Availability</th>
                        @if (CGate::allows("edit-library"))
                        <th>Status</th>
                      
                        <th class="noExport">Action</th>
                        @endif
                       
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="view__homeworks"  aria-hidden="true">
    
    <div class="modal-dialog modal-lg modal-dialog-centered form">
        {{ Form::open(array('role' => 'form', 'route'=>array('BookStatusChange'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => '', 'id' => 'leave-form','novalidate' => 'novalidate')) }}
        <div class="modal-content" >
            
            <div class="modal-body assigen_parent_body">

                <div class="homework_details">
                   some
            
                </div>
           
        </div>

       
        {{ Form::close() }}
    
    </div>
    
</div>

  

@endsection
@section('script')
    <script>
     window.statuschange='{{route('library_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_library_data_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false,width: '15%'},
                { data: 'book_no', name: 'book_no', width: '15%' },
                { data: 'title', name: 'title', width: '15%' },
                
              

                { data: 'available', name: 'available', width: '10%',searchable: false,sortable: false },
                @if (CGate::allows("edit-library"))
                { data: 'bookstatus', name: 'id', searchable: false, sortable: false, className: 'textcenter'},
                @endif
                
                  @if (CGate::allows("edit-library"))
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
                @endif
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('library_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('library_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('library_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('library.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
