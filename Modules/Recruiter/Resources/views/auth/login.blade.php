@extends('recruiter::layouts.auth')
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
                <span>Recruiter</span>
                 Login
            </h4>
            <form method="post" action="{{route('recruiter-login')}}" id="login-form" class="">
                <div class="ss-form-group">
                    <input type="text" name="id" placeholder="Enter phone number or email id">
                    <span class="help-block"></span>
                </div>
                <div>
                  <button type="submit">Log in</button>
                  <p>Don’t have an account? <a href="{{route('recruiter-signup')}}">Sign up</a></p>
                </div>

            </form>



          </div>

        </div>
      </div>
    </div>
</section>
@stop
