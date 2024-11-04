@extends('layout::admin.master')

@section('title','leavetype')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
                        {{ Form::open(array('role' => 'form', 'route'=>array('leave.leavetypeStore'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'leave-form')) }}
        @elseif($layout == "edit")
                    {{ Form::open(array('role' => 'form', 'route'=>array('leavetype.edit',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_department' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @if (@$layout == "create")
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_department' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
            @endif
          
            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('leave.leavetypes')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
            {{ Form::button('<i class="fa fa-refresh"></i>&nbsp;&nbsp;&nbsp;Clear', ['type' => 'reset','class' => 'btn btn-danger btn-sm m-1  px-3']) }}

            

          

          
           
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit Leave Type" : "Create Leave Type"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Create Leave Type</h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                        
                        <div class="col-xs-12 col-sm-4 col-md-3">
                         <div class="item form-group">
                                <label class="control-label margin__bottom" for="status">Leave Type <span class="required">*</span>
                                </label>
                                <div class="feild">
                                    {{Form::text('leave_type',@$data->leave_type,array('id'=>"leave_type",'class'=>"form-control col-md-7 col-xs-12" ,
                                    'placeholder'=>"Casual leave",'required'=>"required"))}}
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
