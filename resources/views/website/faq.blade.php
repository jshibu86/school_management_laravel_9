<!-- resources/views/home.blade.php -->
@extends('website.layout')

@section('title', 'Home Page')

@section('content')
<div class="hero overlay inner-page" style="background-image: url('images/school3.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
		<!-- <img src="images/blob.svg" alt="" class="img-fluid blob"> -->
		<div class="container">
			<div class="row align-items-center justify-content-center pt-5">
				<div class="col-lg-6 text-center pe-lg-5">
					<h1 class="heading text-white mb-3" data-aos="fade-up">FAQs</h1>
					<p class="text-white mb-4" data-aos="fade-up" data-aos-delay="100">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
					<div class="align-items-center mb-4" data-aos="fade-up" data-aos-delay="200">
						<a href="contact.html" class="btn btn-outline-white-reverse me-4">Contact us</a>
						<a href="https://youtu.be/Z-4MmsT_Q5Q" class="text-white glightbox">Watch the video</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-12 mb-4 mb-lg-0" data-aos="fade-up">
					<details>
						<summary>Lorem Ipsum</summary>
						<p style="text-indent: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
					</details>
					<details>
						<summary>Lorem Ipsum</summary>
						<p style="text-indent: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
					</details>
					<details>
						<summary>Lorem Ipsum</summary>
						<p style="text-indent: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
					</details>
					<details>
						<summary>Lorem Ipsum</summary>
						<p style="text-indent: 16px;">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
					</details>
				</div>
			</div>
		</div>
	</div>


@endsection
