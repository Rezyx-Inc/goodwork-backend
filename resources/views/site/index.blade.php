@extends('layouts.main')
@section('mytitle', 'Welcome to '.env('PROJECT_NAME', 'Goodwork'))
@section('content')
<!-------------banner-section-------->

<section class="ss-hm-banner-sec">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6">
          <div class="ss-hm-banner-txt-bx">
            <h2>Navigate your next nursing career with us</h2>
            <p>Goodwork has come up with some of the most recognized healthcare jobs. Our portal will help you find your dream nursing job.</p>
            <ul>
                @guest('frontend')
                <li><a href="{{route('signup')}}">Create a profile</a></li>
                @endguest
              <li><p>128+ Nurses successfully registered across the world</p></li>
            </ul>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="ss-hm-baner-img-dv">
            <ul class="ss-ban-top-map-txt">
              <li><img src="{{URL::asset('landing/img/banner-map-icon.png')}}" /></li>
              <li><h5>Los Angeles, CA</h5></li>
            </ul>
            <img src="{{URL::asset('landing/img/banner-image.png')}}" />

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


  <!---------mobile show about sec---->

  <section class="ss-hm-about-sec ss-mobile-show">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="ss-hm-img-abt">
            <img src="{{URL::asset('landing/img/about-us-image.png')}}">
          </div>
        </div>

         <div class="col-lg-6">
          <div class="ss-hm-hed-sec">
           <h4>About <span class="ss-fnt-nrml">Goodwork</span></h4>
           <h2>Introduction To Best
  <span class="ss-clr-pink">Nursing Portal</span>   </h2>
  <p>
    Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique consectetur mi curabitur congue enim dignissim amet justo. Porta morbi nulla aliquet adipiscing. Sed diam mauris erat faucibus eu posuere ultricies quisque amet. Quam pellentesque in tristique
  </p>
  <p>sed neque scelerisque quam pulvinar. Risus dictum elementum lacus urna. Neque eget sagittis vulputate nam id morbi id. Aliquam molestie posuere pulvinar arcu</p>

  <a href="#">Explore Jobs</a>
         </div>
        </div>
      </div>
    </div>
  </section>

  <!---------mobile show about sec---->







  <!---------mobile show What We Are Offering---->

  <section class="ss-what-we-ofr-sec ss-mobile-show">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="ss-hm-hed-sec">
           <h4>About <span class="ss-fnt-nrml">What We Are Offering</span></h4>
           <h2><span class="ss-whtol-sec">Job Types</span> We Can Offer You!    </h2>
  <p>
    Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique consectetur mi curabitur congue enim dignissim amet justo. Porta morbi nulla aliquet adipiscing.</p>

  <a href="#">Explore Jobs</a>
         </div>

  <!----slider---->

  <div class="owl-carousel ss-what-ofr-slid-mob">

    <!-----slider---->
  <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>
  <!-----slider---->

    <!-----slider---->
  <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>
  <!-----slider---->

    <!-----slider---->
  <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>
  <!-----slider---->

    <!-----slider---->
  <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>
  <!-----slider---->

    <!-----slider---->
  <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>
  <!-----slider---->

  </div>

        </div>

      </div>
    </div>
  </section>


  <!---------mobile show What We Are Offering---->



  <!---------Join Us----------->

  <section class="ss-hmjoin-sec">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="ss-hm-join-hed-tx-sec">
            <h6>Join Us</h6>
            <h2><span class="ss-clr-pink">Join</span> Our Nursing Community and Find Your Ideal Shift!</h2>
            <h5>Connecting Nurses with Opportunities, One Shift at a Time</h5>
          </div>
        </div>

        <div class="col-lg-6">
          <div class="ss-hm-join-rit-p-sc">
            <p>Are you a skilled and dedicated nurse seeking the perfect shift? Look no further. Our platform is designed to make your job hunt simpler, smarter, and more rewarding. Whether you're a seasoned pro or just starting your nursing journey, we have opportunities that match your skills and preferences.</p>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3">
          <div class="ss-join-icon-div-sec">
            <img src="{{URL::asset('landing/img/join-icon-1.png')}}" />
            <h5>Tailored Opportunities</h5>
            <p>Discover a wide range of nursing shifts that align with your expertise and desired schedule. From early morning to overnight, we've got you covered.</p>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="ss-join-icon-div-sec">
            <img src="{{URL::asset('landing/img/join-icon-2.png')}}" />
            <h5>Effortless Application</h5>
            <p>Say goodbye to complicated application processes. With just a few clicks, you can express your interest in shifts that pique your interest.</p>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="ss-join-icon-div-sec">
            <img src="{{URL::asset('landing/img/join-icon-3.png')}}" />
            <h5>Streamlined Communication</h5>
            <p> Receive updates, notifications, and interview requests directly on our platform. No more phone tag – stay informed every step of the way.</p>
          </div>
        </div>

        <div class="col-lg-3">
          <div class="ss-join-icon-div-sec">
            <img src="{{URL::asset('landing/img/join-icon-4.png')}}" />
            <h5>Career Growth</h5>
            <p> Our platform is more than just shifts; it's a nurturing ground for your career. Engage and forge a pathway to a thriving nursing future."</p>
          </div>
        </div>
      </div>

    </div>
  </section>




  <!-------------Our Popular Jobs----------->

  <section class="ss-hm-popular-job-sec">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="ss-hm-popu-jb-hed">
            <h6>Our Popular Jobs</h6>
            <h2><span class="ss-clr-pink">Jobs For </span>Every
  Healthcare Professional</h2>
          </div>

        </div>
      </div>

      <div class="ss-hm-populr-jorow-2">
      <div class="row">
        <div class="col-lg-4">
          <div class="ss-job-prfle-sec">
            <p>Travel <span>+50 Applied</span></p>
            <h4>Manager CRNA - Anesthesia</h4>
            <h6>Medical Solutions Recruiter</h6>
            <ul>
              <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}" /> Los Angeles, CA</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}" /> 10 wks</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}" /> 2500/wk</a></li>
            </ul>
            <h5>Recently Added</h5>
          </div>
        </div>
        <div class="col-lg-4">
           <div class="ss-job-prfle-sec">
            <p>Travel <span>+50 Applied</span></p>
            <h4>Manager CRNA - Anesthesia</h4>
            <h6>Medical Solutions Recruiter</h6>
            <ul>
              <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}" /> Los Angeles, CA</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}" /> 10 wks</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}" /> 2500/wk</a></li>
            </ul>
            <h5>Recently Added</h5>
          </div>
        </div>
        <div class="col-lg-4">
           <div class="ss-job-prfle-sec">
            <p>Travel <span>+50 Applied</span></p>
            <h4>Manager CRNA - Anesthesia</h4>
            <h6>Medical Solutions Recruiter</h6>
            <ul>
              <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}" /> Los Angeles, CA</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}" /> 10 wks</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}" /> 2500/wk</a></li>
            </ul>
            <h5>Recently Added</h5>
          </div>
        </div>

      </div>

      <div class="row">
        <div class="col-lg-4">
          <div class="ss-job-prfle-sec">
            <p>Travel <span>+50 Applied</span></p>
            <h4>Manager CRNA - Anesthesia</h4>
            <h6>Medical Solutions Recruiter</h6>
            <ul>
              <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}" /> Los Angeles, CA</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}" /> 10 wks</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}" /> 2500/wk</a></li>
            </ul>
            <h5>Recently Added</h5>
          </div>
        </div>
        <div class="col-lg-4">
           <div class="ss-job-prfle-sec">
            <p>Travel <span>+50 Applied</span></p>
            <h4>Manager CRNA - Anesthesia</h4>
            <h6>Medical Solutions Recruiter</h6>
            <ul>
              <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}" /> Los Angeles, CA</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}" /> 10 wks</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}" /> 2500/wk</a></li>
            </ul>
            <h5>Recently Added</h5>
          </div>
        </div>
        <div class="col-lg-4">
           <div class="ss-job-prfle-sec">
            <p>Travel <span>+50 Applied</span></p>
            <h4>Manager CRNA - Anesthesia</h4>
            <h6>Medical Solutions Recruiter</h6>
            <ul>
              <li><a href="#"><img src="{{URL::asset('landing/img/location.png')}}" /> Los Angeles, CA</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/calendar.png')}}" /> 10 wks</a></li>
              <li><a href="#"><img src="{{URL::asset('landing/img/dollarcircle.png')}}" /> 2500/wk</a></li>
            </ul>
            <h5>Recently Added</h5>
          </div>
        </div>

      </div>
      <div class="ss-hm-job-prfile-red-sec">
        <a href="#">View All Jobs</a>
      </div>
    </div>

    </div>
  </section>




  <!-----------What We Are Offering------>
  <section class="ss-what-we-ofr-sec ss-desktop-show">
    <div class="container">
      <div class="row">
        <div class="col-lg-4">
          <div class="ss-hm-hed-sec">
           <h4>About <span class="ss-fnt-nrml">What We Are Offering</span></h4>
           <h2><span class="ss-whtol-sec">Job Types</span> We Can Offer You!    </h2>
  <p>
    Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique consectetur mi curabitur congue enim dignissim amet justo. Porta morbi nulla aliquet adipiscing.</p>

  <a href="#">Explore Jobs</a>
         </div>
        </div>

        <div class="col-lg-8">

          <div class="ss-hm-what-icn-bx-mn">

            <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>


            <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>

            <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>
          </div>

  <div class="ss-what-offer-div2">
     <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>

             <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Permanent</h6>
              <p>Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique</p>
            </div>
  </div>


        </div>
      </div>
    </div>
  </section>






  <!------------About Goodwork------------->

  <section class="ss-hm-about-sec ss-desktop-show">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="ss-hm-img-abt">
            <img src="{{URL::asset('landing/img/about-us-image.png')}}" />
          </div>
        </div>

         <div class="col-lg-6">
          <div class="ss-hm-hed-sec">
           <h4>About <span class="ss-fnt-nrml">Goodwork</span></h4>
           <h2>Introduction To Best
  <span class="ss-clr-pink">Nursing Portal</span>   </h2>
  <p>
    Lorem ipsum dolor sit amet consectetur. Adipiscing nisl id at arcu enim id gravida pulvinar. Tristique consectetur mi curabitur congue enim dignissim amet justo. Porta morbi nulla aliquet adipiscing. Sed diam mauris erat faucibus eu posuere ultricies quisque amet. Quam pellentesque in tristique
  </p>
  <p>sed neque scelerisque quam pulvinar. Risus dictum elementum lacus urna. Neque eget sagittis vulputate nam id morbi id. Aliquam molestie posuere pulvinar arcu</p>

  <a href="#">Explore Jobs</a>
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
                    <h2>Easy-to-apply <span class="ss-clr-pink">dream jobs</span>,all on one app</h2>
                    <p>sed neque scelerisque quam pulvinar. Risus dictum elementum lacus urna. Neque eget sagittis vulputate nam id morbi id. Aliquam molestie posuere pulvinar arcu</p>

                    <div class="ss-downld-ap-dv">
                        <a href="#">
                            <ul>
                                <li><i class="fa fa-apple" aria-hidden="true"></i></li>
                                <li>
                                    <p>Download on the</p>
                                    <h6>Apple Store</h6>
                                </li>
                            </ul>
                        </a>

                        <a href="#">
                            <ul>
                                <li><i class="fab fa-google-play"></i></li>
                                <li>
                                    <p>Get in on</p>
                                    <h6>Google Play</h6>
                                </li>
                            </ul>
                        </a>
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
                @guest('frontend')
                <a href="{{route('signup')}}">Sign Up Now</a>
                @endguest
            </div>
        </div>
    </div>
</section>
@stop

@section('js')

@stop
