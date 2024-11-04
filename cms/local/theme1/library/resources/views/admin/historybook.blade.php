@extends('layout::admin.master')

@section('title','library')
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
            <h4 class="mb-0">History of {{ $data->title }}</h4>

           
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('library.index')}}" ><i class='fa fa-arrow-left'></i>&nbsp;&nbsp;Back</a>
           
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Issued Member Id</th>
                        <th>User Name</th>
                        
                        <th>Issued Date</th>
                        <th>Due Date</th>
                        <th>Returned Date</th>
                        <th>Book Status </th>
                       
                       
                      
                      
                       
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

        let id={!! json_decode($data->id) !!};
     
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_historybook_data_from_admin')}}'+ '/' + id;
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false,width: '15%'},
                { data: 'memusername', name: 'library_member.member_username', width: '15%' },
                { data: 'uname', name: 'users.name', width: '15%' },
                { data: 'issued_date', name: 'issued_date', width: '15%' },
                { data: 'returnDate', name: 'return_date', width: '15%' },
                { data: 'returnedDate', name: 'returnedDate', width: '15%',searchable: false,sortable: false },
                { data: 'bookstatus', name: 'bookstatus', width: '15%',searchable: false,sortable: false },
               
               
             
                
                 
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
