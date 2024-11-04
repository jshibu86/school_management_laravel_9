<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <title>{{isset(Configurations::getConfig('site')->site_name) ? Configurations::getConfig('site')->site_name : '' }} | Administrator </title>

        <link rel="stylesheet" type="text/css" href="{{ asset("assets/backend/css/style.css") }}"/>
    
        <!-- Bootstrap -->
        {!!Cms::style('theme/vendors/bootstrap/dist/css/bootstrap.min.css')!!}
        <!-- Font Awesome -->
        {!!Cms::style('theme/vendors/font-awesome/css/font-awesome.min.css')!!}
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
  
    <section class="login_bg">
        <div class="overlay">
            
        </div>
       
		<div class="container container-full">
			<div class="row">
				<div class="col-lg-5 col-md-7 col-sm-9 col-xs-12 login-col">
					<div class="login_box">
						@if (\Session::has('success'))
						<div class="alert alert-success">
						   {!! \Session::get('success') !!}
							
						</div>
						 @endif
						<div class="text-center login_box_head">
							<h2>Welcome Back !</h2>
							<h6>Sign into Your Account</h6>
						</div>
						<div class="loginform_sec">
                            {{ Form::open(array('url'=>route('dobackendlogin'),'method' => 'post')) }}

								<table class="login-table mat-5">

									<tr>
										<td>

                                            {!! Form::text('username', Cookie::get('username'), array('id'=>'username','class' => 'form-control input-fields-input login-input useremail','required' => 'required','placeholder'=>'Enter Email','autocomplete'=>"off")) !!}
											
											<i class="fa fa-user login_icon"></i>
										</td>
									</tr>
									

									<tr>
										<td>
                                            {!! Form::password('password', array('id'=>'password','class' => 'form-control input-fields-input login-input','required' => 'required','placeholder'=>'Password')) !!}
											
											<i class="fa fa-lock login_icon"></i>
										</td>
									</tr>

									<tr>
										<td class="centeralign">
                                            {!! Form::checkbox('remember', '1', Cookie::get('admin_username') ? true : false, array('id'=>'remember')) !!}
                                            <label for="remember"  class="rem_txt"><span></span>Remember Me</label>
											
										</td>

									</tr>

									<tr>
										<td>
                                            {!! Form::submit('Sign In', ['class' => 'login-button ma-5']) !!}
											
										</td>
									</tr>
                                    <tr>
                                        <td>
                                            <div class="error__message">
                                                @if($errors->any())
                                                {{ implode('', $errors->all(':message')) }}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>

								</table>
								
                              
                                    <div class="copy__rights">
                                        
                                        <p>@ @php echo date('Y') @endphp All Rights Reserved. {{isset(Configurations::getConfig('site')->site_name) ? Configurations::getConfig('site')->site_name : '' }}</p>
                                    </div>
                               
									
                            {{Form::close()}}
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	
	<script src="{{ asset('assets/backend/js/app.js') }}" type="module"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
	{!! Cms::script('theme/vendors/moment/min/moment.min.js') !!}
	{!! Cms::script('theme/vendors/jquery/dist/jquery.min.js') !!}
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	{!! Cms::script('theme/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') !!}
	
	
</body>
</html>