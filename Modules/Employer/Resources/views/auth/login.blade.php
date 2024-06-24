@extends('employer::layouts.auth')
@section('content')
<section class="ss-login-work-sec ss-admin-login-sed">
    <div class="container">
      <div class="row">
        <div class="col-lg-6">
          <div class="ss-login-work-logo-div">
            <div class="ss-login-logo-dv">
              <img src="{{URL::asset('landing/img/admin-login-logo.png')}}" />
            </div>
            <p>Building a better way for healthcare workers & employers to find each other</p>

            <a href="{{route('/')}}"><img src="{{URL::asset('landing/img/logo.png')}}" /></a>
          </div>
        </div>

       <div class="col-lg-6">
          <!--------login form------->
          <div class="ss-login-work-dv">
            <h4>
                <span>Employer</span>
                 Login
            </h4>
            <form method="post" action="{{route('employer-login')}}" id="login-form" class="">
            <div class="ss-form-group">
                    <input type="text" name="id" placeholder="Enter phone number or email id"><br>
                    <span class="help-block-email"></span>
                </div>
                <div>
                <button id="submitBtn" >
                    <span id="loading" class="d-none" >
                          <span id="loadSpan" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Loading...
                    </span>
                    <span id="login"> Log in  </span> </button>
                  <p>Don’t have an account? <a href="{{route('employer-signup')}}">Sign up</a></p>
                </div>

            </form>



          </div>

        </div>
      </div>
    </div>
</section>
@stop


@section('js')
<script type="text/javascript" src="{{URL::asset('frontend/vendor/mask/jquery.mask.min.js')}}"></script>
<script>
  $('#submitBtn').click(function(event) {
    event.preventDefault();
    var email = $('input[name="id"]').val();

    $(this).find('input, button').prop('disabled', true);
    // var regexPhone = /^\+1 \(\d{3}\) \d{3}-\d{4}$/;
    var regexEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;


if (!regexEmail.test(email)) {
    $('.help-block-email').text('Please enter a valid email address');
    $('.help-block-email').addClass('text-danger');
    return false;
}else{
    $('.help-block-email').text('');
}
    $('#loading').removeClass('d-none');
    $('#login').addClass('d-none');
    $('#login-form').submit();
  });

</script>

@stop

@section('css')
<style>
    .ss-form-group input{
        margin-bottom: 3px;
    }
    #loading,#login,#loadSpan{
        color:#fff;
    }
</style>
@stop
