@extends('layout::admin.master')

@section('title','School Type')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('schooltype.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'schooltype-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('schooltype.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_fees' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('schooltype.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit School Type" : "Create School Type"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title"> School Type</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">School Type Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('school_type',@$data->school_type,array('id'=>"school_type",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"School Type Name",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Short Name <span class="required">(if any)</span> <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('school_short_name',@$data->school_short_name,array('id'=>"school_short_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"School Type Short name"))}}
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">If Department Applicable <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    <label class="switch">
                                    <input type="checkbox" {{ @$data->is_department == 1 ? "checked" : "" }} id="department" class="toggle-class" name="is_department">
                                    <span class="slider round"></span>
                                </label>
                                </div>
                            </div>
                        </div>

                        
                        </div>
                    </div>
                </div>
            </div>

        
       
       

        {{Form::close()}}
    </div>

@endsection

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    {!! Cms::script("theme/vendors/validator/validator_form.js") !!}

@endsection
