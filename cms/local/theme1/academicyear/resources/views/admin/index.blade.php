@extends('layout::admin.master')

@section('title','academicyear')
@section('style')
{!! Cms::style("theme/vendors/switchery/dist/switchery.min.css") !!}
    <!-- Datatables -->
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
    </style>
@endsection
@section('body')
@include("layout::admin.breadcrump",['route'=> "View Academic Year"])

<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Academic Year</h4>

            @if (CGate::allows("create-academicyear"))
            <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('academicyear.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Academicyear</a>
            @endif
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Title</th>
                        <th>Year</th>
                        <th>Academic Year Start</th>
                        <th>Academic Year End</th>
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
@section('script_link')


@endsection

@section('script')


    <script>
        window.statuschange='{{route('academicyear_action_from_admin')}}';
        $('document').ready(function(){
           
            var element = $("#datatable-buttons1");
            var url =  '{{route('get_academicyear_data_from_admin')}}';
            var column = [
               
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'title', name: 'title', width: '15%' },
                { data: 'year', name: 'year' , className: 'textcenter' },
                { data: 'start_date', name: 'start_date' , className: 'textcenter' },
                { data: 'end_date', name: 'end_date' , className: 'textcenter' },
               
                { data: 'status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                       var acyear=<?php echo json_decode(
                           Configurations::getCurrentAcademicyear()
                       ); ?>;
                        if(acyear == row['id'])
                        {
                            return `<label class="text-success">current Academic Year</label>`;
                        }
                        
                       else{
                        return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                      </label>`;
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
                        name : "Trash",
                        url : "{{route('academicyear_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('academicyear.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);
            
           
        

        });
    </script>

@endsection
