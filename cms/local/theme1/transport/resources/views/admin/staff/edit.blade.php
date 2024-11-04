@extends('layout::admin.master')

@section('title','transportstaff')
@section('style')


@endsection
@section('body')
    <div class="x_content">

        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('transportstaff.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'transport-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('transportstaff.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif
        <div class="box-header with-border mar-bottom20">
           
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_section' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}
            @if (@$layout == "create")
            {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save-Continue', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat_continue' , 'value' => 'Edit_wallet' , 'class' => 'btn btn-dark btn-sm m-1  px-3')) }}
            @endif

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('transportstaff.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              

             
        </div>
           @include("layout::admin.breadcrump",['route'=> $layout == "edit" ?"Edit transport staff" : "Createtransport Staff/Driver"])

             <div class="card">
                <div class="card-body">
                    <h5 class="card-title">{{ @$layout=="create" ? "Create a new": "Edit" }}  Transport Staff/Driver </h5>
                    <hr/>
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Driver Name <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('employee_name',@$data->employee_name,array('id'=>"employee_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>" e.g Jon Doe",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Email <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('email',@$data->email,array('id'=>"email",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>" Jon@gmail.com",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Contact Number <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::number('mobile',@$data->mobile,array('id'=>"mobile",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"999999999",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Date of Birth <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::date('dob',@$data->dob,array('id'=>"dob",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"999999999",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">National id Number <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('national_id_number',@$data->national_id_number,array('id'=>"national_id_number",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"national_id_number",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">License Number <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                           {{Form::text('license_no',@$data->license_no,array('id'=>"license_no",'class'=>"form-control col-md-7 col-xs-12" ,
                                          'placeholder'=>"license_no",'required'=>"required"))}}
                                       </div>
                                   </div>
                            </div>

                            
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Blood Group <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('blood_group',Configurations::BLOODGROUPS,@$data->blood_group ,
                                          array('id'=>'blood_group','class' => 'single-select form-control','required' => 'required' )) }}
                                      </div>
                                </div>
                                     
                            </div>

                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                 <label class="control-label margin__bottom" for="status">Maritial Status <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('maritial_status',Configurations::MARITIALSTATUS,@$data->maritial_status ,
                                          array('id'=>'maritial_status','class' => 'single-select form-control','required' => 'required' )) }}
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
                                 <label class="control-label margin__bottom" for="status">Gender <span class="required">*</span>
                                      </label>
                                      <div class="feild">
                                          {{ Form::select('gender',Configurations::GENDER,@$data->gender ,
                                          array('id'=>'gender','class' => 'single-select form-control','required' => 'required' )) }}
                                      </div>
                                </div>
                                     
                            </div>
                        <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                       <label class="control-label margin__bottom" for="status">Address <span class="required">*</span>
                                       </label>
                                       <div class="feild">
                                        {{  Form::textarea('address_communication', @$data->address_communication, [
                                            'class'      => 'form-control',
                                            'rows'       => 3, 
                                            'name'       => 'address_communication',
                                            'id'         => 'address_communication',
                                           
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
