@extends('layout::admin.master')

@section('title','academicyear')
@section('style')


@endsection
@section('body')
<style>
    
    .hide-calendar .ui-datepicker-calendar{
  display: none;
}
   
</style>
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('academicyear.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'academicyear-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('academicyear.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_academicyear' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

            @if (@$layout == "create")

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif


            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('academicyear.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
           
            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}
           
            
           
        
            @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Academic Year" : "Create Academic Year"])
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Create a new Academic</h5>
                <hr/>
                <div class="col-xs-12">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> Name <span class="required">(if any) </span> <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('name',@$data->title,array('id'=>"name",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'data-validate-length-range'=>"6",'placeholder'=>"e.g Some Name"))}}
                                    </div>
                                </div>
                        </div>
                        
                        <div class="col-xs-12 col-sm-4 col-md-3">
                        <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> Academic Year  From<span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('year_from',@$data->year_from,array('id'=>"year_pick_from",'class'=>"form-control col-md-7 col-xs-12" ,'placeholder'=>"Select Year "))}}
                                    </div>
                                </div>
                               
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                            <div class="item form-group">
                                        <label class="control-label margin__bottom" for="school_name"> Academic Year To <span class="required">*</span>
                                        </label>
                                        <div class="feild">
                                        {{Form::text('year_to',@$data->year_to,array('id'=>"year_pick_to",'class'=>"form-control col-md-7 col-xs-12" ,'placeholder'=>"Select Year "))}}
                                        </div>
                                    </div>
                                   
                            </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> Start Date <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('start_date',@$data->start_date,array('id'=>"datepicker",'class'=>"datepicker_academic_end form-control col-md-7 col-xs-12" ,'placeholder'=>"Select Date "))}}
                                    </div>
                                </div>
                               
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="school_name"> End Date <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('end_date',@$data->end_date,array('id'=>"datepicker2",'class'=>"datepicker_academic_end form-control col-md-7 col-xs-12" ,'placeholder'=>"Select Date "))}}
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
