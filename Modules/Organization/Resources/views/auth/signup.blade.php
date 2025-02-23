@extends('organization::layouts.auth')
@section('content')
<section class="ss-login-work-sec ss-signup-worker-sec">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="ss-login-work-logo-div">
            <h4>What’s your <span>role?</span></h4>
            <div class="ss-login-logo-dv">
              <img src="{{URL::asset('landing/img/logo_signup_organization.png')}}" />
            </div>

            <a href="{{route('/')}}"><img src="{{URL::asset('landing/img/logo.png')}}" /></a>
          </div>
        </div>

       <div class="col-lg-6">
          <!--------login form------->
          <div class="ss-login-work-dv">
            <h4><span>Organization </span> Sign Up</h4>
            <form class="" method="post" action="{{route('organization.signup')}}" id="signup-form-submit">
                <div class="ss-form-group">
                    <input type="text" name="organization_name" placeholder="Organization Name" required><br/>
                    <span class="help-block-organization-name"></span>
                </div>
                <div class="ss-form-group">
                    <input type="text" name="first_name" placeholder="First Name" required><br/>
                    <span class="help-block-first-name"></span>
                </div>

                <div class="ss-form-group">
                    <input type="text" name="last_name" placeholder="Last Name" required/><br/>
                    <span class="help-block-last-name"></span>
                </div>
                <div class="ss-form-group">
                    <input type="email" name="email" placeholder="Email" required><br/>
                    <span class="help-block-email"></span>
                </div>
                <div class="ss-form-group">
                    <input type="tel" id="contact_number" name="mobile" placeholder="Mobile"><br/>
                    <span id="passwordHelpInline" class="form-text">
      (Mobile not required)
    </span><br>
                    <span class="help-block-mobile"></span>
                </div>
                <div>
                    <button id="submitBtn" >
                    <span id="loading" class="d-none" >
                          <span id="loadSpan" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                    </span>
                    <span id="sign"> Sign Up  </span> </button>
                </div>
                <p>Already have an account? <a href="{{route('organization.login')}}">Login</a></p>

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
<script type="text/javascript" src="{{URL::asset('frontend/vendor/mask/jquery.mask.min.js')}}"></script>
<script>
    $('#contact_number').mask('+1 (999) 999-9999');


  $('#submitBtn').click(function(event) {


    event.preventDefault();
    var access = true;
    $(this).find('input, button').prop('disabled', true);
    var regexPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
    var regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    var firstName = $('input[name="first_name"]').val();
    var organization_name = $('input[name="organization_name"]').val();
    var lastName = $('input[name="last_name"]').val();
    var email = $('input[name="email"]').val();
    var mobile = $('#contact_number').val();

    if (organization_name.trim() === '') {
    $('.help-block-organization-name').text('Please enter your organization name');
    $('.help-block-organization-name').addClass('text-danger');
     access = false;
}else{
    $('.help-block-organization-name').text('');
}
    if (firstName.trim() === '') {
    $('.help-block-first-name').text('Please enter your first name');
    $('.help-block-first-name').addClass('text-danger');
     access = false;
}else{
    $('.help-block-first-name').text('');
}

if (lastName.trim() === '') {
    $('.help-block-last-name').text('Please enter your last name');
    $('.help-block-last-name').addClass('text-danger');
    access = false;
}else{
    $('.help-block-last-name').text('');
}

if (!regexEmail.test(email)) {
    $('.help-block-email').text('Please enter a valid email address');
    $('.help-block-email').addClass('text-danger');
    access = false;
}else{
    $('.help-block-email').text('');
}

if ((!regexPhone.test(mobile)) && (mobile.trim() !== '')) {
    $('.help-block-mobile').text('Please enter a valid phone number in the format: +1 (xxx) xxx-xxxx');
    $('.help-block-mobile').addClass('text-danger');
    access = false;
}else{
    $('.help-block-mobile').text('');
}
if(access){
    $('#loading').removeClass('d-none');
    $('#sign').addClass('d-none');
    $('#signup-form-submit').submit();
}

  });
</script>
@stop


<!-- @section('js')
<script type="text/javascript" src="{{URL::asset('frontend/vendor/mask/jquery.mask.min.js')}}"></script>
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
@stop -->

@section('css')
<style>
    .ss-form-group input{
        margin-bottom: 3px;
    }
    #loading,#sign,#loadSpan{
        color:#fff;
    }
</style>
@stop

<!-- @section('js')
<script type="text/javascript" src="{{URL::asset('frontend/vendor/mask/jquery.mask.min.js')}}"></script>
<script>

  $('#signup-form-submit').submit(function(event) {
    var regexPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
    var regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    var firstName = $('input[name="first_name"]').val();
    var lastName = $('input[name="last_name"]').val();
    var email = $('input[name="email"]').val();
    var mobile = $('#contact_number').val();

    if (firstName.trim() === '') {
        event.preventDefault();
        $('.help-block').text('Please enter your first name');
        $('.help-block').addClass('text-danger');
        return false;
    }

    if (lastName.trim() === '') {
        event.preventDefault();
        $('.help-block').text('Please enter your last name');
        $('.help-block').addClass('text-danger');
        return false;
    }

    if (!regexEmail.test(email)) {
        event.preventDefault();
        $('.help-block').text('Please enter a valid email address');
        $('.help-block').addClass('text-danger');
        return false;
    }

    if (!regexPhone.test(mobile)) {
        event.preventDefault();
        $('.help-block').text('Please enter a valid phone number in the format: +1 (xxx) xxx-xxxx');
        $('.help-block').addClass('text-danger');
        return false;
    }
  });
</script>
@stop -->
