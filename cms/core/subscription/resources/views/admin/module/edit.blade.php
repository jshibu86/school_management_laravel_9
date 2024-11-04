@extends('layout::admin.master')

@section('title','subscriptionmanagement-edit')
@section('style')
@include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{asset('assets/backend/css/profile.css')}}">    
@endsection

@section('body')
<div class="container-fluid">    
        <div class="row">
            <div class="card-title btn_style">  
                <h4 class="mb-0">Subscription Management - Module</h4>                                  
            </div>  
        </div>
    @if (Session::get("ACTIVE_GROUP") == "Super Admin")  
        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('module.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'schoolmanagement-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('module.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif 
    <div class="card radius-15">
	    <div class="card-body">
            <label class="form-check-label font-weight-normal text-primary">Create Module </label>
            <hr/>                                                  
            <div class="parent_box">                     	   
                <div class="row">										
                    <div class="col-xs-12 col-md-6">
                        <div class="item form-group">
                            <label class="form-check-label mb-2">Module Name :</label><span class="form-check-label text-danger">*</span>
                            <div class="feild">
                                    {{Form::text('module_name',@$data->module_name,
                                        array('id'=>"module_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12", 
                                        'placeholder'=>"E.g Basic",'required'=>"required"))}}
                            </div>
                        </div>
                    </div>	
                    <div class="col-xs-12 col-md-12">
                        <div class="item form-group">
                            <label class="form-check-label mb-2">Module Description :</label><span class="form-check-label text-danger">*</span>
                                <div class="feild">                                                                               
                                    {{ Form::textarea('module_desc',  @$data->module_desc, array(
                                    'id'=>"module_desc",'rows'=>'5','class'=>"form-control col-xs-12 col-md-8" , 'Placeholder'=>'Enter short description', ))   }}
                                </div>                                       
                        </div>
                    </div>										                                           
                </div>                       	                        
            </div>                  
            <div class="box-header with-border mar-bottom20">                                            
                <a href="{{ route('module.index') }}"  class="btn btn-primary btn-lg m-1 px-3"><i class="fa fa-arrow-left">&nbsp;&nbsp;&nbsp;</i>Back</a>                                   
                {{ Form::button('<i class="fa fa-plus-square"></i>&nbsp;&nbsp;&nbsp;Save Module', array('type' => 'submit', 'id' => 'next_btn', 'name' => 'Confirm' , 'value' => 'Confirm' , 'class' => 'btn btn-primary btn-lg m-1  px-3')) }}                                        
            </div>	
@endif    

</div>
{{Form::close()}}
@endsection

