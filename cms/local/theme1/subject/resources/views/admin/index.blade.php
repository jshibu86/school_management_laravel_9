@extends('layout::admin.master')

@section('title','subject')
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
@include("layout::admin.breadcrump",['route'=> "View subject"])

<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Subject</h4>
            <div class="card_button">

                @if(CGate::allows("create-subject"))
                <a class="btn btn-info btn-sm m-1  px-3" href="{{route('subjectteacherMapping')}}" ><i class='fa fa-user'></i>&nbsp;&nbsp;Subject Staff Assign</a>
                <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('subject.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create</a>
                @endif
            </div>
            
           
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Subject Name</th>
                        <th>Subject Code</th>
                        <th>Type</th>
                        <th>Class</th>
                        <th>Section/Subject Faculty</th>
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
     window.statuschange='{{route('subject_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_subject_data_from_admin')}}';
            var column = [
                
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'name', name: 'subject.name', width: '15%' },
                { data: 'subject_code', name: 'subject.subject_code' , className: 'textcenter' },
                { data: 'type', name: 'subject.type', sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                      
                        return `<label class="badge bg-primary">
                        ${data}
                      </label>`;
                      
                        
                    }
                  },
              
                { data: 'classname', name: 'lclass.name' , className: 'textcenter' },
                { data: 'subjectmapping', name: 'subjectmapping', sortable: false,searchable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                   
                        return data;
                     
                    }
                  },
              
               
               
                 { data: 'subject.status', name: 'subject.status', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                      
                        return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']== "Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                      </label>`;
                      
                        
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
                        url : "{{route('subject_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('subject_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('subject_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('subject.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection
