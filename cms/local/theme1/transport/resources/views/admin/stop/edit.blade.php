@extends('layout::admin.master')

@section('title','transportstop')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('transportstop.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'transport-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('transportstop.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_section' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @if (@$layout == "create")
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('transportstop.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit transport staff" : "Createtransport Staff/Driver"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ @$layout=="create" ? "Create a new": "Edit" }}  Transport Stop </h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Stop Name <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('stop_name',@$data->stop_name,array('id'=>"stop_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"whie feild",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Pickup Time <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::time('pickup_time',date(
                                            "H:i",
                                            strtotime(@$data->pickup_time)
                                        ),array('id'=>"pickup_time",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"Pickup time",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Drop Time <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::time('drop_time',date(
                                            "H:i",
                                            strtotime(@$data->drop_time)
                                        ),array('id'=>"drop_time",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"Pickup time",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Fare Amount <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::number('fare_amount',@$data->fare_amount,array('id'=>"fare_amount",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"100",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                           
                           
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Stop Description <span class="required"></span>
                                       </label>
                                       <div class="feild">
                                        {{  Form::textarea('stop_description', @$data->stop_description, [
                                            'class'      => 'form-control',
                                            'rows'       => 3, 
                                            'name'       => 'stop_description',
                                            'id'         => 'stop_description',
                                           
                                        ])}}
            
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
