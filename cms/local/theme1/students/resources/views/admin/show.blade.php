@extends('layout::admin.master')

@section('title','profile')
@section('style')
@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{asset('assets/backend/css/profile.css')}}">
<style type="text/css" media="print">
	@page {
		size: auto;   /* auto is the initial value */
		margin: 0;  /* this affects the margin in the printer settings */
	}
	</style>
<style>
	#accordion .panel-heading a{
		padding: 0px !important;
		color: #415094 !important;
	}
	#accordion .panel-heading a:hover{
		color: #415094 !important;
	}
	.panel-heading h4{
		font-weight: 500!important;
		color: #415094 !important;
	}
	.panel{
		background: #fff !important;
	}
	#accordion .panel-heading .down-arrow:before{
		width: 10px !important;
   		 height: 10px !important;
	}
	.pro_valueassign {
		font-weight: 500;
		font-size: 13px;
		margin: 3px 0;
	}
	.stu_table.dataTable{
		width: 100% !important;
	}
	.dt-buttons a{
		background-color: #2a3f54 !important;
    color: white !important;
}
	.download{
        background-color: white;
        border: 1px solid #2a3f54;
        padding: 4px 7px;
        border-radius: 50px;
        font-size: 10px;
    }
    .download i{
        color: #09b509;
    }
	.accordion-button{
		padding: 1rem 0.25rem !important;
		text-transform: uppercase !important;
		font-size: 13px !important;
		font-weight: 500!important;
    	color: #415094 !important;
		border-bottom: 1px solid #4150944d;
	}

	
}

</style>
@endsection

@section('body')
<div class="x_content">
    <div class="box-header with-border mar-bottom20">
       
        <a class="btn btn-warning btn-sm m-1  px-3" href="{{route('students.edit',$data->id)}}" ><i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;Edit</a>
		<button class="btn btn-primary btn-sm m-1  px-3 print" type="button"><i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;Print</button>

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('students.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

        @include("layout::admin.breadcrump",['route'=> cms\students\models\StudentsModel::studentname($data->id)])
    </div>
<!-- section begin -->


{{-- Printable section start --}}
<div id="invoice" style="display: none">
			
	<div class="invoice overflow-auto">
		<div style="min-width: 600px">
			<header>
				<div class="row">
					<div class="col-3">
						@if(isset(Configurations::getConfig('site')->imagec))
						<img src="{{ Configurations::getConfig('site')->imagec }} " width="80" alt="nologo"/>
						@endif
							
						
					</div>
					<div class="col-6 company-details">
						<h2 class="name">
						<a target="_blank" href="javascript:;">
							{{isset(Configurations::getConfig('site')->school_name) ?Configurations::getConfig('site')->school_name : 'School'}}
						</a>
						</h2>
						<div>{{ Configurations::getConfig('site')->place }}, {{ Configurations::getConfig('site')->city }},{{ Configurations::getConfig('site')->pin_code }},{{ Configurations::getConfig('site')->country }}</div>
						
						<div><span>Contact : {{ Configurations::getConfig('site')->school_phone }}</span></div>
						<div><span>Email : {{ Configurations::getConfig('site')->school_email }}</span></div>
					</div>
					<div class="col-3" style="text-align: right">
						<img class="" src="{{@$data->user->images ?@$data->user->images :asset('assets/images/staff.jpg')   }}"  width="80" alt="nologo">
					</div>
				</div>
			</header>
			<main>
				<div class="row contacts">
					
					<div class="col invoice-to">
						
						<table>
							<tr>
								<td>Student Name</td>
								<td>:</td>
								<td>{{ ucfirst(@$data->first_name) }} {{ ucfirst(@$data->last_name) }}</td>
							</tr>
							<tr>
								<td>Registration Code</td>
								<td>:</td>
								<td>{{@$data->username }}</td>
							</tr>
							<tr>
								<td>Class</td>
								<td>:</td>
								<td>{{ @$data->class->name }}</td>
							</tr>
							<tr>
								<td>Section</td>
								<td>:</td>
								<td>{{ @$data->section->name }}</td>
							</tr>
							<tr>
								<td>Date of Birth</td>
								<td>:</td>
								<td>{{ @$data->dob }}</td>
							</tr>
							<tr>
								<td>Gender</td>
								<td>:</td>
								<td>{{ ucfirst(@$data->gender) }}</td>
							</tr>
						</table>
						
						
					</div>
					<div class="col invoice-details">
						<table>
							<tr>
								<td>Email</td>
								<td>:</td>
								<td>{{ ucfirst(@$data->email) }}</td>
							</tr>
							<tr>
								<td>Mobile</td>
								<td>:</td>
								<td>{{@$data->mobile }}</td>
							</tr>
							<tr>
								<td>Student type</td>
								<td>:</td>
								<td>{{ucfirst( @$data->student_type) }}</td>
							</tr>
							<tr>
								
								<td>Admission Date</td>
								<td>:</td>
								<td>{{ @$data->admission_date }}</td>
							</tr>
							<tr>
								<td>National Id</td>
								<td>:</td>
								<td>{{ @$data->national_id_number ? @$data->national_id_number : "Not Provided" }}</td>
							</tr>
							<tr>
								<td>Blood group</td>
								<td>:</td>
								<td>{{ ucfirst(@$data->blood_group) }}</td>
							</tr>
						</table>
					</div>
				</div>
				
				
				<div class="notices">
					
					<div class="notice_">Parent Information</div>
				</div>

				<div class="spacer"></div>

				<div class="row contacts">
					<div class="col-3 invoice-to">
						<img class="" src="{{@$data->parent->father_image ?@$data->parent->father_image :asset('assets/images/staff.jpg')   }}" alt="Image" width=100%>
					</div>
					<div class="col-9  invoice-details">
						<div class="row">
							<div class="col-12">
								<table>
									<tr>
										<td>Parent/Guardian Name</td>
										<td>:</td>
										<td>{{ ucfirst(@$data->parent->father_name ) }}</td>
									</tr>
									<tr>
										<td>Contact Number</td>
										<td>:</td>
										<td>{{ @$data->parent->father_mobile  }}</td>
									</tr>
									<tr>
										<td>Email</td>
										<td>:</td>
										<td class="email_cell">{{ @$data->parent->father_email }}</td>
									</tr>
									<tr>
										<td>Occupation</td>
										<td>:</td>
										<td>{{ ucfirst(@$data->parent->father_occupation ) }}</td>
									</tr>
									{{-- <tr>
										<td>Yearly Income</td>
										<td>:</td>
										<td>{{ @$data->parent->yearly_income  }}</td>
									</tr> --}}
									
								
								</table>
							</div>
							{{-- <div class="col-6">
								<table>
									
									<tr>
										<td>Email</td>
										<td>:</td>
										<td class="email_cell">{{ @$data->parent->father_email }}</td>
									</tr>
									<tr>
										<td>Occupation</td>
										<td>:</td>
										<td>{{ ucfirst(@$data->parent->father_occupation ) }}</td>
									</tr>
									
									
									
								</table>
							</div> --}}
						</div>
						
					</div>
				</div>

				<div class="notices">
					
					<div class="notice_">Address Information</div>
				</div>

				<div class="spacer"></div>

				<div class="row">
					<div class="col-4">
						<table>
							<tr>
								<td>House No</td>
								<td>:</td>
								<td>{{ @$address_communication->house_no }} </td>
								
							</tr>
							<tr>
								<td>City</td>
								<td>:</td>
								<td>{{ @$address_communication->province }} </td>
							</tr>
						</table>
					</div>
					<div class="col-4">
						<table>
							<tr>
								<td>Street</td>
								<td>:</td>
								<td>{{ @$address_communication->street_name }} </td>
							</tr>
							<tr>
								<td>Country</td>
								<td>:</td>
								<td>{{ @$address_communication->country }} </td>
							</tr>
						</table>
					</div>
					<div class="col-4">
						<table>
							<tr>
								<td>Postal Code</td>
								<td>:</td>
								<td>{{ @$address_communication->postal_code }} </td>
							</tr>
						</table>
					</div>
				</div>
			</main>
			
		</div>
		
	</div>
</div>

{{-- Printable section end --}}
	
<div class="card radius-15">
	<div class="card-body">
		<div class="card-title">
			<h4 class="mb-0">{{ cms\students\models\StudentsModel::studentname($data->id) }} Information</h4>
		</div>
		<hr/>
		
		{{-- //section start --}}
		<section class="pro_section">
			<div class="container_">
				<div class="row">
		
					<div class="col-lg-3 col-md-4 col-sm-12">
		
						<div class="stu_box">
							<div class="stu_bg"></div>
							<img class="stu_img" src="{{@$data->user->images ?@$data->user->images :asset('assets/images/staff.jpg')   }}" alt="Image">
		
							<div class="stu_box_inner">
								<div class="box_value">
									<h5 class="pro_heading">Student Name</h5>
									<h6 class="pro_value">{{ @$data->first_name }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Registration Code</h5>
									<h6 class="pro_value">{{ @$data->reg_no }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Class</h5>
									<h6 class="pro_value">{{ @$data->class->name }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Section</h5>
									<h6 class="pro_value">{{ @$data->section->name }}</h6>
								</div>
								
								
								<div class="box_value">
									<h5 class="pro_heading">Date of Birth</h5>
									<h6 class="pro_value">{{ @$data->dob }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Gender</h5>
									<h6 class="pro_value">{{ ucfirst(@$data->gender) }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Status </h5>
									<h6 class="pro_value">
										<label class="switch">
											<input type="checkbox" id="{{ @$data->id }}" {{ @$data->status == 1? "checked":"" }} class="toggle-class" onchange="myFunction(this.checked ? 1:0,this.id)">
											<span class="slider round"></span>
										  </label>
		
		
									</h6>
								</div>
		
								
		
								
							</div>
		
						</div>
		
					</div>
		
					
					
					<div class="col-lg-9 col-md-8 col-sm-12">
						<ul
						class="nav nav-pills mb-3"
						id="pills-tab"
						role="tablist"
					  >
						<li class="nav-item" role="presentation">
						  <a
							class="nav-link active"
							id="pills-home-tab"
							data-bs-toggle="pill"
							href="#pills-home"
							role="tab"
							aria-controls="pills-home"
							aria-selected="true"
							>Profile</a
						  >
						</li>
						<li class="nav-item" role="presentation">
						  <a
							class="nav-link"
							id="pills-profile-tab"
							data-bs-toggle="pill"
							href="#pills-profile"
							role="tab"
							aria-controls="pills-profile"
							aria-selected="false"
							>Documents</a
						  >
						</li>
						<li class="nav-item" role="presentation">
						  <a
							class="nav-link"
							id="pills-contact-tab"
							data-bs-toggle="pill"
							href="#pills-contact"
							role="tab"
							aria-controls="pills-contact"
							aria-selected="false"
							>Exam</a
						  >
						  <li class="nav-item" role="presentation">
							<a
							  class="nav-link"
							  id="pills-transport-tab"
							  data-bs-toggle="pill"
							  href="#pills-transport"
							  role="tab"
							  aria-controls="pills-transport"
							  aria-selected="false"
							  >Transport</a
							>
						</li>
					  </ul>
					  <div class="tab-content" id="pills-tabContent">
						<div
						  class="tab-pane fade show active"
						  id="pills-home"
						  role="tabpanel"
						  aria-labelledby="pills-home-tab"
						>
						<h4 class="stu_sub_head">Personal Info {{ @$data->first_name }} {{ @$data->last_name }}</h4>

						<div>
							<div class="tab_box_value">
								<div class="row">
									
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">Email</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading">{{ @$data->user->email }}</h6>
									</div>
								</div>
							</div>
							<div class="tab_box_value">
								<div class="row">
									
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">Contact Mobile</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading">{{ @$data->user->mobile }}</h6>
									</div>
								</div>
							</div>
							<div class="tab_box_value">
								<div class="row">
									
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">Student Type</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading">{{ @$data->student_type }}</h6>
									</div>
								</div>
							</div>
							<div class="tab_box_value">
								<div class="row">
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">Admission Date</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading">{{ @$data->admission_date }}</h6>
									</div>
								</div>
							</div>
							<div class="tab_box_value">
								<div class="row">
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">National ID Number</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading">{{ @$data->national_id_number }}</h6>
									</div>
								</div>
							</div>
							<div class="tab_box_value">
								<div class="row">
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">Religion</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading">{{ @$data->religion }}</h6>
									</div>
								</div>
							</div>
							<div class="tab_box_value">
								<div class="row">
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">Blood Group</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading">{{ @$data->blood_group }}</h6>
									</div>
								</div>
							</div>
							
						</div>
		
						<h4 class="stu_sub_head mt40">Parent Guardian Details</h4>
		
						<div class="parent_box">
							<div>
								<img class="parent_img" src="{{@$data->parent->father_image ?@$data->parent->father_image :asset('assets/images/staff.jpg')   }}" alt="Image">
								
							</div>
		
							<div class="wi-100">
								<div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Father / Guardian Name</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$data->parent->father_name }}</h6>
										</div>
									</div>
								</div>
								<div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Email</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading email_cell">{{ @$data->parent->father_email }}</h6>
										</div>
									</div>
								</div>
								<div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Contact Number</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$data->parent->father_mobile }}</h6>
										</div>
									</div>
								</div>
							</div>
		
						</div>

						<div class="accordion accordion-flush" id="accordionFlushExample">
							<div class="accordion-item">
								<h2 class="accordion-header " id="flush-headingOne">
								  <button
									class="accordion-button collapsed"
									type="button"
									data-bs-toggle="collapse"
									data-bs-target="#flush-collapseOne"
									aria-expanded="false"
									aria-controls="flush-collapseOne"
								  >
								  Address of Communication
								  </button>
								</h2>
								<div
								  id="flush-collapseOne"
								  class="accordion-collapse collapse"
								  aria-labelledby="flush-headingOne"
								  data-bs-parent="#accordionFlushExample"
								>
								  <div class="accordion-body">
									<div class="parent_box">
										{{-- <div>
											<img src="assets/images/staff.jpg" class="parent_img">
										</div> --}}
		
										<div class="wi-100">
											<div class="tab_box_value">
												<div class="row">
													<div class="col-md-5 col-sm-12">
														<h5 class="pro_heading">Building Name</h5>
													</div>
													<div class="col-md-7 col-sm-12">
														<h6 class="pro_heading">{{ @$address_communication->building_name }}</h6>
													</div>
												</div>
											</div>
											<div class="tab_box_value">
												<div class="row">
													<div class="col-md-5 col-sm-12">
														<h5 class="pro_heading">Subbuilding Name</h5>
													</div>
													<div class="col-md-7 col-sm-12">
														<h6 class="pro_heading">{{ @$address_communication->subbuilding_name }}</h6>
													</div>
												</div>
											</div>
											<div class="tab_box_value">
												<div class="row">
													<div class="col-md-5 col-sm-12">
														<h5 class="pro_heading">House No</h5>
													</div>
													<div class="col-md-7 col-sm-12">
														<h6 class="pro_heading">{{ @$address_communication->house_no }}</h6>
													</div>
												</div>
											</div>
											<div class="tab_box_value">
												<div class="row">
													<div class="col-md-5 col-sm-12">
														<h5 class="pro_heading">Street</h5>
													</div>
													<div class="col-md-7 col-sm-12">
														<h6 class="pro_heading">{{ @$address_communication->street_name }}</h6>
													</div>
												</div>
											</div>
											<div class="tab_box_value">
												<div class="row">
													<div class="col-md-5 col-sm-12">
														<h5 class="pro_heading">Postal Code</h5>
													</div>
													<div class="col-md-7 col-sm-12">
														<h6 class="pro_heading">{{ @$address_communication->postal_code }}</h6>
													</div>
												</div>
											</div>
											<div class="tab_box_value">
												<div class="row">
													<div class="col-md-5 col-sm-12">
														<h5 class="pro_heading">Province</h5>
													</div>
													<div class="col-md-7 col-sm-12">
														<h6 class="pro_heading">{{ @$address_communication->province }}</h6>
													</div>
												</div>
											</div>
											<div class="tab_box_value">
												<div class="row">
													<div class="col-md-5 col-sm-12">
														<h5 class="pro_heading">Country</h5>
													</div>
													<div class="col-md-7 col-sm-12">
														<h6 class="pro_heading">{{ @$address_communication->country }}</h6>
													</div>
												</div>
											</div>
										</div>
		
									</div>
								  </div>
								</div>
							  </div>
						</div>

						
						</div>
						<div
						  class="tab-pane fade"
						  id="pills-profile"
						  role="tabpanel"
						  aria-labelledby="pills-profile-tab"
						>
						<div>
							<table id="getstudentsattachments" class="stu_table" cellspacing="0">
								<thead>
								<tr>
								   
									<th>No</th>
									<th>Document Name</th>
									<th>Download</th>
									
									
									
								</tr>
								</thead>
								<tbody>
		
									@forelse (@$data->attachment as $key=>$attachment )
									<tr>
										<td>{{ $key +1 }}</td>
										<td>{{ $attachment->attachment_name }}</td>
										<td><a href="{{ $attachment->attachment_url }}" class="download" target="_blank">Download <i class="fa fa-download" aria-hidden="true"></i></a></td>
									</tr>
										
									@empty
								   <tr>
									<td colspan="3" style="text-align: center">No Data Available</td>
								   </tr>
		
		
										
									@endforelse
					
								</tbody>
							</table>
						</div>
		
						</div>
						<div
						  class="tab-pane fade"
						  id="pills-contact"
						  role="tabpanel"
						  aria-labelledby="pills-contact-tab"
						>
						<div>
							<table class="stu_table">
								<thead>
									<tr>
										   <th class="nowrap">Subject</th>
										<th class="nowrap">	FULL MARKS </th>
										<th class="nowrap">PASSING MARKS</th>
										<th class="nowrap">OBTAINED MARKS</th>
										<th class="nowrap">RESULTS</th>
									</tr>
								</thead>
		
								<tbody>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
								</tbody>
							</table>
						</div>
						</div>

						<div
						  class="tab-pane fade"
						  id="pills-transport"
						  role="tabpanel"
						  aria-labelledby="pills-transport-tab"
						>
						<h4 class="stu_sub_head">Transport Info {{ @$data->first_name }} {{ @$data->last_name }} @if (!@$transport)
							<b class="text-danger">-Not Assign</b>
						@endif</h4>

						<div>

							@if (@$transport)
							<div class="tab_box_value">
								<div class="row">
									
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">Assign Stop</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading">{{ @$transport->stop->stop_name }}</h6>
									</div>
								</div>
								
							</div>

							<div class="tab_box_value">
								<div class="row">
									
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">Assign Route</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading">{{ @$transport->route->from }} - {{ @$transport->route->to }}</h6>
									</div>
								</div>
							</div>
							<div class="tab_box_value">
								<div class="row">
									
									<div class="col-md-5 col-sm-12">
										<h5 class="pro_heading">Assign Bus</h5>
									</div>
									<div class="col-md-7 col-sm-12">
										<h6 class="pro_heading mb-2">Bus No : <b>{{ @$transport->bus->bus_no }}</b></h6>
										<h6 class="pro_heading">Bus Name : <b>{{ @$transport->bus->vehicle_name }}</b></h6>
									</div>
								</div>
							</div>
							@endif
							
						</div>
						</div>
					  </div>
					</div>
					{{-- //end --}}
				</div>
			</div>
		
		</section>	
		<!-- section end -->
	</div>
</div>

</div>
@endsection
@section('scripts')
<!-- //js -->

<script>
var error=false;

console.log(error);
@if($errors->any()){
    $(".collapse").removeClass("in");
    $(".collapse").addClass("in");
}
@endif

var button=document.querySelector(".print");

button.addEventListener("click",function(){
	$("#invoice").show();
	$(".sidebar-wrapper").hide();
	$(".top-header").hide();
	$(".footer").hide();
	$(".box-header").hide();
	$(".radius-15").hide();

	$('.page-content-wrapper').css('margin-left','0px');
	$('.page-content-wrapper').css('margin-top','0px');
	$(".page-wrapper").css("margin-top","0px");
	window.print();
	$("#invoice").hide();
	$(".sidebar-wrapper").show();
	$(".top-header").show();
	$(".footer").show();
	$(".box-header").show();
	$(".radius-15").show();

	$('.page-content-wrapper').css('margin-left','260px');
	$('.page-content-wrapper').css('margin-top','70px');
	$(".page-wrapper").css("margin-top","70px");
});



</script>
@endsection
@section('script')
    <script>
          window.statuschange='{{route('students_action_from_admin')}}';
     
        $('document').ready(function(){

			var teacher_id={!! json_decode($data->id) !!}

            var element = $("#getteachersubjects");
            var url =  '{{route('Getteachersubjects')}}' + '/' + teacher_id;
            var column = [
               
               {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false},
                { data: 'classname', name: 'lclass.name', width: '15%' },
                { data: 'sectionname', name: 'section.name', width: '15%' },
                { data: 'subjectname', name: 'subject.name', width: '15%' },
              
                
               
                
            ];
            var csrf = '{{ csrf_token() }}';

            var options  = {
                //order : [ [ 6, "desc" ] ],
                lengthMenu: [[10,15,25,50, 100 ,250, 500, -1], [10,15,25,50, 100 ,250, 500,"ALL"]],
                button : [
                  
                    {
                        name : "Trash",
                        url : "{{route('teacher_action_from_admin',-1)}}"
                    },
                    {
                        name : "Delete",
                        url : "{{route('teacher.destroy',1)}}",
                        method : "DELETE"
                    }
                ],

            }


            dataTable(element,url,column,csrf,options);

        });
    </script>

@endsection