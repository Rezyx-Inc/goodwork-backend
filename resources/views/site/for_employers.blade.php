@extends('layouts.main')
@section('mytitle', ' For Employers | Saas')
@section('content')


<!-------------banner-section-------->

<section class="ss-hm-banner-sec ss-for-emply-pg-ban-sec">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6">
        <div class="ss-hm-banner-txt-bx">
          <h2>Empower Your Nursing Team with Our Platform</h2>
          <p>Discover Seamless Talent Acquisition: Our platform streamlines the process of finding qualified nurses for your facility. With intuitive search tools and comprehensive profiles, you can easily identify candidates whose skills and experience align with your staffing needs. Say goodbye to sifting through countless resumes – our platform connects you with top nursing talent effortlessly.</p>
          <ul>
            <li><a href="{{route('employer.login')}}">Schedule a demo now</a></li>

          </ul>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="ss-hm-baner-img-dv">
          <ul class="ss-ban-top-map-txt">
            <li><img src="{{URL::asset('landing/img/banner-map-icon.png')}}" /></li>
            <li><h5>Los Angeles, CA</h5></li>
          </ul>
          <img src="{{URL::asset('landing/img/employers-banner-img.png')}}" />

          <ul class="ss-ban-top-map-txt2">
            <li><img src="{{URL::asset('landing/img/banner-map-icon.png')}}" /></li>
            <li><h5>Fredericksburg, VA</h5></li>
          </ul>
        </div>

        <!---mobile -show---->

        <div class="ss-mobile-show ss-ban-mob-txt">
          <h2>Empowering nurses to find their dream path</h2>
          <p>Register with us as our skilled nurse to unleash your passion for nursing.</p>
        </div>

      </div>
    </div>
  </div>
</section>



<!------------Benefits for Employers-------------->


<section class="ss-benefit-emply-mn-sec">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
      <div class="ss-benefit-hed-sed">
        <h6>Benefits for Recruiters</h6>
        <h2>We help healthcare <span class="ss-clr-pink">workers
& employers</span> find each other</h2>
<div class="ss-flower-img-div">
  <img src="{{URL::asset('landing/img/emplyoyers-flower-img.png')}}" />
</div>
      </div>
      </div>
    </div>
  </div>
</section>








<!-------Valuable Feedback------->

<section class="ss-mn-value-sec">
 <div class="container">
   <div class="row">
     <div class="col-lg-12">
       <div class="ss-hm-hed-sec">
         <h4>Valuable Feedback</h4>
         <h2>What the <span class="ss-clr-pink">professionals</span> are
saying about us  </h2>
       </div>

       <!-----slider---->
         <div class="owl-carousel ss-feed-slid">

          <!----slider-1--->
          <div class="ss-feed-slid-bx">
            <div><img src="{{URL::asset('landing/img/feed-img.svg')}}" /></div>
            <h4>Name Here </h4>
            <ul>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
            </ul>
            <p>Lorem ipsum dolor sit amet consectetur. Nisi ullamcorper tincidunt odio arcu id praesent vitae. Facilisis vitae fringilla donec</p>
          </div>



          <!----slider-1--->
          <div class="ss-feed-slid-bx">
            <div><img src="{{URL::asset('landing/img/feed-img.svg')}}" /></div>
            <h4>Name Here </h4>
            <ul>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
            </ul>
            <p>Lorem ipsum dolor sit amet consectetur. Nisi ullamcorper tincidunt odio arcu id praesent vitae. Facilisis vitae fringilla donec</p>
          </div>

          <!----slider-1--->
          <div class="ss-feed-slid-bx">
            <div><img src="{{URL::asset('landing/img/feed-img.svg')}}" /></div>
            <h4>Name Here </h4>
            <ul>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
            </ul>
            <p>Lorem ipsum dolor sit amet consectetur. Nisi ullamcorper tincidunt odio arcu id praesent vitae. Facilisis vitae fringilla donec</p>
          </div>




          <!----slider-1--->
          <div class="ss-feed-slid-bx">
            <div><img src="{{URL::asset('landing/img/feed-img.svg')}}" /></div>
            <h4>Name Here </h4>

            <ul>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
              <li><i class="fa fa-star" aria-hidden="true"></i></li>
            </ul>
            <p>Lorem ipsum dolor sit amet consectetur. Nisi ullamcorper tincidunt odio arcu id praesent vitae. Facilisis vitae fringilla donec</p>
          </div>

         </div>


     </div>
   </div>
 </div>
</section>




<!----- Download Goodwork------>

<section class="ss-hm-dwnld-app-sec">
  <div class="container">
    <div class="row">
      <div class="col-lg-7">
        <div class="ss-downld-app-txt-hed">
         <h4>Download <span class="ss-fnt-nrml">Goodwork</span></h4>
         <h2>Easy-to-apply <span class="ss-clr-pink">dream jobs</span>,
all on one app</h2>
<p>sed neque scelerisque quam pulvinar. Risus dictum elementum lacus urna. Neque eget sagittis vulputate nam id morbi id. Aliquam molestie posuere pulvinar arcu</p>

    <div class="ss-downld-ap-dv">
      <a href="#"><ul>
        <li><i class="fa fa-apple" aria-hidden="true"></i></li>
        <li>
          <p>Download on the</p>
          <h6>Apple Store</h6>
        </li>
      </ul>
    </a>

       <a href="#"><ul>
        <li><i class="fab fa-google-play"></i></li>
        <li>
          <p>Get in on</p>
          <h6>Google Play</h6>
        </li>
      </ul></a>
    </div>

        </div>
      </div>

      <div class="col-lg-5">
        <div class="ss-app-img-dv">
          <img src="{{URL::asset('landing/img/mobile-app-img.png')}}" />
        </div>
      </div>
    </div>
  </div>
</section>



<section class="ss-foot-btm-sign-sec">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h2>sign up to explore your career options today!</h2>
        <a href="{{route('employer-signup')}}">Sign Up Now</a>
      </div>
    </div>
  </div>
</section>


@stop
