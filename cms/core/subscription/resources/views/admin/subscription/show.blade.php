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
            <h4 class="mb-0">Subscription Management</h4>                                  
        </div>  
    </div>
    @if (Session::get("ACTIVE_GROUP") == "Super Admin")  
               
    <div class="card radius-15">
	    <div class="card-body">
            <label class="form-check-label font-weight-normal text-primary">Subscription Plan </label>
            <hr/>                                                                                   	   
            <div class="row">										
                <div class="col-xs-8 col-md-4">
                    <div class="item form-group">
                        <label class="form-check-label mb-2">Subscription Plan :</label><span class="form-check-label text-danger">*</span>
                        <div class="feild">                                                        
                            {{ Form::select ('subscription_plan_price_name', @$planList, @$data->plan_id,
                                array('id'=>'subscription_plan_price_name', 'class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Select',  'disabled' => 'disabled' )) }}
                        </div>
                    </div>
                </div>	
                <div class="col-xs-8 col-md-4">
                    <div class="item form-group">
                        <label class="form-check-label mb-2">Amount(Term) per student :</label><span class="form-check-label text-danger">*</span>
                            <div class="feild">                                
                                {{ Form::text('amount_term_per_student',@$data->term_amount,array('id'=>"amount_term_per_student",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                'placeholder'=>"Enter value",  'disabled' => 'disabled')) }}
                            </div>
                    </div>
                </div>										                                           
            </div>
            <div class="row mb-4">										
                <div class="col-xs-8 col-md-4">
                    <div class="item form-group">
                        <label class="form-check-label p-2">Amount(Session) per student:</label><span class="form-check-label text-danger">*</span>
                            <div class="feild p-2">                                
                            {{ Form::text('amount_session_per_student',@$data->session_amount,array('id'=>"amount_session_per_student",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                'placeholder'=>"Enter value",  'disabled' => 'disabled')) }}
                            </div>
                    </div>
                </div>
                <div class="col-xs-8 col-md-4">
                    <div class="item form-group">
                        <label class="form-check-label p-2">Visible :</label><span class="form-check-label text-danger">*</span>
                            <div class="feild p-2">
                                
                            {{ Form::select('plan_visible', ['1' => 'Yes','0' => 'No'], @$data->visible_status, 
                                array('id'=>"plan_visible",'class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Select',  'disabled' => 'disabled')) }}
                            </div>
                    </div>
                </div>		
                									                                           
            </div>                                                                
            <h5>List of Modules</h5>
            <div class="card card-custom-ash">
                <div class="card-body">                                        
                    <div class="card-text">                                                              
                        <div class="row">		
                        @forelse($moduleList as $moduleId=>$moduleName)
                            <div class="col-xs-10 col-md-4">
                                <div class="item form-group">
                                    <div class="form-check">
                                        <input  type="checkbox" 
                                                name="moduleList[]" class="form-check-input p-2" 
                                                id="module-{{ $moduleId }}" 
                                                value="{{ $moduleId }}" 
                                                @if(in_array($moduleId, $selectedModuleList)) checked @endif >
                                                <label class="form-check-label p-2" for="module-{{ $moduleId }}" >
                                                    {{ $moduleName }}
                                                </label>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-xs-10 col-md-4">
                                <div class="item form-group">
                                    <div class="form-check">                                                        
                                        <label class="form-check-label p-2" for="defaultCheck1">                                                            
                                            <span class="form-check-label text-danger">No Modules Found</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforelse                                                                                                                                          
                        </div>                                                   								                                           
                    </div>                                                                                               
                </div>
            </div>                        	                        
                            
            <div class="box-header with-border mar-bottom20">                                            
                <a href="{{ route('setupplan.index') }}" class="btn btn-primary btn-lg m-1 px-3"><i class="fa fa-arrow-left">&nbsp;&nbsp;&nbsp;</i>Back</a>                   
            </div>	

@endif    

</div>

{{Form::close()}}
@endsection

