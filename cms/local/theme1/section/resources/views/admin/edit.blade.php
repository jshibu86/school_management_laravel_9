@extends('layout::admin.master')

@section('title','section')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('section.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'section-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('section.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_section' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @if (@$layout == "create")
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('section.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

           

          

          


            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Section" : "Create Section"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{@$layout == "edit" ?"Edit Section" : "Create Section"}}</h5>
                <hr/>
                <div class="col-xs-12">
                    <div class="row">
                        
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Class Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                {{ Form::select('class_id',@$class_list,@$data->class_id ,
                                 array('id'=>'status','class' => 'form-control single-select','required' => 'required',"placeholder"=>"Select class" )) }}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> Section Name <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('name',@$data->name,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'data-validate-length-range'=>"6",'placeholder'=>"e.g India",'required'=>"required"))}}
                                    </div>
                                </div>
                        </div>
                        {{-- <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Select School Type <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('school_type',Configurations::SCHOOLTYPES,@$data->school_type ,
                                     array('id'=>'school_type','class' => 'form-control single-select','required' => 'required','placeholder'=>"Select School Type" )) }}
                                    </div>
                            </div>
                        </div> --}}
                        @if (@$layout=="edit" && $data->department_id)
                             <div class="col-xs-12 col-sm-4 col-md-3 schooldepartment ">
                        @else
                            <div class="col-xs-12 col-sm-4 col-md-3 schooldepartment">  
                        @endif
                       
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Select Department <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{ Form::select('department_id',@$departments,@$data->department_id ,
                                     array('id'=>'department_id','class' => 'form-control single-select','required' => 'required',
                                     'placeholder'=>"Select Department" )) }}
                                    </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Capacity <span class="required">*</span>
                                </label>
                                <div class="feild">
                                {{Form::number('capacity',@$data->capacity,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                'min'=>"1",'placeholder'=>"Students Capacity",'required'=>"required"))}}
                                </div>
                            </div>
                    </div>
                        
                        
                        <!-- //status -->
                       
                    </div>
                    </div>
                    
                   
                   
                    {{Form::close()}}
                </div>
            </div>
        </div>   
   
    </div>

@endsection

@section('script')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

    

@endsection

@section("scripts")
<script type="module">
        window.departmentcheck="{{ route('schooltype.index') }}";
            $(document).ready(function() {
                console.log( $('select[name="class_id"]'));
              $('select[name="class_id"]').on("change", function () {
                var class_id=$(this).val();
                console.log("yes",class_id);
                 FeeConfig.GetDepartment(class_id);
              });
        });
    </script>
@endsection
