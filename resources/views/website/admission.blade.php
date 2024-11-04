<!-- resources/views/home.blade.php -->
@extends('website.layout')

@section('title', 'Home Page')

@section('content')		
<style>
	.form-control{
		height:auto !important;
	}
</style>	
<div class="hero overlay inner-page" style="background-image: url('images/team-banner1.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
		<!-- <img src="images/blob.svg" alt="" class="img-fluid blob"> -->
		<div class="container">
			<div class="row align-items-center justify-content-center pt-5">
				<div class="col-lg-6 text-center pe-lg-5">
					<h1 class="heading text-white mb-3" data-aos="fade-up">Admission</h1>
					<p class="text-white mb-4" data-aos="fade-up" data-aos-delay="100">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>				
				</div>
			</div>
		</div>
	</div>

	<div class="section">
			@if(session()->has('success'))
                <div class="alert alert-success border-0 bg-success alert-dismissible fade show">
                    <div class="text-white">{{session("success")}}                      
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
            @endif

			@if($errors->any())
			
				 <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show">
                        <div class="text-black"></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                   
				@foreach($errors->all() as $error)
					{{ $error }}<br/>
				@endforeach
				</div>
			
		@endif
		<div class="row justify-content-center">
			<div class="col-md-8 ">
				<div class="card">
					<div class="card-body">
						
						<form  action="{{route('admission.create')}}" method="POST" enctype="multipart/form-data">
							@csrf					
									
							<div class="row">
									<div class="card-header">
										<h3 align="center"><i class="bi bi-person-lines-fill"></i>  Student Admission Form </h3>						
									</div>
									
									<hr>
									
									@if (in_array('first_name', $enabled_lists))
										<div class="col-8 col-md-4 mb-3">			
											<label for="inputFirstName" class="form-label" >First Name<span class="required">*</span></label>					
											<input type="text" class="form-control" id="inputFirstName" name="first_name" placeholder="" > 						    
										</div>	
									@endif												
									@if (in_array('last_name', $enabled_lists))
										<div class="col-8 col-md-4 mb-3">			
											<label for="inputFirstName" class="form-label"  >Last Name<span class="required">*</span></label>					
											<input type="text" class="form-control" id="inputLastName" name="last_name" placeholder=""> 						    
										</div>	
									@endif	
									@if (in_array('email', $enabled_lists))
										<div class="col-8 col-md-4 mb-3">
											<label for="inputemail" class="form-label">Email Id<span class="required">*</span></label>
											<input type="email" class="form-control" id="email" name="email" value="">   
										</div>		
									@endif
									@if (in_array('mobile', $enabled_lists))
										<div class="col-8 col-md-4 mb-3">
											<label for="inputMobile" class="form-label">Mobile<span class="required">*</span></label>
											<input type="text" class="form-control" id="inputMobile" name="mobile" placeholder="" required>  
										</div>	
									@endif
									@if (in_array('dob', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">
											<label for="inputDOB" class="form-label">Date of Birth<span class="required">*</span></label>
											<input type="date" class="form-control" id="inputDOB" name="dob" placeholder="" required>  					    
										</div>	
									@endif
									@if (in_array('gender', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 " >
											<label for="inputGender">Gender <span class="required">*</span></label>
											<select class="form-select" id="inputGender" name="gender">
													<option value="">Select a gender</option>	
													<option value="Male">Male</option>
													<option value="Female">Female</option>
											</select>
										</div>	
									@endif
									@if (in_array('handicapped', $enabled_lists))
										<div class="col-8 col-md-4 mb-3" >
											<label for="inputHandicap">Is Physically Challenged Child <span class="required">*</span></label>
											<select class="form-select" id="inputHandicap" name="handicapped">
													<option value="yes">Yes</option>
													<option value="no" selected>No</option>
											</select>
										</div>	
									@endif
									@if (in_array('blood_group', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">
											<label for="inputBlood">Blood Group<span class="required">*</span></label>
											<select class="form-select" id="inputBlood" name="blood_group">
													<option value="">Select a blood group</option>	
													<option value="A+">A+</option>								             
													<option value="A-">A-</option>
													<option value="B+">B+</option>
													<option value="B-">B-</option>
													<option value="O+">O+</option>   								
													<option value="O-">O-</option>
													<option value="AB+">AB+</option>
													<option value="AB-">AB-</option>
													<option value="Other">Other</option>		
											</select>            	
										</div>	
									@endif
									@if (in_array('religion', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">
											<label for="inputBlood">Religion<span class="required">*</span></label>
											<select class="form-select" id="religion" name="religion">		
													<option value="">Select a religion</option>	
													<option value="hinduism">Hinduism</option>								             
													<option value="islam">Islam</option>
													<option value="christianity">Christianity</option>
													<option value="buddhism-">Buddhism</option>													
													<option value="Other">Other</option>		
											</select>            	
										</div>	
									@endif
									@if (in_array('image', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">
											<label for="formFile">Student Photo</label>
											<input class="form-control" type="file" id="formFile" name="photo" accept="image/png, image/jpeg"> 						
										</div> 
									@endif
								
									@if (in_array('national_id_number', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 " >
											<label for="inputNationalId">National Id<span class="required">*</span></label>
											<input type="text" class="form-control" id="inputNationalId" name="national_id" value="" >   
										</div>	
									@endif
		
									@if (in_array('previous_class_id', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 " >                  			
											<label for="inputAssignToPreviousClass" class="form-label">Previous Class<span class="required">*</span></label>
											<select class="form-select" id="inputAssignToPreviousClass" name="previous_class_id" >
													<option value="">Please select a class</option>
													@foreach($class_lists as $key => $value)
														<option value="{{ $key }}">{{ $value }}</option>
													@endforeach						
											</select>                               
										</div>
									@endif
									@if (in_array('current_class_id', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">                			
											<label for="inputAssignToCurrentClass" class="form-label">Current Class<span class="required">*</span></label>
											<select class="form-select" id="inputAssignToCurrentClass" name="current_class_id" >
													<option value="">Please select a class</option>															
													@foreach($class_lists as $key => $value)
														<option value="{{ $key }}">{{ $value }}</option>
													@endforeach						
											</select>                               
										</div>
									@endif
		
									@if (in_array('previous_school', $enabled_lists))
										<div class="col-8 col-md-4 mb-3" >
											<label for="inputSchoolName">Previous School Name<span class="required">*</span></label>
											<input type="text" class="form-control" id="SchoolName" name="school_name" placeholder="" >  
										</div>
									@endif									
									@if (in_array('stu_department', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">                			
											<label for="inputAssignToCurrentClass" class="form-label">Select a department<span class="required">*</span></label>
											<select class="form-select" id="inputAssignToCurrentClass" name="stu_department" >
													<option value="">Please select a department</option>															
													@foreach($department_lists as $key => $value)
														<option value="{{ $key }}">{{ $value }}</option>
													@endforeach						
											</select>                               
										</div>
									@endif
		
									@if (in_array('parent_name', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">
											<label for="inputParentName">Parent/Guardian Name<span class="required">*</span></label>
											<input type="text" class="form-control" id="parentName" name="parent_name" placeholder="" > 						      
										</div>
									@endif
		
									@if (in_array('parent_mobile', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 " >
											<label for="parentMobile" class="form-label">Parent Mobile<span class="required">*</span></label>
											<input type="text" class="form-control" id="parentMobile" name="parent_mobile" placeholder="" required>  
										</div>	
									@endif
		
									@if (in_array('parent_email', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">
											<label for="inputEmail">Parent Email<span class="required">*</span></label>
											<input type="email" class="form-control" id="Parentemail" name="parent_email" value="" >    
										</div>
									@endif	
										
									@if (in_array('house_no', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 " >
											<label for="inputHouseNo">House No<span class="required">*</span></label>
											<input type="text" class="form-control" id="inputHouseNo" name="house_no" placeholder="" >  
										</div>	
									@endif
		
									@if (in_array('postal_code', $enabled_lists))
										<div class="col-8 col-md-4 mb-3" >
											<label for="inputPostalCode">Postal Code<span class="required">*</span></label>
											<input type="text" class="form-control" id="inputPostalCode" name="postal_code" placeholder="" >  
										</div>
									@endif
				
									@if (in_array('street', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 " >
											<label for="inputStreetName">Street Name<span class="required">*</span></label>
											<input type="text" class="form-control" id="inputStreetName" name="street" placeholder="" >    
										</div>
									@endif
		
									@if (in_array('city', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 " >
											<label for="inputCity">City<span class="required">*</span></label>
											<input type="text" class="form-control" id="inputCity" name="city" placeholder="" >    
										</div>
									@endif
		
									@if (in_array('country', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">
											<label for="inputCountry">Country<span class="required">*</span></label>
											<input type="text" class="form-control" id="inputCountry" name="country" placeholder="" >  
										</div>
									@endif
									@if (in_array('stu_document_upload1', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">
											<label for="formFile">Upload First Document</label>
											<input class="form-control" type="file" id="formFile" name="stu_document_upload1" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf">
										</div> 
									@endif
									@if (in_array('stu_document_upload2', $enabled_lists))
										<div class="col-8 col-md-4 mb-3 ">
											<label for="formFile">Upload Second Document</label>
											<input class="form-control" type="file" id="formFile" name="stu_document_upload2" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.pdf">
										</div> 
									@endif
									@if(isset($alert_message[0]) && $alert_message[0] != '')									
										<div class="alert alert-success" role="alert"> {{ $alert_message[0] }}</div>
									@endif
									@if (!empty($enabled_lists))
										<div class="col-12 col-md-12 mb-3" >											
												<button type="submit" class="btn btn-sm btn-outline-primary" style="float:right;"><i class="bi bi-person-plus"></i> Register</button>
										</div>		
									@endif			
							
							</div>
							
						</form>
					</div>
				</div>
			</div>
		</div>

@endsection 

@section('scripts')

<script>
    $(document).ready(function() {
        $('#is_active').change(function() {
            if ($(this).is(':checked')) {
                $('#conditional-fields').show();
            } else {
                $('#conditional-fields').hide();
            }
        });
    });
</script>
@endsection