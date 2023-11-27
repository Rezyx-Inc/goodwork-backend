@extends('layouts.main') 
@section('mytitle', ' Forgot password page | Saas')
@section('content')
<section class="page_banner">
<img class="page_banner_grid" src="{{asset('frontend/assets/images/images/page_point.png')}}" alt="">
</section>

<section class="login_section">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="login_box">
                <form action="{{ route('forgot-password-submit') }}" method="post" id="forgot-password">
                    <div class="login_title">
                        <h3>Forgot Password</h3>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleFormControlInput1" placeholder="Email address">
                        <span class="help-block"></span>
                    </div>
                    <a href="javascript:void(0)" onclick="submit_form('forgot-password')" class="btn_sign_in">Submit</a>

                    <div class="dont_ac">
                        <p>Already Have An Account?  <a href="{{route('login')}}" class="login_link">Log In</a></p> 
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</section>
@stop

@section('js')

@endsection