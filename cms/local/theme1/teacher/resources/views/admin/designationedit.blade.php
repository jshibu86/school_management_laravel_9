@extends('layout::admin.master')

@section('title','designation')
@section('style')
<style>
    .select2-results__options li.select2-results__option:nth-child(2) {
    color: red;
}
</style>

@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('designationcreate'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'teacher-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('designationcreate',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           

           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_teacher' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif


            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('designationview')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}
           
            

            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Designation" : "Create Designation"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{$layout == "edit" ?"Edit Designation" : "Create Designation"}}</h5>
                <hr/>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="designation_name"> Designation Name <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('designation_name',@$data->designation_name,array('id'=>"designation_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'data-validate-length-range'=>"6",'placeholder'=>"e.g Principal",'required'=>"required"))}}
                                    </div>
                                </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="school_name"> Type <span class="required">*</span>
                                </label>
                                <div class="field designation_select_field">
                                    {{ Form::select('type', @$types, @$data->type,
                                        [
                                            'id' => 'designation_type',
                                            'class' => 'form-control single-select',
                                            'required' => 'required',
                                            'placeholder' => 'Select Designation'
                                        ])
                                    }}
                                </div>
                                
                                <div class="feild designation_type_feild" style="display:none">
                                    {{ Form::text('type',@$data->type ,
                                     array('id'=>'designation_type','class' => 'form-control type_feild','required' => 'required',"placeholder"=>"Select Designation","disabled" )) }}
                                     <span class="required back_to">Back to Select</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                <label class="control-label margin__bottom" for="description"> Description <span class="required">(if any)</span>
                                </label>
                                <div class="feild">
                                   {{ Form::textarea('description', @$data->description, [
                                        'class'      => 'form-control',
                                        'rows'       => 3, 
                                        'name'       => 'description',
                                        'id'         => 'description',
                                        'onkeypress' => "return nameFunction(event);"
                                    ])}}
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

@endsection
