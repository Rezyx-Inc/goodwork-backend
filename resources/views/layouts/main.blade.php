<!DOCTYPE html>
<html lang="en">
    <head>
        <script type="text/javascript">
            var full_path = '<?= url('/') . '/'; ?>';
            var logged_in = '<?= (Auth()->guard('frontend')->guest()) ? false : true; ?>';
        </script>
        <meta charset="UTF-8">


        <title>@yield('mytitle')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Bootstrap CSS -->
        <link href="{{URL::asset('landing/css/bootstrap.min.css')}}" rel="stylesheet" crossorigin="anonymous">
        <link href="{{URL::asset('landing/css/style.css')}}" rel="stylesheet" >
        <link rel='stylesheet' href='{{URL::asset('landing/css/owl.carousel.css')}}'>
        <script src="https://kit.fontawesome.com/3d90fa36a2.js" crossorigin="anonymous"></script>
        {{-- jquery confirm --}}
        <link href="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.css') }}" rel="stylesheet">
        {{-- <link href="{{ URL::asset('backend/vendors/datatables/jquery.dataTables.min.css') }}" rel="stylesheet"> --}}
        {{-- Notie --}}
        <link rel="stylesheet" type="text/css" href="{{ URL::asset('backend/vendors/notie/dist/notie.css') }}">
        @yield('css')
    </head>

    @php
    $no_header_pages = ['login','verify','signup'];
    $route_name = request()->route()->getName();
    @endphp
    <body>
        <script type="module">
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
            });
        </script>
        <script src="{{URL::asset('landing/js/jquery.min.js')}}"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
        @if(!in_array($route_name, $no_header_pages))
        @include('partials.header')
        @endif
        @yield('content')

        @include('partials.footer')

        <script src="{{URL::asset('landing/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>
        <script src='{{URL::asset('landing/js/owl.carousel.min.js')}}'></script>
        <script type="text/javascript" src="{{ URL::asset('backend/vendors/confirm/jquery-confirm.min.js') }}" type="text/javascript"></script>
        <script type="text/javascript" src="{{URL::asset('backend/vendors/notie/dist/notie.min.js') }}"></script>
        {{-- Jquery Mask --}}
        <script type="text/javascript" src="{{URL::asset('frontend/vendor/mask/jquery.mask.min.js')}}"></script>
        <script type="text/javascript">
            $('.ss-feed-slid').owlCarousel({

            items: 3,

            loop: true,

            autoplay: true,

            autoplayTimeout: 5000,

            margin: 20,

            nav: false,

            dots:true,

            navText: ['<span class="fa fa-angle-left  fa-2x"></span>','<span class="fas fa fa-angle-right fa-2x"></span>'],

                responsive: {

                    0:{

                    items: 1

                    },

                    480:{

                    items: 2

                    },

                    768:{

                    items: 3

                    },

                    992:{

                    items: 3

                    }

                }



            });
        </script>

        <script type="text/javascript">
            $('.ss-what-ofr-slid-mob').owlCarousel({

                items: 3,

                loop: true,

                autoplay: true,

                autoplayTimeout: 5000,

                margin: 20,

                nav: false,

                dots:true,

                navText: ['<span class="fa fa-angle-left  fa-2x"></span>','<span class="fas fa fa-angle-right fa-2x"></span>'],

                    responsive: {

                        0:{

                        items: 1

                        },

                        480:{

                        items: 1

                        },

                        768:{

                        items: 1

                        },

                        992:{

                        items: 1

                        }

                    }



            });
        </script>
        <!-- Bootstrap 4 -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{{URL::asset('frontend/custom/js/profile.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('frontend/custom/js/script.js')}}"></script>
        @yield('js')
        @include('partials.flashMsg')

    </body>
</html>
