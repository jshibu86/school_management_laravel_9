@extends('layout::admin.master')

@section('title','Member Profile')
@section('style')
@include('layout::admin.head.list_head')
<link rel="stylesheet" href="{{asset('assets/backend/css/profile.css')}}">
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
        <a class="btn btn-warning btn-sm" id="print" target="_blank" href="{{ route("leaveprint",@$leave_data->id) }}"><i class="fa fa-print"></i> print</a>
		<a class="btn btn-info btn-sm m-1  px-3" href="{{route('leave.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

       
        
            

        @include("layout::admin.breadcrump",['route'=>"View Leave"])
    </div>
<!-- section begin -->
<div class="card radius-15">
	<div class="card-body">
		<div class="card-title">
			<h4 class="mb-0">leave  Information</h4>
		</div>
		<hr/>
		<section class="pro_section">
			<div class="container_">
				<div class="row">
		
					<div class="col-lg-3 col-md-4 col-sm-12">
		
						<div class="stu_box">
							<div class="stu_bg"></div>
							<img class="stu_img" src="{{@$user->images ?@$user->images :asset('assets/images/staff.jpg')   }}" alt="Image">
		
							<div class="stu_box_inner">
								<div class="box_value">
									<h5 class="pro_heading">Name</h5>
									<h6 class="pro_value">{{ @$user->name }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Reg No</h5>
									<h6 class="pro_value">{{ @$user->username }}</h6>
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
							>Leave Inforamtion</a
						  >
						</li>
                        <li class="nav-item" role="presentation">
                            <a
                              class="nav-link "
                              id="pills-previous-tab"
                              data-bs-toggle="pill"
                              href="#pills-previous"
                              role="tab"
                              aria-controls="pills-previous"
                              aria-selected="true"
                              >Previous Leave</a
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
							<h4 class="stu_sub_head">Personal Info {{ @$user->name }}</h4>
		
							<div>
                                <div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">User Name</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$user->username }}</h6>
										</div>
									</div>
								</div>
								<div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Email</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$user->email }}</h6>
										</div>
									</div>
								</div>
                                <div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Mobile</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$user->mobile }}</h6>
										</div>
									</div>
								</div>

                                <div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Leave reason</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$leave_data->reason }}</h6>
										</div>
									</div>
								</div>

                                <div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Leave Dates</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											<h6 class="pro_heading">{{ @$leave_data->from_date }} - {{ @$leave_data->to_date }}</h6>
										</div>
									</div>
								</div>
                                @if (@$leave_data->attachment)
                                <div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Leave Attachment</h5>
										</div>
										<div class="col-md-7 col-sm-12">
                                            <a class="badge bg-light text-dark" href="{{ @$leave_data->attachment }}" target="_blank">View Attachment</a>
										</div>
									</div>
								</div>
                                @endif

                                <div class="tab_box_value">
									<div class="row">
										<div class="col-md-5 col-sm-12">
											<h5 class="pro_heading">Application Status</h5>
										</div>
										<div class="col-md-7 col-sm-12">
											@if (@$leave_data->application_status == -1)
                                            <span class="badge bg-rose">Rejected</span>

                                            @elseif (@$leave_data->application_status == 1)

                                            <span class="badge bg-success">Approved</span>
                                            @elseif (@$leave_data->application_status == 2)
                                            <span class="badge bg-warning">Pending</span>
                                            @endif
										</div>
									</div>
								</div>
							
							</div>

							{{-- <h4 class="stu_sub_head mt40">Parent Guardian Details</h4> --}}

							
							
						</div>
                        <div
							class="tab-pane fade  "
							id="pills-previous"
							role="tabpanel"
							aria-labelledby="pills-previous-tab"
							>

                            <div class="table-responsive">
                                <table id="datatable-buttons1" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Applicant Username</th>
                                            <th>Type</th>
                                            <th>From / To</th>
                                            <th>Status</th>
                                            
                                        
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                    
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
@section('scripts')
<!-- //js -->

<script type="text/javascript">
var error=false;

console.log(error);
@if($errors->any()){
    $(".collapse").removeClass("in");
    $(".collapse").addClass("in");
}
@endif

    $(".collapse")
    .on("show.bs.collapse", function() {
      $(this)
        .parent()
        .find(".down-arrow")
        .addClass("rotate");
    })
    .on("hide.bs.collapse", function() {
      $(this)
        .parent()
        .find(".down-arrow")
        .removeClass("rotate");
    });

</script>
@endsection
@section('script')
    <script>
     
        $('document').ready(function(){

			var teacher_id={!! json_encode($leave_data->id) !!};

            var element = $("#datatable-buttons1");
            var url =  '{{route('gethistoryLeave')}}' + '/' + teacher_id;
            var column = [
               
                {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
            { data: 'applicantname', name: 'applicantname', width: '15%',searchable: false, sortable: false, },
            { data: 'type', name: 'leave_types.leave_type' , className: 'textcenter' },
            { data: 'fromto', name: 'leave.from_date' , className: 'textcenter' },
            { data: 'status', name: 'status' , className: 'textcenter', searchable: false, sortable: false, },
            
           
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