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
		<a class="btn btn-info btn-sm m-1  px-3" href="{{route('member.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>
        <a class="btn btn-warning btn-sm m-1  px-3" href="{{route('member.show',["id"=>@$data->id,"print"=>"print"])}}" target="_blank"><i class="fa fa-print"></i>&nbsp;&nbsp;&nbsp;ID Card</a>

            

        @include("layout::admin.breadcrump",['route'=>"View Member"])
    </div>
<!-- section begin -->
<div class="card radius-15">
	<div class="card-body">
		<div class="card-title">
			<h4 class="mb-0"> Information</h4>
		</div>
		<hr/>
		<section class="pro_section">
			<div class="container_">
				<div class="row">
		
					<div class="col-lg-3 col-md-4 col-sm-12">
		
						<div class="stu_box">
							<div class="stu_bg"></div>
							<img class="stu_img" src="{{@$data->user->images ?@$user->images :asset('assets/images/staff.jpg')   }}" alt="Image">
		
							<div class="stu_box_inner">
								<div class="box_value">
									<h5 class="pro_heading">Member Name</h5>
									<h6 class="pro_value">{{ @$user->name }}</h6>
								</div>
								<div class="box_value">
									<h5 class="pro_heading">Member ID</h5>
									<h6 class="pro_value">{{ @$data->member_username }}</h6>
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
							
							</div>

							{{-- <h4 class="stu_sub_head mt40">Parent Guardian Details</h4> --}}

							
							
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