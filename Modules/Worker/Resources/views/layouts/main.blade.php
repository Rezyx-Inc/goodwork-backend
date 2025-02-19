<!DOCTYPE HTML>
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
    <link rel="stylesheet" href="{{URL::asset('frontend/css/mdb.min.css')}}" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
    <link rel="stylesheet" href="{{URL::asset('frontend/css/style.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel='stylesheet' href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css'>
    @yield('css')

</head>
<body>

    <script src="{{URL::asset('landing/js/jquery.min.js')}}"></script>

    <script>
    $(document).ready(function() {
        $.ajaxSetup({
            xhrFields: {
                withCredentials: true
            },
            beforeSend: function(xhr) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                if (csrfToken) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                }
            }
        });
    });</script>

    <script src="{{ asset('js/app.js') }}"></script>

    <!--Main Navigation-->
    <header>
        @include('worker::partials.worker_sidebar')
        @include('worker::partials.worker_header')
        
    </header>
    <!--Main Navigation-->

    <!--Main layout-->
    @yield('content')


    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    {{-- CK editor --}}
    <script src="{{URL::asset('backend/vendors/ckeditor/ckeditor.js')}}"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <script src="{{URL::asset('landing/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/js/mdb.min.js')}}"></script>
    <script type="text/javascript" src="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
    {{-- Jquery Mask --}}
    <script type="text/javascript" src="{{URL::asset('frontend/vendor/mask/jquery.mask.min.js')}}"></script>
    <!-- Custom scripts -->
    <script type="text/javascript" src="{{URL::asset('frontend/js/nav-bar-script.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/profile.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/script.js')}}"></script>



    @yield('js')
    @include('partials.flashMsg')



</body>
</html>

