@extends('layout::admin.master')

@section('title','transportvehicle')
@section('style')
<link rel="stylesheet" href="{{asset('assets/backend/css/attendance.css')}}">

@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('transportroute.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'transport-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('transportroute.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_section' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @if (@$layout == "create")
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('transportroute.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit transport Route" : "Create transport Route"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ @$layout=="create" ? "Create a new": "Edit" }}  Transport Route </h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Route From <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('from',@$data->from,array('id'=>"from",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"from",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Route To <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('to',@$data->to,array('id'=>"to",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"to",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                           
                           
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Stops<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('stops[]',@$stops,@$stops_selected ,
                                          array('id'=>'stops','class' => 'multiple-select form-control','required' => 'required',"multiple" )) }}
                                      </div>
                                </div>
                                     
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Vehicle<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('vehicle[]',@$vehicle,@$bus_selected,
                                          array('id'=>'vehicle','class' => 'multiple-select form-control','required' => 'required',"multiple" )) }}
                                      </div>
                                </div>
                                     
                            </div>
                            

                            

                            

                            
                            
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Vehicle Description <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                        {{  Form::textarea('route_description', @$data->route_description, [
                                            'class'      => 'form-control',
                                            'rows'       => 3, 
                                            'name'       => 'route_description',
                                            'id'         => 'route_description',
                                           
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
