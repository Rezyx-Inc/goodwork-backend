<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>GoodWork</title>
    @include('frontend.common.styles')
    <!-- Custom Theme Style -->
    <!-- <link href="{{ URL::asset('backend/custom/css/custom.min.css') }}" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}"> -->
</head>

<body>

    <section class="sign_login_bg">
        <div class="row align-items-center g-0">
            <div class="col left_side">
                <div class="mx-auto text-center">
                    <a href="/login" class="brand">
                        <img src="{{ asset('frontend/assets/images/logoOneLine.png') }}" alt="">
                    </a>
                    <div class="cc23_img my-4">
                        <img class="" src="{{ asset('frontend/assets/images/admin_cc23.png') }}" alt="">
                    </div>
                    <p class="my-4 fs-24 fw-400">We help healthcare workers <br />& organizations find each other</p>
                </div>
            </div>
            <div class="col right_side">

                <div class="right_side_container">
                    <div class="text-center">
                        <p class="fs-32 fw-500">Log in as
                            <span class="text_detail_color">Admin</span>
                        </p>
                        <form method="post" action="{{ route('admin-login.store') }}" id="login-form">
                            @csrf
                            <div class="col-12 mb-4">
                                <!-- <input type="text" class="form-control" id="" placeholder="Enter phone number or email"> -->
                                <input id="email" type="email" maxlength="255" class="form-control" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                                <small class="invalid-feedback"></small>
                            </div>
                            <div class="col-12 mb-2">
                                <!-- <input type="password" class="form-control" id="" placeholder="Password"> -->
                                <input id="password" type="password" maxlength="15" class="form-control"
                                    name="password" required autocomplete="current-password">

                                <small class="invalid-feedback"></small>
                            </div>
                            <div class="col-12">
                                <div class="text-end">
                                    {{-- <a href="{{ route('password.request') }}" class="text_darkpurple fs-14 fw-500">Forgot Password?</a> --}}
                                </div>
                            </div>
                            <div class="col-12 my-3">
                                <div class="d-grid">
                                    <button class="btn btn-dark">Log in</button>
                                </div>
                            </div>
                        </form>

                        <div class="mt-3">
                            <span class="fs-14 fw-400">Need Help?</span>
                            <a href="" class="fw-700 fs-14 text_detail_color">Contact Now</a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

</body>
<script src="{{ URL::asset('backend/vendors/jquery/dist/jquery.min.js') }}"></script>
@include('frontend.common.scripts')
<!-- Notie alert -->
<script type="text/javascript" src="{{ URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
<!-- Custom -->
<script src="{{ URL::asset('backend/custom/js/profile.js') }}"></script>
<script src="{{ URL::asset('backend/custom/js/script.js') }}"></script>

</html>
