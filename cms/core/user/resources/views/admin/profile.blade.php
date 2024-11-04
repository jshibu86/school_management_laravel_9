@extends('layout::admin.master')

@section('title','user')
@section('style')


@endsection
@section('body')
    <div class="x_content">

       
            {{ Form::open(array('role' => 'form', 'route'=>array('updateprofile'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
      
            <div class="box-header with-border mar-bottom20">
                {{ Form::button('<i class="fa fa-save"></i>&nbsp;&nbsp;&nbsp;Save', array('type' => 'submit', 'id' => 'submit_btn', 'name' => 'submit_cat' , 'value' => 'Edit_Product' , 'class' => 'btn btn-success btn-sm m-1  px-3')) }}

                <a class="btn btn-info btn-sm m-1  px-3" href="javascript:history.back()" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

              
            </div>
        
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Edit Profile</h5>
                    <hr/>
                    <div class="col-xs-12">
                       
                    
                        <div class="row">
                            
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="building_name"> House No <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('house_no',@$data->address->house_no,array('id'=>"house_no",'class'=>"form-control col-md-7 col-xs-12" ,
                                'placeholder'=>"house no",'required'=>"required"))}}
                                    </div>
                                 </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="building_name"> Street Name <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('street_name',@$data->address->street_name,array('id'=>"street_name",'class'=>"form-control col-md-7 col-xs-12" ,
                                'placeholder'=>"street name",'required'=>"required"))}}
                                    </div>
                                </div>
                             </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="building_name"> Postal Code <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('postal_code',@$data->address->postal_code,array('id'=>"postal_code",'class'=>"form-control col-md-7 col-xs-12" ,
                                'placeholder'=>"postal code",'required'=>"required"))}}
                                    </div>
                                 </div>
                             </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="building_name"> City <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('province',@$data->address->province,array('id'=>"province",'class'=>"form-control col-md-7 col-xs-12" ,
                                'placeholder'=>"province",'required'=>"required"))}}
                                    </div>
                                 </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3">
                                <div class="item form-group">
                                    <label class="control-label margin__bottom" for="country"> Country <span class="required">*</span>
                                    </label>
                                    <div class="feild">
                                    {{Form::text('country',@$data->address->country,array('id'=>"country",'class'=>"form-control col-md-7 col-xs-12" ,
                                'placeholder'=>"country",'required'=>"required"))}}
                                    </div>
                                 </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-3 ">
                                <label class="control-label margin__bottom" for="email">Image <span class="required"></span>
                                </label>
                                <div class="feild">

                                    <div class="mb-3">
										
										<input class="form-control thumb size_img" type="file" id="imagec_img_user" name="imagec" data-id="user">
                                        <p class="text_danger error_msg"></p>
									</div>
                                    <img id="imagecholder"  style="max-height:150px;" src="{{ @$data->images }}">
                                  

                                    <span class="back_to remove" id="remove_img_user" style="display:none;" data-id="imagec" data-class="user">X</span>
                                        
                                   
                                   
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
