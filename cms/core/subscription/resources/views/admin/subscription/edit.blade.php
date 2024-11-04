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
        @if($layout == "create")
            {{ Form::open(array('role' => 'form', 'route'=>array('schoolmanagement.store'), 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'schoolmanagement-form','novalidate' => 'novalidate')) }}
        @elseif($layout == "edit")
            {{ Form::open(array('role' => 'form', 'route'=>array('schoolmanagement.update',$data->id), 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-label-left', 'id' => 'user-form','novalidate' => 'novalidate')) }}
        @endif 
    
    <div class="card radius-15">
	    <div class="card-body">
            <div class="row">                
                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">Subscription Plan</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"  href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">Price</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#pills-contact" role="tab"
                                aria-controls="pills-contact" aria-selected="false">Payment Preview</a>						 
                            </li>
                        </ul>
                        <hr/>
                    </div>                
            </div>
            <div class="tab-content" id="pills-tabContent">
                <!-- school profile tab content -->
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">												  
                    <div class="parent_box">
                        <div class="col-2">                                                                                                               
                            <img id="profile_img" name="profile_img" class="image rounded-circle" src="{{@$data->parent->father_image ?@$data->parent->father_image :asset('assets/images/staff.jpg')   }}" alt="profile_image" style="width: 120px;height: 120px; padding: px; margin: 0px; ">								
                            <label for="file" class="btn btn-primary px-1 justify-content-between"><i class="fa fa-edit"></i> </label> <input id="file" style="visibility:hidden;" type="file"  onchange="document.getElementById('profile_img').src = window.URL.createObjectURL(this.files[0])" >                                                                                                                                                                                                                      
                        </div>		                                
                        <div class="col-xs-12" style="margin-bottom: 7px;">                           									
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                    <label class="form-check-label mb-2" for="school_name"> School Name 
                                        </label>
                                        <div class="feild">
                                            {{Form::text('school_name',@$data->school_name,array('id'=>"school_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter school name",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">School Name/Abbreviation :</label>
                                        <div class="feild">
                                            {{ Form::text('school_name_abbr',@$data->school_name,array('id'=>"school_name_abbr",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter school abbreviation")) }}
                                        </div>
                                    </div>
                                </div>										                                                                                               										
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Official Email :</label>
                                        <div class="feild">
                                            {{Form::text('school_email',@$data->school_email,array('id'=>"school_email",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter office email",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Official Phone Number :</label>
                                        <div class="feild">
                                            {{ Form::text('school_phoneno',@$data->school_phoneno,array('id'=>"school_phoneno",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter phone number")) }}
                                        </div>
                                    </div>
                                </div>										                                                                                                   										
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Total Student Count :</label>
                                        <div class="feild">
                                            {{Form::text('student_count',@$data->student_count,array('id'=>"student_count",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter total students",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Subscription Plan :</label>
                                        <div class="feild">
                                        {{ Form::select('subscription_plan', ['basic' => 'Basic','Advanced' => 'Advanced'],'default', 
                                            ['class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Subscription Plans']) }}
                                        </div>
                                    </div>
                                </div>										                                                                										
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Billing Cycle :</label>
                                        <div class="feild">
                                        {{ Form::select('billing_cycle', ['basic' => 'Session','Advanced' => 'Advanced'],'default', 
                                            ['class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Select Billing Cycle']) }}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Discount :</label>
                                        <div class="feild">
                                            {{ Form::text('discount',@$data->discount,array('id'=>"discount",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"%")) }}
                                        </div>
                                    </div>
                                </div>										                                                                      										
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Permanent Address :</label>
                                        <div class="feild">
                                            {{Form::text('school_address',@$data->school_address,array('id'=>"school_address",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter school address",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">City :</label>
                                        <div class="feild">
                                            {{ Form::text('school_city',@$data->school_city,array('id'=>"school_city",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter school city")) }}
                                        </div>
                                    </div>
                                </div>										                                                                                                									
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Postal Code :</label>
                                        <div class="feild">
                                            {{Form::text('postal_code',@$data->postal_code,array('id'=>"postal_code",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter Postal Code",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Country :</label>
                                        <div class="feild">
                                            {{ Form::text('school_country',@$data->school_country,array('id'=>"school_country",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter school country")) }}
                                        </div>
                                    </div>
                                </div>										                                           
                                <div class="box-header with-border mar-bottom20">
                                    {{ Form::button('<i class="fa fa-trash"></i>&nbsp;&nbsp;&nbsp;Clear All', array('type' => 'reset', 'id' => 'reset_btn', 'name' => 'reset' , 'value' => 'Reset' , 'class' => 'btn btn-primary btn-lg m-1  px-3')) }}
                                    {{ Form::button('Next&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>', array('type' => 'button', 'id' => 'next_btn', 'name' => 'Next' , 'value' => 'Next' , 'class' => 'btn btn-primary btn-lg m-1  px-3' , 'onclick' => 'moveUserProfile()' )) }}                                                                                                                            
                                </div>
                            </div>                        
                        </div>								                        
                    </div>						
                </div>
                    
                <!-- contact person tab content -->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="parent_box"  style="padding-left: 50px;">                        
                            <div class="row">										
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">First Name :<span>*</span></label>
                                        <div class="feild">
                                            {{Form::text('first_name',@$data->first_name,array('id'=>"first_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter First name",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Last Name :</label>
                                        <div class="feild">
                                            {{ Form::text('last_name',@$data->last_name,array('id'=>"last_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter last name")) }}
                                        </div>
                                    </div>
                                </div>										                                                                      										
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Email :</label>
                                        <div class="feild">
                                            {{Form::text('contact_person_email',@$data->contact_person_email,array('id'=>"contact_person_email",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter email",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Phone Number :</label>
                                        <div class="feild">
                                            {{ Form::text('contact_person_phoneno',@$data->contact_person_phoneno,array('id'=>"contact_person_phoneno",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter phone number")) }}
                                        </div>
                                    </div>
                                </div>										                                                                    										
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Role :</label>
                                        <div class="feild">
                                            {{Form::text('contact_person_role',@$data->contact_person_role,array('id'=>"contact_person_role",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter role",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Gender :</label>
                                        <div class="feild">
                                        {{ Form::select('contact_person_gender', ['male' => 'Male','female' => 'Female'],'default', 
                                            ['class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Select gender']) }}
                                        </div>
                                    </div>
                                </div>										                                                                      										
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Permanent Address :</label>
                                        <div class="feild">
                                        {{Form::text('contact_person_address',@$data->contact_person_address,array('id'=>"contact_person_address",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter address",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">City :</label>
                                        <div class="feild">
                                            {{ Form::text('contact_person_city',@$data->contact_person_city,array('id'=>"contact_person_city",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter city name")) }}
                                        </div>
                                    </div>
                                </div>										                                                                       									
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Postal Code :</label>
                                        <div class="feild">
                                            {{Form::text('contact_person_postcode',@$data->contact_person_postcode,array('id'=>"contact_person_postcode",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter Postal Code",'required'=>"required"))}}
                                        </div>
                                    </div>
                                </div>	
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">
                                        <label class="form-check-label mb-2">Country :</label>
                                        <div class="feild">
                                            {{ Form::text('contact_person_country',@$data->contact_person_country,array('id'=>"contact_person_country",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter country name")) }}
                                        </div>
                                    </div>
                                </div>										                                                                    
                            <div class="box-header with-border mar-bottom20">
                                {{ Form::button('<i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back', array('type' => 'reset', 'id' => 'back_btn', 'name' => 'Back' , 'value' => 'Back' , 'class' => 'btn btn-primary btn-lg m-1  px-3')) }}
                                {{ Form::button('Next&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>', array('type' => 'reset', 'id' => 'next_btn', 'name' => 'Next' , 'value' => 'Next' , 'class' => 'btn btn-primary btn-lg m-1  px-3')) }}                                        
                            </div>
                        
                        </div>	
                    </div>
                </div>

                <!-- Payment preview tab content -->
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">                                                        
                    <ul> <li><h5>Customer Information</h5></li>                 
                        <div class="row mb-4" >                            
                                                                                                                                      										
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="item form-group">
                                    <label class="form-check-label mb-2">School Name :<span>*</span></label>
                                    <div class="feild">
                                        {{Form::text('school_name',@$data->school_name,array('id'=>"preview_school_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                        'readonly' => "readonly" ))}}
                                    </div>
                                </div>
                            </div>	
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="item form-group">
                                    <label class="form-check-label mb-2">Official Email:</label>
                                    <div class="feild">
                                    {{Form::text('school_email',@$data->school_email,array('id'=>"preview_school_email",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                        'readonly' => "readonly"))}}
                                    </div>
                                </div>
                            </div>										                                           
                                   										
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="item form-group">
                                    <label class="form-check-label mb-2">Official Phone No :<span>*</span></label>
                                    <div class="feild">
                                    {{ Form::text('school_phoneno',@$data->school_phoneno,array('id'=>"preview_school_phoneno",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'readonly' => "readonly")) }}
                                    </div>
                                </div>
                            </div>	
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="item form-group">
                                    <label class="form-check-label mb-2">Subscription Plan:</label>
                                    <div class="feild">
                                    {{ Form::text('subscription_plan',@$data->subscription_plan,array('id'=>"preview_subscription_plan",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                        'readonly' => "readonly")) }}                                                           
                                    </div>
                                </div>
                            </div>										                                                                               										
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="item form-group">
                                    <label class="form-check-label mb-2">Billing Cycle :<span>*</span></label>
                                    <div class="feild">
                                    {{ Form::text('billing_cycle',@$data->billing_cycle,array('id'=>"preview_billing_cycle",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                        'readonly' => "readonly")) }}                                                            
                                    </div>
                                </div>
                            </div>	
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="item form-group">
                                    <label class="form-check-label mb-2">Student Count:</label>
                                    <div class="feild">
                                    {{Form::text('student_count',@$data->student_count,array('id'=>"preview_student_count",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                        'readonly' => "readonly"))}}
                                    </div>
                                </div>
                            </div>										                                           
                        </div>
                        
                        <li><h5>List of Features</h5></li>  
                        <div class="card card-custom-ash">
                            <div class="card-body">                                        
                                <div class="card-text">                                                                                 
                                    <div class="row">										
                                    @forelse($moduleLists as $moduleListKey=>$moduleListValue)
                                            <div class="col-xs-10 col-md-4">
                                                <div class="item form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input p-2" type="checkbox" value="" id="defaultCheck1">
                                                            <label class="form-check-label p-2" for="defaultCheck1">
                                                            {{ $moduleListValue }}
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
                        <li><h5>Payment Information</h5></li>                        
                            <div class="card-body">                                        
                                <div class="card-text"> 
                                    <div class="row">										
                                        <div class="col-xs-10 col-md-4">
                                            <label class="form-check-label mb-2">Sub Total :</label>
                                            <hr/>
                                            <label class="control-label">Sales Tax :</label>
                                            <hr/>
                                            <label class="control-label">Training Fee :</label>
                                            <hr/>
                                            <label class="control-label">Discount :</label> 
                                            <hr/>
                                            <label class="control-label">Total Due :</label>
                                            <hr/>
                                        </div>	                                              									                                           
                                    </div>                                                
                                    
                                </div>
                            </div>                        
                    </ul>       
                        
                    <div class="box-header with-border mar-bottom20">                        
                        <a href="#" class="btn btn-primary btn-lg m-1 px-3" onclick="GeneralConfig.movePreviousTab('#pills-profile-tab')"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
                        {{ Form::button('Confirm', array('type' => 'submit', 'id' => 'next_btn', 'name' => 'Confirm' , 'value' => 'Confirm' , 'class' => 'btn btn-primary btn-lg m-1  px-3')) }}                                        
                    </div> 
                </div>                        
            </div>
        </div>        
    </div>
</div>

@endif    

</div>
{{Form::close()}}
@endsection

@section('script')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}
    <script>
        function moveUserProfile() {
            var schoolName = $('#school_name').val();
            var schoolEmail = $('#school_email').val();
            var schoolPhoneno = $('#school_phoneno').val();
            var schoolSubscPlan = $('#subscription_plan').val();
            var schoolBillCycle = $('#billing_cycle').val();
            var schoolCount = $('#student_count').val();            
            $('#preview_school_name').val(schoolName);
            $('#preview_school_email').val(schoolEmail);
            $('#preview_school_phoneno').val(schoolPhoneno);
            $('#preview_subscription_plan').val(schoolSubscPlan);
            $('#preview_billing_cycle').val(schoolBillCycle);
            $('#preview_student_count').val(schoolCount);
        }
    </script>
@endsection
