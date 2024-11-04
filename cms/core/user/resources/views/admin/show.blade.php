@extends('layout::admin.master')

@section('title','users')
@section('style')
    <!-- Datatables -->
    <link rel="stylesheet" href="{{asset('assets/backend/css/profile.css')}}">
    @include('layout::admin.head.list_head')
    <style>
        .table-div table {
            width: 100% !important;
        }
        .stu_img_{
            border-radius: 6px;
    max-width: 100px;
    max-height: 115px;
    height: auto;
        }
    </style>
   
@endsection
@section('body')
@include("layout::admin.breadcrump",['route'=> "View user"])

<div class="card">
    <div class="card-body">
        <div class="card-title btn_style">
            <h4 class="mb-0">{{ @$data->name }}</h4>
            <div class="btns">
                <a class="btn btn-info btn-sm m-1  px-3" href="{{route('user.index',$data->id)}}" ><i class='fa fa-arrow-left'></i>&nbsp;&nbsp;Back</a>
                @if (@$data->id !=1)
                <a class="btn btn-warning btn-sm m-1  px-3" href="{{route('user.edit',$data->id)}}" ><i class='fa fa-plus'></i>&nbsp;&nbsp;Edit</a>
                @endif
            </div>
           
           
        </div>
        <hr/>
        <section class="pro_section">
			<div class="container">
				<div class="row">
		
					<div class="col-lg-3 col-md-4 col-sm-12">
                        <div class="stu_box_">
							{{-- <div class="stu_bg"></div> --}}
							<img class="stu_img_" src="{{@$data->images ?@$data->images :asset('assets/images/staff.jpg')   }}" alt="Image">
                        </div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-12">
                        <div class="tab_box_value">
                            <div class="row">
                                
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="pro_heading">Name</h5>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <h6 class="pro_heading">{{ @$data->name }}</h6>
                                </div>
                            </div>
                            
                        </div>
                        <div class="tab_box_value">
                            <div class="row">
                                
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="pro_heading">User Name</h5>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <h6 class="pro_heading">{{ @$data->username }}</h6>
                                </div>
                            </div>
                            
                        </div>
                        <div class="tab_box_value">
                            <div class="row">
                                
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="pro_heading">Groups</h5>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    @foreach (@$data->group as $group )
                                    <span class="badge bg-dark">{{ @$group->group }}</span>
                                    
                                    @endforeach
                                   
                                </div>
                            </div>
                            
                        </div>

                        <div class="tab_box_value">
                            <div class="row">
                                
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="pro_heading">Email</h5>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <h6 class="pro_heading">{{ @$data->email }}</h6>
                                </div>
                            </div>
                            
                        </div>
                        <div class="tab_box_value">
                            <div class="row">
                                
                                <div class="col-md-3 col-sm-12">
                                    <h5 class="pro_heading">Mobile</h5>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <h6 class="pro_heading">{{ @$data->mobile?@$data->mobile : "Mobile Number Not Provided" }}</h6>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        
    </div>
</div>

    
    
@endsection
@section('script')

    <script>
        $(function(){
            $("input[data-bootstrap-switch]").each(function(){
              $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        });
        </script>
         

@endsection