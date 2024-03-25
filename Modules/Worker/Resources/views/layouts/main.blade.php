<!DOCTYPE html>
<html lang="en">

<head>
    <script type="text/javascript">
        var full_path = '<?= url('/') . '/'; ?>';
        var logged_in = '<?= (Auth()->guard('frontend')->guest()) ? false : true; ?>';
    </script>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>@yield('mytitle')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="{{URL::asset('landing/css/bootstrap.min.css')}}" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <link rel="stylesheet" href="{{URL::asset('frontend/css/mdb.min.css')}}" />
    <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
    <link rel='stylesheet' href='{{URL::asset("employer/custom/css/owl.carousel.css")}}'>
    <link rel='stylesheet' href='{{URL::asset("employer/custom/css/mdb.min.css")}}'>

    <link rel="stylesheet" href="{{URL::asset('frontend/css/style.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('frontend/custom/css/custom.css')}}" />

</head>

<body>
    <script src="{{URL::asset('landing/js/jquery.min.js')}}"></script>
    <header>
        
        @include('worker::partials.worker_sidebar')
        @include('worker::partials.worker_header')
    </header>
    @yield('content')
    <script src="{{URL::asset('landing/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/js/mdb.min.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
    <script src="{{URL::asset('backend/vendors/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/js/nav-bar-script.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('employer/custom/js/owl.carousel.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('employer/custom/js/mdb.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('employer/custom/js/nav-bar-script.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/profile.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/script.js')}}"></script>
    <script type="text/javascript">
</script>
    @yield('js')
    @include('partials.flashMsg')
</body>

</html>