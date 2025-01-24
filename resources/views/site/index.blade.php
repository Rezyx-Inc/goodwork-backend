@extends('layouts.main')
@section('mytitle', 'Welcome to '.env('PROJECT_NAME', 'Goodwork'))
@section('content')
<!-------------banner-section-------->

<section class="ss-hm-banner-sec">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6">
          <div class="ss-hm-banner-txt-bx">
            <h2>Are you ready to be a person, not just an application?</h2>
            <p>Goodwork is designed to facilitate positive, honest interactions between employers and candidates.</p>
            <p>Looking for work is a lot better when we work together.</p>
            <p>Oh, and we also give applicants HALF the fee we charge employers (what?!)</p>
            <ul>
                @guest('frontend')
                <li><a href="{{ route('worker-signup') }}">Create a profile</a></li>
                @endguest
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

  <a href="{{route('explore-jobs')}}">Explore Jobs</a>
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

  <a href="{{route('explore-jobs')}}">Explore Jobs</a>
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






  <!-------------Our Popular Jobs----------->

  <section class="ss-hm-popular-job-sec">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="ss-hm-popu-jb-hed">
            <h2 style="cursor: pointer" onclick="window.location.href = '{{ url('/explore-jobs') }}'">
              Start browsing <span class="ss-clr-pink">jobs now</span> - no login required
            </h2>
          </div>

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
            <h2>How is <span class="ss-whtol-sec">Goodwork</span> different from other job boards?</h2>
            <p>
              We are obsessed with aligning incentives. Employers, Candidates, and Goodwork are set up to be allies, not adversaries.</p>
              <p>Other job boards maximize volume, but Goodwork maximizes value.</p>
          </div>
        </div>

        <div class="col-lg-8">

          <div class="ss-hm-what-icn-bx-mn">

            <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Get Paid To Use Goodwork!</h6>
              <p>Goodwork gives HALF the fee to workers who find their jobs on Goodwork. When you get a job on Goodwork, we charge the recruiter a fee and split it with you. Wow!</p>
            </div>


            <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Limited Applications</h6>
              <p>Candidates can only apply to a limited number of applications each day
                Recruiters must respond to applications before getting new ones</p>
            </div>

            <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>No more spam</h6>
              <p>Recruiters cannot reach out to candidates. They can only message after a candidate applies</p>
            </div>
          </div>

          <div class="ss-what-offer-div2">
            <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Finally, some accountability!</h6>
              <p>Goodwork tracks objective stats about recruiters and workers, which will be part of their profiles. Want to avoid recruiters who bait-and-switch? Goodwork will tell you who does it the most</p>
            </div>

             <div class="ss-hm-what-icn-bx">
              <div></div>
              <h6>Qualification Guardrails</h6>
              <p>Employers can set application requirements, so they don't waste your time or theirs</p>
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
           <p>
            Goodwork isn't just another job board trying to pump as many applications into as many inboxes as possible. We are truly trying to change the way people find work. We believe that by aligning incentives we can create an environment where applicant and employer work as partners rather than adversaries.
          </p>
          <p>Changing the world can be messy. It takes more than posting stock photos of doctors with smoldering looks on their face. That's why you may run into a few bugs here and there or a feature or two that we haven't finished yet. We promise we are working on it and really appreciate your patients.</p>
          <p>Thank you for helping us on this mission.</p>
          <p>-The Goodwork Team</p>
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
