@extends('admin::layouts.lock')
@section('content')
<style>
    .help-block.help-block-error{
        position: absolute;
        bottom: -25px;
    }
</style>
<div class="page-lock">
    <div class="page-logo text-center">
        <a class="brand" href="{{ Route('admin-lockscreen') }}">
            <img src="{{ URL::asset('public/backend/images/fav-icon.png') }}" width="140" alt="logo" />
        </a>
    </div>
    <div class="page-body">
        @if (isset($admin_model->profile_picture) && !empty($admin_model->profile_picture))
        <img class="page-lock-img" src="{{ URL::asset('public/uploads/admin/profile_picture/preview/' . $admin_model->profile_picture) }}" onerror="this.src='{{ URL::asset('public/backend/assets/pages/img/admin-default.jpg') }}'" alt="">
        @else
        <img class="page-lock-img" src="{{ URL::asset('public/backend/assets/pages/img/admin-default.jpg') }}" alt="">
        @endif
        <div class="page-lock-info">
            <h1>{{ ((isset($admin_model->first_name) && $admin_model->first_name != null) ? ucfirst(strtolower($admin_model->first_name)) : "Not Given") . ' ' . ((isset($admin_model->last_name) && $admin_model->last_name != null) ? ucfirst(strtolower($admin_model->last_name)) : "") }}</h1>
            <span class="email"> {{ $admin_model->email }} </span>
            <span class="locked"> Locked </span>
            <form id="lock-form" class="login-form" action="{{ Route('admin-lockscreen') }}" method="POST">
                @csrf
                <div class="input-group input-medium">
                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <input type="password" name="password" class="form-control" placeholder="Password">
                        @if ($errors->has('password'))
                        <div class="help-block help-block-error">{{ $errors->first('password') }}</div>
                        @endif
                    </div>
                    <span class="input-group-btn">
                        <button type="submit" class="btn green icn-only" name="lock-button"><i class="m-icon-swapright m-icon-white"></i></button>
                    </span>
                </div>
                <!-- /input-group -->
                <!--            <div class="relogin">
                                <a href="login.html"> Not Bob Nilson ? </a>
                            </div>-->
            </form>
        </div>
    </div>
    <div class="page-footer-custom"> {{ date('Y') }}&copy; {{env('PROJECT_NAME', 'Demo')}}. </div>
</div>
@stop