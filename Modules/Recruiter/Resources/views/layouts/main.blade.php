<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <title>Home</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{URL::asset('landing/css/bootstrap.min.css')}}" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="{{URL::asset('frontend/css/fontawesome_all.css')}}" />
    {{-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" /> --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" />
    <link rel="stylesheet" href="{{URL::asset('frontend/css/mdb.min.css')}}" />
    <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
    <link rel='stylesheet' href='{{URL::asset("recruiter/custom/css/owl.carousel.css")}}'>
    <link rel='stylesheet' href='{{URL::asset("recruiter/custom/css/mdb.min.css")}}'>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="{{URL::asset('recruiter/custom/css/style.css')}}" />
    <link rel="stylesheet" href="{{URL::asset('recruiter/custom/css/custom.css')}}" />

</head>

<body>
    <script>$(document).ready(function() {
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

    <script src="{{URL::asset('landing/js/jquery.min.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <header>
        @include('recruiter::partials.sidebar')
        @include('recruiter::partials.header')
    </header>
    @yield('content')
    <script src="{{URL::asset('landing/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>
    <!-- this line causes notification popup problem in header -->
    <!-- <script type="text/javascript" src="{{URL::asset('frontend/js/mdb.min.js')}}"></script> -->
    <script type="text/javascript" src="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript" src="{{URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
    <script src="{{URL::asset('backend/vendors/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/js/nav-bar-script.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('recruiter/custom/js/owl.carousel.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('recruiter/custom/js/mdb.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('recruiter/custom/js/nav-bar-script.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/profile.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('frontend/custom/js/script.js')}}"></script>
    <script type="text/javascript">
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    @yield('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script>$(document).ready(function() {
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

        <script>
            // searchable select
            $(document).ready(function() {
                $('.searchable-select').select2({
                    allowClear: false
                });
            });
        </script>
</body>

</html>
