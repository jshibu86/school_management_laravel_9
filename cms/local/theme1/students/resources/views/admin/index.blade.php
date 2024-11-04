@extends('layout::admin.master')

@section('title','students')
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
@include("layout::admin.breadcrump",['route'=> "View students"])

<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">View Students</h4>
            <div class="card_button">
            @if (CGate::allows("create-students"))
                <a class="btn btn-info btn-sm m-1  px-3" href="{{route('students.bulkupload')}}" ><i class='fa fa-file'></i>&nbsp;&nbsp;Bulk Upload</a>
                <a class="btn btn-primary btn-sm m-1  px-3" href="{{route('students.create')}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Create Student</a>
            @endif
               
            </div>
            
          
        </div>
        <hr/>
        <div class="table-responsive">
            <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th  class="noExport">Image</th>
                        <th>Reg.No</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Parent</th>
                        <th class="noExport">Status</th>
                        <th class="noExport">Action</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            
            </table>
        </div>
    </div>
    	<!-- Modal -->
        
        <div class="modal fade" id="assigen__parent"  aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" style="width:500px">
                {{ Form::open(array('role' => 'form', 'route'=>array('students.Assigenparent'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'teacher-form','novalidate' => 'novalidate')) }}
                <div class="modal-content" style="width:500px">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Parent</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body assigen_parent_body">

                        <div class="students_details">
                           
                        </div>

                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="parent_id"> Select Parent
                                    </label>
                                    <div class="feild">
                                        {{ Form::select('parent_id',@$parent_lists,null ,
                                        array('id'=>'status','class' => 'select2Input form-control ')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        
						<div class="row parent__details">
                            
                            
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Assigen</button>
                    </div>
                </div>

            {{ Form::close() }}
            </div>
        </div>
</div>
    

@endsection
@section('scripts')
<script >
     function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'fontawesome'
            })
        }
     window.assigenparent='{{route('students.Assigenparent')}}';
     function parentassign(id)
        {
            AcademicConfig.ParentAssigen(id);
        }
   
</script>
@endsection
@section('script')
    <script>
         window.statuschange='{{route('students_action_from_admin')}}';
        
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_students_data_from_admin')}}';
            var column = [
               
                 
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
               { data: 'pimage', name: 'pimage' , className: 'textcenter' },
                { data: 'reg_no', name: 'reg_no' , className: 'textcenter' },
                { data: 'full_name', name: 'full_name' , className: 'textcenter' },
                
                { data: 'mobile', name: 'mobile' , className: 'textcenter' },
                { data: 'parent', name: 'parent.father_name' , className: 'textcenter' , searchable: false, sortable: false},
                
                { data: 'status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                      
                        return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
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
                        url : "{{route('students_action_from_admin',1)}}"
                    },
                    {
                        name : "Un Publish",
                        url : "{{route('students_action_from_admin',0)}}"
                    },
                    {
                        name : "Trash",
                        url : "{{route('students_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('students.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });

        

        
    </script>

@endsection
