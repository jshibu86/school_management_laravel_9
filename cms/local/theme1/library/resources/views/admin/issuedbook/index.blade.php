@extends('layout::admin.master')

@section('title','issue')
@section('style')
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
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
            <h4 class="mb-0">View Issued Books</h4>

            @if(CGate::allows("create-library"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('issuebook.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Issuebook</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>User Name</th>
                        <th>Member type</th>
                        <th>Library Member ID</th>
                        <th>Book Title</th>
                        <th>Issued Date</th>
                        <th>Due Date</th>
                        
                        <th>Book Status</th>
                        @if (CGate::allows("edit-library"))
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


  

@endsection
@section('script')
    <script>
     window.statuschange='{{route('issued_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_library_data_issued_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false,width: '15%'},
                { data: 'username_', name: 'users.name', width: '15%',searchable: false,sortable: false },
                { data: 'membertype', name: 'users.name', width: '15%' },
                { data: 'memberid', name: 'library_member.member_username', width: '15%' },
                { data: 'title', name: 'books.title', width: '15%' },
                { data: 'issued_date', name: 'issued_books.issued_date', width: '15%' },
                { data: 'return_date', name: 'issued_books.return_date', width: '15%' },
             
                
                { data: 'return', name: 'return', width: '15%', searchable: false, sortable: false,},

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
