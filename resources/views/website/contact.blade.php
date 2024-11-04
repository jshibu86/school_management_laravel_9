<!-- resources/views/home.blade.php -->
@extends('website.layout')

@section('title', 'Home Page')

@section('content')
<div class="hero overlay inner-page"  style="background-image: url(' {{ isset($data['cont_sec1_image1']) ? asset($data['cont_sec1_image1']): asset('images/banner1.jpg')  }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
		<!-- <img src="images/blob.svg" alt="" class="img-fluid blob"> -->
		<div class="container">
			<div class="row align-items-center justify-content-center text-center pt-5">
				<div class="col-lg-6">
					<h1 class="heading text-white mb-3" data-aos="fade-up">{{  $data['cont_sec1_title1'] ?? 'Contact Us' }}</h1>
					<p class="text-white mb-4" data-aos="fade-up" data-aos-delay="100">{{ $data['cont_sec1_desc1'] ??  'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
				</div>
			</div>
		</div>
	</div>

<div class="section">
		<div class="container">
			<div class="row">
				<div class="col-lg-4 mb-5 mb-lg-0" data-aos="fade-up" data-aos-delay="100">
					<div class="contact-info">

						<div class="address mt-2">
							<i class="icon-room"></i>
							<h4 class="mb-2">{{  $data['cont_sec2_title1'] ?? 'Location:' }}</h4>
							<!-- <p>{{@$info->place}}. {{@$info->city}},<br>  {{@$info->country}} {{@$info->pin_code}}</p> -->
							<p>{{ $data['cont_sec2_desc1'] ??  '43 Raymouth Rd. Baltemoer,London 3910' }}</p>
						</div>

						<div class="open-hours mt-4">
							<i class="icon-clock-o"></i>
							<h4 class="mb-2">{{  $data['cont_sec2_title2'] ?? 'Open Hours:' }}</h4>							
							<p>{{ $data['cont_sec2_desc2'] ??  'Sunday-Friday:11:00 AM - 23:00 PM' }}</p>
						</div>

						<div class="email mt-4">
							<i class="icon-envelope"></i>
							<h4 class="mb-2">{{  $data['cont_sec2_title3'] ?? 'Email:' }}</h4>							
							<p>{{ $data['cont_sec2_desc3'] ??  'info@Untree.co' }}</p>
						</div>

						<div class="phone mt-4">
							<i class="icon-phone"></i>
							<h4 class="mb-2">{{  $data['cont_sec2_title4'] ?? 'Call:' }}</h4>
							<!-- <p>+1 {{@$info->school_landline}}</p> -->
							<p>{{ $data['cont_sec2_desc4'] ??  '+1 1234 55488 55' }}</p>
						</div>

					</div>
				</div>
				<div class="col-lg-8" data-aos="fade-up" data-aos-delay="200">
					{{ Form::open(['role' => 'form', 'route' => ['send_message'], 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'custom-validation form-horizontal form-label-left', 'id' => 'user-form', 'novalidate' => 'novalidate']) }}
						<div class="row">
							<div class="col-6 mb-3">
								<input type="text" name="name" class="form-control" placeholder="Your Name">
							</div>
							<div class="col-6 mb-3">
								<input type="email" name="email" class="form-control" placeholder="Your Email">
							</div>
							<div class="col-12 mb-3">
								<input type="text" name="subject" class="form-control" placeholder="Subject">
							</div>
							<div class="col-12 mb-3">
								<textarea name="message" id="message" cols="30" rows="7" class="form-control" placeholder="Message"></textarea>
							</div>

							<div class="col-12">
								<input type="submit" value="Send Message" class="btn btn-primary">
							</div>
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div> <!-- /.untree_co-section -->

	<div class="section pb-0 pt-4">
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12 mb-5 mb-lg-0 p-0" data-aos="fade-up" data-aos-delay="100">
					<h2 class="heading text-primary text-center mb-4 aos-init aos-animate" data-aos="fade-up" data-aos-delay="0" >Our Location</h2>
					<iframe src="{{@$menu["location_link"]}}" width="600" height="450" style="border:0; width: 100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
				</div>
			</div>
		</div>
	</div> <!-- /.untree_co-section -->

@endsection

