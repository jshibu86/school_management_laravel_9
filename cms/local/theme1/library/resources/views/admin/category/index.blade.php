@extends('layout::admin.master')

@section('title','Book Category')
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
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title btn_style">
                    <h4 class="mb-0">Add Category</h4>
                   
                  
                </div>
                <hr/>

                @if($layout == "create")
                    {{ Form::open(array('role' => 'form', 'route'=>array('bookcategory.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'bookcategory-form','novalidate' => 'novalidate')) }}
                @elseif($layout == "edit")
                    {{ Form::open(array('role' => 'form', 'route'=>array('bookcategory.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
                @endif

                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="item form-group">
                               <label class="control-label margin__bottom" for="status">Category Name <span class="required">*</span>
                               </label>
                               <div class="feild">
                                   {{Form::text('cat_name',@$data->cat_name,array('id'=>"cat_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                  'placeholder'=>"category",'required'=>"required"))}}
                               </div>
                        </div>
                    </div>
                     <div class="col-xs-12 col-sm-12 col-md-12">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Category Type <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('category_type',[1=>"online",2=>"offline"],@$data->category_type ,
                                    array('id'=>'category_type','class' => 'single-select form-control','required' => 'required',"placeholder"=>"select category type" )) }}
                                </div>
                          </div>
                               
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-12">
                          <div class="item form-group">
                           <label class="control-label margin__bottom" for="status">Category Image <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    <input class="form-control" type="file" id="formFile" name="category_image" accept="image/*" >
                                </div>
                                 @if (@$layout=="edit")

                                <img src="{{ @$data->category_image }}" width="50" alt="image"/>
                                    
                                @endif
                          </div>
                               
                        </div>
                     </div>
                
                     {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_library' , 'class' => 'mybuttn btn btn-sm btn-primary pull-right')) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="card-title btn_style">
                    <h4 class="mb-0">View Category</h4>
                    
                  
                </div>
                <hr/>
                <div class="table-responsive">
                    <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Category Name</th>
                                
                                <th>Status</th>
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
</div>



  

@endsection
@section('script')
    <script>
     window.statuschange='{{route('category_action_from_admin')}}';
        $('document').ready(function(){

            var element = $("#datatable-buttons1");
            var url =  '{{route('get_library_data_cat_from_admin')}}';
            var column = [
              
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'cat_name', name: 'cat_name', width: '25%' },
              
                 { data: 'status', name: 'id', searchable: false, sortable: false, className: 'textcenter',render : function(data, type, row, meta)
                    {
                        if(row['id']!=1)
                        {
                            return `<label class="switch">
                        <input type="checkbox" id=${row['id']} ${row['status']=="Enabled" ? 'checked':''} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
                        <span class="slider round"></span>
                      </label>`;
                        }else{
                            return "";
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
