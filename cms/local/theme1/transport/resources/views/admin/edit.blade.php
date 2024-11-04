@extends('layout::admin.master')

@section('title','transportvehicle')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('transport.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'transport-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('transport.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_section' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @if (@$layout == "create")
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('transport.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit transport vehicle" : "Create transport Vehicle"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ @$layout=="create" ? "Create a new": "Edit" }}  Transport Vehicle </h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Vehicle No(Registartion) <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('vehicle_reg_no',@$data->vehicle_reg_no,array('id'=>"vehicle_reg_no",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>" e.g KA73T6565",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Vehicle Name<span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('vehicle_name',@$data->vehicle_name,array('id'=>"vehicle_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"Vehicle Name",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Capacity <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('capacity',@$data->capacity,array('id'=>"capacity",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"10",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                           
                            
                            
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Vehicle Type<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('vehicle_type',Configurations::VEHICLETYPE,@$data->vehicle_type ,
                                          array('id'=>'vehicle_type','class' => 'single-select form-control','required' => 'required' )) }}
                                      </div>
                                </div>
                                     
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Driver/Staff<span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('staff_id',@$transport_staff,@$data->staff_id ,
                                          array('id'=>'staff_id','class' => 'single-select form-control','required' => 'required' )) }}
                                      </div>
                                </div>
                                     
                            </div>

                            

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label for="thumbnail" class="control-label margin__bottom">Image<span></span></label>
                                    <div class="">
                                    <span class="input-group-btn">
                                        <input class="form-control thumb" type="file" id="imagec_img_imagec" name="imagec" data-id="imagec"  accept="image/png, image/jpeg">


                                       
                                    </span>
                                    <img id="imagecholder" style="max-height:50px;" src="{{ @$data->image }}">

                                    @if (@$layout !="create" && @$data->image)
                                    <span class="back_to remove" id="remove_img_imagec" data-id="imagec" data-class="imagec" >X</span>
                                   

                                    @else
                                    <span class="back_to remove" id="remove_img_imagec" data-id="imagec" data-class="imagec" style="display:none;">X</span>
                                   
                                        
                                    @endif
                                    
                                    
                                    </div>
                                </div>
                        </div>

                            
                            
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Vehicle Description <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                        {{  Form::textarea('vehicle_description', @$data->vehicle_description, [
                                            'class'      => 'form-control',
                                            'rows'       => 3, 
                                            'name'       => 'vehicle_description',
                                            'id'         => 'vehicle_description',
                                           
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
