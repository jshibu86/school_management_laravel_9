@extends('layout::admin.master')

@section('title','markdistribution')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('markdistribution.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'markdistribution-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('markdistribution.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_mark' , 'class' => 'btn btn-success btn-sm m-1  px-3 ')) }}

            @if (@$layout == "create")  

            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}

            @endif
           

           

             <a class="btn btn-info btn-sm m-1  px-3" href="{{route('markdistribution.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit markdistribution" : "Create markdistribution"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create New Mark Distribution</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="alert alert-primary d-flex align-items-center" role="alert">
                        <i class="fa fa-info-circle " aria-hidden="true"></i>
                        <div class="ms-3">
                           After adding a new mark Dsitribution select this Distribution Type Entry Mark Option in Configurations Settings
                        </div>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="row">
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Mark Dsitribution Name <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('distribution_name',@$data->distribution_name,array('id'=>"distribution_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"Exam,Homework",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Mark Value <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('mark',@$data->mark,array('id'=>"mark",'class'=>"form-control col-md-7 col-xs-12" ,
                                   'placeholder'=>"70",'required'=>"required"))}}
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
