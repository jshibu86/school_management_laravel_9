@extends('layout::admin.master')

@section('title','schoolmanagement-edit')
@section('style')
@include('layout::admin.head.list_head')
    <link rel="stylesheet" href="{{asset('assets/backend/css/profile.css')}}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection

@section('body')
<div class="container-fluid">    
        <div class="row">
            <div class="card-title btn_style">  
                <h4 class="mb-0">School Management</h4>                                  
            </div>  
        </div>
    @if (Session::get("ACTIVE_GROUP") == "Super Admin")  
     
    
    <div class="card radius-15">
	    <div class="card-body">
            <div class="row">                
                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#pills-home"
                                role="tab" aria-controls="pills-home" aria-selected="true">School Profile</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"  href="#pills-profile"
                                role="tab" aria-controls="pills-profile" aria-selected="false">Contact Person</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" href="#pills-contact" role="tab"
                                aria-controls="pills-contact" aria-selected="false">Payment Preview</a>						 
                            </li>
                        </ul>                        
                    </div>         
                    <hr/>       
            </div>
            
            <div class="tab-content" id="pills-tabContent">                
                <!-- school profile tab content -->
                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">												  
                    <div class="parent_box">
                        <div class="col-2">                                                                                     
                            <div class="item form-group">                                                                
                                <img id="pimage" name="pimage" class="image rounded-circle" src="{{@$data->image ? @$data->image :asset('assets/images/staff.jpg')   }}" alt="profile_image" style="width: 120px;height: 120px; padding: px; margin: 0px; ">								
                                <label for="file" class="btn btn-primary px-1 justify-content-between"><i class="fa fa-edit"></i> </label> 
                                <input id="file" name="photo" style="visibility:hidden;" type="file"  onchange="document.getElementById('pimage').src = window.URL.createObjectURL(this.files[0])" >                                                                                                                                                                        
                            </div>                                                  
                        </div>	
                        <!-- form input text fields-->
                        <div class="col-xs-12" style="margin-bottom: 7px;">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> School Name 
                                        </label><span class="form-check-label text-danger">*</span>
                                        <div class="feild">
                                        {{Form::text('school_name',@$data->school_name,array('id'=>"school_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"First name",'required'=>"required"))}}
                                        </div>
                                    </div>                                                
                                </div>                                
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> School Name  / Abbreivation:
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('school_name_abbr',@$data->school_name,
                                            array('id'=>"school_name_abbr",'class'=>"form-control rounded-pill col-md-7 col-xs-12", 
                                            'placeholder'=>"Enter school abbreviation")) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Official Email: 
                                        </label>
                                        <div class="feild">
                                        {{Form::text('school_email',@$data->email,
                                            array('id'=>"school_email",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter office email",'required'=>"required"))}}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Official Phone Number: 
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('school_phoneno',@$data->phoneno,
                                            array('id'=>"school_phoneno",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                             'placeholder'=>"Enter phone number")) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Total Student Count: 
                                        </label>
                                        <div class="feild">
                                        {{Form::text('student_count',@$data->student_count,
                                            array('id'=>"student_count",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter total students",'required'=>"required"))}}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Subscription Plan: 
                                        </label>
                                        <div class="feild"> 
                                        {{ Form::select('subscription_plan',@$planList, @$data->plan_id,
                                            ['id'=> 'subscription_plan','class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Select Plan']) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Billing Cycle: 
                                        </label>
                                        <div class="feild">
                                        {{ Form::select('billing_cycle', ['1' => 'Session','2' => 'Advanced'],@$data->billing_id,
                                            ['class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Select Cycle']) }}
                                        </div>
                                    </div>                                                
                                </div>                                
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Discount :  
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('discount',@$data->discount,array('id'=>"discount",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"%")) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Permanent Address:  
                                        </label>
                                        <div class="feild">
                                        {{Form::text('school_address',@$data->address,
                                            array('id'=>"address",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter school address",'required'=>"required"))}}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> City : 
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('school_city',@$data->city,
                                            array('id'=>"school_city",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter school city")) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Postal Code:  
                                        </label>
                                        <div class="feild">
                                        {{Form::text('postal_code',@$data->pincode,
                                            array('id'=>"postal_code",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter Postal Code",'required'=>"required"))}}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Country: 
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('school_country',@$data->country,
                                            array('id'=>"school_country",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter school country")) }}
                                        </div>
                                    </div>                                                
                                </div>
                            </div>
                        </div>			                        			                        
                    </div>		
                    <div class="box-header with-border mar-bottom20">                                                    
                        <a href="#" class="btn btn-primary btn-lg m-1 px-3" onclick="GeneralConfig.moveNextTab('#pills-profile-tab')">Next&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></a>
                        <!-- {{ Form::button('Next&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-right"></i>', array('type' => 'button', 'id' => 'next_btn', 'name' => 'Next' , 'value' => 'Next' , 'class' => 'btn btn-primary btn-lg m-1  px-3' , 'onclick' => 'GeneralConfig.moveNextTab("#pills-profile-tab")', )) }}                                                                                                                             -->
                    </div>					
                </div>
                    
                <!-- contact person tab content -->
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    <div class="parent_box"  style="padding-left: 50px;">
                        <!-- form input text fields-->
                        <div class="col-xs-12" style="margin-bottom: 7px;">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> First Name :
                                        </label> <span class="form-check-label text-danger">*</span>
                                        <div class="feild">
                                        {{ Form::text('first_name',@$data->contacts->first()->first_name,
                                            array('id'=>"first_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12", 
                                            'placeholder'=>"Enter first name")) }}
                                        </div>
                                    </div>                                                
                                </div>                                
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Last Name :
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('last_name',@$data->contacts->first()->last_name,
                                            array('id'=>"last_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12", 
                                            'placeholder'=>"Enter last name")) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Email: 
                                        </label>
                                        <div class="feild">
                                        {{Form::text('contact_person_email',@$data->contacts->first()->email,
                                            array('id'=>"contact_person_email",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter email",'required'=>"required"))}}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Phone Number: 
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('contact_person_phoneno',@$data->contacts->first()->phoneno,
                                            array('id'=>"contact_person_phoneno",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter phone number")) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Role: 
                                        </label>
                                        <div class="feild">
                                        {{Form::text('contact_person_role',@$data->contacts->first()->role,
                                            array('id'=>"contact_person_role",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter role",'required'=>"required"))}}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Gender: 
                                        </label>
                                        <div class="feild">
                                        {{ Form::select('contact_person_gender', ['male' => 'Male','female' => 'Female'], 'default',
                                            ['class' => 'form-control rounded-pill col-md-7 col-xs-12', 'placeholder' => 'Select gender']) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 mt-3">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Permanent Address: 
                                        </label>
                                        <div class="feild">
                                        {{Form::text('contact_person_address',@$data->contacts->first()->address,
                                            array('id'=>"contact_person_address",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter address",'required'=>"required"))}}
                                        </div>
                                    </div>                                                
                                </div>                                
                                <div class="col-xs-12 col-sm-6 col-md-4 mt-3">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> City :  
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('contact_person_city',@$data->contacts->first()->city,
                                            array('id'=>"contact_person_city",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter city name")) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4 mt-3">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Postal Code:  
                                        </label>
                                        <div class="feild">
                                        {{Form::text('contact_person_postcode',@$data->contacts->first()->pincode,array('id'=>"contact_person_postcode",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'placeholder'=>"Enter Postal Code",'required'=>"required"))}}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-4 col-md-4 mt-3">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Country : 
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('contact_person_country',@$data->contacts->first()->country,
                                            array('id'=>"contact_person_country",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'placeholder'=>"Enter country name")) }}
                                        </div>
                                    </div>                                                
                                </div>                               
                            </div>
                        </div>                       
                    </div>
                    <div class="box-header with-border mar-bottom20">                        
                        <a href="#" class="btn btn-primary btn-lg m-1 px-3" onclick="GeneralConfig.movePreviousTab('#pills-home-tab')"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
                        <a href="#" class="btn btn-primary btn-lg m-1 px-3" onclick="GeneralConfig.moveNextTab('#pills-contact-tab')">Next&nbsp;&nbsp;&nbsp;<i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>

                <!-- Payment preview tab content -->
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">                                                        
                    <ul> 
                        <li><h5>Customer Information</h5></li>                                         
                            <div class="row mb-4">
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> School Name :
                                        </label><span class="form-check-label text-danger">*</span>
                                        <div class="feild">
                                        {{Form::text('school_name',@$data->school_name,
                                            array('id'=>"preview_school_name",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'readonly' => "readonly" ))}}
                                        </div>
                                    </div>                                                
                                </div>  
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Official Email :
                                        </label>
                                        <div class="feild">
                                        {{Form::text('school_email',@$data->email,
                                            array('id'=>"preview_school_email",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'readonly' => "readonly"))}}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Official Phone No :
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('school_phoneno',@$data->school_phoneno,
                                            array('id'=>"preview_school_phoneno",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'readonly' => "readonly")) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Subscription Plan :
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('subscription_plan',@$data->subscription_plan,
                                            array('id'=>"preview_subscription_plan",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'readonly' => "readonly")) }}  
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Billing Cycle :
                                        </label>
                                        <div class="feild">
                                        {{ Form::text('billing_cycle',@$data->billing_cycle,
                                            array('id'=>"preview_billing_cycle",'class'=>"form-control rounded-pill col-md-7 col-xs-12",
                                            'readonly' => "readonly")) }}
                                        </div>
                                    </div>                                                
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-4">
                                    <div class="item form-group">                                    
                                        <label class="form-check-label mb-2" for="first_name"> Student Count :
                                        </label>
                                        <div class="feild">
                                        {{Form::text('student_count',@$data->student_count,
                                            array('id'=>"preview_student_count",'class'=>"form-control rounded-pill col-md-7 col-xs-12" ,
                                            'readonly' => "readonly"))}}
                                        </div>
                                    </div>                                                
                                </div> 
                            </div>
                        <li><h5>List of Features</h5></li>  
                        <div class="card card-custom-ash">
                            <div class="card-body">                                        
                                <div class="card-text">                                                                                                              
                                     <!-- For Laravel request (show option) -->                                                                               
                                        <div id="module-container" class="row">		
                                            @forelse($moduleList as $moduleId => $moduleName)
                                                <div class="col-xs-10 col-md-4">
                                                    <div class="item form-group">
                                                        <div class="form-check">
                                                            <input 
                                                                type="checkbox" 
                                                                name="moduleList[]" 
                                                                class="form-check-input p-2" 
                                                                id="module-{{ $moduleId }}" 
                                                                value="{{ $moduleId }}" 
                                                                @if(in_array($moduleId, $selectedModuleList)) checked @endif 
                                                            >
                                                            <label class="form-check-label p-2" for="module-{{ $moduleId }}">
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
                        <li><h5>Payment Information</h5></li>
                        <div class="card ">
                            <div class="card-body">                                        
                                <div class="card-text"> 
                                    <div class="row">										
                                        <div class="col-xs-10 col-md-4">
                                            <label class="control-label">Sub Total :</label>
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
                        </div> 
                    </ul>       
                        
                    <div class="box-header with-border mar-bottom20">                        
                        <a href="#" class="btn btn-primary btn-lg m-1 px-3" onclick="GeneralConfig.movePreviousTab('#pills-profile-tab')"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>                       
                    </div> 
                </div>                        
            </div>
        </div>        
    </div>
</div>

@endif    

</div>

@endsection

@section('script')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

    <script>
        $(document).on('change', '#subscription_plan', function() {   

            let plan_id = document.getElementById('subscription_plan').value;
            console.log("Plan ID: " +plan_id);
            var inputData = new FormData();
            inputData.append('plan_id',plan_id);

            axios.post('{{ route('schoolmanagement.filtermodulelist') }}', inputData)
        .then(response => {
            let modulelist = response.data.moduleList;
            let filtermoduleslist = response.data.filteredModuleList;

            // Convert filteredModuleList to array if it's a string
            if (typeof filtermoduleslist === 'string') {
                filtermoduleslist = JSON.parse(filtermoduleslist);
            }

            let moduleHtml = '';

            if (modulelist.length > 0) {
                modulelist.forEach(module => {
                    // Convert module ID and filtermoduleslist items to strings for comparison
                    const moduleIdString = module.id.toString();
                    const isChecked = filtermoduleslist.includes(moduleIdString); // Compare as strings
                    
                    console.log(`Module ID: ${module.id}, Checked: ${isChecked}`);

                    moduleHtml += `
                        <div class="col-xs-10 col-md-4">
                            <div class="item form-group">
                                <div class="form-check">
                                    <input class="form-check-input p-2" type="checkbox" value="${module.id}" id="module_${module.id}" ${isChecked ? 'checked' : ''}>
                                    <label class="form-check-label p-2" for="module_${module.id}">
                                        ${module.module_name}
                                    </label>
                                </div>
                            </div>
                        </div>
                    `;
                });
            } else {
                // Show message if no modules are found
                moduleHtml = `
                    <div class="col-xs-10 col-md-4">
                        <div class="item form-group">
                            <div class="form-check">
                                <label class="form-check-label p-2">
                                    <span class="form-check-label text-danger">No Modules Found</span>
                                </label>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Insert the generated HTML into the module container
            $('#module-container').html(moduleHtml);
        })
        .catch(error => {
            console.error('Error:', error);
            $('#module-container').html('<p class="text-danger">Failed to load modules. Please try again.</p>');
        });
});

    </script>
  
@endsection
