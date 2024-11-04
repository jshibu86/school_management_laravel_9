<!doctype html>
<html lang="en">
<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="">
	<link rel="shortcut icon" href="favicon.png">

	<meta name="description" content="school, education" />
	<meta name="keywords" content="bootstrap, bootstrap5" />

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;600;700&display=swap" rel="stylesheet">


	
	<link rel="stylesheet" href="fonts/icomoon/style.css">
	<link rel="stylesheet" href="fonts/flaticon/font/flaticon.css">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

	<link rel="stylesheet" href="{{asset('website/css/aos.css')}}">
	<link rel="stylesheet" href="{{asset('website/css/glightbox.min.css')}}">

	<link rel="stylesheet" href="{{asset('website/css/bootstrap-grid.css')}}">
	<link rel="stylesheet" href="{{asset('website/css/glightbox.min.css')}}">
	<link rel="stylesheet" href="{{asset('website/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('website/css/tiny-slider.css')}}">
	<link rel="stylesheet" href="{{asset('website/css/flatpickr.min.css')}}"> 

	<title>Lorem Ipsum &mdash; Website Template </title>
</head>
<body>
<div class="site-mobile-menu site-navbar-target">
		<div class="site-mobile-menu-header">
			<div class="site-mobile-menu-close">
				<span class="icofont-close js-menu-toggle"></span>
			</div>
		</div>
		<div class="site-mobile-menu-body"></div>
	</div>

	<nav class="site-nav">
		<div class="container">
			<div class="menu-bg-wrap">
				<div class="site-navigation">
					<div class="row g-0 align-items-center">
						<div class="col-2">
							<a href="{{ url('/') }}" class="logo m-0 float-start">School<span class="text-primary">.</span></a>
						</div>
						<div class="col-8 text-center ">
							<div id="web-header">
								<ul class="js-clone-nav d-none d-lg-inline-block text-start site-menu mx-auto">
									<li class="home"><a href="{{ url('/') }}">Home</a></li>								
									<li class="events"><a href="{{ route('events') }}">Events</a></li>
									<li class="gallery"><a href="{{ route('gallery_page') }}">Gallery</a></li>								
									<li class="academics"><a href="{{ url('/courses')  }}">Academics</a></li>									
									<li class="about"><a href="{{ url('/about') }}">About</a></li>
									<li class="contact"><a href="{{ route('contactus_page') }}">Contact Us</a></li>
									<li class="admission"><a href="{{ url('/admission') }}">Admission</a></li>
								</ul>
							</div>
						</div>
						<div class="col-2 text-end">
							<a href="#" class="burger ms-auto float-end site-menu-toggle js-menu-toggle d-inline-block d-lg-none light">
								<span></span>
							</a>
							<a href="#" class="call-us d-flex align-items-center">
								<span class="icon-phone"></span>
								<span>123-489-9381</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</nav>
    <div class="content">
        @yield('content')
    </div>
	@php
		$footerData = Configurations::getFooterData();		
	@endphp
	
<div class="site-footer">
	<div class="container">
		<div class="row">
			<div class="col d-flex align-items-center">										
					<p>Copyright &copy;<script>document.write(new Date().getFullYear());</script>. All Rights Reserved. &mdash; Designed with love by Laravel 				
			</div>
			<div class="col-12 col-sm-6 col-md-6 col-lg-3">							
				<ul class="list-unstyled social">							
					<li>	
					<a href="{{ isset($footerData['home_sec7_link1']) ? 'http://'.$footerData['home_sec7_link1'] : '' }}" target="_blank"><span class="icon-instagram"></span></a></li>
					<li><a href="{{ isset($footerData['home_sec7_link2']) ? 'http://'.$footerData['home_sec7_link2'] : '' }}" target="_blank"><span class="icon-twitter"></span></a></li>
					<li><a href="{{ isset($footerData['home_sec7_link3']) ? 'http://'.$footerData['home_sec7_link3'] : '' }}" target="_blank"><span class="icon-facebook"></span></a></li>
					<li><a href="{{ isset($footerData['home_sec7_link4']) ? 'http://'.$footerData['home_sec7_link4'] : '' }}" target="_blank"><span class="icon-linkedin"></span></a></li>							
				</ul>				
			</div>			
		</div>
	</div>	
</div> <!-- /.site-footer -->

 


	<script src="{{ asset('/website/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('/website/js/tiny-slider.js') }}"></script>

	<script src="{{ asset('/website/js/flatpickr.min.js') }}"></script>
	<script src="{{ asset('/website/js/aos.js') }}"></script>
	<script src="{{ asset('/website/js/glightbox.min.js') }}"></script>
	<script src="{{ asset('/website/js/navbar.js') }}"></script>
	<script src="{{ asset('/website/js/counter.js') }}"></script>
	<script src="{{ asset('/website/js/custom.js') }}"></script>

  </body>


</html>