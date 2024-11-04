@extends('layout::admin.master')

@section('title','chapter topic')
@section('style')
@include('layout::admin.head.list_head')
<link type="text/css" rel="stylesheet" href="{{ asset("assets/backend/js/lightgallery/css/lightgallery-bundle.css") }}" />
<link rel="stylesheet" href="{{asset('assets/backend/css/profile.css')}}">
<style>
     @font-face {
            font-family: 'lg';
           
            src: url('https://cdnjs.cloudflare.com/ajax/libs/lightgallery/1.6.12/fonts/lg.ttf') format('truetype');
           
        }
    #lightgallery img{
        width: 200px;
        max-width: 250px;
    }

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


</style>
@endsection
@section('body')
    <div class="x_content">

       
        <div class="box-header with-border mar-bottom20">

            @if (CGate::allows("edit-chapter"))
            <a class="btn btn-warning btn-sm m-1  px-3" href="{{route('chaptertopic.edit',@$data->id)}}" ><i class="fa fa-edit"></i>&nbsp;&nbsp;&nbsp;Edit</a>
            @endif
           

            <a class="btn btn-info btn-sm m-1  px-3" href="{{route('chapter.show',@$data->chapter_id)}}" ><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;&nbsp;Back</a>

          
          
            @include("layout::admin.breadcrump",['route'=> Str::limit(@$data->topic_name, 20) ])
           
            
        </div>
        

       
<div class="card">
    <div class="card-body">
        <div class="card-title">
            <h4></h4>
            <!-- section begin -->
<section class="pro_section">
	<div class="container_">
		<div class="row">

			<div class="col-lg-12 col-md-12 col-sm-12">

				<div class="stu_box_">
					<div class="stu_bga"></div>
					

					<div class="stu_box_inner">
                       
                        <div class="topic_name">
                            <h4 class="stu_sub_head">{{ @$data->topic_name }} </h4>
                        </div>
                        
                        @if (@$data->topic_description)
                        <div class="topic_description">
                            {{-- <h4 class="stu_sub_head">topic Description</h4> --}}
                            <br/>
                            {!! @$data->topic_description !!}
                        </div>
                        @endif

                        <div class="topic_contents">
                            <div class="row">
                               
                                <div id="lightgallery"> 
                                @foreach (@$data->contents as$key=> $content)
                                @if($content->content_type=="image")
                                
                                        <a href="{{ asset(@$content->content_url) }}" data-lg-size="1600-2400">
                                            <img alt="{{ @$content->content_type }}" src="{{ asset(@$content->content_url) }}" />
                                        </a>
                                    
                                @endif
                               
                                @endforeach
                            </div>
                                
                            </div>

                            <div class="row">
                                @foreach (@$data->contents as$key=> $content)
                                @if ($content->content_type=="document")
                              
                                    <div class="col-xs-12 col-md-2 col-lg-2 file__">
                                        <a href="{{ @$content->content_url }}" target="_blank">View Attachfile-{{ $key+1 }}  <i class="fa fa-file-pdf-o" aria-hidden="true"></i></a>
                                    </div>
                              
                                    
                                @endif
                               
                                @endforeach
                               
                            </div>

                            <div class="row">
                                @foreach (@$data->contents as$key=> $content)
                                @if ($content->content_type=="video")
                              
                                    <div class="col-xs-12 col-md-12 col-lg-12">
                                        <iframe  style="overflow:hidden;width:100%"  width="100%" height="500" src="{{ @$content->content_url }}">
                                        </iframe>
                                    </div>
                              
                                    
                                @endif
                               
                                @endforeach
                               
                            </div>
                        </div>
                       
                        
                        
                      
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
<script src="{{ asset("assets/backend/js/lightgallery/js/lightgallery.min.js") }}"></script>

<!-- lightgallery plugins -->
<script src="{{ asset("assets/backend/js/lightgallery/js/lg-thumbnail.umd.js") }}""></script>
<script src="{{ asset("assets/backend/js/lightgallery/js/lg-zoom.umd.js") }}""></script>
<script type="text/javascript">
    lightGallery(document.getElementById('lightgallery'), {
        plugins: [lgZoom, lgThumbnail],
        licenseKey: 'your_license_key',
        speed: 500,
        // ... other settings
    });
</script>
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
                { data: 'topic_name', name: 'topic_name'},
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
    <script src="https://cdn.jsdelivr.net/npm/lightgallery.js@1.4.0/lib/js/lightgallery.min.js"></script>
    {!! Cms::script("theme/vendors/validator/validator.js") !!}

@endsection
