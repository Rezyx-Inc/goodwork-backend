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
    <!-- Font Awesome -->
     <!-- Bootstrap CSS -->
     <!-- <link href="{{URL::asset('landing/css/bootstrap.min.css')}}" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"> -->
     <link href="{{URL::asset('landing/css/bootstrap.min.css')}}" rel="stylesheet" crossorigin="anonymous">

    {{-- <link rel="stylesheet" href="{{URL::asset('frontend/css/fontawesome_all.css')}}" /> --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" />
    <!-- Google Fonts Roboto -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <!-- MDB -->
    <link rel="stylesheet" href="{{URL::asset('frontend/css/mdb.min.css')}}" />
    {{-- jquery confirm --}}
    <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    {{-- <link href="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
    {{-- Notie --}}
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
    <!-- Custom styles -->
    <link rel="stylesheet" href="{{URL::asset('frontend/css/style.css')}}" />
    @yield('css')
</head>
<body>
    <script src="{{URL::asset('landing/js/jquery.min.js')}}"></script>
    <!--Main Navigation-->
    <header>
        @include('worker::partials.worker_sidebar')
        @include('worker::partials.worker_header')
    </header>
<!--Main Navigation-->

<!--Main layout-->
@yield('content')

<!-- Option 1: Bootstrap Bundle with Popper -->
<!-- <script src="{{URL::asset('landing/js/bootstrap.bundle.min.js')}}" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> -->
<script src="{{URL::asset('landing/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>

<!--Main layout-->
    <!-- MDB -->
    <script type="text/javascript" src="{{URL::asset('frontend/js/mdb.min.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.js') }}" type="text/javascript"></script>
    {{-- <script type="text/javascript" src="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script> --}}
    <script type="text/javascript" src="{{URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
    {{-- CK editor --}}
    <script src="{{URL::asset('backend/vendors/ckeditor/ckeditor.js')}}"></script>
    {{-- Jquery Mask --}}
    {{-- <script type="text/javascript" src="{{URL::asset('frontend/vendor/mask/jquery.mask.min.js')}}"></script> --}}
    <!-- Custom scripts -->
    <script type="text/javascript" src="{{URL::asset('frontend/js/nav-bar-script.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/profile.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/script.js')}}"></script>
    @yield('js')
    @include('partials.flashMsg')


</body>
</html>

