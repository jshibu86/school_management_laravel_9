@extends('layout::admin.master')

@section('title','virtual Communication')
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
@include("layout::admin.breadcrump",['route'=> "Virtual Comunication"])

<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">Virtual Meeting Table</h4>
            <div class="card_button">

               @if(@$is_allowed == true)
                <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('virtualcomunication.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
               @endif
            </div>
            
           
          
        </div>
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation"> <a class="nav-link active" id="pills-class-tab" data-bs-toggle="pill"
                    href="#pills-class" role="tab" aria-controls="pills-home" aria-selected="true">Class Meeting</a>
            </li>
            <li class="nav-item" role="presentation"> <a class="nav-link" id="pills-pta-tab" data-bs-toggle="pill"
                    href="#pills-pta" role="tab" aria-controls="pills-pta" aria-selected="false">PTA Meeting</a>
            </li>
        </ul>    
        <hr/>
        
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="pills-class" role="tabpanel" aria-labelledby="pills-class-tab">
                <div class="table-responsive">
                    <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date Created</th>
                                <th>Meeting Title</th>
                                <th>Moderator</th>
                                <th>Participant</th>
                                <th>Meeting Date</th>
                                <th>Time</th>
                                <th class="noExport">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             
                        </tbody>
                    
                    </table>
                </div>

            </div>
            <div class="tab-pane fade" id="pills-pta" role="tabpanel" aria-labelledby="pills-pta-tab">
                <div class="table-responsive">
                    <table id="datatable-buttons2" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Date Created</th>
                                <th>Meeting Title</th>
                                <th>Moderator</th>
                                <th>Participant</th>
                                <th>Meeting Date</th>
                                <th>Time</th>
                                <th class="noExport">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                             
                        </tbody>
                    
                    </table>
                </div>
            </div>
        
      
    </div>
</div>

    

@endsection
@section('script')
    <script>
           window.statuschange = '{{ route('virtualcomunication_action_from_admin') }}';

            $(document).ready(function(){
                var element = $("#datatable-buttons1");
                var url = '{{ route('get_virtualcomunication_data_from_admin') }}';
                var column = [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'date_created', name: 'date_created', width: '15%'},
                    {data: 'title', name: 'title', className: 'textcenter'},
                    {data: 'moderator_name', name: 'moderator_name', className: 'textcenter'},
                    {data: 'participant', name: 'participant', className: 'textcenter'},
                    {data: 'date', name: 'date', className: 'textcenter'},
                    {data: 'formatted_time', name:'formatted_time', className: 'textcenter'},
                    {data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
                ];
                var csrf = '{{ csrf_token() }}';

                var options = {
                    lengthMenu: [[15, 25, 50, 100, 250, 500, -1], [15, 25, 50, 100, 250, 500, "ALL"]],
                    buttons: [
                        {
                            name: "Publish",
                            url: "{{ route('virtualcomunication_action_from_admin', 1) }}"
                        },
                        {
                            name: "Un Publish",
                            url: "{{ route('virtualcomunication_action_from_admin', 0) }}"
                        },
                        {
                            name: "Trash",
                            url: "{{ route('virtualcomunication_action_from_admin', -1) }}"
                        },
                        {
                            name: "Delete",
                            url: "{{ route('virtualcomunication.destroy', 1) }}",
                            method: "DELETE"
                        }
                    ],
                };

                dataTable(element, url, column, csrf, options);
            });

    </script>

    <script>
         window.statuschange = '{{ route('virtualcomunication_action_from_admin') }}';

        $(document).ready(function(){
            var element = $("#datatable-buttons2");
            var url = '{{ route('get_pta_virtualcomunication_data_from_admin') }}';
            var column = [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'date_created', name: 'date_created', width: '15%'},
                {data: 'title', name: 'title', className: 'textcenter'},
                {data: 'moderator_name', name: 'moderator_name', className: 'textcenter'},
                {data: 'participant', name: 'participant', className: 'textcenter'},
                {data: 'date', name: 'date', className: 'textcenter'},
                {data: 'formatted_time', name:'formatted_time', className: 'textcenter'},
                {data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            var csrf = '{{ csrf_token() }}';

            var options = {
                lengthMenu: [[15, 25, 50, 100, 250, 500, -1], [15, 25, 50, 100, 250, 500, "ALL"]],
                buttons: [
                    {
                        name: "Publish",
                        url: "{{ route('virtualcomunication_action_from_admin', 1) }}"
                    },
                    {
                        name: "Un Publish",
                        url: "{{ route('virtualcomunication_action_from_admin', 0) }}"
                    },
                    {
                        name: "Trash",
                        url: "{{ route('virtualcomunication_action_from_admin', -1) }}"
                    },
                    {
                        name: "Delete",
                        url: "{{ route('virtualcomunication.destroy', 1) }}",
                        method: "DELETE"
                    }
                ],
            };

            dataTable(element, url, column, csrf, options);
        });

    </script>

@endsection
