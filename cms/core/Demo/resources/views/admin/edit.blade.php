@extends('layout::admin.master')

@section('title','Demo')
@section('style')
<link rel="stylesheet" href="{{asset('assets/backend/css/profile.css')}}">  

@endsection
@section('body')
<div class="container-fluid">    
            
        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('Demo.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'Demo-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('Demo.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif      
        <div class="row">
            <div class="card-title btn_style mb-4">  
                <h4 class="mb-0">Admin Management - Demo</h4>                                  
            </div>  
        </div>
        <div class="card radius-15">            
	        <div class="card-body">         
                <div class="card-title btn_style mb-4">  
                    <h5 class="mb-0 text-primary">Demo Request</h5>                                  
                </div>         
                <div class="parent_box"  style="padding-left: 50px;">                                                                                                                       
                    <div class="row">										
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                            <label class="control-label">School Name :<span class="form-check-label text-danger">*</span></label>
                                <div class="feild">
                                    {{Form::text('school_name',@$data->school_name,array('id'=>"school_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                    'placeholder'=>"Enter school name",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                                <label class="control-label">Contact Person :</label>
                                <div class="feild">
                                    {{ Form::text('contact_name',@$data->contact_name,array('id'=>"contact_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                    'placeholder'=>"Enter name")) }}
                                </div>
                            </div>
                        </div>										                                                              										
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                                <label class="control-label">Email :</label>
                                <div class="feild">
                                    {{Form::text('email',@$data->email,array('id'=>"email",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                    'placeholder'=>"Enter email",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                                <label class="control-label">Official Phone Number :</label>
                                <div class="feild">
                                    {{ Form::text('phoneno',@$data->phoneno,array('id'=>"phoneno",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                    'placeholder'=>"Enter phone number")) }}
                                </div>
                            </div>
                        </div>										                                                            									
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                                <label class="control-label">Role :</label>
                                <div class="feild">
                                    {{Form::text('role',@$data->role,array('id'=>"role",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                    'placeholder'=>"Enter role",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                                <label class="control-label">Gender :</label>
                                <div class="feild">
                                {{ Form::select('gender', ['male' => 'Male','female' => 'Female'], @$data->gender,
                                    ['class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Select gender']) }}
                                </div>
                            </div>
                        </div>										                                           
                                                        
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                                <label class="control-label">Permanent Address :</label>
                                <div class="feild">
                                {{Form::text('address',@$data->address,array('id'=>"address",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                    'placeholder'=>"Enter address",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                                <label class="control-label">City :</label>
                                <div class="feild">
                                    {{ Form::text('city',@$data->city,array('id'=>"city",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                    'placeholder'=>"Enter city name")) }}
                                </div>
                            </div>
                        </div>										                                                               
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                                <label class="control-label">Postal Code :</label>
                                <div class="feild">
                                    {{Form::text('pincode',@$data->pincode,array('id'=>"pincode",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                    'placeholder'=>"Enter Postal Code",'required'=>"required"))}}
                                </div>
                            </div>
                        </div>	
                        <div class="col-xs-12 col-sm-6 col-md-4">
                            <div class="item form-group">
                                <label class="control-label">Country :</label>
                                <div class="feild">
                                    {{ Form::text('country',@$data->country,array('id'=>"country",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                    'placeholder'=>"Enter country name")) }}
                                </div>
                            </div>
                        </div>										                                           
                    </div>
                </div>    
                <div class="box-header with-border mar-bottom20">
                    <a href="{{ route('Demo.index') }}"  class="btn btn-primary btn-lg m-1 px-3"><i class="fa fa-arrow-left">&nbsp;&nbsp;&nbsp;</i>Back</a>                                   
                    {{ Form::button('Save&nbsp;&nbsp;&nbsp;<i class="fa fa-plus-square"></i>', array('type' => 'submit', 'id' => 'next_btn', 'name' => 'create' , 'value' => 'Create' , 'class' => 'btn btn-primary btn-lg m-1  px-3' )) }}                         
                </div>			                        
            </div>						
        </div>
                                
    {{Form::close()}}
</div>

@endsection

@section('script')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

@endsection
