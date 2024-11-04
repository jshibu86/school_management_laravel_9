<!-- resources/views/home.blade.php -->
@extends('website.layout')

@section('title', 'Home Page')

@section('content')
<div class="hero overlay inner-page" style="background-image: url('images/school3.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
		<!-- <img src="images/blob.svg" alt="" class="img-fluid blob"> -->
		<div class="container">
			<div class="row align-items-center justify-content-center pt-5">
				<div class="col-lg-6 text-center pe-lg-5">
					<h1 class="heading text-white mb-3" data-aos="fade-up">School Features</h1>
					<p class="text-white mb-4" data-aos="fade-up" data-aos-delay="100">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
					<div class="align-items-center mb-4" data-aos="fade-up" data-aos-delay="200">
						<a href="{{ url('/contact') }}" class="btn btn-outline-white-reverse me-4">Contact us</a>
						<a href="#" class="text-white glightbox">Watch the video</a>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="section">
		<div class="container">
			<div class="row justify-content-between">
				<div class="col-lg-7 mb-4 mb-lg-0" data-aos="fade-up">
					<img src="images/school1.jpg" alt="Image" class="img-fluid rounded
					">
				</div>
				<div class="col-lg-4 ps-lg-2" data-aos="fade-up" data-aos-delay="100">
					<div class="mb-5">
						<h2 class="text-black h4">Make learning joy and smooth.</h2>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
					</div>
					<div class="d-flex mb-3 service-alt">
						<div>
							<span class="bi-wallet-fill me-4"></span>
						</div>
						<div>
							<h3>Build future</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
						</div>
					</div>

					<div class="d-flex mb-3 service-alt">
						<div>
							<span class="bi-pie-chart-fill me-4"></span>
						</div>
						<div>
							<h3>Insure the future</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>

	<div class="section sec-features">
		<div class="container">
			<div class="row g-5">
				<div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
					<div class="feature d-flex">
						<span class="bi-bag-check-fill"></span>
						<div>
							<h3>Build future</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
					<div class="feature d-flex">
						<span class="bi-wallet-fill"></span>
						<div>
							<h3>Insure the future</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
					<div class="feature d-flex">
						<span class="bi-pie-chart-fill"></span>
						<div>
							<h3>Responsible schooling</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="section">
		<div class="container">
			<div class="row">
				<div class="col-lg-7 order-lg-2 mb-4 mb-lg-0" data-aos="fade-up">
					<img src="images/classroom2.jpg" alt="Image" class="img-fluid">
				</div>
				<div class="col-lg-5 pe-lg-5" data-aos="fade-up" data-aos-delay="100">
					<div class="mb-5">
						<h2 class="text-black h4">Straight-forward way of schooling</h2>
					</div>
					<div class="d-flex mb-3 service-alt">
						<div>
							<span class="bi-wallet-fill me-4"></span>
						</div>
						<div>
							<h3>Build future</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
						</div>
					</div>

					<div class="d-flex mb-3 service-alt">
						<div>
							<span class="bi-pie-chart-fill me-4"></span>
						</div>
						<div>
							<h3>Insure the future</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
						</div>
					</div>

					<div class="d-flex mb-3 service-alt">
						<div>
							<span class="bi-bag-check-fill me-4"></span>
						</div>
						<div>
							<h3>Responsible schooling</h3>
							<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="section sec-cta overlay" style="background-image: url('images/img-3.jpg')">
	<div class="container">
		<div class="row justify-content-between align-items-center">
			<div class="col-lg-5" data-aos="fade-up" data-aos-delay="0">
				<h2 class="heading">Wanna Talk To Us?</h2>
				<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
			</div>
			<div class="col-lg-5 text-end" data-aos="fade-up" data-aos-delay="100">
				<a href="#" class="btn btn-outline-white-reverse">Contact us</a>
			</div>
		</div>
	</div>
</div>


<div class="section sec-portfolio bg-light pb-5	">
	<div class="container">
		<div class="row mb-5">
			<div class="col-lg-5 mx-auto text-center ">
				<h2 class="heading text-primary mb-3" data-aos="fade-up" data-aos-delay="0">Our Team</h2>
				<p class="mb-4" data-aos="fade-up" data-aos-delay="100">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>

				<div id="post-slider-nav" data-aos="fade-up" data-aos-delay="200">
					<button class="btn btn-primary py-2" class="prev" data-controls="prev">Prev</button>
					<button class="btn btn-primary py-2" class="next" data-controls="next">Next</button>
				</div>

			</div>
		</div>
	</div>

	<div class="post-slider-wrap" data-aos="fade-up" data-aos-delay="300">



		<div id="post-slider" class="post-slider">
			<div class="item text-center">
				<a href="{{ url('/single') }}" class="card d-block">
					<img src="images/person_1.jpg" class="card-img-top img-fluid w-50 rounded-circle mb-3" alt="Image">
					<div class="card-body">
						<h5 class="card-title">Lorem ipsum </h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
					</div>
				</a>
			</div>

			<div class="item text-center">
				<a href="{{ url('/single') }}" class="card d-block">
					<img src="images/person_2.jpg" class="card-img-top img-fluid w-50 rounded-circle mb-3" alt="Image">
					<div class="card-body">
						<h5 class="card-title">Lorem ipsum </h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
					</div>
				</a>
			</div>

			<div class="item text-center">
				<a href="{{ url('/single') }}" class="card d-block">
					<img src="images/person_3.jpg" class="card-img-top img-fluid w-50 rounded-circle mb-3" alt="Image">
					<div class="card-body">
						<h5 class="card-title">Lorem ipsum </h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
					</div>
				</a>
			</div>

			<div class="item text-center">
				<a href="{{ url('/single') }}" class="card d-block">
					<img src="images/person_4.jpg" class="card-img-top img-fluid w-50 rounded-circle mb-3" alt="Image">
					<div class="card-body">
						<h5 class="card-title">Lorem ipsum </h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
					</div>
				</a>
			</div>

			<div class="item text-center">
				<a href="{{ url('/single') }}" class="card d-block">
					<img src="images/person_5.jpg" class="card-img-top img-fluid w-50 rounded-circle mb-3" alt="Image">
					<div class="card-body">
						<h5 class="card-title">Lorem ipsum </h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
					</div>
				</a>
			</div>
		</div>
	</div>


</div>


<div class="section sec-testimonial bg-light">
	<div class="container">
		<div class="row mb-5 justify-content-center">
			<div class="col-lg-6 text-center">
				<h2 class="heading text-primary">Testimonials</h2>
			</div>

		</div>


		<div class="testimonial-slider-wrap">
			<div class="testimonial-slider" id="testimonial-slider">
				<div class="item">
					<div class="testimonial-half d-lg-flex bg-white">
						<div class="img" style="background-image: url('images/classroom1.jpg')">

						</div>
						<div class="text">
							<blockquote>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
							</blockquote>
							<div class="author">
								<strong class="d-block text-black">John Campbell</strong>
								<span>Head Master</span>
							</div>
						</div>
					</div>
				</div>

				<div class="item">
					<div class="testimonial-half d-lg-flex bg-white">
						<div class="img" style="background-image: url('images/classroom2.jpg')">

						</div>
						<div class="text">
							<blockquote>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
							</blockquote>
							<div class="author">
								<strong class="d-block text-black">John Campbell</strong>
								<span>Vice-Principal</span>
							</div>
						</div>
					</div>
				</div>

				<div class="item">
					<div class="testimonial-half d-lg-flex bg-white">
						<div class="img" style="background-image: url('images/classroom3.jpg')">

						</div>
						<div class="text">
							<blockquote>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
							</blockquote>
							<div class="author">
								<strong class="d-block text-black">John Campbell</strong>
								<span>Correspondent</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>



<div class="section sec-news">
	<div class="container">
		<div class="row mb-5">
			<div class="col-lg-7">
				<h2 class="heading text-primary">Latest Events</h2>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4">
				<div class="card post-entry">
					<a href="{{ url('/single') }}"><img src="images/event1.jpg" class="card-img-top" alt="Image"></a>
					<div class="card-body">
						<div><span class="text-uppercase font-weight-bold date">May 10, 2022</span></div>
						<h5 class="card-title"><a href="{{ url('/single') }}">Lorem ipsum</a></h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
						<p class="mt-5 mb-0"><a href="#">Read more</a></p>
					</div>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="card post-entry">
					<a href="{{ url('/single') }}"><img src="images/event4.jpg" class="card-img-top" alt="Image"></a>
					<div class="card-body">
						<div><span class="text-uppercase font-weight-bold date">Feb 26, 2022</span></div>
						<h5 class="card-title"><a href="{{ url('/single') }}">Lorem ipsum</a></h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
						<p class="mt-5 mb-0"><a href="#">Read more</a></p>
					</div>
				</div>
			</div>

			<div class="col-lg-4">
				<div class="card post-entry">
					<a href="{{ url('/single') }}"><img src="images/event3.jpg" class="card-img-top" alt="Image"></a>
					<div class="card-body">
						<div><span class="text-uppercase font-weight-bold date">Jan 20, 2022</span></div>
						<h5 class="card-title"><a href="{{ url('/single') }}">Lorem ipsum</a></h5>
						<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
						<p class="mt-5 mb-0"><a href="{{ url('/single') }}">Read more</a></p>
					</div>
				</div>
			</div>


		</div>
	</div>
</div>


@endsection
