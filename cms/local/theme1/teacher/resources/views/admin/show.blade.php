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
.accordion-button{
		padding: 1rem 0.25rem !important;
		text-transform: uppercase !important;
		font-size: 13px !important;
		font-weight: 500!important;
    	color: #415094 !important;
		border-bottom: 1px solid #4150944d;
	}
</style>
@endsection

@section('body')
<div class="x_content">
    <div class="box-header with-border mar-bottom20">
		
        <a class="btn btn-warning btn-sm m-1  px-3" href="{{route('teacher.edit',$data->id)}}" ><i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;Edit</a>
		<button class="btn btn-primary btn-sm m-1  px-3 print" type="button"><i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;Print</button>
		<a class="btn btn-info btn-sm m-1  px-3" href="{{route('teacher.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
            

        @include("layout::admin.breadcrump",['route'=> cms\teacher\models\TeacherModel::teachername($data->id)])
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
								<td>Staff Name</td>
								<td>:</td>
								<td>{{ ucfirst(@$data->teacher_name) }}</td>
							</tr>
							<tr>
								<td>Employee Code</td>
								<td>:</td>
								<td>{{@$data->employee_code }}</td>
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
							<tr>
								<td>Email</td>
								<td>:</td>
								<td>{{ @$data->email}}</td>
							</tr>
						</table>
						
						
					</div>
					<div class="col invoice-details">
						<table>
							
							<tr>
								<td>Mobile</td>
								<td>:</td>
								<td>{{@$data->mobile }}</td>
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
						<img class="" src="{{@$data->parent->father_image ?@$data->parent->father_image :asset('assets/images/staff.jpg')   }}" alt="Image" width=80>
					</div>
					<div class="col-9  invoice-details">
						<div class="row">
							<div class="col-6">
								<table>
									<tr>
										<td>Parent/Guardian Name</td>
										<td>:</td>
										<td>{{ ucfirst(@$data->guardian_name ) }}</td>
									</tr>
									<tr>
										<td>Relationship</td>
										<td>:</td>
										<td>{{ ucfirst(@$data->relation ) }}</td>
									</tr>
									<tr>
										<td>Contact Number</td>
										<td>:</td>
										<td>{{ @$data->guardian_mobile   }}</td>
									</tr>
									
									
								
								</table>
							</div>
							
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
			<h4 class="mb-0">{{ cms\teacher\models\TeacherModel::teachername($data->id)}} Information</h4>
		</div>
		<hr/>
		<section class="pro_section">
			<div class="container_">
				<div class="row">
		
					<div class="col-lg-3 col-md-4 col-sm-12">
		
						<div class="stu_box">
							<div class="stu_bg"></div>
							<img class="stu_img" src="{{@$data->user->images ?@$data->user->images :asset('assets/images/staff.jpg')   }}" alt="Image">
		
							<div class="stu_box_inner">
								<div class="box_value">
									<h5 class="pro_heading">Faculty Name</h5>
									<h6 class="pro_value">{{ @$data->teacher_name }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Employee Code</h5>
									<h6 class="pro_value">{{ @$data->employee_code }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Class Teacher</h5>
									@if(@$data->classteacher)
									<h6 class="pro_value">{{ @$data->classteacher->classname }} - {{ @$data->classteacher->sectionname }}</h6>
									@else
									<h6 class="pro_valueassign text-danger">Not Assign</h6>
									@endif
								</div>
								
								<div class="box_value">
									<h5 class="pro_heading">Date of Birth</h5>
									<h6 class="pro_value">{{ @$data->dob }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Gender</h5>
									<h6 class="pro_value">{{ @$data->gender }}</h6>
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
							>Teacher Subjects</a
						  >
						</li>

						<li class="nav-item" role="presentation">
						  <a
							class="nav-link"
							id="pills-history-tab"
							data-bs-toggle="pill"
							href="#pills-history"
							role="tab"
							aria-controls="pills-history"
							aria-selected="false"
							>Payment History</a
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
							<h4 class="stu_sub_head">Personal Info {{ @$data->teacher_name }}</h4>
		
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
											<h5 class="pro_heading">Mobile</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$data->user->mobile }}</h6>
										</div>
									</div>
								</div>
								<div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Date of Join</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$data->date_ofjoin }}</h6>
										</div>
									</div>
								</div>
								<div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Qualification</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$data->qualification }}</h6>
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
								<div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Marital Status</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$data->maritial_status }}</h6>
										</div>
									</div>
								</div>
							</div>

							<h4 class="stu_sub_head mt40">Parent Guardian Details</h4>

							<div class="parent_box">
								{{-- <div>
									<img src="assets/images/staff.jpg" class="parent_img">
								</div> --}}

								<div class="wi-100">
									<div class="tab_box_value">
										<div class="row">
											<div class="col-md-5 col-sm-12">
												<h5 class="pro_heading">Father / Guardian Name</h5>
											</div>
											<div class="col-md-7 col-sm-12">
												<h6 class="pro_heading">{{ @$data->guardian_name }}</h6>
											</div>
										</div>
									</div>
									<div class="tab_box_value">
										<div class="row">
											<div class="col-md-5 col-sm-12">
												<h5 class="pro_heading">Relation</h5>
											</div>
											<div class="col-md-7 col-sm-12">
												<h6 class="pro_heading">{{ @$data->relation }}</h6>
											</div>
										</div>
									</div>
									<div class="tab_box_value">
										<div class="row">
											<div class="col-md-5 col-sm-12">
												<h5 class="pro_heading">Contact Number</h5>
											</div>
											<div class="col-md-7 col-sm-12">
												<h6 class="pro_heading">{{ @$data->guardian_mobile }}</h6>
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
														{{-- <div class="tab_box_value">
															<div class="row">
																<div class="col-md-5 col-sm-12">
																	<h5 class="pro_heading">Building Name</h5>
																</div>
																<div class="col-md-7 col-sm-12">
																	<h6 class="pro_heading">{{ @$address_communication->building_name }}</h6>
																</div>
															</div>
														</div> --}}
														{{-- <div class="tab_box_value">
															<div class="row">
																<div class="col-md-5 col-sm-12">
																	<h5 class="pro_heading">Subbuilding Name</h5>
																</div>
																<div class="col-md-7 col-sm-12">
																	<h6 class="pro_heading">{{ @$address_communication->subbuilding_name }}</h6>
																</div>
															</div>
														</div> --}}
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
								@if(@$data->kin_fullname)
								<div class="accordion-item">
									<h2 class="accordion-header " id="flush-headingTwo">
									  <button
										class="accordion-button collapsed"
										type="button"
										data-bs-toggle="collapse"
										data-bs-target="#flush-collapseTwo"
										aria-expanded="false"
										aria-controls="flush-collapseTwo"
									  >
									  Next of Kin
									  </button>
									</h2>
									<div
										id="flush-collapseTwo"
										class="accordion-collapse collapse"
										aria-labelledby="flush-headingTwo"
										data-bs-parent="#accordionFlushExample"
										>
											<div class="accordion-body">
												<div class="parent_box">
													
					
													<div class="wi-100">
														<div class="tab_box_value">
															<div class="row">
																<div class="col-md-5 col-sm-12">
																	<h5 class="pro_heading">Kin Fullname</h5>
																</div>
																<div class="col-md-7 col-sm-12">
																	<h6 class="pro_heading">{{ @$data->kin_fullname }}</h6>
																</div>
															</div>
														</div>
														<div class="tab_box_value">
															<div class="row">
																<div class="col-md-5 col-sm-12">
																	<h5 class="pro_heading">Kin Relation</h5>
																</div>
																<div class="col-md-7 col-sm-12">
																	<h6 class="pro_heading">{{ @$data->kin_relationship }}</h6>
																</div>
															</div>
														</div>
														<div class="tab_box_value">
															<div class="row">
																<div class="col-md-5 col-sm-12">
																	<h5 class="pro_heading">Phone</h5>
																</div>
																<div class="col-md-7 col-sm-12">
																	<h6 class="pro_heading">{{ @$data->kin_phonenumber }}</h6>
																</div>
															</div>
														</div>
														<div class="tab_box_value">
															<div class="row">
																<div class="col-md-5 col-sm-12">
																	<h5 class="pro_heading">Email</h5>
																</div>
																<div class="col-md-7 col-sm-12">
																	<h6 class="pro_heading">{{ @$data->kin_email }}</h6>
																</div>
															</div>
														</div>
														<div class="tab_box_value">
															<div class="row">
																<div class="col-md-5 col-sm-12">
																	<h5 class="pro_heading"> Occupation</h5>
																</div>
																<div class="col-md-7 col-sm-12">
																	<h6 class="pro_heading">{{ @$data->kin_occupation }}</h6>
																</div>
															</div>
														</div>
														<div class="tab_box_value">
															<div class="row">
																<div class="col-md-5 col-sm-12">
																	<h5 class="pro_heading">Address</h5>
																</div>
																<div class="col-md-7 col-sm-12">
																	<h6 class="pro_heading">{{ @$data->kin_address }}</h6>
																</div>
															</div>
														</div>
														
													</div>
					
												</div>


											</div>
									</div>



								</div>
								@endif
							</div>
						</div>
						<div
						  class="tab-pane fade"
						  id="pills-profile"
						  role="tabpanel"
						  aria-labelledby="pills-profile-tab"
						>
						<div>
							<table id="getteachersubjects" class="stu_table" cellspacing="0">
								<thead>
								<tr>
								   
									<th>No</th>
									<th>Class</th>
									<th>Section</th>
									<th>Subject</th>
									
									
								</tr>
								</thead>
								<tbody>
					
								</tbody>
							</table>
						</div>
						</div>


						<div
						  class="tab-pane fade"
						  id="pills-history"
						  role="tabpanel"
						  aria-labelledby="pills-history-tab"
						>
						<div>
							<table id="getteachersubjects" class="stu_table" cellspacing="0">
								<thead>
								<tr>
								   
									<th>No</th>
									<th>Month/Year</th>
									<th>Salary {{Configurations::getConfig("site")->currency_symbol}}</th>
									<th>View</th>
									
									
									
								</tr>
								</thead>
								<tbody>
									@foreach (@$payment_history as $history)
										<tr>
											<td>{{$loop->index+1}}</td>
											<td>{{$history->month}} / {{$history->year}}</td>
											<td>{{$history->basic_salery}}</td>
											<td><a href="{{route("viewpayslip",$history->id)}}" target="_blank"><i class="fa fa-eye"></i></a></td>
											
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						</div>
					  </div>
					</div>
					
					
		
				</div>
			</div>
		
		</section>	
	</div>
</div>

<!-- section end -->
</div>
@endsection


@section("script")
<script>
	console.log("print");
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
@section('scripts')
    <script>
     
        $('document').ready(function(){

			var teacher_id={!! json_decode($data->id) !!}

            var element = $("#getteachersubjects");
            var url =  '{{route('Getteachersubjects')}}' + '/' + teacher_id;
            var column = [
               
               {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable:false},
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