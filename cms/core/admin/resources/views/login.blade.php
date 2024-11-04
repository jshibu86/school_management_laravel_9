<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <title>{{isset(Configurations::getConfig('site')->school_name) ? Configurations::getConfig('site')->school_name : '' }} | Administrator </title>

        <link rel="stylesheet" type="text/css" href="{{ asset("assets/backend/css/style2.css") }}"/>
		{!!Cms::style('theme/vendors/css/pace.min.css')!!}
		{!!Cms::style('theme/vendors/js/pace.min.js')!!}
		{!!Cms::style('theme/vendors/css/bootstrap.min.css')!!}
		{!!Cms::style('theme/vendors/css/icons.css')!!}
		{!!Cms::style('theme/vendors/css/app.css')!!}

		
		
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&family=Roboto&display=swap" />
	

		<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
		
		<style>
			.select2-selection--single{
				height: 39px!important;
			}
			.select2-selection__placeholder{
				margin-left: 20px!important;
			}
			.select2-selection__rendered{
				margin-top: 4px !important;
			}
			.select2-selection__arrow{
				top:4px !important;
			}
			.login_icon1{
				position: relative;
				top: -31px;
				left: 10px;
				color: #0fa6e5;
			}
			.select2-container .select2-selection--single .select2-selection__rendered {
		
			padding-left: 33px !important;
			
			}
			.select2-container--default .select2-selection--single .select2-selection__clear {
				
				margin-top: 4px !important;
			}
		</style>
    </head>
<body>
  	<!-- wrapper -->
	<div class="wrapper">
		<div class="section-authentication-login d-flex align-items-center justify-content-center mt-4">
			<div class="row">
				<div class="col-12 col-lg-8 mx-auto">
					<div class="card radius-15 overflow-hidden">
						<div class="row g-0">
							<div class="col-xl-6">
								<div class="card-body p-5">
									<div class="text-center">
										@if(isset(Configurations::getConfig('site')->imagec))
										<img src="{{ Configurations::getConfig('site')->imagec }} " class="school_logo"/>
										@endif
										<h2>{{isset(Configurations::getConfig('site')->school_name) ? Configurations::getConfig('site')->school_name : '' }}</h2>
									</div>
									@if($inactive != 1)
										<div class="">
											
											<div class="login-separater text-center mb-4"> <span>SIGN IN WITH USERNAME</span>
												<hr>
											</div>
											<div class="form-body">
												{{ Form::open(array('url'=>route('dobackendlogin'),'method' => 'post',"class"=>"row g-3")) }}
												
													<div class="col-12">
														<label for="inputEmailAddress" class="form-label">User Name</label>
														{!! Form::text('username', Cookie::get('username'), array('id'=>'inputEmailAddress','class' => 'form-control input-fields-input login-input useremail','required' => 'required','placeholder'=>'Enter Username','autocomplete'=>"off")) !!}
														
													</div>
													<div class="col-12">
														<label for="inputChoosePassword" class="form-label">Enter Password</label>
														<div class="input-group" id="show_hide_password">
															


															<input type="password" class="form-control border-end-0" id="inputChoosePassword" value="12345678" placeholder="Enter Password" name="password" required> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-check form-switch">
															


															<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="" name="remember">
															<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
														</div>
													</div>
													<div class="col-md-6 text-end">	
													</div>
													<div class="col-12">
														<div class="d-grid">
															<button type="submit" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Sign in</button>
														</div>
													</div>
													
													{{Form::close()}}
											</div>
											<br/>
											<div class="copy__rights">
											
												<p>@ @php echo date('Y') @endphp All Rights Reserved. {{isset(Configurations::getConfig('site')->site_name) ? Configurations::getConfig('site')->site_name : '' }}</p>
											</div>
											
											<div class="error__message">
												@if($errors->any())
												{{ implode('', $errors->all(':message')) }}
												@endif
											</div>
										</div>
									@else
										<div class="mt-4 bg-danger p-2 rounded">
											<p class="h6 text-light text-center">Your School Site was Diactivated.Can You Please Contact the Admin for further Info.</p>
										</div>
									  
									@endif
								</div>
							 </div>
							<div class="col-xl-6 bg-login-color d-flex align-items-center justify-content-center">
								<img src="{{ asset('skin/theme1/theme/vendors/images/login-images/login-frent-img.jpg') }}" class="img-fluid" alt="...">
							</div>
						</div>
						<!--end row-->
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- end wrapper -->
   
	{!! Cms::script('theme/vendors/js/jquery.min.js') !!}
	
	<script src="{{ asset('assets/backend/js/app.js') }}" type="module"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	
	
</body>
</html>