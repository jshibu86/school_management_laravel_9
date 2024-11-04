<!-- resources/views/home.blade.php -->
@extends('website.layout')

@section('title', 'Home Page')

@section('content')

<div class="hero overlay inner-page"  style="background-image: url(' {{ isset($data['acad_sec1_image1']) ? asset($data['acad_sec1_image1']): asset('images/bgimg1.jpg')  }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
		<!-- <img src="images/blob.svg" alt="" class="img-fluid blob"> -->
		<div class="container">
			<div class="row align-items-center justify-content-center text-center pt-5">
				<div class="col-lg-6">
					<h1 class="heading text-white mb-3" data-aos="fade-up">{{  $data['acad_sec1_title1'] ?? 'Academics' }}</h1>
				</div>
			</div>
		</div>
	</div>

	<div class="section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-4 ps-lg-2">
					<div class="mb-5">
						<h2 class="text-black h4">{{  $data['acad_sec2_title1'] ?? 'Academics' }}</h2>
						<p>{{ $data['acad_sec2_desc1'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
					</div>
					<div class="d-flex mb-3 service-alt">
						<div>
							@if(isset($data['acad_sec2_image2']))
								<img src="{{ asset($data['acad_sec2_image2']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
							@else
								<span class="bi-wallet-fill me-4"></span>
							@endif							
						</div>
						<div>
							<h3>{{  $data['acad_sec2_title2'] ?? 'Curriculum & Syllabus' }}</h3>
							<p>{{ $data['acad_sec2_desc2'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
						</div>
					</div> 

					<div class="d-flex mb-3 service-alt">
						<div>
							@if(isset($data['acad_sec2_image3']))
								<img src="{{ asset($data['acad_sec2_image3']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
							@else
								<span class="bi-pie-chart-fill me-4"></span>
							@endif							
						</div>
						<div>
							<h3>{{  $data['acad_sec2_title2'] ?? 'Kindergarten Curriculum' }}</h3>
							<p>{{ $data['acad_sec2_desc2'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
						</div>
					</div> 

					<div class="d-flex mb-3 service-alt">
						<div>
							@if(isset($data['acad_sec2_image4']))
								<img src="{{ asset($data['acad_sec2_image4']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
							@else
								<span class="bi-wallet-fill me-4"></span>
							@endif							
						</div>
						<div>
							<h3>{{  $data['acad_sec2_title4'] ?? 'Primary Curriculum' }}</h3>
							<p>{{ $data['acad_sec2_desc4'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
						</div>
					</div> 
					
				</div>
				<div class="col-lg-7 mb-4 mb-lg-0">
					<img src="images/event1.jpg" alt="Image" class="img-fluid rounded">
				</div>

			</div>
		</div>
	</div>


	<div class="section sec-cta overlay" style="background-image: url('images/img-3.jpg')">
		<div class="container">
			<div class="row justify-content-between align-items-center">
				<div class="col-lg-5" data-aos="fade-up" data-aos-delay="0">
					<h2 class="heading">{{  $data['acad_sec3_title1'] ?? 'Wanna Talk To Us?' }}</h2>
					<p>{{ $data['acad_sec3_desc1'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
				</div>
				<div class="col-lg-5 text-end" data-aos="fade-up" data-aos-delay="100">
					<a href="#" class="btn btn-outline-white-reverse">Contact us</a>
				</div>
			</div>
		</div>
	</div>
	<div class="section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-7 mb-4 mb-lg-0">
					@if(isset($data['acad_sec4_image1']))
						<img src="{{ asset($data['acad_sec4_image1']) }}" alt="Image" class="img-fluid rounded">
					@else
						<img src="images/classroom1.jpg" alt="Image" class="img-fluid rounded">
					@endif
				</div>
				<div class="col-lg-4 ps-lg-2">
					<div class="mb-5">						
						<h2 class="text-black h4">{{  $data['acad_sec4_title1'] ?? 'Make learning joy and smooth.' }}</h2>
						<p>{{ $data['acad_sec4_desc1'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
					</div>
					<div class="d-flex mb-3 service-alt">
						<div>
							@if(isset($data['acad_sec4_image2']))
								<img src="{{  asset($data['acad_sec4_image2']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
							@else
							<span class="bi-bag-check-fill me-4"></span>
							@endif							
						</div>
						<div>
							<h3>{{  $data['acad_sec4_title2'] ?? 'Primary Curriculum' }}</h3>
							<p>{{ $data['acad_sec4_desc2'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
						</div>
					</div>
					<div class="d-flex mb-3 service-alt">
						<div>
							@if(isset($data['acad_sec4_image3']))
								<img src="{{asset($data['acad_sec4_image3']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
							@else
								<span class="bi-wallet-fill me-4"></span>
							@endif							
						</div>
						<div>
							<h3>{{ $data['acad_sec4_title3'] ?? 'Middle Curriculum' }}</h3>
							<p>{{ $data['acad_sec4_desc3'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
						</div>
					</div>
					<div class="d-flex mb-3 service-alt">
						<div>
							@if(isset($data['acad_sec4_image4']))
								<img src="{{  asset($data['acad_sec4_image4']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
							@else
							<span class="bi-pie-chart-fill me-4"></span>
							@endif							
						</div>
						<div>
							<h3>{{  $data['acad_sec4_title4'] ?? 'High School' }}</h3>
							<p>{{ $data['acad_sec4_desc4'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
						</div>
					</div>
					
				</div>

			</div>
		</div>
	</div>

@endsection
