<!-- resources/views/home.blade.php -->
@extends('website.layout')

@section('title', 'Home Page')

@section('content')
<div class="hero overlay inner-page" style="background-image: url(' {{$data['about_sec1_img'] ?? 'images/banner1.jpg' }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
		<!-- <img src="images/blob.svg" alt="" class="img-fluid blob"> -->
		<div class="container">
			<div class="row align-items-center justify-content-center text-center pt-5">
				<div class="col-lg-6">
					<h1 class="heading text-white mb-3" data-aos="fade-up">{{ $data['about_sec1_title'] ?? 'About Us' }}</h1>
				</div>
			</div>
		</div>
	</div>

	<div class="section sec-halfs py-0">
		<div class="half-content d-lg-flex align-items-stretch">
			<div class="img" style="background-image: url(' {{$data['about_sec2_image1'] ?? 'images/classroom2.jpg' }}'); data-aos="fade-in" data-aos-delay="100">
				
			</div>
			<div class="text">
				<h2 class="heading text-primary mb-3">{{ $data['about_sec2_title1'] ?? 'We are trusted by more than 5,000 parents' }}</h2>
				<p class="mb-5">{{ $data['about_sec2_desc1'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.' }}</p>
				<p><a href="#" class="btn btn-outline-primary py-2">Read more</a></p>
			</div>
		</div>

		<div class="half-content d-lg-flex align-items-stretch">
			<div class="img order-md-2" style="background-image: url(' {{$data['about_sec2_image2'] ?? 'images/classroom3.jpg' }}'); data-aos="fade-in">				
			</div>
			<div class="text">
				<h2 class="heading text-primary mb-3">{{ $data['about_sec2_title2'] ?? 'We are trusted by more than 5,000 parents' }}</h2>
				<p class="mb-5">{{ $data['about_sec2_desc2'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.' }}</p>
				<p><a href="#" class="btn btn-outline-primary py-2">Read mores</a></p>
			</div>
		</div>

	</div>

	<div class="section sec-features">
		<div class="container">
			<div class="row g-5">
				<div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
					<div class="feature d-flex">									
						@if(isset($data['about_sec3_image1']))
							<img src="{{ asset($data['about_sec3_image1']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
						@else
							<span class="bi-bag-check-fill"></span>	
						@endif						
						<div>
							<h3>{{ $data['about_sec3_title1'] ?? 'Build future' }}</h3>
							<p>{{ $data['about_sec3_desc1'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">				
					<div class="feature d-flex">									
						@if(isset($data['about_sec3_image2']))
							<img src="{{ asset($data['about_sec3_image2']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
						@else
							<span class="bi-wallet-fill"></span>
						@endif						
						<div>
							<h3>{{ $data['about_sec3_title2'] ?? 'Insure the future' }}</h3>
							<p>{{ $data['about_sec3_desc2'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">				
					<div class="feature d-flex">									
						@if(isset($data['about_sec3_image3']))
							<img src="{{ asset($data['about_sec3_image3']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
						@else
							<span class="bi-pie-chart-fill"></span>
						@endif						
						<div>
							<h3>{{ $data['about_sec3_title3'] ?? 'Responsible schooling' }}</h3>
							<p>{{ $data['about_sec3_desc3'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-7 mb-4 mb-lg-0">
					 <img src="{{isset($data['about_sec4_image1']) ? asset($data['about_sec4_image1']) : asset('images/classroom1.jpg')}}"  alt="image" class="img-fluid rounded">
					<!-- <img src="images/classroom1.jpg" alt="Image" class="img-fluid rounded"> -->
				</div>
				<div class="col-lg-4 ps-lg-2">
					<div class="mb-5">					
						<h2 class="text-black h4">{{ $data['about_sec4_title1'] ?? 'Make learning joy and smooth.' }}</h2>
						<p>{{ $data['about_sec4_desc1'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
					</div>
					<div class="d-flex mb-3 service-alt">
						<div>
							@if(isset($data['about_sec4_image2']))
								<img src="{{ asset($data['about_sec4_image2']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
							@else
								<span class="bi-wallet-fill me-4"></span>
							@endif								
						</div>
						<div>
							<h3>{{ $data['about_sec4_title2'] ?? 'Build future' }}</h3>
							<p>{{ $data['about_sec4_desc2'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>							
						</div>
					</div>

					<div class="d-flex mb-3 service-alt">						
						<div>
							@if(isset($data['about_sec4_image3']))
								<img src="{{ asset($data['about_sec4_image3']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
							@else
								<span class="bi-pie-chart-fill me-4"></span>
							@endif								
						</div>
						<div>
						<h3>{{ $data['about_sec4_title3'] ?? 'Insure the future' }}</h3>
						<p>{{ $data['about_sec4_desc3'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>							
						</div>
					</div>

					<div class="d-flex mb-3 service-alt">
						<div>
							@if(isset($data['about_sec4_image4']))
								<img src="{{ asset($data['about_sec4_image4']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
							@else
								<span class="bi-wallet-fill me-4"></span>
							@endif								
						</div>
						<div>
						<h3>{{ $data['about_sec4_title4'] ?? 'Responsible schooling' }}</h3>
						<p>{{ $data['about_sec4_desc4'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="section sec-teachers">
		<div class="container">			
			<div class="row mb-5">
				<div class="col-lg-5 mx-auto text-center" data-aos="fade-up">
					<h2 class="heading text-primary">Our Teachers</h2>
					<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
				</div>
			</div>

			<div class="row">
			
				@foreach($teachers as $data)
				
					<div class="col-lg-4 mb-4 text-center" data-aos="fade-up" data-aos-delay="0">						
					<div class="image-container" style="width: 150px; height: 150px; margin: 0 auto;">
            			<img src="{{ isset($data['image']) ? asset($data['image']) : asset('images/person_1.jpg') }}" alt="image"  alt="Image" class="img-fluid w-50 rounded-circle mb-3">						
        			</div>	
					
						<h5 class="text-black"> {{ $data['teacher_name'] ?? 'Default Teacher' }} </h5>						
						<p>{{ $data['qualification'] ?? 'Lorem ipsum dolor sit amet,consectetur adipisic ing elit,sed eius .incididunt' }}</p>
						
					</div>
					@endforeach
					<!-- <div class="col-lg-4 mb-4 text-center" data-aos="fade-up" data-aos-delay="100">
							<img src="images/person_2.jpg" alt="Image" class="img-fluid w-50 rounded-circle mb-3">
							<h5 class="text-black">Claire Smith</h5>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
						</div>
							<div class="col-lg-4 mb-4 text-center" data-aos="fade-up" data-aos-delay="200">
								<img src="images/person_3.jpg" alt="Image" class="img-fluid w-50 rounded-circle mb-3">
								<h5 class="text-black">Jessica Wilson</h5>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
							</div>

							<div class="col-lg-4 mb-4 text-center" data-aos="fade-up" data-aos-delay="0">
								<img src="images/person_4.jpg" alt="Image" class="img-fluid w-50 rounded-circle mb-3">
								<h5 class="text-black">William Anderson</h5>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
							</div>
							<div class="col-lg-4 mb-4 text-center" data-aos="fade-up" data-aos-delay="100">
								<img src="images/person_5.jpg" alt="Image" class="img-fluid w-50 rounded-circle mb-3">
								<h5 class="text-black">Julie Harvey</h5>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
							</div>
							<div class="col-lg-4 mb-4 text-center" data-aos="fade-up" data-aos-delay="200">
								<img src="images/person_2.jpg" alt="Image" class="img-fluid w-50 rounded-circle mb-3">
								<h5 class="text-black">Shana Clarkson</h5>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
							</div> -->
					</div>
				
		</div>
	</div>



	
@endsection
