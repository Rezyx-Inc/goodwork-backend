@extends('recruiter::layouts.auth')
@section('content')
<section class="ss-login-work-sec ss-signup-worker-sec">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="ss-login-work-logo-div">
            <h4>What’s your <span>role?</span></h4>
            <div class="ss-login-logo-dv">
              <img src="{{URL::asset('public/landing/img/login-logo.png')}}" />
            </div>

            <a href="{{route('/')}}"><img src="{{URL::asset('public/landing/img/logo.png')}}" /></a>
          </div>
        </div>

       <div class="col-lg-6">
          <!--------login form------->
          <div class="ss-login-work-dv">
            <h4><span>Recruiter </span> Sign Up</h4>
            <form class="" method="post" action="{{route('recruiter.signup')}}" id="signup-form-submit">
                <div class="ss-form-group">
                    <input type="text" name="first_name" placeholder="First Name">
                    <span class="help-block"></span>
                </div>

                <div class="ss-form-group">
                    <input type="text" name="last_name" placeholder="Last Name" />
                    <span class="help-block"></span>
                </div>
                <div class="ss-form-group">
                    <input type="email" name="email" placeholder="Email">
                    <span class="help-block"></span>
                </div>
                <div class="ss-form-group">
                    <input type="tel" id="contact_number" name="mobile" placeholder="Mobile" onchange=contactNumber(this)>
                    <span class="help-block"></span>
                </div>
                <div>
                    <button type="submit">Sign Up</button>
                </div>
                <p>Already have an account? <a href="{{route('recruiter.login')}}">Login</a></p>
                
            </form>


            <!-- <div class="ss-skipfor-div">
              <a href="#">Skip for now >></a>
            </div> -->
          </div>

        </div>
      </div>
    </div>
</section>
@stop

@section('js')
<script type="text/javascript" src="{{URL::asset('public/frontend/vendor/mask/jquery.mask.min.js')}}"></script>
<script>
  $('#contact_number').mask('+1 (999) 999-9999');
  $('#contact_number').on('input', function() {
    var inputValue = $(this).val();
    if(inputValue.includes('() -')){
      var numericValue = inputValue.replace(/\D/g, ''); 
      $(this).val(numericValue); 
    }
  });
</script>
@stop
