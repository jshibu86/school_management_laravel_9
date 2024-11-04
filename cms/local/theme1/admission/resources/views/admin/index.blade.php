@extends('layout::admin.master')

@section('title','admission')
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
@include("layout::admin.breadcrump",['route'=> "View admission"])

<div class="card">
    <div class="card-body">
    
        <div class="card-title btn_style">
            <h4 class="mb-0">View Admissions</h4>            
            <div class="card_button">
                @if(CGate::allows('create-admission'))                
                <a class="btn btn-info btn-sm m-1  px-3" href="{{route('admissionform')}}" target="_blank">Admission Form Settings</i></a>
                <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('admission.new')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create New Admission</a>
                 @endif
                 
            </div>
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
            <tr>
                <!-- <th class="noExport">{!! Form::checkbox('select_all', 'checked_all', false, array('id'=>'select-all-item')) !!}{!! Html::decode(Form::label('select-all-item','<span></span>')) !!}</th> -->
                <th>No</th>
                <th>Image</th>
                <th>Student Name</th>
                <th>Mobile Number</th>               
                <th>Parent Name</th>
                <th>Parent Mobile</th>
                <th>Status</th>
                <th class="noExport">Action</th>
            </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

@endsection
@section('script')
    <script>
         window.statuschange='{{route('get_admission_data_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_admission_data_from_admin')}}';
            var column = [
                // { data: 'check', name: 'check', searchable: false, sortable: false , width: '9%' , render : function(data, type, row, meta)
                //     {
                //         return '<input id="'+data+'" class="check_class" type="checkbox" value='+row["id"]+' name="selected_admission[]"><label for="'+data+'"><span></span></label>';
                //     }
                // },
                { data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'image', name: 'image' , className: 'textcenter' },
                { data: 'full_name', name: 'full_name', className: 'textcenter' },
                { data: 'mobile', name: 'mobile' , className: 'textcenter' },
                { data: 'parent_name', name: 'parent_name' , className: 'textcenter' },
                { data: 'parent_mobile', name: 'parent_mobile' , className: 'textcenter' },
                { data: 'admission_status', name: 'admission_status' , className: 'textcenter' },                
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'}
            ];
            
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[15,25,50, 100 ,250, 500, -1], [15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                    {
                        name : "Publish" ,
                        url : "{{route('admission_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('admission_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('admission_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('admission.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }

            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
