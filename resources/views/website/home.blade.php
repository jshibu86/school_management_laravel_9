<!-- resources/views/home.blade.php -->
@extends('website.layout')

@section('title', 'Home Page')


@section('content')
    <div class="hero overlay"
        style="background-image: url(' {{ isset($data['home_sec1_image1']) ? asset($data['home_sec1_image1']): asset('images/elementary-school-kids-1.jpg')  }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        <!-- <img src="images/blob.svg" alt="" class="img-fluid blob"> -->
        <div class="container">
            <div class="row align-items-center justify-content-between pt-5">
                <div class="col-lg-6 text-center text-lg-start pe-lg-5">
                    <h1 class="heading text-white mb-3" data-aos="fade-up">{{  $data['home_sec1_title1'] ?? 'Smart schooling for brighter kids.' }}
                    </h1>
                    <p class="text-white mb-5" data-aos="fade-up" data-aos-delay="100">
                        {{ $data['home_sec1_desc1'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
                    <div class="align-items-center mb-5 mm" data-aos="fade-up" data-aos-delay="200">
                        <a href="{{ url('/contact') }}" class="btn btn-outline-white-reverse me-4">Contact us</a>
                        <a href="#" class="text-white glightbox">Watch the video</a>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="img-wrap">
                        <!-- <img src="images/img-1.jpg" alt="Image" class="img-fluid rounded"> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- section begins -->

    <div class="section rs-features style3 pt-100 pb-100 md-pt-70 md-pb-70">
        <div class="container">
            <div class="row g-5">
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 md-mb-40" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature d-flex features-item">
                        <img src="images/pink.png" alt="images">
                        <div class="content-part mt-3">
                            <div class="icon-part">
                                <!-- <img src="  alt=""> -->
                                <!-- <img src="images/blue1.png" alt=""> -->
                                @if(isset($data['home_sec2_image1']))
				                <img src="{{ asset($data['home_sec2_image1']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
				                @else
                                 <img src="images/pink1.png" alt="">
				                @endif	                               
                            </div>
                            <h4 class="title mt-2"><a href="#">{{ $data['home_sec2_title1'] ?? 'Healthy Meals' }}</a></h4>
                            <p>{{ $data['home_sec2_desc1'] ??  'Lorem ipsum dolor sit amet, consectetur adipisic ing elit, sed eius .incididunt' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 md-mb-40" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature d-flex features-item">
                        <img src="images/blue.png" alt="images">
                        <div class="content-part mt-3">
                            <div class="icon-part">
                                @if(isset($data['home_sec2_image2']))
				                <img src="{{ asset($data['home_sec2_image2']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
				                @else
                                <img src="images/blue1.png" alt="">
				                @endif	                              
                            </div>
                            <h4 class="title mt-2">
                                <a href="#">{{ $data['home_sec2_title2'] ?? 'Children Safety' }}</a>
                            </h4>
                            <p>  {{ $data['home_sec2_desc2'] ?? 'Lorem ipsum dolor sit amet, consectetur adipisic ing elit, sed eius .incididunt' }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 md-mb-40" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature d-flex features-item">
                        <img src="images/green.png" alt="images">
                        <div class="content-part mt-3">
                            <div class="icon-part">                                
                                @if(isset($data['home_sec2_image3']))
				                <img src="{{ asset($data['home_sec2_image3']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
				                @else
                                <img src="images/green1.png" alt="">
				                @endif                                
                            </div>
                            <h4 class="title mt-2">
                                <a href="#">{{ $data['home_sec2_title3'] ?? 'Cute Environment' }}</a>
                            </h4>
                            <p>  {{ $data['home_sec2_desc3'] ?? 'Lorem ipsum dolor sit amet, consectetur adipisic ing elit, sed eius .incididunt' }} </p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 md-mb-40" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature d-flex features-item">
                        <img src="images/orange.png" alt="images">
                        <div class="content-part mt-3">
                            <div class="icon-part">                                
                                @if(isset($data['home_sec2_image4']))
				                <img src="{{ asset($data['home_sec2_image4']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
				                @else
                                <img src="images/orange1.png" alt="">
				                @endif                              
                            </div>
                            <h4 class="title mt-2">
                                <a href="#">{{ $data['home_sec2_title4'] ?? 'Creative Learning' }}</a>
                            </h4>
                            <p>  {{ $data['home_sec2_desc4'] ?? 'Lorem ipsum dolor sit amet, consectetur adipisic ing elit, sed eius .incididunt' }} </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- section ends -->

    <!-- STARTS RIGHT SIDE SECTION-->
    <div class="section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-7 mb-4 mb-lg-0">
                @if(isset($data['home_sec3_image1']))
                    <img src="{{ asset($data['home_sec3_image1']) }}" alt="Image" class="img-fluid rounded">  
                @else
                    <img src="images/school1.jpg" alt="Image" class="img-fluid rounded">                
                @endif
                </div>
                <div class="col-lg-4 ps-lg-2">
                    <div class="mb-5">
                        <h2 class="text-black h4">{{ $data['home_sec3_title1'] ?? 'Lorem ipsum dolor.' }}</h2>
                        <p>{{ $data['home_sec3_desc1'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
                    </div>
                        <div class="d-flex mb-3 service-alt">
                            <div>
                                @if(isset($data['home_sec3_image2']))
                                <span><img src="{{ asset($data['home_sec3_image2'])  }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">    </span>                                                                                                
                                @else
                                <span class="bi-pie-chart-fill me-4"></span>
                                @endif
                            </div>
                            <div>
                                <h3>{{ $data['home_sec3_title2'] ?? 'Build great' }}</h3>
                                <p>{{ $data['home_sec3_desc2'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3 service-alt">
                            <div>
                                @if(isset($data['home_sec3_image3']))
                                <span><img src="{{ asset($data['home_sec3_image3'])  }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">    </span>                                                                                                
                                @else
                                <span class="bi-wallet-fill me-4"></span>
                                @endif
                            </div>
                            <div>
                                <h3>{{ $data['home_sec3_title3'] ?? 'Build great' }}</h3>
                                <p>{{ $data['home_sec3_desc3'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3 service-alt">
                            <div>
                                @if(isset($data['home_sec3_image4']))
                                <span><img src="{{ asset($data['home_sec3_image4'])  }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">    </span>                                                                                                
                                @else
                                <span class="bi-pie-chart-fill me-4"></span>
                                @endif
                            </div>
                            <div>
                                <h3>{{ $data['home_sec3_title4'] ?? 'Build great' }}</h3>
                                <p>{{ $data['home_sec3_desc4'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
                            </div>
                        </div>                                            
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ENDS RIGHT SIDE SECTION -->

    <!-- SECTION-4 BLUE SECTION -->
        <div class="section sec-features">
            <div class="container">
                <div class="row g-5">
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="0">
                        <div class="feature d-flex">
                        @if(isset($data['home_sec4_image1']))
                            <img src="{{ asset($data['home_sec4_image1']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
                        @else
                            <span class="bi-bag-check-fill"></span>
                        @endif
                            <div>
                                <h3>{{ $data['home_sec4_title1'] ?? 'Build great' }}</h3>
                                <p>{{ $data['home_sec4_desc1'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>                                
                            </div>
                        </div>
                    </div>  
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="feature d-flex">
                            @if(isset($data['home_sec4_image2']))
                                <img src="{{ asset($data['home_sec4_image2']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
                            @else
                            <span class="bi-wallet-fill"></span>
                            @endif
                        <div>
                                <h3>{{ $data['home_sec4_title2'] ?? 'Insure the future' }}</h3>
                                <p>{{ $data['home_sec4_desc2'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>                                
                                </div>
                            </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature d-flex">
                        @if(isset($data['home_sec4_image3']))
                            <img src="{{ asset($data['home_sec4_image3']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
                        @else
                        <span class="bi-pie-chart-fill"></span>
                        @endif
                                <div>
                                <h3>{{ $data['home_sec4_title3'] ?? 'Responsible schooling' }}</h3>
                                <p>{{ $data['home_sec4_desc3'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>                                
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- END SECTION-4 BLUE SECTION -->                                    
    
     <!-- STARTS LEFT SIDE SECTION-->
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 order-lg-2 mb-4 mb-lg-0">
                    @if(isset($data['home_sec5_image1']))
                    <img src="{{  asset($data['home_sec5_image1']) }}" alt="Image" class="img-fluid">
                    @else
                    <img src="images/classroom2.jpg" alt="Image" class="img-fluid">
                    @endif
                </div>
                <div class="col-lg-5 pe-lg-5">
                    <div class="mb-5">
                        <h2 class="text-black h4">{{ $data['home_sec5_title1'] ?? 'Straight-forward way of schooling' }}</h2>
                        <p>{{ $data['home_sec5_desc1'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
                    </div>
                    <div class="d-flex mb-3 service-alt">
                        <div>
                            @if(isset($data['home_sec5_image2']))
                                <img src="{{ asset($data['home_sec5_image2']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
                            @else
                            <span class="bi-wallet-fill me-4"></span>
                            @endif                            
                        </div>
                        <div>
                            <h3>{{ $data['home_sec5_title2'] ?? 'Build future' }}</h3>
                            <p>{{ $data['home_sec5_desc2'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
                        </div>
                    </div>   
                    <div class="d-flex mb-3 service-alt">
                        <div>
                            @if(isset($data['home_sec5_image3']))
                                <img src="{{ asset($data['home_sec5_image3']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
                            @else
                            <span class="bi-pie-chart-fill me-4"></span>
                            @endif                            
                        </div>
                        <div>
                            <h3>{{ $data['home_sec5_title3'] ?? 'Insure the future' }}</h3>
                            <p>{{ $data['home_sec5_desc3'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
                        </div>
                    </div> 
                    <div class="d-flex mb-3 service-alt">
                        <div>
                            @if(isset($data['home_sec5_image4']))
                                <img src="{{ asset($data['home_sec5_image4']) }}" alt="Icon" class="me-4" style="width: 50px; height: 50px;">
                            @else
                            <span class="bi-bag-check-fill me-4"></span>
                            @endif                            
                        </div>
                        <div>
                            <h3>{{ $data['home_sec5_title4'] ?? 'Responsible schooling' }}</h3>
                            <p>{{ $data['home_sec5_desc4'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
                        </div>
                    </div>                                   
                </div>
            </div>
        </div>
    </div>
    <!-- ENDS LEFT SIDE SECTION-->

</div>


    <!-- <div class="section sec-services">
     <div class="container">
      <div class="row mb-5">
       <div class="col-lg-5 mx-auto text-center" data-aos="fade-up">
        <h2 class="heading text-primary">Our Courses</h2>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
       </div>
      </div>

      <div class="row">
       <div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up">
        <div class="service text-center">
         <span class="bi-cash-coin"></span>
         <div>
          <h3>Lorem ipsum</h3>
          <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          <p><a href="#" class="btn btn-outline-primary py-2 px-3">Read more</a></p>
         </div>
        </div>

       </div>
       <div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="service text-center">
         <span class="bi-chat-text"></span>
         <div>
          <h3>Grow your future</h3>
          <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          <p><a href="#" class="btn btn-outline-primary py-2 px-3">Read more</a></p>
         </div>
        </div>
       </div>
       <div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
        <div class="service text-center">
         <span class="bi-fingerprint"></span>
         <div>
          <h3>Lorem iipsum</h3>
          <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          <p><a href="#" class="btn btn-outline-primary py-2 px-3">Read more</a></p>
         </div>
        </div>
       </div>

       <div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="service text-center">
         <span class="bi-gear"></span>
         <div>
          <h3>Lorem ipsum</h3>
          <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          <p><a href="#" class="btn btn-outline-primary py-2 px-3">Read more</a></p>
         </div>
        </div>

       </div>
       <div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="200">
        <div class="service text-center">
         <span class="bi-graph-up-arrow"></span>
         <div>
          <h3>Lorem ipsum</h3>
          <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          <p><a href="#" class="btn btn-outline-primary py-2 px-3">Read more</a></p>
         </div>
        </div>
       </div>
       <div class="col-12 col-sm-6 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="300">
        <div class="service text-center">
         <span class="bi-layers"></span>
         <div>
          <h3>Digital World</h3>
          <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          <p><a href="#" class="btn btn-outline-primary py-2 px-3">Read more</a></p>
         </div>
        </div>
       </div>

      </div>
     </div>
    </div> -->


    <div class="section sec-cta overlay" style="background-image: url('images/img-3.jpg')">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-5" data-aos="fade-up" data-aos-delay="0">
                    <h2 class="heading">{{ $data['home_sec6_title1'] ?? 'Wanna Talk To Us?' }}</h2>
                    <p>{{ $data['home_sec6_desc1'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna.' }}</p>
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
                    <h2 class="heading text-primary mb-3" data-aos="fade-up" data-aos-delay="0">Lorem ipsum</h2>
                    <p class="mb-4" data-aos="fade-up" data-aos-delay="100">Lorem ipsum dolor sit amet, consectetur
                        adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in
                        blandit magna. </p>

                    <div id="post-slider-nav" data-aos="fade-up" data-aos-delay="200">
                        <button class="btn btn-primary py-2" class="prev" data-controls="prev">Prev</button>
                        <button class="btn btn-primary py-2" class="next" data-controls="next">Next</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="post-slider-wrap" data-aos="fade-up" data-aos-delay="300">



            <div id="post-slider" class="post-slider">
                @foreach ($teachers as $data)
                    <div class="item text-center">
                        <a href="{{ url('#') }}" class="card d-block">
                            <div class="image-container" style="width: 250px; height: 150px; margin: 0 auto;">
                                <img src="{{ isset($data['image']) ? asset($data['image']) : asset('images/person_1.jpg') }}"
                                    alt="Image" alt="Image" class="img-fluid w-50 rounded-circle mb-3">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $data['teacher_name'] ?? 'Default Teacher' }} </h5>
                                <p>{{ $data['qualification'] ?? 'Lorem ipsum dolor sit amet,consectetur adipisic ing elit,sed eius .incididunt' }}
                                </p>
                            </div>
                        </a>
                    </div>
                @endforeach
                <!-- <div class="item text-center">
         <a href="{{ url('/casestudy') }}" class="card d-block">
          <img src="images/person_2.jpg" class="card-img-top img-fluid w-50 rounded-circle mb-3" alt="Image">
          <div class="card-body">
           <h5 class="card-title">Lorem ipsum</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          </div>
         </a>
        </div>

        <div class="item text-center">
         <a href="{{ url('/casestudy') }}" class="card d-block">
          <img src="images/person_3.jpg" class="card-img-top img-fluid w-50 rounded-circle mb-3" alt="Image">
          <div class="card-body">
           <h5 class="card-title">Lorem ipsum</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          </div>
         </a>
        </div>

        <div class="item text-center">
         <a href="{{ url('/casestudy') }}" class="card d-block">
          <img src="images/person_4.jpg" class="card-img-top img-fluid w-50 rounded-circle mb-3" alt="Image">
          <div class="card-body">
           <h5 class="card-title">Lorem ipsum</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          </div>
         </a>
        </div>

        <div class="item text-center">
         <a href="{{ url('/casestudy') }}" class="card d-block">
          <img src="images/person_5.jpg" class="card-img-top img-fluid w-50 rounded-circle mb-3" alt="Image">
          <div class="card-body">
           <h5 class="card-title">Lorem ipsum</h5>
           <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. </p>
          </div>
         </a>
        </div> -->
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
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit
                                        felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. Separated
                                        they live in Bookmarksgrove right at the coast of the Semantics, a large language
                                        ocean.</p>
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
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit
                                        felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. Separated
                                        they live in Bookmarksgrove right at the coast of the Semantics, a large language
                                        ocean.</p>
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
                                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis volutpat hendrerit
                                        felis non facilisis. Cras et justo lectus. Pellentesque in blandit magna. Separated
                                        they live in Bookmarksgrove right at the coast of the Semantics, a large language
                                        ocean.</p>
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
                @foreach ($events as $event)
                    <div class="col-lg-4">
                        <div class="card post-entry">
                            <div class="image-container"
                                style="width: 100%; height: 300px; overflow:hidden; position:relative;">
                                <img src="{{ isset($event['event_image']) ? asset($event['event_image']) : asset('images/event1.jpg') }}"
                                    alt="image">
                            </div>
                            <div class="card-body">

                                <div><span class="text-uppercase font-weight-bold date">{{ $event['event_date'] }} </span>
                                </div>
                                <h5 class="card-title"><a href="{{ url('/casestudy') }}">
                                        {{ $event['event_name'] ?? 'Default Title' }} </a></h5>
                                <p>{{ $event['event_name'] ?? 'Lorem ipsum dolor sit amet,consectetur adipisic ing elit,sed eius .incididunt' }}
                                </p>
                                <p class="mt-5 mb-0"><a href="#">Read more</a></p>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </div>
@endsection


@section('scripts')
<script>
$(function(){
    // this will get the full URL at the address bar
    var url = window.location.href; 

    // passes on every "a" tag 
    $("#web-header a").each(function() {
            // checks if its the same on the address bar
        if(url == (this.href)) { 
            $(this).closest("li").addClass("active");
        }
    });
});
</script>
@endsection