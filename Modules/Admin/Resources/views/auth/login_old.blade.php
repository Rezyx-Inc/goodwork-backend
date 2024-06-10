@extends('admin::layouts.login')
@section('content')
<div class="animate form login_form">
    <section class="login_content">
      <form method="post" action="{{route('admin-login.store')}}" id="login-form">
        <h1>{{env('PROJECT_NAME', 'Goodwork')}}</h1>
        <div class="login-form">
          <input type="text" class="form-control" name="email" placeholder="Email" required="true" />
          <span class="help-block"></span>
        </div>
        <div class="login-form">
          <input type="password" class="form-control" name="password" placeholder="Password" required="true" />
          <span class="help-block"></span>
        </div>
        <div>
          <a class="btn btn-success submit" href="javascript:void(0)" onclick="submit_form('login-form')">Log in</a>
          {{-- <a class="reset_pass" href="#">Lost your password?</a> --}}
        </div>

        <div class="clearfix"></div>

        {{-- <div class="separator">
          <p class="change_link">New to site?
            <a href="#signup" class="to_register"> Create Account </a>
          </p>

          <div class="clearfix"></div>
          <br />

          <div>
            <h1><i class="fa fa-paw"></i> {{env('PROJECT_NAME', 'Goodwork')}}</h1>
            <p>©{{date('Y')}} creating account means you confirm our <a href="#">Privacy</a> and <a href="#">Terms</a></p>
          </div>
        </div> --}}
      </form>
    </section>
  </div>

  {{-- <div id="register" class="animate form registration_form">
    <section class="login_content">
      <form>
        <h1>Create Account</h1>
        <div>
          <input type="text" class="form-control" placeholder="Username" required="" />
        </div>
        <div>
          <input type="email" class="form-control" placeholder="Email" required="" />
        </div>
        <div>
          <input type="password" class="form-control" placeholder="Password" required="" />
        </div>
        <div>
          <a class="btn btn-default submit" href="index.html">Submit</a>
        </div>

        <div class="clearfix"></div>

        <div class="separator">
          <p class="change_link">Already a member ?
            <a href="#signin" class="to_register"> Log in </a>
          </p>

          <div class="clearfix"></div>
          <br />

          <div>
            <h1><i class="fa fa-paw"></i> Gentelella Alela!</h1>
            <p>©2016 All Rights Reserved. Gentelella Alela! is a Bootstrap 4 template. Privacy and Terms</p>
          </div>
        </div>
      </form>
    </section>
  </div> --}}
@stop
