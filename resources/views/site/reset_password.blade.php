@extends('layouts.main') 
@section('mytitle', ' Reset password page | Saas')
@section('content')
<section class="page_banner">
<img class="page_banner_grid" src="{{asset('public/frontend/assets/images/images/page_point.png')}}" alt="">
</section>

<section class="login_section">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="login_box">
                <form action="{{ route('post-reset-password') }}" method="post" id="reset-password">
                    <div class="login_title">
                        <h3>Reset Password</h3>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" id="" placeholder="Password">
                        <span class="help-block"></span>
                    </div>
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label">Re-enter Password</label>
                        <input type="password" name="confirm_password" class="form-control" id="" placeholder="Re-enter Password">
                        <span class="help-block"></span>
                    </div>
                    <a href="javascript:void(0)" onclick="submit_form('reset-password')" class="btn_sign_in">Submit</a>

                </form>
            </div>
        </div>
    </div>
</div>
</section>
@stop

@section('js')

@endsection