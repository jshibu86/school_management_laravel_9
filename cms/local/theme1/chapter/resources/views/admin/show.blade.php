@extends('layout::admin.master')

@section('title','subject mapping')
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
.stu_box_inner {
   
    padding: 23px 30px 40px !important;
   
}
.stu_sub_heada{
    font-weight: bold!important;
    line-height: 1.5;
}
.chapter_description{
    margin-top: 15px;
}
.dt-buttons {
	display: none;
}
</style>
@endsection
@section('body')
    <div class="x_content">

       
        <div class="box-header with-border mar-bottom20">
           

            

          
			<a class="btn btn-info btn-sm m-1  px-3" href="{{route('chapter.index')}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

		
          
            @include("layout::admin.breadcrump",['route'=> "View Chapter with Topics"])
           
            
        </div>
        

       

 <div class="card">
	<div class="card-body">
		<div class="card-title">
			<h4>View Chapter with Topics</h4>
			<hr/>
			<!-- section begin -->
<section class="pro_section">
	<div class="container_">
		<div class="row">

			<div class="col-lg-4 col-md-12 col-sm-12">

				<div class="stu_box">
					<div class="stu_bga"></div>
					

					<div class="stu_box_inner">
                        <h4 class="stu_sub_head">Chapter Info</h4>
                        <h4 class="stu_sub_heada">{{ @$data->chapter_name }}</h4>
						
						
						<div class="box_value">
							<h5 class="pro_heading">Class</h5>
							<h6 class="pro_value">{{ @$data->class->name }}</h6>
						</div>
                        <div class="box_value">
							<h5 class="pro_heading">Section</h5>
							<h6 class="pro_value">{{ $data->section->name }}</h6>
						</div>
						
						<div class="box_value">
							<h5 class="pro_heading">Created Date</h5>
							<h6 class="pro_value">{{ date('d-m-Y', strtotime($data->created_at)); }}</h6>
						</div>
                        @if (@$data->chapter_description)
                        <div class="chapter_description">
                            <h4 class="stu_sub_head">Chapter Description</h4>
                            <br/>
                            {!! @$data->chapter_description !!}
                        </div>
                        @endif
                       
                      
                      
					</div>

				</div>

			</div>
			
			<div class="col-lg-8 col-md-12 col-sm-12">
				
				<div class="stu_box_inner tab_box">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#tab_content1" data-toggle="tab">Chapter Topics - <span>{{ count(@$data->topics) }}</span></a>
						</li>
						
						
					</ul>

					

					<div class="tab-content">
						

						<!-- Tab 2 -->
						<div class="tab_content tab-pane active" id="tab_content1">

							<div>
								<table id="gettopics" class="stu_table" cellspacing="0">
									<thead>
									<tr>
									   
										<th>No</th>
										<th>Topic Name</th>
										<th>Action</th>
									
									</tr>
									</thead>
									<tbody>
						
									</tbody>
								</table>
							</div>

						</div>
						<!-- Tab 2 end -->

						

					</div>

				</div>

			</div>

		</div>
	</div>

</section>	
<!-- section end -->
		</div>
	</div>
 </div>

 
         
    

</div>

        
    

@endsection
@section('scripts')

<script type="module">
    function notify_script(title,text,type,hide) {
            new PNotify({
                title: title,
                text: text,
                type: type,
                hide: hide,
                styling: 'bootstrap3'
            })
        }
    window.sectionurl="{{ route('section.index') }}";
    window.subjecturl="{{ route('subject.index') }}";
   

    AcademicConfig.CommonClassSectionSubjects(notify_script)
</script>
@endsection
@section('script')
    <script>
     
        $('document').ready(function(){

			var chapter_id={!! json_decode($data->id) !!}

            var element = $("#gettopics");
            var url =  '{{route('get_chapter_topic_data_from_admin')}}' + '/' + chapter_id;
            var column = [
               
               {data: 'DT_RowIndex', name: 'DT_RowIndex',searchable: false,sortable: false},
                { data: 'topic_name', name: 'topic_name',sortable: false},
                { data: 'action', name: 'id', searchable: false, sortable: false, className: 'textcenter'},
              
                
               
                
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

@section('script_link')

    <!-- validator -->
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

@endsection
